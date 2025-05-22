<?php

namespace App\Http\Controllers\allReviews;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HrController extends Controller
{
    public function showHrReview($emp_id){

        $table = 'hr_review_tables';

        $hrColumnMapings = [
            'emp_id' =>'Employee ID',
            'adherence_hr' => '1. How would you rate the employee’s adherence to company policies and procedures?',
            'comments_adherence_hr' => 'Justify Review',
            'professionalism_positive' => '2. Does the employee maintain professionalism and a positive attitude in the workplace?',
            'comments_professionalism' => 'Justify Review',
            'respond_feedback' => '3. How well does the employee respond to feedback or suggestions for improvement from colleagues?',
            'comments_respond_feedback' => 'Justify Review',
            'initiative' => '4. Does the employee take the initiative to seek feedback and act on it?',
            'comments_initiative' => 'Justify Review',
            'interest_learning' => '5. Has the employee shown interest in learning and participating in training programs?',
            'comments_interest_learning' => 'Justify Review',
            'company_leave_policy' => '6. Does the employee consistently adhere to the company’s leave policy?',
            'comments_company_leave_policy' => 'Justify Review',
            'HrTotalReview' => 'HR Total Review'
        ];

        // $users = DB::table($table)->get();
        $users = DB::table($table)->where('emp_id', $emp_id)->first();
        if (!$users) {
            return redirect()->back()->with('error', 'No review found for this employee.');
        }
        return view('review/hrReviewDetails', compact('hrColumnMapings', 'users', 'emp_id'));
    }
    
}
