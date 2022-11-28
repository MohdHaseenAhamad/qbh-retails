<?php

namespace Crater\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'defaultAnswer',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function setDateAnswerAttribute($value)
    {
        if ($value && $value != null) {
            $this->attributes['date_answer'] = Carbon::createFromFormat('Y-m-d', $value);
        }
    }

    public function setTimeAnswerAttribute($value)
    {
        if ($value && $value != null) {
            $this->attributes['time_answer'] = date("H:i:s", strtotime($value));
        }
    }

    public function setDateTimeAnswerAttribute($value)
    {
        if ($value && $value != null) {
            $this->attributes['date_time_answer'] = Carbon::createFromFormat('Y-m-d H:i', $value);
        }
    }

    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = json_encode($value);
    }

    public function getDefaultAnswerAttribute()
    {
        $value_type = getCustomFieldValueKey($this->type);

        return $this->$value_type;
    }

    public function getInUseAttribute()
    {
        return $this->customFieldValues()->exists();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customFieldValues()
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    public function scopeWhereCompany($query)
    {
        return $query->where('custom_fields.company_id', request()->header('company'));
    }

    public function scopeWhereSearch($query, $search)
    {
        $query->where(function ($query) use ($search) {
            $query->where('label', 'LIKE', '%'.$search.'%')
                ->orWhere('name', 'LIKE', '%'.$search.'%');
        });
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

        if ($filters->get('type')) {
            $query->whereType($filters->get('type'));
        }

        if ($filters->get('search')) {
            $query->whereSearch($filters->get('search'));
        }
    }

    public function scopeWhereType($query, $type)
    {
        $query->where('custom_fields.model_type', $type);
    }

    public static function createCustomField($request)
    {
        $data = $request->validated();
        $data[getCustomFieldValueKey($request->type)] = $request->default_answer;
        $data['company_id'] = $request->header('company');
        $data['label_ar'] = $request->label_ar;
        if($data['model_type'] == 'Invoice'){
            $data['model_type'] = 'Proforma';
            $data['slug'] = clean_slug($data['model_type'], $request->name);
            CustomField::create($data);
            $data['model_type'] = 'Invoice';
            $data['slug'] = clean_slug($data['model_type'], $request->name);
            return CustomField::create($data);
        }else{
            $data['slug'] = clean_slug($data['model_type'], $request->name);
        }
        return CustomField::create($data);
    }

    public function updateCustomField($request)
    {
        $data = $request->validated();
        $data['label_ar'] = $request->label_ar;
        $data[getCustomFieldValueKey($request->type)] = $request->default_answer;
        $this->update($data);
        if($data['model_type'] == 'Invoice'){
            $proforma_exist_slug = str_replace("_INVOICE_","_PROFORMA_",$this->slug);
            $proforma_exist = CustomField::where('slug', $proforma_exist_slug)->first();
            if(!$proforma_exist){
                $newProforma = $this;
                $newProforma->model_type = 'Proforma';
                $newProforma->slug = $proforma_exist_slug;
                CustomField::create($newProforma->toArray());
            }else{
                $data['model_type'] = 'Proforma';
                $proforma_exist->update($data);
            }
        }

        return $this;
    }
}
