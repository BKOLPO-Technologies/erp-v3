<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    
    // Define the relationship with the Product model
    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_products') // Pivot table name
                    ->withPivot('quantity', 'price', 'discount') // Access pivot data (quantity, price)
                    ->withTimestamps(); // Automatically handle created_at and updated_at
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship with Purchase Products
    public function purchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_no', 'invoice_no');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}
