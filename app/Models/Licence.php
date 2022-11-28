<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licence extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'trial_version', 'client_id', 'plan_id', 'expire_at', 'description', 'status', 'licence_key'];
}
