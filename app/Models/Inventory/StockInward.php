<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockInward extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(InventoryProduct::class);
    }

    public function vendor()
    {
        return $this->belongsTo(InventoryVendor::class);
    }

    public function getStatusAttribute($value)
    {
        return $value ? '1' : '0';
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2);
    }

    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 2);
    }

    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity);
    }

    public function getFormattedReferenceLotAttribute()
    {
        return strtoupper($this->reference_lot);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    public function scopeWithVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeWithProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeWithReferenceLot($query, $referenceLot)
    {
        return $query->where('reference_lot', $referenceLot);
    }

    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeWithPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    public function scopeWithQuantityRange($query, $minQuantity, $maxQuantity)
    {
        return $query->whereBetween('quantity', [$minQuantity, $maxQuantity]);
    }

    public function scopeWithCreatedAtRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeWithUpdatedAtRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('updated_at', [$startDate, $endDate]);
    }

    public function scopeWithSoftDeleted($query)
    {
        return $query->withTrashed();
    }

    public function scopeWithoutSoftDeleted($query)
    {
        return $query->withoutTrashed();
    }

    public function scopeOnlySoftDeleted($query)
    {
        return $query->onlyTrashed();
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeOldestFirst($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    public function scopeWithSoftDeletes($query)
    {
        return $query->withTrashed();
    }

    public function scopeWithoutSoftDeletes($query)
    {
        return $query->withoutTrashed();
    }

    public function scopeOnlySoftDeletes($query)
    {
        return $query->onlyTrashed();
    }

}
