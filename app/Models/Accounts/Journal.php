<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    // Define an inverse relationship with Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Define an inverse relationship with Ledger
    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }
}
