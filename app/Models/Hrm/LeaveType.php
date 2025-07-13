<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $guarded = [];

    // Relationships
    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }
}
