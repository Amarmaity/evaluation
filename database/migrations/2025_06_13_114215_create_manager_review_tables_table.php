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
        Schema::create('manager_review_tables', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('rate_employee_quality');
            $table->string('comments_rate_employee_quality');
            $table->string('organizational_goals');
            $table->string('comments_organizational_goals');
            $table->string('collaborate_colleagues');
            $table->string('comments_collaborate_colleagues');
            $table->string('leadership_responsibilities');
            $table->string('comments_leadership_responsibilities');
            $table->string('demonstrated');
            $table->string('comments_demonstrated');
            $table->string('thinking_contribution');
            $table->string('comments_thinking_contribution');
            $table->string('informed_progress');
            $table->string('comments_comments_informed_progress');
            $table->string('ManagerTotalReview');
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
        Schema::dropIfExists('manager_review_tables');
    }
};
