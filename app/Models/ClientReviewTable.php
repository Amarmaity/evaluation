<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AllClient;

class ClientReviewTable extends Model
{
    use HasFactory;

    protected $table = 'client_review_tables';

    protected $fillable = [
        'emp_id',
        'client_id',
        'understand_requirements',
        'comment_understand_requirements',
        'business_needs',
        'comments_business_needs',
        'detailed_project_scope',
        'comments_detailed_project_scope',
        'responsive_reach_project',
        'comments_responsive_reach_project',
        'comfortable_discussing',
        'comments_comfortable_discussing',
        'regular_updates',
        'comments_regular_updates',
        'concerns_addressed',
        'comments_concerns_addressed',
        'technical_expertise',
        'comments_technical_expertise',
        'best_practices',
        'comments_best_practices',
        'suggest_innovative',
        'comments_suggest_innovative',
        'quality_code',
        'comments_quality_code',
        'encounter_issues',
        'comments_encounter_issues',
        'code_scalable',
        'comments_code_scalable',
        'solution_perform',
        'comments_solution_perform',
        'project_delivered',
        'comments_project_delivered',
        'communicated_handled',
        'comments_communicated_handled',
        'development_process',
        'comments_development_process',
        'unexpected_challenges',
        'comments_unexpected_challenges',
        'effective_workarounds',
        'comments_effective_workarounds',
        'bugs_issues',
        'comments_bugs_issues',
        'ClientTotalReview',
        'financial_year'
    ];

        public function client()
{
    return $this->belongsTo(AllClient::class, 'client_id');
}
}
