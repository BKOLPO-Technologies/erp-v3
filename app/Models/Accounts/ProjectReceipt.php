<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;

class ProjectReceipt extends Model
{
    protected $guarded = [];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
