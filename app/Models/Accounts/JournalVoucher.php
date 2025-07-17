<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalVoucher extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'transaction_code',
        'company_id',
        'branch_id',
        'transaction_date',
        'type',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function details()
    {
        return $this->hasMany(JournalVoucherDetail::class);
    }
}
