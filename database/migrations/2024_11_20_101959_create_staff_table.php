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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('hr_code')->unique(); 
            $table->string('full_name');
            $table->enum('sex', ['Male', 'Female', 'Other'])->nullable(); 
            $table->date('birthday')->nullable(); 
            $table->string('birthplace')->nullable(); 
            $table->string('hometown')->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed'])->nullable();
            $table->string('nation')->nullable();
            $table->string('religion')->nullable(); 
            $table->string('id_document_type')->nullable();
            $table->date('id_creation_date')->nullable(); 
            $table->string('place_of_issue')->nullable(); 
            $table->string('resident_of')->nullable();
            $table->text('current_address')->nullable(); 
            $table->string('literacy')->nullable(); 
            $table->enum('status',['Working', 'Maternity', 'Inactivity'])->nullable(); 
            $table->string('job_position')->nullable(); 
            $table->string('workplace')->nullable(); 
            $table->string('bank_account_number')->nullable();
            $table->string('account_name')->nullable(); 
            $table->string('bank_of_issue')->nullable(); 
            $table->string('personal_tax_code')->nullable(); 
            $table->decimal('hourly_rate', 8, 2)->default(0); 
            $table->string('phone')->nullable(); 
            $table->string('facebook')->nullable(); 
            $table->string('linkedin')->nullable();
            $table->string('skype')->nullable(); 
            $table->string('email')->unique(); 
            $table->string('default_language')->nullable(); 
            $table->enum('direction', ['LTR', 'RTL'])->default('LTR'); 
            $table->text('email_signature')->nullable(); 
            $table->string('education')->nullable(); 
            $table->string('previous_job')->nullable(); 
            $table->string('publication_job')->nullable(); 
            $table->string('award')->nullable(); 
            $table->string('documents')->nullable(); 
            $table->text('other_information')->nullable(); 
            $table->boolean('is_administrator')->default(false); 
            $table->boolean('send_welcome_email')->default(0)->comment('1=>Email Send, 0=>Email Not Send');
            $table->string('password'); 
            $table->string('show_password'); 
            $table->softDeletes();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
