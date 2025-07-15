<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(InventoryProduct::class);
    }

}
