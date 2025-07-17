<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    // Relationships
    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }
}
