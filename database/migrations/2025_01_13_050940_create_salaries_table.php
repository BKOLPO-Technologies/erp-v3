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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('staff_id');
            $table->decimal('salary', 15, 2);
            $table->integer('month');
            $table->bigInteger('year');
            $table->decimal('payment_amount', 15, 2)->default(0);
            $table->string('payment_mode')->nullable();
            $table->decimal('will_get', 15, 2)->default(0);
            $table->string('status')->default('Unpaid');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
