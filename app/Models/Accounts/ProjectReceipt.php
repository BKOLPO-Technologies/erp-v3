<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectReceipt extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
