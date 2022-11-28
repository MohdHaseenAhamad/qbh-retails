<?php

namespace Crater\Models;

use App;
use PDF;
use Carbon\Carbon;
use Crater\Mail\SendInvoiceMail;
use Crater\Services\SerialNumberFormatter;
use Crater\Traits\GeneratesPdfTrait;
use Crater\Traits\HasCustomFieldsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Vinkla\Hashids\Facades\Hashids;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;
use NumberToWords\NumberToWords;
use Crater\Traits;

class Debit extends Model implements HasMedia
{
    use HasFactory;
    use HasCustomFieldsTrait;
    use InteractsWithMedia;
    use GeneratesPdfTrait;

    public const STATUS_DRAFT = 'DRAFT';
    public const STATUS_SENT = 'SENT';
    public const STATUS_VIEWED = 'VIEWED';
    public const STATUS_COMPLETED = 'COMPLETED';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'total' => 'integer',
        'tax' => 'integer',
        'sub_total' => 'integer',
        'discount' => 'float',
        'discount_val' => 'integer',
        'exchange_rate' => 'float'
    ];

    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'formattedCreatedAt',
        'formattedDebitDate',
        'debitPdfUrl'
    ];

    public function getDebitPdfUrlAttribute()
    {
        return url('/debits/pdf/'.$this->unique_hash);
    }

    public function getFormattedCreatedAtAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->created_at)->format($dateFormat);
    }

    public function getFormattedDebitDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->debit_date)->format($dateFormat);
    }

    public function getFormattedPurchaseDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->purchase_date)->format($dateFormat);
    }

    public function setPurchaseDateAttribute($value)
    {
        if ($value) {
            $this->attributes['debit_date'] = Carbon::createFromFormat('Y-m-d', $value);
        }
    }

    public function emailLogs()
    {
        return $this->morphMany('App\Models\EmailLog', 'mailable');
    }

    public function items()
    {
        return $this->hasMany('Crater\Models\DebitItem');
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Supplier::class, 'customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    
    public function preparedby()
    {
        return $this->belongsTo(PreparedBy::class, 'prepared_by_id');
    }

    public function scopeWhereCompany($query)
    {
        $query->where('debits.company_id', request()->header('company'));
    }

    public function scopeWhereStatus($query, $status)
    {
        return $query->where('debits.status', $status);
    }

    public function scopeWhereSearch($query, $search)
    {
        foreach (explode(' ', $search) as $term) {
            $query->whereHas('customer', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%'.$term.'%')
                    ->orWhere('contact_name', 'LIKE', '%'.$term.'%')
                    ->orWhere('company_name', 'LIKE', '%'.$term.'%');
            });
        }
    }

    public function scopeWhereSupplier($query, $supplier_id)
    {
        $query->where('debits.customer_id', $supplier_id);
    }

    public function scopeWhereOrder($query, $orderByField, $orderBy)
    {
        $query->orderBy($orderByField, $orderBy);
    }

    public function scopePaginateData($query, $limit)
    {
        if ($limit == 'all') {
            return $query->get();
        }

        return $query->paginate($limit);
    }

    public function scopeWhereDebitNumber($query, $debitNumber)
    {
        return $query->where('debits.debit_number', 'LIKE', '%'.$debitNumber.'%');
    }
    public function scopeWhereDebit($query, $debit_id)
    {
        $query->orWhere('id', $debit_id);
    }
    public function scopeDebitsBetween($query, $start, $end)
    {
        return $query->whereBetween(
            'debits.debit_date',
            [$start->format('Y-m-d'), $end->format('Y-m-d')]
        );
    }

    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        // if ($filters->get('search')) {
        //     $query->whereSearch($filters->get('search'));
        // }

        // if ($filters->get('status')) {
        //     $query->whereStatus($filters->get('status'));
        // }

        // if ($filters->get('paid_status')) {
        //     $query->wherePaidStatus($filters->get('status'));
        // }

        // if ($filters->get('debit_id')) {
        //     $query->whereDebit($filters->get('debit_id'));
        // }

        if ($filters->get('debit_number')) {
            $query->whereDebitNumber($filters->get('debit_number'));
        }

        if ($filters->get('from_date') && $filters->get('to_date')) {
            $start = Carbon::createFromFormat('Y-m-d', $filters->get('from_date'));
            $end = Carbon::createFromFormat('Y-m-d', $filters->get('to_date'));
            $query->debitsBetween($start, $end);
        }

        if ($filters->get('customer_id')) {
            $query->whereSupplier($filters->get('customer_id'));
        }
        if ($filters->get('status')) {
            $query->whereStatus($filters->get('status'));
        }

        if ($filters->get('orderByField') || $filters->get('orderBy')) {
            $field = $filters->get('orderByField') ? $filters->get('orderByField') : 'sequence_number';
            $orderBy = $filters->get('orderBy') ? $filters->get('orderBy') : 'desc';
            $query->whereOrder($field, $orderBy);
        }
    }

    public static function createDebitNote($request)
    {
        $data = $request->getDebitPayload();

        if ($request->has('debitSend')) {
            $data['status'] = Debit::STATUS_SENT;
        }

        if($request->discount_per_item == 'NO'){
            $data['deduction_per_item'] = 'YES';
            $data['discount_per_item'] = 'NO';
        }else{
            $data['discount_per_item'] = 'YES';
            $data['deduction_per_item'] = 'NO';
        }

        // GET SET COMPANY AND CLIENT DETAILS
        $data['company_details'] = json_encode(Company::whereId($data['company_id'])->with('address','address.country')->first());
        $data['supplier_details'] = json_encode(Supplier::whereId($data['customer_id'])->with('address', 'address.country')->first());

        $debit = Debit::create($data);
        $debit->unique_hash = Hashids::connection(Debit::class)->encode($debit->id);

        $serial = (new SerialNumberFormatter())
            ->setModel($debit)
            ->setCompany($debit->company_id, 'debit')
            ->setCustomer($debit->customer_id);

        $serial->getNextNumber();
        $debit->sequence_number = $serial->nextSequenceNumber;
        $debit->customer_sequence_number = $serial->nextCustomerSequenceNumber;
        $debit->save();

        self::createItems($debit, $request->items);

        $company_currency = CompanySetting::getSetting('currency', $request->header('company'));

        if ((string)$data['currency_id'] !== $company_currency) {
            ExchangeRateLog::addExchangeRateLog($debit);
        }
        // dd($request->has('taxes'));
        if ($request->has('taxes') && (! empty($request->taxes))) {
            self::createTaxes($debit, $request->taxes);
        }

        if ($request->customFields) {
            $debit->addCustomFields($request->customFields);
        }

        $debit = Debit::with([
            'items',
            'items.fields',
            'items.fields.customField',
            'customer',
            'taxes'
        ])
            ->find($debit->id);

        return $debit;
    }

    public static function createItems($debit, $debitItems)
    {
        $exchange_rate = $debit->exchange_rate;

        foreach ($debitItems as $debitItem) {
            $debitItem['company_id'] = $debit->company_id;
            $debitItem['exchange_rate'] = $exchange_rate;
            $debitItem['base_price'] = $debitItem['price'] * $exchange_rate;
            $debitItem['base_discount_val'] = $debitItem['discount_val'] * $exchange_rate;
            $debitItem['base_tax'] = $debitItem['tax'] * $exchange_rate;
            $debitItem['base_total'] = $debitItem['total'] * $exchange_rate;

            if (array_key_exists('recurring_invoice_id', $debitItem)) {
                unset($debitItem['recurring_invoice_id']);
            }

            $item = $debit->items()->create($debitItem);

            if (array_key_exists('taxes', $debitItem) && $debitItem['taxes']) {
                foreach ($debitItem['taxes'] as $tax) {
                    $tax['company_id'] = $debit->company_id;
                    if (gettype($tax['amount']) !== "NULL") {
                        if (array_key_exists('recurring_invoice_id', $debitItem)) {
                            unset($debitItem['recurring_invoice_id']);
                        }

                        $item->taxes()->create($tax);
                    }
                }
            }

            if (array_key_exists('custom_fields', $debitItem) && $debitItem['custom_fields']) {
                $item->addCustomFields($debitItem['custom_fields']);
            }
        }
    }

    public function send($data)
    {
        $data = $this->sendDebitNoteData($data);

        \Mail::to($data['to'])->send(new SendDebitMail($data));

        if ($this->status == Debit::STATUS_DRAFT) {
            $this->status = Debit::STATUS_SENT;
            $this->sent = true;
            $this->save();
        }

        return [
            'success' => true,
            'type' => 'send',
        ];
    }

    public function sendDebitNoteData($data)
    {
        $data['debit'] = $this->toArray();
        $data['customer'] = $this->supplier->toArray();
        $data['company'] = Company::find($this->company_id);
        $data['body'] = $this->getEmailBody($data['body']);
        $data['attach']['data'] = ($this->getEmailAttachmentSetting()) ? $this->getPDFData() : null;

        return $data;
    }

    public static function createTaxes($debit, $taxes)
    {
        $exchange_rate = $debit->exchange_rate;

        foreach ($taxes as $tax) {
            $tax['company_id'] = $debit->company_id;
            $tax['exchnage_rate'] = $debit->exchange_rate;
            $tax['base_amount'] = $tax['amount'] * $exchange_rate;
            $tax['currency_id'] = $debit->currency_id;

            if (gettype($tax['amount']) !== "NULL") {
                if (array_key_exists('recurring_invoice_id', $tax)) {
                    unset($tax['recurring_invoice_id']);
                }

                $debit->taxes()->create($tax);
            }
        }
    }

    public function getPDFData()
    {
        $taxes = collect();

        if ($this->tax_per_item === 'YES') {
            foreach ($this->items as $item) {
                foreach ($item->taxes as $tax) {
                    $found = $taxes->filter(function ($item) use ($tax) {
                        return $item->tax_type_id == $tax->tax_type_id;
                    })->first();

                    if ($found) {
                        $found->amount += $tax->amount;
                    } else {
                        $taxes->push($tax);
                    }
                }
            }
        }
        $debitTemplate = self::find($this->id)->template_name;

        $company = Company::find($this->company_id);
        // dd($company->owner);
        $locale = UserSetting::getSetting('language', $company->owner->id);
        $customFields = CustomField::with(['customFieldValues' => function ($query) {
            $query->where('custom_field_valuable_id', $this->id);
            }])->where([['model_type', 'Debit'], ['company_id', $this->company_id]])->get();
        foreach($customFields as $key => $customField)
        {
            if(count($customField->customFieldValues->toArray()) != 0 ){
                if($customField->type == 'Input' || $customField->type == 'TextArea' || $customField->type == 'Phone' || $customField->type == 'Url' || $customField->type == 'Dropdown')
                {
                    if($customField->customFieldValues->toArray()[0]['string_answer'] == null){
                        $customFields[$key]['value'] = $customField->string_answer ?? 'NA';
                    }else{
                    $customFields[$key]['value'] = $customField->customFieldValues->toArray()[0]['string_answer'];
                    }
                }
                elseif($customField->type == 'Number')
                    {
                        if($customField->customFieldValues->toArray()[0]['number_answer'] == null){
                            $customFields[$key]['value'] = $customField->number_answer ?? 'NA';
                        }else{
                        
                        $customFields[$key]['value'] = $customField->customFieldValues->toArray()[0]['number_answer'];
                        }
                    }
                elseif($customField->type == 'Switch')
                    {
                        if($customField->customFieldValues->toArray()[0]['boolean_answer'] == null){
                            $customFields[$key]['value'] = $customField->boolean_answer ?? 'NA';
                            }else{
                            $customFields[$key]['value'] = $customField->customFieldValues->toArray()[0]['boolean_answer'];
                            }   
                    }
                elseif($customField->type == 'Date')
                    {

                        if($customField->customFieldValues->toArray()[0]['date_answer'] == null){
                            $customFields[$key]['value'] = $customField->date_answer ?? 'NA';
                        }else{
                        $customFields[$key]['value'] = $customField->customFieldValues->toArray()[0]['date_answer'];
                        } 
                    }
                elseif($customField->type == 'Time')
                    {
                        if($customField->customFieldValues->toArray()[0]['time_answer'] == null){
                            $customFields[$key]['value'] = $customField->time_answer ?? 'NA';
                        }else{
                        $customFields[$key]['value'] = $customField->customFieldValues->toArray()[0]['time_answer'];
                        }
                    }
                elseif($customField->type == 'DateTime')
                    {
                        if($customField->customFieldValues->toArray()[0]['date_time_answer'] == null){
                            $customFields[$key]['value'] = $customField->date_time_answer ?? 'NA';
                        }else{
                        $customFields[$key]['value'] = $customField->customFieldValues->toArray()[0]['date_time_answer'];
                        }
                    }
                else
                    {
                        $customFields[$key]['value'] = 'NA';
                    }
                }else{
                    if($customField->type == 'Input' || $customField->type == 'TextArea' || $customField->type == 'Phone' || $customField->type == 'Url' || $customField->type == 'Dropdown')
                    {
                        $customFields[$key]['value'] = $customField->string_answer ?? 'NA';
                    }
                    elseif($customField->type == 'Number')
                        {
                            $customFields[$key]['value'] = $customField->number_answer ?? 'NA';
                        }
                    elseif($customField->type == 'Switch')
                        {
                            $customFields[$key]['value'] = $customField->boolean_answer ?? 'NA';
                        }
                    elseif($customField->type == 'Date')
                        {
                            $customFields[$key]['value'] = $customField->date_answer ?? 'NA';
                        }
                    elseif($customField->type == 'Time')
                        {
                            $customFields[$key]['value'] = $customField->time_answer ?? 'NA';
                        }
                    elseif($customField->type == 'DateTime')
                        {
                            $customFields[$key]['value'] = $customField->date_time_answer ?? 'NA';
                        }
                    else
                        {
                            $customFields[$key]['value'] = 'NA';
                        }
                }
        }

        App::setLocale($locale);

        $logo = $company->logo_path;
        $letterhead = $company->letterhead_path;
        $displayQRCodeAsBase64 = GenerateQrCode::fromArray([
            new Seller($this->customer->company->name), // seller name        
            new TaxNumber($this->company->vat),
            new InvoiceDate(date('Y-m-d h:i:s A',strtotime($this->debit_date))),
            new InvoiceTotalAmount($this->total/100),
            new InvoiceTaxAmount($this->tax/100)
        ])->render();

        // dd($this);
        $numberToWords = new NumberToWords();
        $currencyTransformer_ar = $numberToWords->getCurrencyTransformer('ar');
        
        $total_amount_in_ar = $currencyTransformer_ar->toWords($this->total, 'SAR');
        $currencyTransformer_en = $numberToWords->getCurrencyTransformer('en');
        
        $total_amount_in_en = $currencyTransformer_en->toWords($this->total, 'SAR');

        $notes = json_decode($this->notes);
        $notes_description = $notes_heading = '';
        if($notes){
            $notes_description = $notes->description;
            $notes_heading = $notes->heading;
        }

        view()->share([
            'qrcode' => $displayQRCodeAsBase64 ?? '',
            'invoice' => $this,
            'customFields' => $customFields,
            'company_details' => json_decode($this->company_details, true),
            'supplier_details' => json_decode($this->supplier_details, true),
            // 'company' =>$this->company,
            // 'customer' =>$this->customer,
            'company' => json_decode($this->company_details),
            'customer' => json_decode($this->supplier_details),
            'customer_cr_number' =>$this->customer->prefix ?? 'NA',
            'customer_vat_number' =>$this->customer->website ?? 'NA',
            'company_addresss' => $this->getCompanyAddress(),
            "total_amount_in_ar" => $total_amount_in_ar,
            "total_amount_in_en" => $total_amount_in_en,
            'company_address' =>array_merge($this->getFieldsArray(), $this->getExtraFields()),
            // 'shipping_address' => $this->getCustomerShippingAddress(),
            'billing_address' => $this->getCustomerBillingAddress(),
            'notes' => $this->getNotes($notes_description),
            'note_heading' => ucwords($notes_heading),
            'logo' => $logo ?? null,
            'letterhead' => $letterhead ?? null,
            'taxes' => $taxes,
            'signature' => $this->preparedby ?? null,
            'signature_image' => $this->preparedby ? $this->preparedby->signature_image : null
        ]);
        
        return PDF::loadView('app.pdf.debit_note.'.$debitTemplate);
    }

    public function getCompanyAddress()
    {
        if ($this->company && (! $this->company->address()->exists())) {
            return false;
        }
        $format = CompanySetting::getSetting('invoice_company_address_format', $this->company_id);
        return $this->getFormattedString($format);
    }

    public function getExtraFields()
    {
        return [
            '{DEBIT_DATE}' => $this->formattedInvoiceDate,
            '{DEBIT_NUMBER}' => $this->invoice_number,
            '{DEBIT_REF_NUMBER}' => $this->reference_number,
            '{DEBIT_LINK}' => url('/customer/debits/pdf/'.$this->unique_hash),
        ];
    }

    public function getCustomerBillingAddress()
    {
        if ($this->customer && (! $this->customer->billingAddress()->exists())) {
            return false;
        }

        $format = CompanySetting::getSetting('invoice_billing_address_format', $this->company_id);

        return $this->getFormattedString($format);
    }

    public function getNotes($description)
    {
        return $this->getFormattedString($description);
    }

    public function getEmailBody($body)
    {
        $values = array_merge($this->getFieldsArray(), $this->getExtraFields());

        $body = strtr($body, $values);

        return preg_replace('/{(.*?)}/', '', $body);
    }

    public function updateDebitNote($request)
    {
        $data = $request->getDebitPayload();
        // dd($data);
        
        if($this->is_edit == '1'){
        $data['company_details'] = json_encode(Company::whereId($data['company_id'])->with('address','address.country')->first());
        $data['supplier_details'] = json_encode(Supplier::whereId($data['customer_id'])->with('address', 'address.country')->first());
            $this->update($data);
        }else{
            $this->update([
                'upper_margin' => $data['upper_margin'],
                'lower_margin' => $data['lower_margin'],
                'template_name' => $data['template_name'],
            ]);
            $debit_note = Debit::with([
            'items',
            'items.fields',
            'items.fields.customField',
            'customer',
            'taxes'
        ])
            ->find($this->id);

        return $debit_note;
        }

        $company_currency = CompanySetting::getSetting('currency', $request->header('company'));

        if ((string)$data['currency_id'] !== $company_currency) {
            ExchangeRateLog::addExchangeRateLog($this);
        }

        $this->items->map(function ($item) {
            $fields = $item->fields()->get();

            $fields->map(function ($field) {
                $field->delete();
            });
        });

        $this->items()->delete();
        $this->taxes()->delete();

        self::createItems($this, $request->items);

        if ($request->has('taxes') && (! empty($request->taxes))) {
            self::createTaxes($this, $request->taxes);
        }

        // if ($request->customFields) {
        //     $this->updateCustomFields($request->customFields);
        // }

        $debit_note = Debit::with([
            'items',
            'items.fields',
            'items.fields.customField',
            'customer',
            'taxes'
        ])
            ->find($this->id);

        return $debit_note;
    }

    public static function debitNoteTemplates()
    {
        $templates = Storage::disk('views')->files('/app/pdf/debit_note');
        $debitNoteTemplates = [];

        foreach ($templates as $key => $template) {
            $templateName = Str::before(basename($template), '.blade.php');
            $debitNoteTemplates[$key]['name'] = $templateName;
            $debitNoteTemplates[$key]['path'] = vite_asset('img/PDF/'.$templateName.'.png');
        }

        return $debitNoteTemplates;
    }

    public function scopeWhereCompanyId($query, $company)
    {
        $query->where('debits.company_id', $company);
    }

    public function getFilterPDFData($debits, $query_data, $date_heading)
    {
        view()->share([
            'debits' => $debits,
            'date_heading' => $date_heading,
            'query_data' => $query_data
        ]);
        return PDF::loadView('app.pdf.filtered.debit');
    }
}
