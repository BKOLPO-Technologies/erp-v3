<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoiceItem extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'purchase_invoice_id',
        'product_id',
        'quantity',
        'price',
        'discount',
        'total',
    ];

    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
