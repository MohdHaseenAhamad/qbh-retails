<?php

namespace Crater\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'type', 'company_id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['formattedCreatedAt'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function clients()
    {
        return $this->hasMany(Customer::class);
    }
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getFormattedCreatedAtAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);

        return Carbon::parse($this->created_at)->format($dateFormat);
    }

    public function scopeWhereCompany($query)
    {
        $query->where('company_id', request()->header('company'));
    }

    public function whereCode($query, $code)
    {
        $query->orWhere('code', $code);
    }

    public function whereType($query, $type)
    {
        $query->orWhere('type', $type);
    }

    public function scopeWhereSearch($query, $search)
    {
        $query->where('name', 'LIKE', '%'.$search.'%');
    }

    public function scopePaginateData($query, $limit)
    {
        if ($limit == 'all') {
            return $query->get();
        }

        return $query->paginate($limit);
    }

    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        if ($filters->get('code')) {
            $query->whereCode($filters->get('code'));
        }

        if ($filters->get('company_id')) {
            $query->whereCompany($filters->get('company_id'));
        }

        if ($filters->get('search')) {
            $query->whereSearch($filters->get('search'));
        }

        if ($filters->get('type')) {
            $query->whereType($filters->get('type'));
        }
    }
}
