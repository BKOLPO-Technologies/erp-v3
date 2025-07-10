<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(ProjectItem::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function receipts()
    {
        return $this->hasMany(ProjectReceipt::class, 'invoice_no', 'reference_no');
    }

    public function paidSales()
    {
        return $this->hasMany(Sale::class, 'project_id')->where('status', 'paid');
    }



}
