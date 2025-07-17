<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    // Relationship: A unit has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
