<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $guarded = [];

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class, 'product_id');
    }


    
}
