<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomingChalanProduct extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'incoming_chalan_id',
        'product_id',
        'quantity',
        'receive_quantity',
    ];

    public function incomingChalan()
    {
        return $this->belongsTo(IncomingChalan::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
