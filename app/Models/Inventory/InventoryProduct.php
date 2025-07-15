<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class InventoryProduct extends Model
{
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

}
