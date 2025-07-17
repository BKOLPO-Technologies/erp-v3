<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'company', 'phone', 'email', 'address', 'city', 'region', 
        'country', 'postbox', 'taxid', 'customergroup', 'name_s', 'phone_s', 
        'email_s', 'address_s', 'city_s', 'region_s', 'country_s', 'postbox_s',
    ];
}
