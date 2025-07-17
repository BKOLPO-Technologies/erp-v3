<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutcomingChalanProduct extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'outcoming_chalan_id',
        'product_id',
        'quantity',
        'receive_quantity',
    ];

    public function outcomingChalan()
    {
        return $this->belongsTo(OutcomingChalan::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
