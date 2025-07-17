<?php
namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CalculatesSupplierPurchases;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use CalculatesSupplierPurchases;

    use SoftDeletes;
    protected $guarded = [];

    // Define the relationship with purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Define the relationship with payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

   
}

