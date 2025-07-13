<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at'];
    
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'id');
    }
}
