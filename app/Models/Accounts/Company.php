<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    // Define an inverse relationship with Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
