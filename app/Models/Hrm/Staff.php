<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $guarded = [];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function educations()
    {
        return $this->hasMany(Education::class, 'staff_id');
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class, 'staff_id');
    }

    public function awards()
    {
        return $this->hasMany(Award::class, 'staff_id');
    }

    public function employmentHistories()
    {
        return $this->hasMany(EmploymentHistory::class, 'staff_id');
    }


}
