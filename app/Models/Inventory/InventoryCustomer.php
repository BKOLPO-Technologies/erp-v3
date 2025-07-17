<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryCustomer extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
