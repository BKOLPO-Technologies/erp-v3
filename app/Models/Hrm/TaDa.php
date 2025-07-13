<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;

class TaDa extends Model
{
    protected $guarded = [];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function type()
    {
        return $this->belongsTo(TaDaType::class, 'tada_type_id');
    }


    public function details()
    {
        return $this->hasMany(TaDetail::class, 'ta_id');
    }

    public function files()
    {
        return $this->hasMany(TaFile::class, 'ta_id');
    }
}
