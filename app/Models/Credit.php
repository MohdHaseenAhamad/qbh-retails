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
use Crater\Models\Invoice;

class Credit extends Model implements HasMedia
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
        'formattedCreditDate',
        'creditPdfUrl'
    ];

    public function getCreditPdfUrlAttribute()
    {
        return url('/credits/pdf/'.$this->unique_hash);
    }

    public function getFormattedCreatedAtAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->created_at)->format($dateFormat);
    }

    public function getFormattedCreditDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->credit_date)->format($dateFormat);
    }

    public function getFormattedInvoiceDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->invoice_date)->format($dateFormat);
    }

    public function setInvoiceDateAttribute($value)
    {
        if ($value) {
            $this->attributes['credit_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $value);
        }
    }

    public function emailLogs()
    {
        return $this->morphMany('App\Models\EmailLog', 'mailable');
    }

    public function items()
    {
        return $this->hasMany('Crater\Models\CreditItem');
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    
    public function preparedby()
    {
        return $this->belongsTo(PreparedBy::class, 'prepared_by_id');
    }

    public function scopeWhereCompanyId($query, $company)
    {
        $query->where('credits.company_id', $company);
    }

    public function scopeWhereCompany($query)
    {
        $query->where('credits.company_id', request()->header('company'));
    }

    public function scopeWhereStatus($query, $status)
    {
        return $query->where('credits.status', $status);
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

    public function scopeWhereCustomer($query, $customer_id)
    {
        $query->where('credits.customer_id', $customer_id);
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

    public function scopeWhereCreditNumber($query, $creditNumber)
    {
        return $query->where('credits.credit_number', 'LIKE', '%'.$creditNumber.'%');
    }
    public function scopeWhereCredit($query, $credit_id)
    {
        $query->orWhere('id', $credit_id);
    }
    public function scopeCreditsBetween($query, $start, $end)
    {
        if($start){
            $query->where('credits.credit_date','>=',$start->format('Y-m-d'));
        }
        if($end){
            $query->where('credits.credit_date','<=',$end->format('Y-m-d'));
        }
        return $query;
    }

    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        if ($filters->get('search')) {
            $query->whereSearch($filters->get('search'));
        }

        if ($filters->get('status')) {
            $query->whereStatus($filters->get('status'));
        }

        if ($filters->get('paid_status')) {
            $query->wherePaidStatus($filters->get('status'));
        }

        if ($filters->get('credit_id')) {
            $query->whereCredit($filters->get('credit_id'));
        }

        if ($filters->get('credit_number')) {
            $query->whereCreditNumber($filters->get('credit_number'));
        }

        if ($filters->get('from_date') || $filters->get('to_date')) {
            $start = $filters->get('from_date') ? Carbon::createFromFormat('Y-m-d', $filters->get('from_date')) : '';
            $end = $filters->get('to_date') ? Carbon::createFromFormat('Y-m-d', $filters->get('to_date')) : '';
            $query->creditsBetween($start, $end);
        }

        if ($filters->get('customer_id')) {
            $query->whereCustomer($filters->get('customer_id'));
        }

        if ($filters->get('orderByField') || $filters->get('orderBy')) {
            $field = $filters->get('orderByField') ? $filters->get('orderByField') : 'sequence_number';
            $orderBy = $filters->get('orderBy') ? $filters->get('orderBy') : 'desc';
            $query->whereOrder($field, $orderBy);
        }
    }

    public static function createCreditNote($request)
    {
        $data = $request->getCreditPayload();
        if ($request->has('creditSend')) {
            $data['status'] = Credit::STATUS_SENT;
        }

        if($request->discount_per_item == 'NO'){
            $data['deduction_per_item'] = 'YES';
            $data['discount_per_item'] = 'NO';
        }else{
            $data['discount_per_item'] = 'YES';
            $data['deduction_per_item'] = 'NO';
        }

        $invoice = Invoice::where('id', $data['invoice_id'])->first();
        if($invoice && $invoice->status == 'COMPLETED'){
            $invoice->update(['status' => Invoice::STATUS_CANCEL]);
            $payments = $invoice->payments;
            foreach($payments as $payment){
                $payment->update(['status' => Payment::STATUS_CANCEL]);
            }
        }
        // GET SET COMPANY AND CLIENT DETAILS
        $data['company_details'] = json_encode(Company::whereId($data['company_id'])->with('address','address.country')->first());
        $data['client_details'] = json_encode(Customer::whereId($data['customer_id'])->with('address', 'address.country')->first());
        $credit = Credit::create($data);
        $credit->unique_hash = Hashids::connection(Credit::class)->encode($credit->id);

        $serial = (new SerialNumberFormatter())
            ->setModel($credit)
            ->setCompany($credit->company_id, 'credit')
            ->setCustomer($credit->customer_id);

        $serial->getNextNumber();
        $credit->sequence_number = $serial->nextSequenceNumber;
        $credit->customer_sequence_number = $serial->nextCustomerSequenceNumber;

        $credit->save();

        self::createItems($credit, $request->items);

        $company_currency = CompanySetting::getSetting('currency', $request->header('company'));

        if ((string)$data['currency_id'] !== $company_currency) {
            ExchangeRateLog::addExchangeRateLog($credit);
        }
        // dd($request->has('taxes'));
        if ($request->has('taxes') && (! empty($request->taxes))) {
            self::createTaxes($credit, $request->taxes);
        }

        if ($request->customFields) {
            $credit->addCustomFields($request->customFields);
        }

        $credit = Credit::with([
            'items',
            'items.fields',
            'items.fields.customField',
            'customer',
            'taxes'
        ])
            ->find($credit->id);

        return $credit;
    }

    public static function createItems($credit, $creditItems)
    {
        $exchange_rate = $credit->exchange_rate;

        foreach ($creditItems as $creditItem) {
            $creditItem['company_id'] = $credit->company_id;
            $creditItem['exchange_rate'] = $exchange_rate;
            $creditItem['base_price'] = $creditItem['price'] * $exchange_rate;
            $creditItem['base_discount_val'] = $creditItem['discount_val'] * $exchange_rate;
            $creditItem['base_tax'] = $creditItem['tax'] * $exchange_rate;
            $creditItem['base_total'] = $creditItem['total'] * $exchange_rate;

            if (array_key_exists('recurring_invoice_id', $creditItem)) {
                unset($creditItem['recurring_invoice_id']);
            }

            $item = $credit->items()->create($creditItem);

            if (array_key_exists('taxes', $creditItem) && $creditItem['taxes']) {
                foreach ($creditItem['taxes'] as $tax) {
                    $tax['company_id'] = $credit->company_id;
                    if (gettype($tax['amount']) !== "NULL") {
                        if (array_key_exists('recurring_invoice_id', $creditItem)) {
                            unset($creditItem['recurring_invoice_id']);
                        }

                        $item->taxes()->create($tax);
                    }
                }
            }

            if (array_key_exists('custom_fields', $creditItem) && $creditItem['custom_fields']) {
                $item->addCustomFields($creditItem['custom_fields']);
            }
        }
    }

    public function send($data)
    {
        $data = $this->sendCreditNoteData($data);

        \Mail::to($data['to'])->send(new SendInvoiceMail($data));

        if ($this->status == Invoice::STATUS_DRAFT) {
            $this->status = Invoice::STATUS_SENT;
            $this->sent = true;
            $this->save();
        }

        return [
            'success' => true,
            'type' => 'send',
        ];
    }

    public function sendCreditNoteData($data)
    {
        $data['invoice'] = $this->toArray();
        $data['customer'] = $this->customer->toArray();
        $data['company'] = Company::find($this->company_id);
        $data['body'] = $this->getEmailBody($data['body']);
        $data['attach']['data'] = ($this->getEmailAttachmentSetting()) ? $this->getPDFData() : null;

        return $data;
    }

    public static function createTaxes($credit, $taxes)
    {
        $exchange_rate = $credit->exchange_rate;

        foreach ($taxes as $tax) {
            $tax['company_id'] = $credit->company_id;
            $tax['exchnage_rate'] = $credit->exchange_rate;
            $tax['base_amount'] = $tax['amount'] * $exchange_rate;
            $tax['currency_id'] = $credit->currency_id;

            if (gettype($tax['amount']) !== "NULL") {
                if (array_key_exists('recurring_invoice_id', $tax)) {
                    unset($tax['recurring_invoice_id']);
                }

                $credit->taxes()->create($tax);
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
        $invoiceTemplate = self::find($this->id)->template_name;

        $company = Company::find($this->company_id);
        // dd($company->owner);
        $locale = UserSetting::getSetting('language', $company->owner->id);
        $customFields = CustomField::with(['customFieldValues' => function ($query) {
            $query->where('custom_field_valuable_id', $this->id);
            }])->where([['model_type', 'Invoice'], ['company_id', $this->company_id]])->get();
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
// dd(date('Y-m-d h:i:s A',strtotime($this->credit_date)));
        $displayQRCodeAsBase64 = GenerateQrCode::fromArray([
            new Seller($this->invoice->customer->company->name), // seller name        
            new TaxNumber($this->invoice->company->vat),
            new InvoiceDate(date('Y-m-d h:i:s A',strtotime($this->credit_date))),
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
            'client_details' => json_decode($this->client_details, true),
            // 'company' =>$this->company,
            // 'customer' =>$this->customer,
            'company' => json_decode($this->company_details),
            'customer' => json_decode($this->client_details),
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
        
        return PDF::loadView('app.pdf.credit_note.'.$invoiceTemplate);
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
            '{INVOICE_DATE}' => $this->formattedInvoiceDate,
            '{INVOICE_DUE_DATE}' => $this->formattedDueDate,
            '{INVOICE_NUMBER}' => $this->invoice_number,
            '{INVOICE_REF_NUMBER}' => $this->reference_number,
            '{INVOICE_LINK}' => url('/customer/credits/pdf/'.$this->unique_hash),
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

    public function updateCreditNote($request)
    {
        $data = $request->getCreditPayload();
        // $oldTotal = $this->total;

        // $total_paid_amount = $this->total - $this->due_amount;

        // if ($total_paid_amount > 0 && $this->customer_id !== $request->customer_id) {
        //     return 'customer_cannot_be_changed_after_payment_is_added';
        // }

        // if ($request->total < $total_paid_amount) {
        //     return 'total_invoice_amount_must_be_more_than_paid_amount';
        // }

        // if ($oldTotal != $request->total) {
        //     $oldTotal = (int) round($request->total) - (int) $oldTotal;
        // } else {
        //     $oldTotal = 0;
        // }
        // if($request->discount_per_item == 'NO'){
        //     $data['deduction_per_item'] = 'YES';
        //     $data['discount_per_item'] = 'NO';
        // }else{
        //     $data['discount_per_item'] = 'YES';
        //     $data['deduction_per_item'] = 'NO';
        // }

        // $data['due_amount'] = ($this->due_amount + $oldTotal);
        // $data['base_due_amount'] = $data['due_amount'] * $data['exchange_rate'];
        // $data['customer_sequence_number'] = $serial->nextCustomerSequenceNumber;

        // $this->changeInvoiceStatus($data['due_amount']);

        if($this->is_edit_credit == '1'){
        $data['company_details'] = json_encode(Company::whereId($data['company_id'])->with('address','address.country')->first());
        $data['client_details'] = json_encode(Customer::whereId($data['customer_id'])->with('address', 'address.country')->first());
            $this->update($data);
        }else{
            $credit_note = Credit::with([
            'items',
            'items.fields',
            'items.fields.customField',
            'customer',
            'taxes'
        ])->find($this->id);

        return $credit_note;
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

        $credit_note = Credit::with([
            'items',
            'items.fields',
            'items.fields.customField',
            'customer',
            'taxes'
        ])
            ->find($this->id);

        return $credit_note;
    }

    public static function creditNoteTemplates()
    {
        $templates = Storage::disk('views')->files('/app/pdf/credit_note');
        $creditNoteTemplates = [];

        foreach ($templates as $key => $template) {
            $templateName = Str::before(basename($template), '.blade.php');
            $creditNoteTemplates[$key]['name'] = $templateName;
            $creditNoteTemplates[$key]['path'] = vite_asset('img/PDF/'.$templateName.'.png');
        }

        return $creditNoteTemplates;
    }

    public function getFilterPDFData($credits, $query_data, $date_heading)
    {
        view()->share([
            'credits' => $credits,
            'date_heading' => $date_heading,
            'query_data' => $query_data
        ]);
            return PDF::loadView('app.pdf.filtered.credit');
    }
}
