<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class evaluationTable extends Model
{
    use HasFactory;
    protected $table = 'evaluation_tables';

    protected $fillable = [
        'designation',
        'salary_grade',
        'employee_name',
        'emp_id',
        'department',
        'evaluation_purpose',
        'division',
        'manager_name',
        'joining_date',
        'review_period',
        'accuracy_neatness',
        'comments_accuracy',
        'adherence',
        'comments_adherence',
        'synchronization',
        'comments_synchronization',
        'qualityworktotalrating',
        'punctuality',
        'comments_punctuality',
        'attendance',
        'comments_attendance',
        'initiatives_at_workplace',
        'comments_initiatives',
        'submits_reports',
        'comments_submits_reports',
        'work_habits_rating',
        'skill_ability',
        'comments_skill_ability',
        'learning_improving',
        'comments_learning_improving',
        'problem_solving_ability',
        'comments_problem_solving',
        'jk_total_rating',
        'total_scoring_system',
        'recomendation',
        'evalutors_name',
        'evaluator_signatur',
        'evaluator_signatur_date',
        'respond_contributes',
        'comments_respond_contributes',
        'responds_positively',
        'comments_responds_positively',
        'supervisor',
        'comments_supervisor',
        'adapts_changing',
        'comments_adapts_changing',
        'seeks_feedback',
        'comments_seeks_feedback',
        'ir_total_rating',
        'challenges',
        'comments_challenges',
        'personal_growth',
        'comments_personal_growth',
        'work_motivation',
        'comments_work_motivation',
        'leadership_rating',
        'progress_unsatisfactory',
        'comments_unsatisfactory',
        'progress_acceptable',
        'comments_acceptable',
        'progress_outstanding',
        'comments_outstanding',
        'final_comment',
        'director_name',
        'director_signatur',
        'director_signatur_date',
        'director_feedback_flag',
        'financial_year'
    ];
}
