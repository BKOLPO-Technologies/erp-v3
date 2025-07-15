<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    // Relationship: A product belongs to a unit
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    // Define the relationship with the Purchase model
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)
                    ->withPivot('quantity', 'price') // Access pivot data (quantity and price)
                    ->withTimestamps(); // Keep track of created_at and updated_at
    }
    
    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'sale_product') // Define pivot table name
                    ->withPivot('quantity', 'price') // Access pivot data (quantity, price)
                    ->withTimestamps(); // Automatically manage created_at and updated_at timestamps
    }

    // Relationship with SaleProduct
    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class, 'product_id');
    }

    // Relationship with StockIn
    public function stockIns()
    {
        return $this->hasMany(StockIn::class, 'product_id');
    }

    // Relationship with StockOut
    public function stockOuts()
    {
        return $this->hasMany(StockOut::class, 'product_id');
    }

    public function receivedQuantities()
    {
        return $this->hasManyThrough(
            OutcomingChalanProduct::class, // Final table
            OutcomingChalan::class, // Intermediate table
            'sale_id', // Foreign key in OutcomingChalan pointing to Sale
            'outcoming_chalan_id', // Foreign key in OutcomingChalanProduct pointing to OutcomingChalan
            'id', // Local key in Product
            'id'  // Local key in OutcomingChalan
        );
    }

    public function purchasesFiltered($fromDate, $toDate)
    {
        return $this->hasMany(Purchase::class, 'project_id')
            ->whereBetween('invoice_date', [$fromDate, $toDate]);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }


    
}
