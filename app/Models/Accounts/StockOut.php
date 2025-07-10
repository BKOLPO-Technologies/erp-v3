<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
