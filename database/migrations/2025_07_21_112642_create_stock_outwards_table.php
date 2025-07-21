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
        Schema::create('stock_outwards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('inventory_products')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('inventory_customers')->onDelete('cascade');
            $table->string('reference_lot')->unique()->comment('Unique reference for the stock inward');
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->text('comments')->nullable()->comment('Additional comments or notes regarding the stock inward');
            $table->tinyInteger('status')->default(1)->comment('Status of the stock inward, 1 for active, 0 for inactive');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_outwards');
    }
};
