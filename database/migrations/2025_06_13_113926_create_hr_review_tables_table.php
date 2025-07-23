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
        Schema::create('hr_review_tables', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('adherence_hr', 20)->nullable();
            $table->string('comments_adherence_hr', 150)->nullable();
            $table->string('professionalism_positive', 20)->nullable();
            $table->string('comments_professionalism', 150)->nullable();
            $table->string('respond_feedback', 20)->nullable();
            $table->string('comments_respond_feedback', 150)->nullable();
            $table->string('initiative', 20)->nullable();
            $table->string('comments_initiative', 150)->nullable();
            $table->string('interest_learning', 20)->nullable();
            $table->string('comments_interest_learning', 150)->nullable();
            $table->string('company_leave_policy', 20)->nullable();
            $table->string('comments_company_leave_policy', 150)->nullable();
            $table->string('HrTotalReview', 100);
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
        Schema::dropIfExists('hr_review_tables');
    }
};
