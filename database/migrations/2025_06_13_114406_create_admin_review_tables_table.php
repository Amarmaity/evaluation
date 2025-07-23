<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_review_tables', function (Blueprint $table) {
            $table->id();
             $table->string('emp_id');
            $table->string('demonstrated_attendance', 20)->nullable();
            $table->string('comments_demonstrated_attendance', 150)->nullable();
            $table->string('employee_manage_shift', 20)->nullable();
            $table->string('comments_employee_manage_shift', 150)->nullable();
            $table->string('documentation_neatness', 20)->nullable();
            $table->string('comments_documentation_neatness', 150)->nullable();
            $table->string('followed_instructions', 20)->nullable();
            $table->string('comments_followed_instructions', 150)->nullable();
            $table->string('productive', 20)->nullable();
            $table->string('comments_productive', 150)->nullable();
            $table->string('changes_schedules', 20)->nullable();
            $table->string('comments_changes_schedules', 150)->nullable();
            $table->string('leave_policy', 20)->nullable();
            $table->string('comments_leave_policy', 150)->nullable();
            $table->string('salary_deduction', 20)->nullable();
            $table->string('comments_salary_deduction', 150)->nullable();
            $table->string('interact_housekeeping', 20)->nullable();
            $table->string('comments_interact_housekeeping', 150)->nullable();
            $table->string('AdminTotalReview', 100)->nullable();
            $table->string('financial_year')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_review_tables');
    }
};
