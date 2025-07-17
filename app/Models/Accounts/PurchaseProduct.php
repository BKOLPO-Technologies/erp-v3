<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseProduct extends Model
{
    protected $table = 'purchase_product'; // Define the table name if it's different from plural of model
    use SoftDeletes;
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'price',
        'discount',
    ];

    // Relationship with Purchase
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
