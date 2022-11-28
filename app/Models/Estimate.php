<?php

namespace Crater\Models;

use App;
use PDF;use Carbon\Carbon;
use Crater\Mail\SendEstimateMail;
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
use NumberToWords\NumberToWords;


class Estimate extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use GeneratesPdfTrait;
    use HasCustomFieldsTrait;

    public const STATUS_DRAFT = 'DRAFT';
    public const STATUS_SENT = 'SENT';
    public const STATUS_VIEWED = 'VIEWED';
    public const STATUS_EXPIRED = 'EXPIRED';
    public const STATUS_ACCEPTED = 'ACCEPTED';
    public const STATUS_REJECTED = 'REJECTED';
    public const STATUS_ALL = 'ALL';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'formattedExpiryDate',
        'formattedEstimateDate',
        'estimatePdfUrl',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'total' => 'integer',
        'tax' => 'integer',
        'sub_total' => 'integer',
        'discount' => 'float',
        'discount_val' => 'integer',
        'exchange_rate' => 'float'
    ];

    public function setEstimateDateAttribute($value)
    {
        if ($value) {
            $this->attributes['estimate_date'] = Carbon::createFromFormat('Y-m-d', $value);
        }
    }

    public function setExpiryDateAttribute($value)
    {
        if ($value) {
            $this->attributes['expiry_date'] = Carbon::createFromFormat('Y-m-d', $value);
        }
    }

    public function getEstimatePdfUrlAttribute()
    {
        return url('/estimates/pdf/'.$this->unique_hash);
    }

    public function emailLogs()
    {
        return $this->morphMany('App\Models\EmailLog', 'mailable');
    }

    public function items()
    {
        return $this->hasMany('Crater\Models\EstimateItem');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
      public function bankdetail()
    {
        return $this->belongsTo(BankDetail::class, 'bank_detail_id');
    }

    public function creator()
    {
        return $this->belongsTo('Crater\Models\User', 'creator_id');
    }

    public function company()
    {
        return $this->belongsTo('Crater\Models\Company');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }
    public function preparedby()
    {
        return $this->belongsTo(PreparedBy::class, 'prepared_by_id');
    }

    public function getFormattedExpiryDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->expiry_date)->format($dateFormat);
    }

    public function getFormattedEstimateDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->estimate_date)->format($dateFormat);
    }
    public function scopeWhereCompanyId($query, $company)
    {
        $query->where('estimates.company_id', $company);
    }

    public function scopeEstimatesBetween($query, $start, $end)
    {
        if($start){
            $query->where('estimates.estimate_date','>=',$start->format('Y-m-d'));
        }
        if($end){
            $query->where('estimates.estimate_date','<=',$end->format('Y-m-d'));
        }
        return $query;
    }

    public function scopeWhereStatus($query, $status)
    {
        if($status == self::STATUS_ALL){
            return $query;
        }
        return $query->where('estimates.status', $status);
    }

    public function scopeWhereEstimateNumber($query, $estimateNumber)
    {
        return $query->where('estimates.estimate_number', 'LIKE', '%'.$estimateNumber.'%');
    }

    public function scopeWhereEstimate($query, $estimate_id)
    {
        $query->orWhere('id', $estimate_id);
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

    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        if ($filters->get('search')) {
            $query->whereSearch($filters->get('search'));
        }

        if ($filters->get('estimate_number')) {
            $query->whereEstimateNumber($filters->get('estimate_number'));
        }

        if ($filters->get('status')) {
            $query->whereStatus($filters->get('status'));
        }

        if ($filters->get('estimate_id')) {
            $query->whereEstimate($filters->get('estimate_id'));
        }

        if ($filters->get('from_date')|| $filters->get('to_date')) {
            $start = $filters->get('from_date') ? Carbon::createFromFormat('Y-m-d', $filters->get('from_date')) : '';
            $end = $filters->get('to_date') ?  Carbon::createFromFormat('Y-m-d', $filters->get('to_date')) : '';
            $query->estimatesBetween($start, $end);
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

    public function scopeWhereOrder($query, $orderByField, $orderBy)
    {
        $query->orderBy($orderByField, $orderBy);
    }

    public function scopeWhereCompany($query)
    {
        $query->where('estimates.company_id', request()->header('company'));
    }

    public function scopeWhereCustomer($query, $customer_id)
    {
        $query->where('estimates.customer_id', $customer_id);
    }

    public function scopePaginateData($query, $limit)
    {
        if ($limit == 'all') {
            return $query->get();
        }

        return $query->paginate($limit);
    }

    public static function createEstimate($request)
    {
        $data = $request->getEstimatePayload();

        if ($request->has('estimateSend')) {
            $data['status'] = self::STATUS_SENT;
        }
        if($request->discount_per_item == 'NO'){
            $data['deduction_per_item'] = 'YES';
            $data['discount_per_item'] = 'NO';
        }else{
            $data['discount_per_item'] = 'YES';
            $data['deduction_per_item'] = 'NO';
        }
        $company = Company::where('id', $request->header('company'))->first();
        $data['comp_name'] = $company->name ?? '';
        $data['comp_name_ar'] = $company->name_ar ?? '';
        $data['comp_cr'] = $company->cr ?? '';
        $data['comp_cr_ar'] = $company->cr_ar ?? '';
        $data['comp_vat'] = $company->vat ?? '';
        $data['comp_vat_ar'] = $company->vat_ar ?? '';
        $data['comp_phone_ar'] = $company->phone_ar ?? '';
        $data['comp_state_ar'] = $company->state_ar ?? '';
        $data['comp_city_ar'] = $company->city_ar ?? '';
        $data['comp_zip_ar'] = $company->zip_ar ?? '';
        $data['comp_address_street_1_ar'] = $company->address_street_1_ar ?? '';
        $data['comp_address_street_2_ar'] = $company->address_street_2_ar ?? '';
        $data['comp_phone'] = $company->address->phone ?? '';
        $data['comp_state'] = $company->address->state ?? '';
        $data['comp_city'] = $company->address->city ?? '';
        $data['comp_zip'] = $company->address->zip ?? '';
        $data['comp_address_street_1'] = $company->address->address_street_1 ?? '';
        $data['comp_address_street_2'] = $company->address->address_street_2 ?? '';
        $data['cus_name'] = $data['customer']['billing']['name'] ?? '';
        $data['cus_name_ar'] = $data['customer']['name_ar'] ?? '';
        $data['cus_prefix'] = $data['customer']['prefix'] ?? '';
        $data['cus_prefix_ar'] = $data['customer']['prefix_ar'] ?? '';
        $data['cus_website'] = $data['customer']['website'] ?? ''; //
        $data['cus_website_ar'] = $data['customer']['website_ar'] ?? '';
        $data['cus_state_ar'] = $data['customer']['state_ar'] ?? '';
        $data['cus_city_ar'] = $data['customer']['city_ar'] ?? '';
        $data['cus_address_street_1_ar'] = $data['customer']['address_street_1_ar'] ?? '';
        $data['cus_address_street_2_ar'] = $data['customer']['address_street_2_ar'] ?? '';
        $data['cus_phone_ar'] = $data['customer']['phone_ar'] ?? '';
        $data['cus_zip_ar'] = $data['customer']['zip_ar'] ?? '';
        $data['cus_state'] = $data['customer']['billing']['state'] ?? '';
        $data['cus_city'] = $data['customer']['billing']['city'] ?? '';
        $data['cus_address_street_1'] = $data['customer']['billing']['address_street_1'] ?? '';
        $data['cus_address_street_2'] = $data['customer']['billing']['address_street_2'] ?? '';
        $data['cus_phone'] = $data['customer']['billing']['phone'] ?? '';
        $data['cus_zip'] = $data['customer']['billing']['zip'] ?? '';
        $data['is_edit'] = '1';
        $bank_detail = BankDetail::where('id', $data['bank_detail_id'])->first();
        if($bank_detail){
            $data['comp_account_name_ar'] = $bank_detail->account_name_ar ?? '';
            $data['comp_bank_name_ar'] = $bank_detail->bank_name_ar ?? '';
            $data['comp_account_no_ar'] = $bank_detail->account_no_ar ?? '';
            $data['comp_iban_ar'] = $bank_detail->iban_ar ?? '';
            $data['comp_swift_code_ar'] = $bank_detail->swift_code_ar ?? '';
            $data['comp_account_name'] = $bank_detail->account_name ?? '';
            $data['comp_bank_name'] = $bank_detail->bank_name ?? '';
            $data['comp_account_no'] = $bank_detail->account_no ?? '';
            $data['comp_iban'] = $bank_detail->iban ?? '';
            $data['comp_swift_code'] = $bank_detail->swift_code ?? '';
        }
        $estimate = self::create($data);
        $estimate->unique_hash = Hashids::connection(Estimate::class)->encode($estimate->id);
        
        $serial = (new SerialNumberFormatter())
            ->setModel($estimate)
            ->setCompany($estimate->company_id, 'estimate')
            ->setCustomer($estimate->customer_id);

        $serial->getNextNumber();

        $estimate->sequence_number = $serial->nextSequenceNumber;
        $estimate->customer_sequence_number = $serial->nextCustomerSequenceNumber;
        $estimate->save();

        $company_currency = CompanySetting::getSetting('currency', $request->header('company'));

        if ((string)$data['currency_id'] !== $company_currency) {
            ExchangeRateLog::addExchangeRateLog($estimate);
        }

        self::createItems($estimate, $request, $estimate->exchange_rate);

        if ($request->has('taxes') && (! empty($request->taxes))) {
            self::createTaxes($estimate, $request, $estimate->exchange_rate);
        }

        $customFields = $request->customFields;

        if ($customFields) {
            $estimate->addCustomFields($customFields);
        }

        return $estimate;
    }

    public function updateEstimate($request)
    {
        $data = $request->getEstimatePayload();

        // $serial = (new SerialNumberFormatter())
        //     ->setModel($this)
        //     ->setCompany($this->company_id)
        //     ->setCustomer($request->customer_id)
        //     ->setModelObject($this->id)
        //     ->setNextNumbers(null, 'estimate');
        // $data['customer_sequence_number'] = $serial->nextCustomerSequenceNumber;

        if($request->discount_per_item == 'NO'){
            $data['deduction_per_item'] = 'YES';
            $data['discount_per_item'] = 'NO';
        }else{
            $data['discount_per_item'] = 'YES';
            $data['deduction_per_item'] = 'NO';
        }
        $company = Company::where('id', $request->header('company'))->first();
        $data['comp_name'] = $company->name ?? '';
        $data['comp_name_ar'] = $company->name_ar ?? '';
        $data['comp_cr'] = $company->cr ?? '';
        $data['comp_cr_ar'] = $company->cr_ar ?? '';
        $data['comp_vat'] = $company->vat ?? '';
        $data['comp_vat_ar'] = $company->vat_ar ?? '';
        $data['comp_phone_ar'] = $company->phone_ar ?? '';
        $data['comp_state_ar'] = $company->state_ar ?? '';
        $data['comp_city_ar'] = $company->city_ar ?? '';
        $data['comp_zip_ar'] = $company->zip_ar ?? '';
        $data['comp_address_street_1_ar'] = $company->address_street_1_ar ?? '';
        $data['comp_address_street_2_ar'] = $company->address_street_2_ar ?? '';
        $data['comp_phone'] = $company->address->phone ?? '';
        $data['comp_state'] = $company->address->state ?? '';
        $data['comp_city'] = $company->address->city ?? '';
        $data['comp_zip'] = $company->address->zip ?? '';
        $data['comp_address_street_1'] = $company->address->address_street_1 ?? '';
        $data['comp_address_street_2'] = $company->address->address_street_2 ?? '';
        $data['cus_name'] = $data['customer']['billing']['name'] ?? '';
        $data['cus_name_ar'] = $data['customer']['name_ar'] ?? '';
        $data['cus_prefix'] = $data['customer']['prefix'] ?? '';
        $data['cus_prefix_ar'] = $data['customer']['prefix_ar'] ?? '';
        $data['cus_website'] = $data['customer']['website'] ?? ''; //
        $data['cus_website_ar'] = $data['customer']['website_ar'] ?? '';
        $data['cus_state_ar'] = $data['customer']['state_ar'] ?? '';
        $data['cus_city_ar'] = $data['customer']['city_ar'] ?? '';
        $data['cus_address_street_1_ar'] = $data['customer']['address_street_1_ar'] ?? '';
        $data['cus_address_street_2_ar'] = $data['customer']['address_street_2_ar'] ?? '';
        $data['cus_phone_ar'] = $data['customer']['phone_ar'] ?? '';
        $data['cus_zip_ar'] = $data['customer']['zip_ar'] ?? '';
        $data['cus_state'] = $data['customer']['billing']['state'] ?? '';
        $data['cus_city'] = $data['customer']['billing']['city'] ?? '';
        $data['cus_address_street_1'] = $data['customer']['billing']['address_street_1'] ?? '';
        $data['cus_address_street_2'] = $data['customer']['billing']['address_street_2'] ?? '';
        $data['cus_phone'] = $data['customer']['billing']['phone'] ?? '';
        $data['cus_zip'] = $data['customer']['billing']['zip'] ?? '';

        $bank_detail = BankDetail::where('id', $data['bank_detail_id'])->first();
        if($bank_detail){
            $data['comp_account_name_ar'] = $bank_detail->account_name_ar ?? '';
            $data['comp_bank_name_ar'] = $bank_detail->bank_name_ar ?? '';
            $data['comp_account_no_ar'] = $bank_detail->account_no_ar ?? '';
            $data['comp_iban_ar'] = $bank_detail->iban_ar ?? '';
            $data['comp_swift_code_ar'] = $bank_detail->swift_code_ar ?? '';
            $data['comp_account_name'] = $bank_detail->account_name ?? '';
            $data['comp_bank_name'] = $bank_detail->bank_name ?? '';
            $data['comp_account_no'] = $bank_detail->account_no ?? '';
            $data['comp_iban'] = $bank_detail->iban ?? '';
            $data['comp_swift_code'] = $bank_detail->swift_code ?? '';
        }

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

        self::createItems($this, $request, $this->exchange_rate);

        if ($request->has('taxes') && (! empty($request->taxes))) {
            self::createTaxes($this, $request, $this->exchange_rate);
        }

        if ($request->customFields) {
            $this->updateCustomFields($request->customFields);
        }

        return Estimate::with([
                'items.taxes',
                'items.fields',
                'items.fields.customField',
                'customer',
                'taxes'
            ])
            ->find($this->id);
    }

    public static function createItems($estimate, $request, $exchange_rate)
    {
        $estimateItems = $request->items;

        foreach ($estimateItems as $estimateItem) {
            $estimateItem['company_id'] = $request->header('company');
            $estimateItem['exchange_rate'] = $exchange_rate;
            $estimateItem['base_price'] = $estimateItem['price'] * $exchange_rate;
            $estimateItem['base_discount_val'] = $estimateItem['discount_val'] * $exchange_rate;
            $estimateItem['base_tax'] = $estimate['tax'] * $exchange_rate;
            $estimateItem['base_total'] = $estimateItem['total'] * $exchange_rate;

            $item = $estimate->items()->create($estimateItem);

            if (array_key_exists('taxes', $estimateItem) && $estimateItem['taxes']) {
                foreach ($estimateItem['taxes'] as $tax) {
                    if (gettype($tax['amount']) !== "NULL") {
                        $tax['company_id'] = $request->header('company');
                        $item->taxes()->create($tax);
                    }
                }
            }

            if (array_key_exists('custom_fields', $estimateItem) && $estimateItem['custom_fields']) {
                $item->addCustomFields($estimateItem['custom_fields']);
            }
        }
    }

    public static function createTaxes($estimate, $request, $exchange_rate)
    {
        $estimateTaxes = $request->taxes;

        foreach ($estimateTaxes as $tax) {
            if (gettype($tax['amount']) !== "NULL") {
                $tax['company_id'] = $request->header('company');
                $tax['exchange_rate'] = $exchange_rate;
                $tax['base_amount'] = $tax['amount'] * $exchange_rate;
                $tax['currency_id'] = $estimate->currency_id;

                $estimate->taxes()->create($tax);
            }
        }
    }

    public function sendEstimateData($data)
    {
        $data['estimate'] = $this->toArray();
        $data['user'] = $this->customer->toArray();
        $data['company'] = $this->company->toArray();
        $data['body'] = $this->getEmailBody($data['body']);
        $data['attach']['data'] = ($this->getEmailAttachmentSetting()) ? $this->getPDFData() : null;

        return $data;
    }

    public function send($data)
    {
        $data = $this->sendEstimateData($data);

        if ($this->status == Estimate::STATUS_DRAFT) {
            $this->status = Estimate::STATUS_SENT;
            $this->save();
        }

        \Mail::to($data['to'])->send(new SendEstimateMail($data));

        return [
            'success' => true,
            'type' => 'send',
        ];
    }
    public function getFilterPDFData($quotations, $query_data, $date_heading)
    {        
        view()->share([
            'quotations' => $quotations,
            'date_heading' => $date_heading,
            'query_data' => $query_data
        ]);
            return PDF::loadView('app.pdf.filtered.quotation');
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

        $estimateTemplate = self::find($this->id)->template_name;

        $company = Company::find($this->company_id);
        $locale = CompanySetting::getSetting('language', $company->id);
        $customFields = CustomField::with(['customFieldValues' => function ($query) {
            $query->where('custom_field_valuable_id', $this->id);
            }])->where([['model_type', 'Estimate'], ['company_id', $this->company_id]])->get();
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
        // dd($this);
        $numberToWords = new NumberToWords();
        $currencyTransformer_en = $numberToWords->getCurrencyTransformer('en');
        
        $total_amount_in_en = $currencyTransformer_en->toWords($this->total, 'SAR');
        $logo = $company->logo_path;
        $letterhead = $company->letterhead_path;
        // dd($this);
// dd($this->preparedby->signature);
        // dd($this->getCustomerBillingAddress('pdf'));
        view()->share([
            'estimate' => $this,
            'customFields' => $customFields,
            'company' =>$this->company,
            'logo' => $logo ?? null,
            "total_amount_in_en" => $total_amount_in_en,
            'company_addresss' => $this->getCompanyAddress(),
            'company_address' =>array_merge($this->getFieldsArray(), $this->getExtraFields()),
            'shipping_address' => $this->getCustomerShippingAddress(),
            'billing_address' => $this->getCustomerBillingAddress('pdf'),
            'notes' => $this->getNotes(),
            'note_heading' => $this->getNoteHeading($this->notes),
            'letterhead' => $letterhead ?? null,
            'taxes' => $taxes,
            'signature' => $this->preparedby ?? null,
            'signature_image' => $this->preparedby ? $this->preparedby->signature_image : null
        ]);

        return PDF::loadView('app.pdf.estimate.'.$estimateTemplate);
    }

    public function getCompanyAddress()
    {
        if ($this->company && (! $this->company->address()->exists())) {
            return false;
        }

        $format = CompanySetting::getSetting('estimate_company_address_format', $this->company_id);

        return $this->getFormattedString($format);
    }

    public function getCustomerShippingAddress()
    {
        if ($this->customer && (! $this->customer->shippingAddress()->exists())) {
            return false;
        }

        $format = CompanySetting::getSetting('estimate_shipping_address_format', $this->company_id);

        return $this->getFormattedString($format);
    }

    public function getCustomerBillingAddress($type='')
    {
        if ($this->customer && (! $this->customer->billingAddress()->exists())) {
            return false;
        }
        if($type == 'pdf'){
            $format = CompanySetting::getSetting('estimate_billing_address_format', $this->company_id);
            return $this->getFormattedString($format, $this);
        }else{
            $format = CompanySetting::getSetting('estimate_billing_address_format', $this->company_id);
            return $this->getFormattedString($format);
        }

    }

    public function getNotes()
    {
        return $this->getFormattedString($this->notes);
    }

    public function getEmailAttachmentSetting()
    {
        $estimateAsAttachment = CompanySetting::getSetting('estimate_email_attachment', $this->company_id);

        if ($estimateAsAttachment == 'NO') {
            return false;
        }

        return true;
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
            '{ESTIMATE_DATE}' => $this->formattedEstimateDate,
            '{ESTIMATE_EXPIRY_DATE}' => $this->formattedExpiryDate,
            '{ESTIMATE_NUMBER}' => $this->estimate_number,
            '{ESTIMATE_REF_NUMBER}' => $this->reference_number,
            '{ESTIMATE_LINK}' => url('/customer/estimates/pdf/'.$this->unique_hash),
        ];
    }

    public static function estimateTemplates()
    {
        $templates = Storage::disk('views')->files('/app/pdf/estimate');
        $estimateTemplates = [];

        foreach ($templates as $key => $template) {
            $templateName = Str::before(basename($template), '.blade.php');
            // if($templateName != 'quotation1' && $templateName != 'quotation2'){
                $estimateTemplates[$key]['name'] = $templateName;
                $estimateTemplates[$key]['path'] = vite_asset('/img/PDF/'.$templateName.'.png');
            // }
        }

        return $estimateTemplates;
    }

    public function getInvoiceTemplateName()
    {
        $templateName = Str::replace('quotation', 'invoice', $this->template_name);

        $name = [];

        foreach (Invoice::invoiceTemplates() as $template) {
            $name[] = $template['name'];
        }

        if (in_array($templateName, $name) == false) {
            $templateName = 'invoice1';
        }

        return $templateName;
    }

    public function checkForEstimateConvertAction()
    {
        $convertEstimateAction = CompanySetting::getSetting(
            'estimate_convert_action',
            $this->company_id
        );

        if ($convertEstimateAction === 'delete_estimate') {
            $this->delete();
        }

        if ($convertEstimateAction === 'mark_estimate_as_accepted') {
            $this->status = self::STATUS_ACCEPTED;
            $this->save();
        }

        return true;
    }
}
