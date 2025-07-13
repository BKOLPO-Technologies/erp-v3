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
        Schema::create('ta_das', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('tada_type_id');
            $table->string('name')->nullable();
            $table->string('designation')->nullable();
            $table->string('project_name')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta_das');
    }
};
