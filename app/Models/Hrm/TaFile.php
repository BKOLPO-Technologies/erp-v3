<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;

class TaFile extends Model
{
    protected $fillable = ['ta_id', 'file_path'];

    public function tada()
    {
        return $this->belongsTo(TaDa::class, 'ta_id');
    }
}
