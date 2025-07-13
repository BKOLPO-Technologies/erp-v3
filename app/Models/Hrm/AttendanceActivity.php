<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;

class AttendanceActivity extends Model
{
    protected $guarded = [];


    public function staff(){
        return $this->belongsTo(Staff::class, 'user_id', 'id');
    }

    public function shift(){
        return $this->belongsTo(Shift::class, 'shift_id', 'id');
    }
}
