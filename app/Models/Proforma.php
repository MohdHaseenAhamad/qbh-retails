<?php

namespace Crater\Models;

use App;
//use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use PDF;
use Carbon\Carbon;
use Crater\Mail\SendProformaMail;
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

class Proforma extends Model implements HasMedia
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
    public const STATUS_ALL = 'ALL';

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
        'formattedProformaDate',
        'formattedDueDate',
        'proformaPdfUrl',
    ];

    public function setProformaDateAttribute($value)
    {
        if ($value) {
            $this->attributes['proforma_date'] = Carbon::createFromFormat('Y-m-d', $value);
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
        return $this->hasMany('Crater\Models\ProformaItem');
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

    public function performa()
    {
        return $this->hasOne(Performa::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function recurringProforma()
    {
        return $this->belongsTo(RecurringProforma::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function preparedby()
    {
        return $this->belongsTo(PreparedBy::class, 'prepared_by_id');
    }
    public function bankdetail()
    {
        return $this->belongsTo(BankDetail::class, 'bank_detail_id');
    }

    public function getProformaPdfUrlAttribute()
    {
        return url('/proformas/pdf/'.$this->unique_hash);
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

        if ($retrospective_edit == 'disable_on_proforma_sent' && (in_array($this->status, $status)) && ($this->paid_status === Proforma::STATUS_PARTIALLY_PAID || $this->paid_status === Proforma::STATUS_PAID)) {
            $allowed = false;
        } elseif ($retrospective_edit == 'disable_on_proforma_partial_paid' && ($this->paid_status === Proforma::STATUS_PARTIALLY_PAID || $this->paid_status === Proforma::STATUS_PAID)) {
            $allowed = false;
        } elseif ($retrospective_edit == 'disable_on_proforma_paid' && $this->paid_status === Proforma::STATUS_PAID) {
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
            return self::STATUS_DRAFT;
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

    public function getFormattedProformaDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->proforma_date)->format($dateFormat);
    }

    public function scopeWhereStatus($query, $status)
    {
        if($status == self::STATUS_ALL){
            return $query;
        }
        return $query->where('proformas.status', $status);
    }

    public function scopeWherePaidStatus($query, $status)
    {
        return $query->where('proformas.paid_status', $status);
    }

    public function scopeWhereDueStatus($query, $status)
    {
        return $query->whereIn('proformas.paid_status', [
            self::STATUS_UNPAID,
            self::STATUS_PARTIALLY_PAID,
        ]);
    }

    public function scopeWhereProformaNumber($query, $proformaNumber)
    {
        return $query->where('proformas.proforma_number', 'LIKE', '%'.$proformaNumber.'%');
    }

    public function scopeProformasBetween($query, $start, $end)
    {
        if($start){
            $query->where('proformas.proforma_date','>=',$start->format('Y-m-d'));
        }
        if($end){
            $query->where('proformas.proforma_date','<=',$end->format('Y-m-d'));
        }
        return $query;
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

        if ($filters->get('status')) {
            if (
                $filters->get('status') == self::STATUS_UNPAID ||
                $filters->get('status') == self::STATUS_PARTIALLY_PAID ||
                $filters->get('status') == self::STATUS_PAID
            ) {
                $query->wherePaidStatus($filters->get('status'));
            } elseif ($filters->get('status') == self::STATUS_DUE) {
                $query->whereDueStatus($filters->get('status'));
            } else {
                $query->whereStatus($filters->get('status'));
            }
        }

        if ($filters->get('paid_status')) {
            $query->wherePaidStatus($filters->get('status'));
        }

        if ($filters->get('proforma_id')) {
            $query->whereproforma($filters->get('proforma_id'));
        }

        if ($filters->get('proforma_number')) {
            $query->whereProformaNumber($filters->get('proforma_number'));
        }

        if ($filters->get('from_date') || $filters->get('to_date')) {
            $start = $filters->get('from_date') ? Carbon::createFromFormat('Y-m-d', $filters->get('from_date')) : '';
            $end = $filters->get('to_date') ? Carbon::createFromFormat('Y-m-d', $filters->get('to_date')) : '';
            $query->proformasBetween($start, $end);
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

    public function scopeWhereProforma($query, $proforma_id)
    {
        $query->orWhere('id', $proforma_id);
    }

    public function scopeWhereCompany($query)
    {
        $query->where('proformas.company_id', request()->header('company'));
    }

    public function scopeWhereCompanyId($query, $company)
    {
        $query->where('proformas.company_id', $company);
    }

    public function scopeWhereCustomer($query, $customer_id)
    {
        $query->where('proformas.customer_id', $customer_id);
    }

    public function scopePaginateData($query, $limit)
    {
        if ($limit == 'all') {
            return $query->get();
        }

        return $query->paginate($limit);
    }

    public static function createProforma($request)
    {
        $data = $request->getProformaPayload();
        if ($request->has('ProformaSend')) {
            $data['status'] = Proforma::STATUS_SENT;
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
        if($bank_detail)
        {
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

        if($request->discount_per_item == 'NO'){
            $data['deduction_per_item'] = 'YES';
            $data['discount_per_item'] = 'NO';
        }else{
            $data['discount_per_item'] = 'YES';
            $data['deduction_per_item'] = 'NO';
        }
        $proforma = Proforma::create($data);

        $serial = (new SerialNumberFormatter())
            ->setModel($proforma)
            ->setCompany($proforma->company_id, 'proforma')
            ->setCustomer($proforma->customer_id);
        $serial->getNextNumber();

        $proforma->sequence_number = $serial->nextSequenceNumber;
        $proforma->customer_sequence_number = $serial->nextCustomerSequenceNumber;
        $proforma->unique_hash = Hashids::connection(Proforma::class)->encode($proforma->id);
        $proforma->save();

        self::createItems($proforma, $request->items);
        $company_currency = CompanySetting::getSetting('currency', $request->header('company'));

        if ((string)$data['currency_id'] !== $company_currency) {
            ExchangeRateLog::addExchangeRateLog($proforma);
        }
        // dd($request->has('taxes'));
        if ($request->has('taxes') && (! empty($request->taxes))) {
            self::createTaxes($proforma, $request->taxes);
        }

        if ($request->customFields) {
            $proforma->addCustomFields($request->customFields);
        }

        $proforma = Proforma::with([
            'items',
            'items.fields',
            'items.fields.customField',
            'customer',
            'taxes'
        ])
            ->find($proforma->id);

        return $proforma;
    }

    public function updateProforma($request)
    {

        $data = $request->getProformaPayload();
        $oldTotal = $this->total;
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

        $total_paid_amount = $this->total - $this->due_amount;

        if ($total_paid_amount > 0 && $this->customer_id !== $request->customer_id) {
            return 'customer_cannot_be_changed_after_payment_is_added';
        }

        if ($request->total < $total_paid_amount) {
            return 'total_proforma_amount_must_be_more_than_paid_amount';
        }

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

        $this->changeProformaStatus($data['due_amount']);

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

        $proforma = Proforma::with([
            'items',
            'items.fields',
            'items.fields.customField',
            'customer',
            'taxes'
        ])
            ->find($this->id);

        return $proforma;
    }

    public function sendProformaData($data)
    {
        $data['proforma'] = $this->toArray();
        $data['customer'] = $this->customer->toArray();
        $data['company'] = Company::find($this->company_id);
        $data['body'] = $this->getEmailBody($data['body']);
        $data['attach']['data'] = ($this->getEmailAttachmentSetting()) ? $this->getPDFData() : null;

        return $data;
    }

    public function preview($data)
    {
        $data = $this->sendProformaData($data);

        return [
            'type' => 'preview',
            'view' => new SendProformaMail($data)
        ];
    }

    public function send($data)
    {
        $data = $this->sendProformaData($data);

        \Mail::to($data['to'])->send(new SendProformaMail($data));

        if ($this->status == Proforma::STATUS_DRAFT) {
            $this->status = Proforma::STATUS_SENT;
            $this->sent = true;
            $this->save();
        }

        return [
            'success' => true,
            'type' => 'send',
        ];
    }

    public static function createItems($proforma, $proformaItems)
    {
        $exchange_rate = $proforma->exchange_rate;

        foreach ($proformaItems as $proformaItem) {
            $proformaItem['company_id'] = $proforma->company_id;
            $proformaItem['exchange_rate'] = $exchange_rate;
            $proformaItem['base_price'] = $proformaItem['price'] * $exchange_rate;
            $proformaItem['base_discount_val'] = $proformaItem['discount_val'] * $exchange_rate;
            $proformaItem['base_tax'] = $proformaItem['tax'] * $exchange_rate;
            $proformaItem['base_total'] = $proformaItem['total'] * $exchange_rate;

            if (array_key_exists('recurring_proforma_id', $proformaItem)) {
                unset($proformaItem['recurring_proforma_id']);
            }
            $item = $proforma->items()->create($proformaItem);
            if (array_key_exists('taxes', $proformaItem) && $proformaItem['taxes']) {
                foreach ($proformaItem['taxes'] as $tax) {
                    $tax['company_id'] = $proforma->company_id;
                    if (gettype($tax['amount']) !== "NULL") {
                        if (array_key_exists('recurring_proforma_id', $proformaItem)) {
                            unset($proformaItem['recurring_proforma_id']);
                        }

                        $item->taxes()->create($tax);
                    }
                }
            }

            if (array_key_exists('custom_fields', $proformaItem) && $proformaItem['custom_fields']) {
                $item->addCustomFields($proformaItem['custom_fields']);
            }
        }
    }

    public static function createTaxes($proforma, $taxes)
    {
        $exchange_rate = $proforma->exchange_rate;

        foreach ($taxes as $tax) {
            $tax['company_id'] = $proforma->company_id;
            $tax['exchnage_rate'] = $proforma->exchange_rate;
            $tax['base_amount'] = $tax['amount'] * $exchange_rate;
            $tax['currency_id'] = $proforma->currency_id;

            if (gettype($tax['amount']) !== "NULL") {
                if (array_key_exists('recurring_proforma_id', $tax)) {
                    unset($tax['recurring_proforma_id']);
                }

                $proforma->taxes()->create($tax);
            }
        }
    }
    
    public function getFilterPDFData($proformas, $query_data, $date_heading)
    {
        // dd($query_data);
        
        view()->share([
            'proformas' => $proformas,
            'date_heading' => $date_heading,
            'query_data' => $query_data
        ]);
            return PDF::loadView('app.pdf.filtered.proforma');
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
        
        $proformaTemplate = self::find($this->id)->template_name;

        $company = Company::find($this->company_id);
        // dd($company->owner);
        $locale = UserSetting::getSetting('language', $company->owner->id);
        $customFields = CustomField::with(['customFieldValues' => function ($query) {
            $query->where('custom_field_valuable_id', $this->id);
            }])->where([['model_type', 'proforma'], ['company_id', $this->company_id]])->get();
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
        
        
        $numberToWords = new NumberToWords();
        $currencyTransformer_ar = $numberToWords->getCurrencyTransformer('ar');
        
        $total_amount_in_ar = $currencyTransformer_ar->toWords($this->due_amount, 'SAR');
        $currencyTransformer_en = $numberToWords->getCurrencyTransformer('en');
        
        $total_amount_in_en = $currencyTransformer_en->toWords($this->due_amount, 'SAR');
       
        view()->share([
            'proforma' => $this,
            'customFields' => $customFields,
            'company' =>$this->company,
            'customer' =>$this->customer,
            'customer_cr_number' =>$this->customer->prefix ?? 'NA',
            'customer_vat_number' =>$this->customer->website ?? 'NA',
            'company_addresss' => $this->getCompanyAddress(),
            "total_amount_in_ar" => $total_amount_in_ar,
            "total_amount_in_en" => $total_amount_in_en,
            'company_address' =>array_merge($this->getFieldsArray(), $this->getExtraFields()),
            'billing_address' => $this->getCustomerBillingAddress('pdf'),
            'notes' => $this->getNotes(),
            'note_heading' => $this->getNoteHeading($this->notes),
            'logo' => $logo ?? null,
            'letterhead' => $letterhead ?? null,
            'taxes' => $taxes,
            'signature' => $this->preparedby ?? null,
            'signature_image' => $this->preparedby ? $this->preparedby->signature_image : null
        ]);
        if($type != 'performa'){
            return PDF::loadView('app.pdf.proforma.'.$proformaTemplate);
        }else{
            return PDF::loadView('app.pdf.performa.'.$proformaTemplate);
        }
    }

    public function getEmailAttachmentSetting()
    {
        $proformaAsAttachment = CompanySetting::getSetting('proforma_email_attachment', $this->company_id);

        if ($proformaAsAttachment == 'NO') {
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
        $format = CompanySetting::getSetting('proforma_company_address_format', $this->company_id);
        return $this->getFormattedString($format);
    }

    public function getCustomerShippingAddress()
    {
        if ($this->customer && (! $this->customer->shippingAddress()->exists())) {
            return false;
        }

        $format = CompanySetting::getSetting('proforma_shipping_address_format', $this->company_id);

        return $this->getFormattedString($format);
    }

    public function getCustomerBillingAddress($type = '')
    {
        if ($this->customer && (! $this->customer->billingAddress()->exists())) {
            return false;
        }
        if($type == 'pdf'){
            $format = CompanySetting::getSetting('invoice_billing_address_format', $this->company_id);
            return $this->getFormattedString($format, $this);
        }else{
            $format = CompanySetting::getSetting('invoice_billing_address_format', $this->company_id);
            return $this->getFormattedString($format);
        }

        $format = CompanySetting::getSetting('invoice_billing_address_format', $this->company_id);
        return $this->getFormattedString($format);
    }

    public function getNotes()
    {
        return $this->getFormattedString($this->notes);
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
            '{PROFORMA_DATE}' => $this->formattedProformaDate,
            '{PROFORMA_DUE_DATE}' => $this->formattedDueDate,
            '{PROFORMA_NUMBER}' => $this->proforma_number,
            '{PROFORMA_REF_NUMBER}' => $this->reference_number,
            '{PROFORMA_LINK}' => url('/customer/proformas/pdf/'.$this->unique_hash),
        ];
    }

    public static function proformaTemplates()
    {
        $templates = Storage::disk('views')->files('/app/pdf/proforma');
        $proformaTemplates = [];

        foreach ($templates as $key => $template) {
            $templateName = Str::before(basename($template), '.blade.php');
            $proformaTemplates[$key]['name'] = $templateName;
            $proformaTemplates[$key]['path'] = vite_asset('img/PDF/'.$templateName.'.png');
        }

        return $proformaTemplates;
    }

    public function addProformaPayment($amount)
    {
        $this->due_amount += $amount;
        $this->base_due_amount = $this->due_amount * $this->exchange_rate;

        $this->changeProformaStatus($this->due_amount);
    }

    public function subtractProformaPayment($amount)
    {
        $this->due_amount -= $amount;
        $this->base_due_amount = $this->due_amount * $this->exchange_rate;

        $this->changeProformaStatus($this->due_amount);
    }

    public function changeProformaStatus($amount)
    {
        if ($amount < 0) {
            return [
                'error' => 'invalid_amount',
            ];
        }

        if ($amount == 0) {
            $this->status = Proforma::STATUS_COMPLETED;
            $this->paid_status = Proforma::STATUS_PAID;
        } elseif ($amount == $this->total) {
            $this->status = $this->getPreviousStatus();
            $this->paid_status = Proforma::STATUS_UNPAID;
        } else {
            $this->status = $this->getPreviousStatus();
            $this->paid_status = Proforma::STATUS_PARTIALLY_PAID;
        }

        $this->save();
    }
}
