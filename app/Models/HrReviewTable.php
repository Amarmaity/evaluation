<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrReviewTable extends Model
{
    use HasFactory;
    protected $table = 'hr_review_tables';
    protected $fillable = [
        'emp_id',
        'adherence_hr',
        'comments_adherence_hr',
        'professionalism_positive',
        'comments_professionalism',
        'respond_feedback',
        'comments_respond_feedback',
        'initiative',
        'comments_initiative',
        'interest_learning',
        'comments_interest_learning',
        'company_leave_policy',
        'comments_company_leave_policy',
        'HrTotalReview',
        'financial_year'
    ];
}
