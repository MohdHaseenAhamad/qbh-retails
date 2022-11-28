<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public static function getSetting($key, $user_id)
    {
        $setting = static::where('key',$key)->where('user_id',$user_id)->first();
        if ($setting) {
            return $setting->value;
        } else {
            return null;
        }
    }
}
