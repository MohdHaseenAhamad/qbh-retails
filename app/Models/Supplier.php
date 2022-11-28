<?php

namespace Crater\Models;

use PDF;
use Carbon\Carbon;
use Crater\Traits\HasCustomFieldsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Crater\Traits\GeneratesPdfTrait;

class Supplier extends Authenticatable implements HasMedia 
{
    use HasApiTokens;
    use Notifiable;
    use InteractsWithMedia;
    use HasCustomFieldsTrait;
    use HasFactory;
    use HasRolesAndAbilities;
    use GeneratesPdfTrait;
    
	protected $guarded = [
        'id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = [
        'currency',
    ];

    protected $appends = [
        'formattedCreatedAt',
        'avatar'
    ];

    public function getFormattedCreatedAtAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->created_at)->format($dateFormat);
    }

    public function setPasswordAttribute($value)
    {
        if ($value != null) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function creator()
    {
        return $this->belongsTo(Supplier::class, 'creator_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->whereType('client');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function billingAddress()
    {
        return $this->hasOne(Address::class)->where('type', Address::BILLING_TYPE);
    }
    
    public function shippingAddress()
    {
        return $this->hasOne(Address::class)->where('type', Address::SHIPPING_TYPE);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function getAvatarAttribute()
    {
        $avatar = $this->getMedia('customer_avatar')->first();

        if ($avatar) {
            return  asset($avatar->getUrl());
        }

        return 0;
    }

    public static function deleteSuppliers($ids)
    {
        foreach ($ids as $id) {
            $supplier = self::find($id);

            // if ($supplier->estimates()->exists()) {
            //     $supplier->estimates()->delete();
            // }

            // if ($supplier->invoices()->exists()) {
            //     $supplier->invoices()->delete();
            // }

            // if ($supplier->payments()->exists()) {
            //     $supplier->payments()->delete();
            // }

            if ($supplier->addresses()->exists()) {
                $supplier->addresses()->delete();
            }

            // if ($supplier->expenses()->exists()) {
            //     $supplier->expenses()->delete();
            // }

            // if ($supplier->recurringInvoices()->exists()) {
            //     foreach ($supplier->recurringInvoices as $recurringInvoice) {
            //         if ($recurringInvoice->items()->exists()) {
            //             $recurringInvoice->items()->delete();
            //         }

            //         $recurringInvoice->delete();
            //     }
            // }

            $supplier->delete();
        }

        return true;
    }

    public static function createSupplier($request)
    {
        $supplier = Supplier::create($request->getSupplierPayload());

        if ($request->shipping) {
            if ($request->hasAddress($request->shipping)) {
                $supplier->addresses()->create($request->getShippingAddress());
            }
        }

        if ($request->billing) {
            if ($request->hasAddress($request->billing)) {
                $supplier->addresses()->create($request->getBillingAddress());
            }
        }

        $customFields = $request->customFields;

        if ($customFields) {
            $supplier->addCustomFields($customFields);
        }

        $supplier = Supplier::with('billingAddress', 'shippingAddress', 'fields')->find($supplier->id);

        return $supplier;
    }

    public static function updateSupplier($request, $supplier)
    {
        // dd($request->getsupplierPayload());
        
        // $condition = $supplier->estimates()->exists() || $supplier->invoices()->exists() || $supplier->payments()->exists() || $supplier->recurringInvoices()->exists();
        $condition = false;

        if (($supplier->currency_id !== $request->currency_id) && $condition) {
            return 'you_cannot_edit_currency';
        }

        $supplier->update($request->getSupplierPayload());

        $supplier->addresses()->delete();

        if ($request->shipping) {
            if ($request->hasAddress($request->shipping)) {
                $supplier->addresses()->create($request->getShippingAddress());
            }
        }

        if ($request->billing) {
            if ($request->hasAddress($request->billing)) {
                $supplier->addresses()->create($request->getBillingAddress());
            }
        }

        $customFields = $request->customFields;

        if ($customFields) {
            $supplier->updateCustomFields($customFields);
        }

        $supplier = Supplier::with('billingAddress', 'shippingAddress', 'fields')->find($supplier->id);

        return $supplier;
    }

    public function scopeWhereSupplier($query, $supplier_id)
    {
        $query->orWhere('suppliers.id', $supplier_id);
    }
    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        if ($filters->get('search')) {
            $query->whereSearch($filters->get('search'));
        }

        if ($filters->get('contact_name')) {
            $query->whereContactName($filters->get('contact_name'));
        }

        if ($filters->get('display_name')) {
            $query->whereDisplayName($filters->get('display_name'));
        }

        if ($filters->get('supplier_id')) {
            $query->whereSupplies($filters->get('supplier_id'));
        }

        if ($filters->get('phone')) {
            $query->wherePhone($filters->get('phone'));
        }

        if ($filters->get('orderByField') || $filters->get('orderBy')) {
            $field = $filters->get('orderByField') ? $filters->get('orderByField') : 'name';
            $orderBy = $filters->get('orderBy') ? $filters->get('orderBy') : 'asc';
            $query->whereOrder($field, $orderBy);
        }
    }

    public function scopePaginateData($query, $limit)
    {
        if ($limit == 'all') {
            return $query->get();
        }

        return $query->paginate($limit);
    }

    public function scopeWhereCompany($query)
    {
        return $query->where('suppliers.company_id', request()->header('company'));
    }

    public function scopeWhereContactName($query, $contactName)
    {
        return $query->where('contact_name', 'LIKE', '%'.$contactName.'%');
    }

    public function scopeWhereDisplayName($query, $displayName)
    {
        return $query->where('name', 'LIKE', '%'.$displayName.'%');
    }

    public function scopeWhereOrder($query, $orderByField, $orderBy)
    {
        $query->orderBy($orderByField, $orderBy);
    }

    public function scopeWhereSearch($query, $search)
    {
        foreach (explode(' ', $search) as $term) {
            $query->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', '%'.$term.'%')
                    ->orWhere('email', 'LIKE', '%'.$term.'%')
                    ->orWhere('phone', 'LIKE', '%'.$term.'%');
            });
        }
    }

    public function scopeApplyPurchaseFilters($query, array $filters)
    {
        $filters = collect($filters);

        if ($filters->get('from_date') && $filters->get('to_date')) {
            $start = Carbon::createFromFormat('Y-m-d', $filters->get('from_date'));
            $end = Carbon::createFromFormat('Y-m-d', $filters->get('to_date'));
            $query->purchasesBetween($start, $end);
        }
    }

    public function scopePurchasesBetween($query, $start, $end)
    {
        $query->whereHas('purchases', function ($query) use ($start, $end) {
            $query->whereBetween(
                'purchase_date',
                [$start->format('Y-m-d'), $end->format('Y-m-d')]
            );
        });
    }

    public function scopeWherePhone($query, $phone)
    {
        return $query->where('phone', 'LIKE', '%'.$phone.'%');
    }

    // public function scopeWhereOrder($query, $orderByField, $orderBy)
    // {
    //     $query->orderBy($orderByField, $orderBy);
    // }

    public function getFilterPDFData($suppliers, $query_data)
    {
        // dd($query_data);
        
        view()->share([
            'suppliers' => $suppliers,
            'query_data' => $query_data
        ]);
            return PDF::loadView('app.pdf.filtered.supplier');
    }
}
