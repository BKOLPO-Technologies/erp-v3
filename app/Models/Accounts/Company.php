<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    // Define an inverse relationship with Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
