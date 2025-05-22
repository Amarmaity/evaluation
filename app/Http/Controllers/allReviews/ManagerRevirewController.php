<?php

namespace App\Http\Controllers\allReviews;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ManagerRevirewController extends Controller
{
    public function showManagwerReview($emp_id){

        $table = 'manager_review_tables';

        $managerColumnMapings = [
            'emp_id' => 'Employee ID',
            'rate_employee_quality' => '1. How would you rate the employee’s quality of work, including accuracy, neatness, and timeliness?',
            'comments_rate_employee_quality' => 'Justify Review',
            'organizational_goals' => '2. Does the employee align their work with the  organization’s goals and objectives?',
            'comments_organizational_goals' => 'Justify Review',
            'collaborate_colleagues' => '3. How effectively does the employee contribute to team efforts and collaborate with colleagues?',
            'comments_collaborate_colleagues' => 'Justify Review',
            'leadership_responsibilities' => '4. Can you provide an example of when the employee demonstrated problem-solving skills?',
            'comments_leadership_responsibilities' => 'Justify Review',
            'demonstrated' => '5. Has the employee shown leadership potential or accepted additional responsibilities?',
            'comments_demonstrated' => 'Justify Review',
            'thinking_contribution' => '6. How would you rate the employee’s innovative thinking and contribution to team success?',
            'comments_thinking_contribution' => 'Justify Review',
            'informed_progress' => '7. Does the employee effectively keep you informed about work progress and issues?',
            'comments_comments_informed_progress' => 'Justify Review',
            'ManagerTotalReview' => 'Manager Total Review'

        ];
        $users = DB::table($table)->where('emp_id', $emp_id)->first();
        if (!$users) {
            return redirect()->back()->with('error', 'No review found for this employee.');
        }
        return view('review/managerReviewDetails', compact('managerColumnMapings', 'users', 'emp_id'));
    }
}
