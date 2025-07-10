<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded = [];

    // Relationship: A unit has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
