<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockIn extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    /**
     * Get the product that owns the inventory record.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the supplier that owns the inventory record.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the sale that owns the inventory record.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
