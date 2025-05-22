<?php

namespace App\Http\Controllers\allReviews;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminReviewController extends Controller
{
    public function showAdminReview($emp_id)
    {
        $table = 'admin_review_tables';

        $adminColumnMappings = [
            'emp_id' => 'Employee ID',
            'demonstrated_attendance' => '1. Has the employee demonstrated regular attendance and punctuality?',
            'comments_demonstrated_attendance' => 'Justify Review',
            'employee_manage_shift' => '2. How well does the employee manage time within the shift?',
            'comments_employee_manage_shift' => 'Justify Review',
            'documentation_neatness' => '3. How would you rate the employee’s accuracy and neatness in reports and documentation?',
            'comments_documentation_neatness' => 'Justify Review',
            'followed_instructions' => '4. Has the employee followed administrative procedures and job instructions properly?',
            'comments_followed_instructions' => 'Justify Review',
            'productive' => '5. Does the employee effectively manage time and stay productive during working hours?',
            'comments_productive' => 'Justify Review',
            'changes_schedules' => '6. How well does the employee handle changes in schedules or assignments?',
            'comments_changes_schedules' => 'Justify Review',
            'leave_policy' => '7. Does the employee consistently adhere to the company’s leave policy?',
            'comments_leave_policy' => 'Justify Review',
            'salary_deduction' => '8. Has there been any salary deduction due to the employee’s leave?',
            'comments_salary_deduction' => 'Justify Review',
            'interact_housekeeping' => '9. How well does the employee interact with the housekeeping staff?',
            'comments_interact_housekeeping' => 'Justify Review',
            'AdminTotalReview' => 'Admin Total Review'
        ];

        // Fixing the typo: Change 'tabel' to 'table'
        $users = DB::table($table)->where('emp_id', $emp_id)->first();
        if (!$users) {
            return redirect()->back()->with('error', 'No review found for this employee.');
        }

        // Fixing compact: Variable names shouldn't have '$' inside compact()
        return view('review/adminReviewDetails', compact('adminColumnMappings', 'users', 'emp_id'));
    }
}
