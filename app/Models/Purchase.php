<?php

namespace Crater\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Crater\Mail\SendPurchaseMail;
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
use App;
use PDF;

class Purchase extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use GeneratesPdfTrait;
    use HasCustomFieldsTrait;

    public const STATUS_DRAFT = 'DRAFT';
    public const STATUS_SENT = 'SENT';
    public const STATUS_VIEWED = 'VIEWED';
    public const STATUS_OVERDUE = 'OVERDUE';
    public const STATUS_COMPLETED = 'COMPLETED';

    public const STATUS_DUE = 'DUE';
    public const STATUS_UNPAID = 'UNPAID';
    public const STATUS_PARTIALLY_PAID = 'PARTIALLY_PAID';
    public const STATUS_PAID = 'PAID';

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
        'formattedInvoiceDate',
        'formattedPurchaseDate',
        'formattedOrderDate',
        'formattedSupplyDate',
        'purchasePdfUrl',
    ];

    public function setPurchaseDateAttribute($value)
    {
        if ($value) {
            $this->attributes['purchase_date'] = Carbon::createFromFormat('Y-m-d', $value);
        }
    }

    public function setDueDateAttribute($value)
    {
        if ($value) {
            $this->attributes['due_date'] = Carbon::createFromFormat('Y-m-d', $value);
        }
    }

    public function emailLogs()
    {
        return $this->morphMany('App\Models\EmailLog', 'mailable');
    }

    public function items()
    {
        return $this->hasMany('Crater\Models\PurchaseItem');
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function preparedby()
    {
        return $this->belongsTo(PreparedBy::class, 'prepared_by_id');
    }


    public function getPurchasePdfUrlAttribute()
    {
        return url('/purchase/pdf/'.$this->unique_hash);
    }

    public function getAllowEditAttribute()
    {
        $retrospective_edit = CompanySetting::getSetting('retrospective_edits', $this->company_id);

        $allowed = true;

        $status = [
            self::STATUS_DRAFT,
            self::STATUS_SENT,
            self::STATUS_VIEWED,
            self::STATUS_OVERDUE,
            self::STATUS_COMPLETED,
        ];

        if ($retrospective_edit == 'disable_on_purchase_sent' && (in_array($this->status, $status)) && ($this->paid_status === Purchase::STATUS_PARTIALLY_PAID || $this->paid_status === Purchase::STATUS_PAID)) {
            $allowed = false;
        } elseif ($retrospective_edit == 'disable_on_purchase_partial_paid' && ($this->paid_status === Purchase::STATUS_PARTIALLY_PAID || $this->paid_status === Purchase::STATUS_PAID)) {
            $allowed = false;
        } elseif ($retrospective_edit == 'disable_on_purchase_paid' && $this->paid_status === Purchase::STATUS_PAID) {
            $allowed = false;
        }

        return $allowed;
    }

    public function getPreviousStatus()
    {
        if ($this->due_date < Carbon::now()) {
            return self::STATUS_OVERDUE;
        } elseif ($this->viewed) {
            return self::STATUS_VIEWED;
        } elseif ($this->sent) {
            return self::STATUS_SENT;
        } else {
            return self::STATUS_COMPLETED;
        }
    }

    public function getFormattedCreatedAtAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->created_at)->format($dateFormat);
    }

    public function getFormattedDueDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->due_date)->format($dateFormat);
    }

    public function getFormattedPurchaseDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->purchase_date)->format($dateFormat);
    }

    public function getFormattedInvoiceDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->invoice_date)->format($dateFormat);
    }

    public function getFormattedOrderDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->order_date)->format($dateFormat);
    }

    public function getFormattedSupplyDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->supply_date)->format($dateFormat);
    }
 
    public function scopeWhereStatus($query, $status)
    {
        return $query->where('purchases.status', $status);
    }

    public function scopeWherePaidStatus($query, $status)
    {
        return $query->where('purchases.paid_status', $status);
    }

    public function scopeWhereDueStatus($query, $status)
    {
        return $query->whereIn('purchases.paid_status', [
            self::STATUS_UNPAID,
            self::STATUS_PARTIALLY_PAID,
        ]);
    }

    public function scopeWherePurchaseNumber($query, $purchaseNumber)
    {
        return $query->where('purchases.purchase_no', 'LIKE', '%'.$purchaseNumber.'%');
    }

    public function scopePurchasesBetween($query, $start, $end)
    {
        return $query->whereBetween(
            'purchases.purchase_date',
            [$start->format('Y-m-d'), $end->format('Y-m-d')]
        );
    }

    public function scopeWhereSearch($query, $search)
    {
        foreach (explode(' ', $search) as $term) {
            $query->whereHas('supplier', function ($query) use ($term) {
                $query->where('name', 'LIKE', '%'.$term.'%')
                    ->orWhere('contact_name', 'LIKE', '%'.$term.'%')
                    ->orWhere('company_name', 'LIKE', '%'.$term.'%');
            });
        }
    }

    public function scopeWherePerformaNull($query)
    {
        $query->doesntHave('performa');
    }

    public function scopeWhereOrder($query, $orderByField, $orderBy)
    {
        $query->orderBy($orderByField, $orderBy);
    }

    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        if ($filters->get('search')) {
            $query->whereSearch($filters->get('search'));
        }
        if ($filters->get('notCreated')) {
            $query->wherePerformaNull();
        }

        // if ($filters->get('status')) {
        //     if (
        //         $filters->get('status') == self::STATUS_UNPAID ||
        //         $filters->get('status') == self::STATUS_PARTIALLY_PAID ||
        //         $filters->get('status') == self::STATUS_PAID
        //     ) {
        //         $query->wherePaidStatus($filters->get('status'));
        //     } elseif ($filters->get('status') == self::STATUS_DUE) {
        //         $query->whereDueStatus($filters->get('status'));
        //     } else {
        //         $query->whereStatus($filters->get('status'));
        //     }
        // }

        // if ($filters->get('paid_status')) {
        //     $query->wherePaidStatus($filters->get('status'));
        // }

        if ($filters->get('purchase_id')) {
            $query->wherePurchase($filters->get('purchase_id'));
        }

        if ($filters->get('purchase_no')) {
            $query->wherePurchaseNumber($filters->get('purchase_no'));
        }

        if ($filters->get('from_date') && $filters->get('to_date')) {
            if(($filters->get('from_date') != '' || $filters->get('from_date') != null) && ($filters->get('to_date') != '' || $filters->get('to_date') != null)){
                $start = Carbon::createFromFormat('Y-m-d', $filters->get('from_date'));
                $end = Carbon::createFromFormat('Y-m-d', $filters->get('to_date'));
                $query->purchasesBetween($start, $end);
            }
        }

        if ($filters->get('supplier_id')) {
            $query->whereSupplier($filters->get('supplier_id'));
        }

        if ($filters->get('orderByField') || $filters->get('orderBy')) {
            $field = $filters->get('orderByField') ? $filters->get('orderByField') : 'sequence_number';
            $orderBy = $filters->get('orderBy') ? $filters->get('orderBy') : 'desc';
            $query->whereOrder($field, $orderBy);
        }
    }

    public function scopeWherePurchase($query, $purchase_id)
    {
        $query->orWhere('id', $purchase_id);
    }

    public function scopeWhereCompany($query)
    {
        $query->where('purchases.company_id', request()->header('company'));
    }

    public function scopeWhereCompanyId($query, $company)
    {
        $query->where('purchases.company_id', $company);
    }

    public function scopeWhereSupplier($query, $supplier_id)
    {
        $query->where('purchases.supplier_id', $supplier_id);
    }

    public function scopePaginateData($query, $limit)
    {
        if ($limit == 'all') {
            return $query->get();
        }

        return $query->paginate($limit);
    }

    public static function createPurchase($request)
    {
        // dd($request->all());
        $data = $request->getPurchasePayload();
        if ($request->has('purchaseSend')) {
            $data['status'] = Purchase::STATUS_SENT;
        }
        // dd($data['customer']['billing']['zip']);
        $company = Company::where('id', $request->header('company'))->first();
        
        // GET SET COMPANY AND CLIENT DETAILS
        $data['company_details'] = json_encode(Company::whereId($data['company_id'])->with('address','address.country')->first());
        $data['supplier_details'] = json_encode(Supplier::whereId($data['customer_id'])->with('address', 'address.country')->first());
        $data['status'] = self::STATUS_COMPLETED;
        if($request->discount_per_item == 'NO'){
            $data['deduction_per_item'] = 'YES';
            $data['discount_per_item'] = 'NO';
        }else{
            $data['discount_per_item'] = 'YES';
            $data['deduction_per_item'] = 'NO';
        }
        $purchase = Purchase::create($data);

        $serial = (new SerialNumberFormatter())
            ->setModel($purchase)
            ->setCompany($purchase->company_id, 'purchase')
            ->setCustomer($purchase->purchase_id);
        $serial->getNextNumber();

        $purchase->sequence_number = $serial->nextSequenceNumber;
        $purchase->customer_sequence_number = $serial->nextCustomerSequenceNumber;
        $purchase->unique_hash = Hashids::connection(Purchase::class)->encode($purchase->id);
        $purchase->save();

        self::createItems($purchase, $request->items);

        $company_currency = CompanySetting::getSetting('currency', $request->header('company'));

        if ((string)$data['currency_id'] !== $company_currency) {
            ExchangeRateLog::addExchangeRateLog($purchase);
        }
        // dd($request->has('taxes'));
        if ($request->has('taxes') && (! empty($request->taxes))) {
            self::createTaxes($purchase, $request->taxes);
        }

        if ($request->customFields) {
            $purchase->addCustomFields($request->customFields);
        }

        $purchase = purchase::with([
            'items',
            'items.fields',
            'items.fields.customField',
            'supplier',
            'taxes'
        ])
            ->find($purchase->id);

        return $purchase;
    }

    public function updatePurchase($request)
    {

        $data = $request->getPurchasePayload();
        $oldTotal = $this->total;
        $total_paid_amount = $this->total - $this->due_amount;

        if ($oldTotal != $request->total) {
            $oldTotal = (int) round($request->total) - (int) $oldTotal;
        } else {
            $oldTotal = 0;
        }
        if($request->discount_per_item == 'NO'){
            $data['deduction_per_item'] = 'YES';
            $data['discount_per_item'] = 'NO';
        }else{
            $data['discount_per_item'] = 'YES';
            $data['deduction_per_item'] = 'NO';
        }

        $data['due_amount'] = ($this->due_amount + $oldTotal);
        $data['base_due_amount'] = $data['due_amount'] * $data['exchange_rate'];
        // $data['customer_sequence_number'] = $serial->nextCustomerSequenceNumber;

        $this->changePurchaseStatus($data['due_amount']);

        $this->update($data);

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

        if ($request->customFields) {
            $this->updateCustomFields($request->customFields);
        }

        $purchase = Purchase::with([
            'items',
            'items.fields',
            'items.fields.customField',
            'supplier',
            'taxes'
        ])->find($this->id);

        return $purchase;
    }

    public function sendPurchaseData($data)
    {
        $data['purchase'] = $this->toArray();
        $data['customer'] = $this->supplier->toArray();
        $data['company'] = Company::find($this->company_id);
        $data['body'] = $this->getEmailBody($data['body']);
        $data['attach']['data'] = ($this->getEmailAttachmentSetting()) ? $this->getPDFData() : null;

        return $data;
    }

    public function preview($data)
    {
        $data = $this->sendPurchaseData($data);

        return [
            'type' => 'preview',
            'view' => new SendPurchaseMail($data)
        ];
    }

    public function send($data)
    {
        $data = $this->sendPurchaseData($data);

        \Mail::to($data['to'])->send(new SendPurchaseMail($data));

        if ($this->status == Purchase::STATUS_COMPLETED) {
            $this->status = Purchase::STATUS_SENT;
            $this->sent = true;
            $this->save();
        }

        return [
            'success' => true,
            'type' => 'send',
        ];
    }

    public static function createItems($purchase, $purchaseItems)
    {
        $exchange_rate = $purchase->exchange_rate;

        foreach ($purchaseItems as $purchaseItem) {
            $purchaseItem['company_id'] = $purchase->company_id;
            $purchaseItem['exchange_rate'] = $exchange_rate;
            $purchaseItem['base_price'] = $purchaseItem['price'] * $exchange_rate;
            $purchaseItem['base_discount_val'] = $purchaseItem['discount_val'] * $exchange_rate;
            $purchaseItem['base_tax'] = $purchaseItem['tax'] * $exchange_rate;
            $purchaseItem['base_total'] = $purchaseItem['total'] * $exchange_rate;

            if (array_key_exists('recurring_invoice_id', $purchaseItem)) {
                unset($purchaseItem['recurring_invoice_id']);
            }

            $item = $purchase->items()->create($purchaseItem);

            if (array_key_exists('taxes', $purchaseItem) && $purchaseItem['taxes']) {
                foreach ($purchaseItem['taxes'] as $tax) {
                    $tax['company_id'] = $purchase->company_id;
                    if (gettype($tax['amount']) !== "NULL") {
                        if (array_key_exists('recurring_invoice_id', $purchaseItem)) {
                            unset($purchaseItem['recurring_invoice_id']);
                        }

                        $item->taxes()->create($tax);
                    }
                }
            }

            if (array_key_exists('custom_fields', $purchaseItem) && $purchaseItem['custom_fields']) {
                $item->addCustomFields($purchaseItem['custom_fields']);
            }
        }
    }

    public static function createTaxes($purchase, $taxes)
    {
        $exchange_rate = $purchase->exchange_rate;

        foreach ($taxes as $tax) {
            $tax['company_id'] = $purchase->company_id;
            $tax['exchnage_rate'] = $purchase->exchange_rate;
            $tax['base_amount'] = $tax['amount'] * $exchange_rate;
            $tax['currency_id'] = $purchase->currency_id;

            if (gettype($tax['amount']) !== "NULL") {
                if (array_key_exists('recurring_invoice_id', $tax)) {
                    unset($tax['recurring_invoice_id']);
                }

                $purchase->taxes()->create($tax);
            }
        }
    }
    
    public function getFilterPDFData($purchases, $query_data, $date_heading)
    {
        // dd($query_data);
        
        view()->share([
            'purchases' => $purchases,
            'date_heading' => $date_heading,
            'query_data' => $query_data
        ]);
            return PDF::loadView('app.pdf.filtered.purchase');
    }

    public function getPDFData($type = '')
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

        $purchaseTemplate = self::find($this->id)->template_name;

        $company = Company::find($this->company_id);
        // dd($company->owner);
        $locale = UserSetting::getSetting('language', $company->owner->id);
        $customFields = CustomField::with(['customFieldValues' => function ($query) {
            $query->where('custom_field_valuable_id', $this->id);
            }])->where([['model_type', 'Purchase'], ['company_id', $this->company_id]])->get();
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
        // dd($this->supplier->company->name);
        $logo = $company->logo_path;
        $letterhead = $company->letterhead_path;
        $numberToWords = new NumberToWords();
        $currencyTransformer_ar = $numberToWords->getCurrencyTransformer('ar');
        
        $total_amount_in_ar = $currencyTransformer_ar->toWords($this->due_amount, 'SAR');
        $currencyTransformer_en = $numberToWords->getCurrencyTransformer('en');
        
        $total_amount_in_en = $currencyTransformer_en->toWords($this->due_amount, 'SAR');
        view()->share([
            'purchase' => $this,
            // 'customFields' => $customFields,
            // 'company' =>$this->company,
            // 'customer' =>$this->customer,
            'company' =>json_decode($this->company_details),
            'supplier' =>json_decode($this->supplier_details),
            'customer_cr_number' =>$this->customer->prefix ?? 'NA',
            'customer_vat_number' =>$this->customer->website ?? 'NA',
            // 'company_addresss' => $this->getCompanyAddress(),
            "total_amount_in_ar" => $total_amount_in_ar,
            "total_amount_in_en" => $total_amount_in_en,
            // 'company_address' =>array_merge($this->getFieldsArray(), $this->getExtraFields()),
            // 'shipping_address' => $this->getCustomerShippingAddress(),
            // 'billing_address' => $this->getCustomerBillingAddress(),
            // 'notes' => $this->getNotes(),
            // 'note_heading' => $this->getNoteHeading($this->notes),
            'logo' => $logo ?? null,
            'letterhead' => $letterhead ?? null,
            'taxes' => $taxes,
            'signature' => $this->preparedby ?? null,
            'signature_image' => $this->preparedby != null ? ( $this->preparedby->signature_image ?? '') : ''
        ]);
        
        // return PDF::loadView('app.pdf.purchase.'.$purchaseTemplate);
        return PDF::loadView('app.pdf.purchase.purchase1');
    }

    public function getEmailAttachmentSetting()
    {
        $purchaseAsAttachment = CompanySetting::getSetting('purchase_email_attachment', $this->company_id);

        if ($purchaseAsAttachment == 'NO') {
            return false;
        }

        return true;
    }

    public function getCompanyAddress()
    {
        if ($this->company && (! $this->company->address()->exists())) {
            return false;
        }
        //
        $format = CompanySetting::getSetting('purchase_company_address_format', $this->company_id);
        // return $this->getFormattedString($format);
        return $format;
    }

    public function getSupplierShippingAddress()
    {
        if ($this->supplier && (! $this->supplier->shippingAddress()->exists())) {
            return false;
        }

        $format = CompanySetting::getSetting('purchase_shipping_address_format', $this->company_id);

        return $this->getFormattedString($format);
    }

    public function getSupplierBillingAddress()
    {
        if ($this->supplier && (! $this->supplier->billingAddress()->exists())) {
            return false;
        }

        $format = CompanySetting::getSetting('purchase_billing_address_format', $this->company_id);

        // return $this->getFormattedString($format);
        return $format;
    }

    public function getNotes()
    {
        // return $this->getFormattedString($this->notes);
        return $this->notes;
    }

    public function getEmailBody($body)
    {
        $values = array_merge($this->getFieldsArray(), $this->getExtraFields());

        $body = strtr($body, $values);

        return preg_replace('/{(.*?)}/', '', $body);
    }

    public function getExtraFields()
    {
        return [
            '{PURCHASE_NUMBER}' => $this->purchase_no,
            '{INVOICE_NUMBER}' => $this->invoice_no,
            '{PURCHASE_DATE}' => $this->formattedPurchaseDate,
            '{INVOICE_DATE}' => $this->formattedInvoiceDate,
            '{PURCHASE_LINK}' => url('/customer/purchases/pdf/'.$this->unique_hash),
        ];
    }

    public static function purchaseTemplates()
    {
        $templates = Storage::disk('views')->files('/app/pdf/purchase');
        $purchaseTemplates = [];

        foreach ($templates as $key => $template) {
            $templateName = Str::before(basename($template), '.blade.php');
            $purchaseTemplates[$key]['name'] = $templateName;
            $purchaseTemplates[$key]['path'] = vite_asset('img/PDF/'.$templateName.'.png');
        }

        return $purchaseTemplates;
    }

    public function addPurchasePayment($amount)
    {
        $this->due_amount += $amount;
        $this->base_due_amount = $this->due_amount * $this->exchange_rate;

        $this->changePurchaseStatus($this->due_amount);
    }

    public function subtractPurchasePayment($amount)
    {
        $this->due_amount -= $amount;
        $this->base_due_amount = $this->due_amount * $this->exchange_rate;

        $this->changePurchaseStatus($this->due_amount);
    }

    public function changePurchaseStatus($amount)
    {
        if ($amount < 0) {
            return [
                'error' => 'invalid_amount',
            ];
        }

        if ($amount == 0) {
            $this->status = Purchase::STATUS_COMPLETED;
            $this->paid_status = Purchase::STATUS_PAID;
        } elseif ($amount == $this->total) {
            $this->status = $this->getPreviousStatus();
            $this->paid_status = Purchase::STATUS_UNPAID;
        } else {
            $this->status = $this->getPreviousStatus();
            $this->paid_status = Purchase::STATUS_PARTIALLY_PAID;
        }

        $this->save();
    }
}
