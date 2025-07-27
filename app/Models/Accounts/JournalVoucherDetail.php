<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalVoucherDetail extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'journal_voucher_id',
        'ledger_id',
        'reference_no',
        'description',
        'debit',
        'credit',
    ];

    public function journalVoucher()
    {
        return $this->belongsTo(JournalVoucher::class);
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }
}
