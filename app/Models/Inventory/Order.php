<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'user_id',
        'order_number',
        'reference_number',
        'order_date',
        'expected_delivery_date',
        'subtotal',
        'discount',
        'vat_amount',
        'tax_amount',
        'shipping_cost',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_status',
        'order_status',
        'payment_method',
        'payment_terms',
        'shipping_address',
        'billing_address',
        'notes',
        'terms_and_conditions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'order_date',
        'expected_delivery_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Payment status constants
     */
    public const PAYMENT_STATUS_UNPAID = 'unpaid';
    public const PAYMENT_STATUS_PARTIAL = 'partial';
    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_REFUNDED = 'refunded';

    /**
     * Order status constants
     */
    public const ORDER_STATUS_DRAFT = 'draft';
    public const ORDER_STATUS_PENDING = 'pending';
    public const ORDER_STATUS_APPROVED = 'approved';
    public const ORDER_STATUS_PROCESSING = 'processing';
    public const ORDER_STATUS_SHIPPED = 'shipped';
    public const ORDER_STATUS_DELIVERED = 'delivered';
    public const ORDER_STATUS_COMPLETED = 'completed';
    public const ORDER_STATUS_CANCELLED = 'cancelled';

    /**
     * Get the customer associated with the order.
     */
    public function customer()
    {
        return $this->belongsTo(InventoryCustomer::class);
    }

    /**
     * Get the user who created the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    

    /**
     * Get the payments for the order.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope a query to only include paid orders.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_STATUS_PAID);
    }

    /**
     * Scope a query to only include unpaid orders.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_STATUS_UNPAID);
    }

    /**
     * Scope a query to only include orders with a specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('order_status', $status);
    }

    /**
     * Check if order is paid.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_STATUS_PAID;
    }

    /**
     * Check if order is completed.
     */
    public function isCompleted(): bool
    {
        return $this->order_status === self::ORDER_STATUS_COMPLETED;
    }

    /**
     * Calculate the remaining due amount.
     */
    public function getRemainingDueAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    /**
     * Generate the next order number.
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD-';
        $lastOrder = self::orderBy('id', 'desc')->first();
        $number = $lastOrder ? (int) str_replace($prefix, '', $lastOrder->order_number) + 1 : 1;
        
        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}