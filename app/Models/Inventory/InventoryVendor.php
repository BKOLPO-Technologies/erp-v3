<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\InventoryVendor; 
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryVendor extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
