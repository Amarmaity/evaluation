<?php

namespace App\Http\Controllers;


use App\Models\evaluationTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\EvaluationOtpMail;

class HomeController extends Controller
{
    //
    public function index()
    {
        return view("evaluationForm/evaluationForm");
    }

    // Send OTP to user email
    public function sendOtp(Request $request)
    {
        $sessionEmail = Session::get('user_email'); // Get email from session

        if (!$sessionEmail) {
            return response()->json([
                'success' => false,
                'message' => 'No email found in session!'
            ], 400);
        }

        $otp = random_int(100000, 999999); // Generate 6-digit OTP
        Session::put('otp', $otp);

        // Send OTP to user email
        Mail::to($sessionEmail)->send(new EvaluationOtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully to your email!'
        ]);
    }

    // Verify OTP
    public function evaluationverifyOtp(Request $request)
    {
        Log::info('Received OTP Verification Request:', $request->all());

        $sessionEmail = Session::get('otp_email'); // Get the OTP email from session
        Log::info('Email from session:', ['otp_email' => $sessionEmail]);

        // Validate OTP and Email
        $request->validate([
            'otp' => 'required|integer',
            'email' => 'required|email'
        ]);

        // Check if the email matches the one used for OTP generation
        if ($request->email !== $sessionEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Email does not match OTP request!'
            ], 400);
        }

        // Check if OTP matches the one stored in session
        if ($request->otp == Session::get('otp')) {
            Session::forget('otp');
            Session::put('otp_verified', true);
            Session::save();

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid OTP!'
        ], 400);
    }

    public function submitEvaluation(Request $request)
    {
        Log::info('Request Data:', $request->all());
        Log::info('Session Data:', Session::all());

        // Check if OTP is verified
        if (!Session::get('otp_verified')) {
            return response()->json([
                'success' => false,
                'message' => 'OTP verification required before submitting evaluation!'
            ], 400);
        }

        // Validate the email with the one used for OTP generation
        if (Session::get('user_email') !== $request->email) {
            return response()->json([
                'success' => false,
                'message' => 'Email does not match OTP request!'
            ], 400);
        }

        // Validation for general evaluation data (Part 1)
        $request->validate([
            'evaluator_signatur' => 'nullable|mimes:jpg,png,pdf|max:2048',
            'director_signatur' => 'nullable|mimes:jpg,png,pdf|max:2048',
        ]);

        $evaluatorSignaturePath = $request->hasFile('evaluator_signatur')
            ? $request->file('evaluator_signatur')->store('signatures', 'public') // Store in public directory
            : null;

        // Prepare the general evaluation data (Part 1)
        $evaluationData = [
            'designation' => $request->input('designation'),
            'salary_grade' => $request->input('salary_grade'),
            'employee_name' => $request->input('employee_name'),
            'emp_id' => $request->input('emp_id'),
            'department' => $request->input('department'),
            'evaluation_purpose' => $request->input('evaluation_purpose'),
            'division' => $request->input('division'),
            'manager_name' => $request->input('manager_name'),
            'joining_date' => $request->input('joining_date'),
            'review_period' => $request->input('review_period'),
            'accuracy_neatness' => $request->input('accuracy_neatness'),
            'comments_accuracy' => $request->input('comments_accuracy'),
            'adherence' => $request->input('adherence'),
            'comments_adherence' => $request->input('comments_adherence'),
            'synchronization' => $request->input('synchronization'),
            'comments_synchronization' => $request->input('comments_synchronization'),
            'qualityworktotalrating' => $request->input('qualityworktotalrating'),
            'punctuality' => $request->input('punctuality'),
            'comments_punctuality' => $request->input('comments_punctuality'),
            'attendance' => $request->input('attendance'),
            'comments_attendance' => $request->input('comments_attendance'),
            'initiatives_at_workplace' => $request->input('initiatives_at_workplace'),
            'comments_initiatives' => $request->input('comments_initiatives'),
            'submits_reports' => $request->input('submits_reports'),
            'comments_submits_reports' => $request->input('comments_submits_reports'),
            'work_habits_rating' => $request->input('work_habits_rating'),
            'skill_ability' => $request->input('skill_ability'),
            'comments_skill_ability' => $request->input('comments_skill_ability'),
            'learning_improving' => $request->input('learning_improving'),
            'comments_learning_improving' => $request->input('comments_learning_improving'),
            'problem_solving_ability' => $request->input('problem_solving_ability'),
            'comments_problem_solving' => $request->input('comments_problem_solving'),
            'jk_total_rating' => $request->input('jk_total_rating'),
            'total_scoring_system' => $request->input('total_scoring_system'),
            'recomendation' => $request->input('recomendation'),
            'evalutors_name' => $request->input('evalutors_name'),
            'evaluator_signatur' => $evaluatorSignaturePath,
            'evaluator_signatur_date' => $request->input('evaluator_signatur_date'),
            'respond_contributes' => $request->input('respond_contributes'),
            'comments_respond_contributes' => $request->input('comments_respond_contributes'),
            'responds_positively' => $request->input('responds_positively'),
            'comments_responds_positively' => $request->input('comments_responds_positively'),
            'supervisor' => $request->input('supervisor'),
            'comments_supervisor' => $request->input('comments_supervisor'),
            'adapts_changing' => $request->input('adapts_changing'),
            'comments_adapts_changing' => $request->input('comments_adapts_changing'),
            'seeks_feedback' => $request->input('seeks_feedback'),
            'comments_seeks_feedback' => $request->input('comments_seeks_feedback'),
            'ir_total_rating' => $request->input('ir_total_rating'),
            'challenges' => $request->input('challenges'),
            'comments_challenges' => $request->input('comments_challenges'),
            'personal_growth' => $request->input('personal_growth'),
            'comments_personal_growth' => $request->input('comments_personal_growth'),
            'work_motivation' => $request->input('work_motivation'),
            'comments_work_motivation' => $request->input('comments_work_motivation'),
            'leadership_rating' => $request->input('leadership_rating'),
            'progress_unsatisfactory' => $request->input('progress_unsatisfactory'),
            'comments_unsatisfactory' => $request->input('comments_unsatisfactory'),
            'progress_acceptable' => $request->input('progress_acceptable'),
            'comments_acceptable' => $request->input('comments_acceptable'),
            'progress_outstanding' => $request->input('progress_outstanding'),
            'comments_outstanding' => $request->input('comments_outstanding'),
        ];

        try {
            $evaluation = evaluationTable::create($evaluationData);
            return response()->json([
                'success' => true,
                'message' => 'Evaluation submitted successfully!',
                'evaluation_id' => $evaluation->id, 
                'emp_id' => $evaluation->emp_id, 
            ]);
        } catch (\Exception $e) {
            Log::error('Error while submitting evaluation:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error while submitting evaluation. Please try again later.'
            ], 500);
        }
    }

    public function submitEvaluationDirector(Request $request, $emp_id)
    {
        $request->validate([
            'final_comment' => 'required|string',
            'director_name' => 'required|string',
            'director_signatur' => 'nullable|mimes:jpg,png,pdf|max:2048',
            'director_signatur_date' => 'required|date',
        ]);

        try {
            // Use emp_id to fetch the row (not ID)
            $evaluation = evaluationTable::where('emp_id', $emp_id)->firstOrFail();

            $directorSignaturePath = $request->hasFile('director_signatur')
                ? $request->file('director_signatur')->store('signatures', 'public')
                : $evaluation->director_signatur;

            // Update only the director-related fields
            $evaluation->update([
                'final_comment' => $request->input('final_comment'),
                'director_name' => $request->input('director_name'),
                'director_signatur' => $directorSignaturePath,
                'director_signatur_date' => $request->input('director_signatur_date'),
            ]);

            return redirect()->back()->with('success', 'Director feedback submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Error submitting director feedback: ', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error submitting director feedback.');
        }
    }
}
