<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CalculatesClientPurchases;


class Client extends Model
{
    use CalculatesClientPurchases;

    protected $guarded = [];

    // Define the relationship with the Sale model
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Define the relationship with payments
    public function payments()
    {
        return $this->hasMany(ProjectReceipt::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

}
