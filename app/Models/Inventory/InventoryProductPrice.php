<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryProductPrice extends Model
{
    protected $table = 'inventory_product_prices';

    use SoftDeletes;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(InventoryProduct::class, 'inventory_product_id');
    }
    
}
