<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveDocument extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
