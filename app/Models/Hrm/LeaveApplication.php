<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
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

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function documents()
    {
        return $this->belongsTo(LeaveDocument::class, 'leave_application_id');
    }
}
