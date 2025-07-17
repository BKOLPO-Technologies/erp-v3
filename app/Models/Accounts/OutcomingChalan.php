<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutcomingChalan extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'sale_id',
        'invoice_date',
        'description',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function products()
    {
        return $this->hasMany(OutcomingChalanProduct::class);
    }

    public function outcomingChalanProducts()
    {
        return $this->hasMany(OutcomingChalanProduct::class, 'outcoming_chalan_id');
    }
}
