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
        Schema::create('evaluation_tables', function (Blueprint $table) {
            $table->id();
            $table->string('designation', 100)->nullable();
            $table->string('salary_grade', 100)->nullable();
            $table->string('employee_name', 100)->nullable();
            $table->string('emp_id');
            $table->string('department', 100)->nullable();
            $table->string('evaluation_purpose', 100)->nullable();
            $table->string('division', 100)->nullable();
            $table->string('manager_name', 100)->nullable();
            $table->string('joining_date', 30)->nullable();
            $table->string('review_period', 30)->nullable();
            $table->string('accuracy_neatness', 100)->nullable();
            $table->text('comments_accuracy', 200)->nullable();
            $table->string('adherence', 30)->nullable();
            $table->text('comments_adherence', 200)->nullable();
            $table->string('synchronization', 30)->nullable();
            $table->text('comments_synchronization', 200)->nullable();
            $table->string('qualityworktotalrating', 100)->nullable();
            $table->string('punctuality', 30)->nullable();
            $table->text('comments_punctuality', 200)->nullable();
            $table->string('attendance', 30)->nullable();
            $table->text('comments_attendance', 200)->nullable();
            $table->string('initiatives_at_workplace', 30)->nullable();
            $table->text('comments_initiatives', 200)->nullable();
            $table->string('submits_reports', 30)->nullable();
            $table->text('comments_submits_reports', 200)->nullable();
            $table->string('work_habits_rating', 30)->nullable();
            $table->string('skill_ability', 200)->nullable();
            $table->text('comments_skill_ability', 200)->nullable();
            $table->string('learning_improving', 30)->nullable();
            $table->text('comments_learning_improving', 200)->nullable();
            $table->string('problem_solving_ability', 30)->nullable();
            $table->text('comments_problem_solving', 200)->nullable();
            $table->string('jk_total_rating', 30)->nullable();
            $table->string('total_scoring_system', 200)->nullable();
            $table->text('recomendation', 200)->nullable();
            $table->text('evalutors_name', 50)->nullable();
            $table->string('evaluator_signatur', 255)->nullable();
            $table->date('evaluator_signatur_date')->nullable();
            $table->string('respond_contributes', 50)->nullable();
            $table->text('comments_respond_contributes', 200)->nullable();
            $table->string('responds_positively', 30)->nullable();
            $table->text('comments_responds_positively', 200)->nullable();
            $table->string('supervisor', 30)->nullable();
            $table->text('comments_supervisor', 200)->nullable();
            $table->string('adapts_changing', 30)->nullable();
            $table->text('comments_adapts_changing', 200)->nullable();
            $table->string('seeks_feedback', 30)->nullable();
            $table->text('comments_seeks_feedback', 200)->nullable();
            $table->string('ir_total_rating', 30)->nullable();
            $table->string('challenges', 30)->nullable();
            $table->text('comments_challenges', 200)->nullable();
            $table->string('personal_growth', 30)->nullable();
            $table->text('comments_personal_growth', 200)->nullable();
            $table->string('work_motivation', 30)->nullable();
            $table->text('comments_work_motivation', 200)->nullable();
            $table->string('leadership_rating', 30)->nullable();
            $table->string('progress_unsatisfactory', 30)->nullable();
            $table->text('comments_unsatisfactory', 200)->nullable();
            $table->string('progress_acceptable', 30)->nullable();
            $table->text('comments_acceptable', 200)->nullable();
            $table->string('progress_outstanding', 50)->nullable();
            $table->text('comments_outstanding', 200)->nullable();
            $table->text('final_comment', 255)->nullable();
            $table->text('director_name', 50)->nullable();
            $table->string('director_signatur', 255)->nullable();
            $table->date('director_signatur_date')->nullable();
            $table->tinyInteger('director_feedback_flag')->default(0);
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
        Schema::dropIfExists('evaluation_tables');
    }
};
