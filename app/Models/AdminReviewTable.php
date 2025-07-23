<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminReviewTable extends Model
{
    use HasFactory;

    protected $table = 'admin_review_tables';
    protected $fillable = [
        'emp_id',
        'demonstrated_attendance',
        'comments_demonstrated_attendance',
        'employee_manage_shift',
        'comments_employee_manage_shift',
        'documentation_neatness',
        'comments_documentation_neatness',
        'followed_instructions',
        'comments_followed_instructions',
        'productive',
        'comments_productive',
        'changes_schedules',
        'comments_changes_schedules',
        'leave_policy',
        'comments_leave_policy',
        'salary_deduction',
        'comments_salary_deduction',
        'interact_housekeeping',
        'comments_interact_housekeeping',
        'AdminTotalReview',
        'financial_year'
    ];
}
