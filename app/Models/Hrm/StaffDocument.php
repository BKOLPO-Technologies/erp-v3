<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;

class StaffDocument extends Model
{
    protected $guarded = [];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
