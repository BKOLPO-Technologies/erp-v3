<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
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
