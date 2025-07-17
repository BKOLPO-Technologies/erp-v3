<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ledger extends Model
{
    use SoftDeletes;
    //protected $guarded = [];
    protected $fillable = [
        'name',
        'opening_balance',
        'ob_type',
        'status',
        'created_by',
        'updated_by',
    ];

    // Relationship: A ledger can have many ledger groups
    public function ledgerGroups()
    {
        return $this->hasMany(LedgerGroup::class);
    }

    // public function groups() {
    //     return $this->belongsToMany(LedgerGroup::class, 'ledger_group_details', 'ledger_id', 'group_id');
    // }

    public function journalVoucherDetails()
    {
        return $this->hasMany(JournalVoucherDetail::class, 'ledger_id', 'id');
    }

    public function groups()
    {
        return $this->belongsToMany(LedgerGroup::class, 'ledger_group_subgroup_ledgers', 'ledger_id', 'group_id');
    }

    public function subGroups()
    {
        return $this->belongsToMany(LedgerSubGroup::class, 'ledger_group_subgroup_ledgers', 'ledger_id', 'sub_group_id');
    }

    public function ledgerGroupSubgroup()
    {
        return $this->hasOne(LedgerGroupSubgroupLedger::class, 'ledger_id');
    }


}
