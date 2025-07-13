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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->unsignedBigInteger('device_user_id')->nullable(); 
            $table->unsignedBigInteger('shift_id')->nullable(); 
            $table->date('date')->nullable();  
            $table->string('time')->nullable();  
            $table->string('late')->nullable();  
            $table->string('long')->nullable(); 
            $table->string('location')->nullable();  
            $table->tinyinteger('type')->default('1')->comment('1=>Manually, 2=>Zkteco')->nullable();  
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
