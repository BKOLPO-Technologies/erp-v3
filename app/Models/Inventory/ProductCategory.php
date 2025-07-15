<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(InventoryProduct::class);
    }
}
