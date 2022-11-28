<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'option', 'value'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeWhereCompany($query, $company_id)
    {
        $query->where('company_id', $company_id);
    }

    public static function setSettings($settings, $company_id)
    {
        foreach ($settings as $key => $value) {
            if($key == 'discount_per_item' || $key == 'deduction_per_item'){
                if($key == 'discount_per_item'){
                    $opposite_key ='deduction_per_item';
                }elseif($key == 'deduction_per_item'){
                    $opposite_key = 'discount_per_item';
                }
                if($value == 'YES'){
                    $opposite_value ='NO';
                }elseif($value == 'NO'){
                    $opposite_value = 'YES';
                }
                self::updateOrCreate(
                    [
                        'option' => $key,
                        'company_id' => $company_id,
                    ],
                    [
                        'option' => $key,
                        'company_id' => $company_id,
                        'value' => $value,
                    ]
                );
                self::updateOrCreate(
                    [
                        'option' => $opposite_key,
                        'company_id' => $company_id,
                    ],
                    [
                        'option' => $opposite_key,
                        'company_id' => $company_id,
                        'value' => $opposite_value,
                    ]
                );
            }else{
                self::updateOrCreate(
                    [
                        'option' => $key,
                        'company_id' => $company_id,
                    ],
                    [
                        'option' => $key,
                        'company_id' => $company_id,
                        'value' => $value,
                    ]
                );
            }
        }
    }

    public static function getAllSettings($company_id)
    {
        return static::whereCompany($company_id)->get()->mapWithKeys(function ($item) {
            return [$item['option'] => $item['value']];
        });
    }

    public static function getSettings($settings, $company_id)
    {
        return static::whereIn('option', $settings)->whereCompany($company_id)
            ->get()->mapWithKeys(function ($item) {
                return [$item['option'] => $item['value']];
            });
    }

    public static function getSetting($key, $company_id)
    {
        $setting = static::whereOption($key)->whereCompany($company_id)->first();
        if ($setting) {
            return $setting->value;
        } else {
            return null;
        }
    }
}
