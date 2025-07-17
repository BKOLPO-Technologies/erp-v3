<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at'];
    
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'id');
    }
}
