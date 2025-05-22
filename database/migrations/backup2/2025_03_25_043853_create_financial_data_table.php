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
        Schema::create('financial_data', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name', 150)->nullable();
            $table->string('emp_id')->nullable();
            $table->string('evaluation_score',150)->nullable();
            $table->string('hr_review', 150)->nullable();
            $table->string('admin_review', 150)->nullable();
            $table->string('manager_review', 150)->nullable();
            $table->string('clint_review', 150)->nullable();
            $table->string('apprisal_score', 150)->nullable();
            $table->string('current_salary', 150)->nullable();
            $table->string('percentage_given', 150)->nullable();
            $table->string('update_salary', 150)->nullable();
            $table->string('final_salary', 150)->nullable();
            $table->string('apprisal_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_data');
    }
};
