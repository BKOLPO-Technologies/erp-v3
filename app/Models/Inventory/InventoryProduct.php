<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryProduct extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }


    public function tag()
    {
        return $this->belongsTo(ProductTag::class);
    }

    public function brand()
    {
        return $this->belongsTo(ProductBrand::class);
    }

    public function unit()
    {
        return $this->belongsTo(ProductUnit::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag', 'product_id', 'tag_id');
    }


    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class, 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function prices()
    {
        return $this->hasMany(InventoryProductPrice::class);
    }

    public function latestPrice()
    {
        return $this->hasOne(InventoryProductPrice::class)->latest();
    }
    
    public function stockInwards()
    {
        return $this->hasMany(StockInward::class, 'product_id');
    }

    // stock
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_id');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'product_id');
    }



}
