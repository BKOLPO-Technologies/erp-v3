<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
