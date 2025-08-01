<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentHistory extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
