<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    // Define the relationship with the Product model
    public function products()
    {
        return $this->belongsToMany(Product::class, 'work_order_product') // Pivot table name
                    ->withPivot('quantity', 'price') // Access pivot data (quantity, price)
                    ->withTimestamps(); // Automatically handle created_at and updated_at
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
