<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUnit extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(InventoryProduct::class);
    }
}
