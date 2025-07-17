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
        Schema::create('attendance_activities', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_id'); 
            $table->date('date')->nullable();  
            $table->string('time')->nullable();  
            $table->string('late')->nullable();  
            $table->string('long')->nullable(); 
            $table->string('location')->nullable();  
            $table->string('device_id')->nullable(); 
            $table->string('timestamp')->nullable();   
            $table->tinyInteger('type')->default(1)->comment('1=>Manually, 2=>Zkteco');
            $table->softDeletes();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_activities');
    }
};
