<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerReviewTable extends Model
{
    use HasFactory;
    // Explicitly define the table name
    protected $table = 'manager_review_tables';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'emp_id',
        'rate_employee_quality',
        'comments_rate_employee_quality',
        'organizational_goals',
        'comments_organizational_goals',
        'collaborate_colleagues',
        'comments_collaborate_colleagues',
        'leadership_responsibilities',
        'comments_leadership_responsibilities',
        'demonstrated',
        'comments_demonstrated',
        'thinking_contribution',
        'comments_thinking_contribution',
        'informed_progress',
        'comments_comments_informed_progress',
        'ManagerTotalReview'
    ];
}
