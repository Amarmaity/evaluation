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
        Schema::create('client_review_tables', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('understand_requirements', 20)->nullable();
            $table->string('comment_understand_requirements', 150)->nullable();
            $table->string('business_needs', 20)->nullable();
            $table->string('comments_business_needs', 150)->nullable();
            $table->string('detailed_project_scope', 20)->nullable();
            $table->string('comments_detailed_project_scope', 150)->nullable();
            $table->string('responsive_reach_project', 20)->nullable();
            $table->string('comments_responsive_reach_project', 150)->nullable();
            $table->string('comfortable_discussing', 20)->nullable();
            $table->string('comments_comfortable_discussing', 150)->nullable();
            $table->string('regular_updates', 20)->nullable();
            $table->string('comments_regular_updates', 150)->nullable();
            $table->string('concerns_addressed', 20)->nullable();
            $table->string('comments_concerns_addressed', 150)->nullable();
            $table->string('technical_expertise', 20)->nullable();
            $table->string('comments_technical_expertise', 150)->nullable();
            $table->string('best_practices', 20)->nullable();
            $table->string('comments_best_practices', 150)->nullable();
            $table->string('suggest_innovative', 20)->nullable();
            $table->string('comments_suggest_innovative', 150)->nullable();
            $table->string('quality_code', 20)->nullable();
            $table->string('comments_quality_code', 150)->nullable();
            $table->string('encounter_issues', 20)->nullable();
            $table->string('comments_encounter_issues', 150)->nullable();
            $table->string('code_scalable', 20)->nullable();
            $table->string('comments_code_scalable', 150)->nullable();
            $table->string('solution_perform', 20)->nullable();
            $table->string('comments_solution_perform', 150)->nullable();
            $table->string('project_delivered', 20)->nullable();
            $table->string('comments_project_delivered', 150)->nullable();
            $table->string('communicated_handled', 20)->nullable();
            $table->string('comments_communicated_handled', 150)->nullable();
            $table->string('development_process', 20)->nullable();
            $table->string('comments_development_process', 150)->nullable();
            $table->string('unexpected_challenges', 20)->nullable();
            $table->string('comments_unexpected_challenges', 150)->nullable();
            $table->string('effective_workarounds', 20)->nullable();
            $table->string('comments_effective_workarounds', 150)->nullable();
            $table->string('bugs_issues', 20)->nullable();
            $table->string('comments_bugs_issues', 150)->nullable();
            $table->string('ClientTotalReview', 100)->nullable();
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
        Schema::dropIfExists('client_review_tables');
    }
};
