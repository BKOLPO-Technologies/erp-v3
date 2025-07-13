<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;

class TaDetail extends Model
{
    protected $fillable = ['ta_id', 'purpose', 'amount', 'remarks'];

    public function tada()
    {
        return $this->belongsTo(TaDa::class, 'ta_id');
    }
}
