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
        Schema::create('super_add_users', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('dob');
            $table->string('gender');
            $table->string('mobno');
            $table->string('employee_id')->unique();
            $table->string('evaluation_purpose');
            $table->string('division');
            $table->string('manager_name')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->string('user_type')->nullable();
            $table->json('user_roles')->nullable();
            $table->decimal('salary', 10, 2);
            $table->string('email')->unique();
            $table->json('client_id')->nullable();
            $table->string('salary_grade');
            $table->string('password');
            $table->string('company_percentage')->nullable();
            $table->string('financial_year')->nullable();
            $table->boolean('status')->default(1);
            $table->date('probation_date')->nullable();
            $table->string('employee_status', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('super_add_users');
    }
};
