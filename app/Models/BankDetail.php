<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
class BankDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo('Crater\Models\User', 'creator_id');
    }

    public static function createBankDetail($request)
    {
        $data['account_name'] = $request->account_name;
        $data['account_name_ar'] = $request->account_name_ar;
        $data['bank_name'] = $request->bank_name;
        $data['bank_name_ar'] = $request->bank_name_ar;
        $data['account_no'] = $request->account_no;
        $data['account_no_ar'] = $request->account_no_ar;
        $data['iban'] = $request->iban;
        $data['iban_ar'] = $request->iban_ar;
        $data['swift_code'] = $request->swift_code;
        $data['swift_code_ar'] = $request->swift_code_ar;
        $data['company_id'] = $request->header('company');
        $data['creator_id'] = Auth::id();
        $item = self::create($data);
        return $item;
    }

    public function updateBankDetail($request)
    {
        $this->update($request->all());


        return BankDetail::find($this->id);
    }

    public function scopeWhereSearch($query, $search)
    {
        return $query->where('bank_details.name', 'LIKE', '%'.$search.'%');
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
        $query->where('bank_details.company_id', request()->header('company'));
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

        if ($filters->get('item_id')) {
            $query->whereItem($filters->get('item_id'));
        }

        if ($filters->get('orderByField') || $filters->get('orderBy')) {
            $field = $filters->get('orderByField') ? $filters->get('orderByField') : 'account_name';
            $orderBy = $filters->get('orderBy') ? $filters->get('orderBy') : 'asc';
            $query->whereOrder($field, $orderBy);
        }
    }
}
