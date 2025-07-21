<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Optional foreign keys for customers
            $table->foreignId('customer_id')->nullable()->constrained('inventory_customers')->nullOnDelete(); // for sales
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Order details
            $table->string('order_number')->unique(); 
            $table->string('reference_number')->nullable(); 
            $table->date('order_date')->default(now());
            $table->date('expected_delivery_date')->nullable();

            // Financial fields
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->integer('vat_rate')->nullable()->default(0);
            $table->decimal('vat_amount', 12, 2)->default(0);
            $table->integer('tax_rate')->nullable()->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);

            // Status tracking
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid');
            $table->enum('order_status', ['draft', 'pending', 'approved', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'])->default('draft');
            $table->string('payment_method')->nullable();
            $table->string('payment_terms')->nullable();

            // Additional info
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('notes')->nullable();
            $table->text('terms_and_conditions')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Add indexes for better performance
            $table->index('customer_id');
            $table->index('user_id');
            $table->index('order_number');
            $table->index('reference_number');
            $table->index('order_date');
            $table->index('expected_delivery_date');
            $table->index('payment_status');
            $table->index('order_status');
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
