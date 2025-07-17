<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    protected $guarded = [];

    use SoftDeletes;
    // Define the relationship with the Product model
    public function products()
    {
        return $this->belongsToMany(Product::class, 'quotation_product') // Pivot table name
                    ->withPivot('quantity', 'price', 'discount') // Access pivot data (quantity, price)
                    ->withTimestamps(); // Automatically handle created_at and updated_at
     }
 
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
