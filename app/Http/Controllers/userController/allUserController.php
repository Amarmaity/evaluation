<?php

namespace App\Http\Controllers\userController;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\AdminReviewTable;
use App\Models\ClientReviewTable;
use App\Models\evaluationTable;
use App\Models\FinancialData;
use App\Models\HrReviewTable;
use App\Models\ManagerReviewTable;
use App\Models\SuperAddUser;
use App\Models\SuperUserTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;



class allUserController extends Controller
{
    //

    public function indexUserLogin()
    {
        $superUser = null;
        return view("loginusers/userlogin", compact('superUser'));
    }


    // Handle user login and send OTP
    public function loginUserAutenticacaon(Request $request)
    {
        // Validate the input fields
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:4',
            'user_type' => 'required|string',
        ]);
        // Check if the user exists by email
        $userslogin = SuperAddUser::where('email', $validated['email'])->first();
        $superUsersLogin = SuperUserTable::where('email', $validated['email'])->first();

        if ((isset($userslogin) && $userslogin->email !== $validated['email'])  || (isset($superUsersLogin) && $superUsersLogin->email !== $validated['email'])) {

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email address!',
            ]);
        } elseif ((isset($userslogin) && $userslogin->user_type !== $validated['user_type'])  || (isset($superUsersLogin) && $superUsersLogin->user_type !== $validated['user_type'])) {
            # code...
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect user type!',
            ]);
        } elseif ((isset($superUsersLogin) && !Hash::check($validated['password'], $superUsersLogin->password)) ||
            (isset($userslogin) && !Hash::check($validated['password'], $userslogin->password))
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect password!',
            ]);
        }

        // Generate OTP and store it in the session
        $otp = random_int(100000, 999999);

        $userEmail = isset($userslogin) ? $userslogin->email : (isset($superUsersLogin) ? $superUsersLogin->email : null);
        $userType = isset($userslogin) ? $userslogin->user_type : (isset($superUsersLogin) ? $superUsersLogin->email : null);
        $empId = isset($userslogin) ? $userslogin->employee_id  : (isset($superUsersLogin) ? $superUsersLogin->employee_id : null);
        Session::put('user_email', $userEmail);
        Session::put('user_type', $userType);
        Session::put('employee_id', $empId);
        Session::put('otp', $otp);
        Session::put('otp_email', $validated['email']);
        Session::put('otp_sent_time', now());

        try {
            Mail::to($validated['email'])->send(new OtpMail($otp));

            return response()->json([
                'status' => 'success',
                'message' => 'OTP has been sent to your email!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP email. Please try again later.',
                'debug' => env('APP_DEBUG') ? $e->getMessage() : null,
            ]);
        }
    }

    // OTP Verification and User Login
    public function loginUserverifyOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|integer',
        ]);

        // Retrieve the OTP and email from the session
        $otpSession = Session::get('otp');
        $otpEmail = Session::get('otp_email');
        $otpSentTime = Session::get('otp_sent_time');

        // Check if OTP is expired (e.g., valid for 10 minutes)
        if ($otpSentTime && now()->diffInMinutes($otpSentTime) > 10) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP has expired. Please request a new one.',
            ]);
        }

        // Validate the OTP and email
        if ($validated['otp'] == $otpSession && $validated['email'] == $otpEmail) {

            // Find the user based on email
            $user = SuperAddUser::where('email', $validated['email'])->first();
            $superUser = SuperUserTable::where('email', $validated['email'])->first();
            if ($user || $superUser) {
                $userEmail = isset($user) ? $user->email : $superUser->email;
                $userType = isset($user) ? $user->user_type : $superUser->user_type;

                // Store user data in session
                // Session::put('user_id', $user->id);
                Session::put('user_email', $userEmail);
                Session::put('user_type', $userType);
                if ($userType == 'Super User') {
                    $redirectRoute = match ($userType) {
                        'Super User' => route('super-admin-view'),
                        'Super User' => route('appraisal-view'),
                        'Super User' => route('logged-Out'),
                        default => route('all-user-login'),
                    };
                } else {
                    // Redirect based on user type
                    $redirectRoute = match ($userType) {
                        'admin' => route('admin-dashboard'),
                        'hr' => route('hr-dashboard'),
                        'users' => route('users-dashboard'),
                        'manager' => route('manager-dashboard'),
                        'client' => route('client-dashboard'),
                        default => route('login'),
                    };
                }


                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP verified successfully!',
                    'redirect' => $redirectRoute
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid OTP. Please try again.',
        ]);
    }


    public function userLogOut(Request $request)
    {

        Session::flush();
        $request->session()->invalidate();

        // Regenerate the CSRF token to prevent reusing the previous token after logout
        session()->regenerateToken();

        // Redirect to login page
        return redirect('/')->with('logout_reload', true);
    }

    //All Users view dashboard

    public function admin()
    {
        return view('delostyleUsers/admin-dashboard');
    }

    public function adminReviewSection()
    {

        return view('delostyleUsers/admin-review-section');
    }

    public function clientReviewSection()
    {

        return view('delostyleUsers.client-review-section');
    }

    //Admin, Hr, Manager,
    public function searchUser(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = SuperAddUser::query();

        // Exclude users with designation or user_type = 'client'
        $query->where('designation', '!=', 'client')
            ->where('user_type', '!=', 'client');

        // Search by employee ID or full name
        $query->where(function ($q) use ($keyword) {
            $q->where('employee_id', 'like', "%{$keyword}%")
                ->orWhereRaw("CONCAT(fname, ' ', lname) LIKE ?", ["%{$keyword}%"]);
        });


        $users = $query->get();

        if ($users->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No users found!'
            ]);
        }
    }


    //Client Search
    public function clientSearch(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = SuperAddUser::query();

        // Filter users that are of type 'client'
        // $query->whereJsonContains('user_roles', 'client');

        $query->where('user_roles', 'like', '%"client"%');

        // Search by employee ID or full name
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('employee_id', 'like', "%{$keyword}%")
                    ->orWhereRaw("CONCAT(fname, ' ', lname) LIKE ?", ["%{$keyword}%"]);
            });
        }

        $users = $query->get();

        if ($users->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No users found!'
            ]);
        }
    }



















    public function adminReviewStore(Request $request)
    {
        $emp_id = $request->input('emp_id');
        $financial_year = $request->input('financial_year');

        // 1. Check if employee exists
        $employee = SuperAddUser::where('employee_id', $emp_id)->first();
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee record not found!'
            ], 404);
        }

        // 2. Check probation period
        if ($employee->probation_date && now()->lt(Carbon::parse($employee->probation_date))) {
            return response()->json([
                'success' => false,
                'message' => 'Your review cannot be submitted. Employee is still under probation period.'
            ], 403);
        }

        // 3. Check evaluation exists for the given emp_id and financial_year
        $evaluation = EvaluationTable::where('emp_id', $emp_id)
            ->where('financial_year', $financial_year)
            ->first();

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => "Cannot submit review. Evaluation submission is pending for employee ID: $emp_id for financial year: $financial_year"
            ], 400);
        }

        // 4. Check if admin review already exists for the same emp_id and financial_year
        $reviewExists = AdminReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financial_year)
            ->exists();

        if ($reviewExists) {
            return response()->json([
                'success' => false,
                'message' => 'You already submitted a review for this employee for the selected financial year.'
            ], 409);
        }

        // 5.Clecking the Finalcial Year with SuperAddUser financil column 
        if ($employee->financial_year !== $financial_year) {
            return response()->json([
                'success' => false,
                'message' => 'This is not the current financial year. Try with the correct financial year.'
            ], 400);
        }

        $request->validate([
            // 'emp_id' => 'required|string',
            // 'demonstrated_attendance' => 'required|string',
            // 'comments_demonstrated_attendance' => 'required|string|max:255',
            // 'employee_manage_shift' => 'required|string',
            // 'comments_employee_manage_shift' => 'required|string|max:255',
            // 'documentation_neatness' => 'required|string',
            // 'comments_documentation_neatness' => 'required|string|max:255',
            // 'followed_instructions' => 'required|string',
            // 'comments_followed_instructions' => 'required|string|max:255',
            // 'productive' => 'required|string',
            // 'comments_productive' => 'required|string|max:255',
            // 'changes_schedules' => 'required|string',
            // 'comments_changes_schedules' => 'required|string|max:255',
            // 'leave_policy' => 'required|string',
            // 'comments_leave_policy' => 'required|string|max:255',
            // 'salary_deduction' => 'required|string',
            // 'comments_salary_deduction' => 'required|string|max:255',
            // 'interact_housekeeping' => 'required|string',
            // 'comments_interact_housekeeping' => 'required|string|max:255',
            // 'AdminTotalReview' => 'required|numeric',
            'financial_year' => [
                'required',
                Rule::unique('admin_review_tables', 'financial_year')->where(function ($query) use ($request) {
                    return $query->where('emp_id', $request->input('emp_id'));
                }),
            ],
        ], [
            'financial_year.unique' => 'You already submitted for this financial year.',
        ]);


        $data = $request->only([
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
        ]);

        AdminReviewTable::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully!'
        ]);
    }

    public function hr()
    {
        return view('delostyleUsers/hr-dashboard');
    }

    public function hrReviewSection()
    {
        return view('delostyleUsers/hr-review-section');
    }




    public function hrReviewStore(Request $request)
    {
        $emp_id = $request->input('emp_id');
        $financial_year = $request->input('financial_year');

        // 1. Check if employee exists
        $employee = SuperAddUser::where('employee_id', $emp_id)->first();
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee record not found!'
            ], 404);
        }

        // 2. Check probation period
        if ($employee->probation_date && now()->lt(Carbon::parse($employee->probation_date))) {
            return response()->json([
                'success' => false,
                'message' => 'Your review cannot be submitted. Employee is still under probation period.'
            ], 403);
        }

        // 3. Check financial year match with employee
        if ($employee->financial_year !== $financial_year) {
            return response()->json([
                'success' => false,
                'message' => 'This is not the current financial year. Try with the correct financial year.'
            ], 400);
        }

        // 4. Check evaluation exists for this employee and financial year
        $evaluation = evaluationTable::where('emp_id', $emp_id)
            ->where('financial_year', $financial_year)
            ->first();

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => "Cannot submit review. Evaluation has to be submitted first for: $emp_id for financial year: $financial_year"
            ], 400);
        }

        // 5. Check if review already exists for this emp_id + financial_year
        $reviewExists = HrReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financial_year)
            ->exists();

        if ($reviewExists) {
            return response()->json([
                'success' => false,
                'message' => 'You already submitted a review for this employee for the selected financial year.'
            ], 409); // 409 Conflict
        }

        // 6. Validation
        $request->validate([
            // 'emp_id' => 'required|string',
            // 'adherence_hr' => 'required|string',
            // 'comments_adherence_hr' => 'required|string|max:255',
            // 'professionalism_positive' => 'required|string',
            // 'comments_professionalism' => 'required|string|max:255',
            // 'respond_feedback' => 'required|string',
            // 'comments_respond_feedback' => 'required|string|max:255',
            // 'initiative' => 'required|string',
            // 'comments_initiative' => 'required|string|max:255',
            // 'interest_learning' => 'required|string',
            // 'comments_interest_learning' => 'required|string|max:255',
            // 'company_leave_policy' => 'required|string',
            // 'comments_company_leave_policy' => 'required|string|max:255',
            // 'HrTotalReview' => 'required|string',
            'financial_year' => [
                'required',
                Rule::unique('hr_review_tables', 'financial_year')->where(function ($query) use ($request) {
                    return $query->where('emp_id', $request->input('emp_id'));
                }),
            ],
        ], [
            'financial_year.unique' => 'You already submitted for this financial year.',

        ]);

        // 7. Save the review
        $data = $request->only([
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
        ]);

        HrReviewTable::create($data);


        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully!'
        ]);
    }


    public function user()
    {
        $employee = SuperAddUser::all();
        return view('delostyleUsers/users-dashboard', ['employee' => $employee]);
    }

    public function manager()
    {
        return view('delostyleUsers/manager-dashboard');
    }

    public function managerReviewSection()
    {

        return view('delostyleUsers/manager-review-section');
    }




    public function managerReviewStore(Request $request)
    {
        $emp_id = $request->input('emp_id');
        $financial_year = $request->input('financial_year');

        // 1. Check if employee exists
        $employee = SuperAddUser::where('employee_id', $emp_id)->first();
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee record not found!'
            ], 404);
        }

        // 2. Check probation period
        if ($employee->probation_date && now()->lt(Carbon::parse($employee->probation_date))) {
            return response()->json([
                'success' => false,
                'message' => 'Your review cannot be submitted. Employee is still under probation period.'
            ], 403);
        }

        // 3. Check evaluation for this emp_id and financial_year
        $evaluation = EvaluationTable::where('emp_id', $emp_id)
            ->where('financial_year', $financial_year)
            ->first();

        // 4. Check if a review already exists for the same emp_id and financial_year
        $reviewExists = ManagerReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financial_year)
            ->exists();

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => "Cannot submit review. Evaluation submission is pending for employee ID: $emp_id for financial year: $financial_year"
            ], 400);
        }

        if ($reviewExists) {
            return response()->json([
                'success' => false,
                'message' => 'You already submitted a review for this employee for the selected financial year.'
            ], 409);
        }
        // 5.Clecking the Finalcial Year with SuperAddUser financil column 
        if ($employee->financial_year !== $financial_year) {
            return response()->json([
                'success' => false,
                'message' => 'This is not the current financial year. Try with the correct financial year.'
            ], 400);
        }


        $request->validate([
            // 'emp_id' => 'required|string',
            // 'rate_employee_quality' => 'required|string',
            // 'comments_rate_employee_quality' => 'required|string|max:255',
            // 'organizational_goals' => 'required|string',
            // 'comments_organizational_goals' => 'required|string|max:255',
            // 'collaborate_colleagues' => 'required|string',
            // 'comments_collaborate_colleagues' => 'required|string|max:255',
            // 'demonstrated' => 'required|string',
            // 'comments_demonstrated' => 'required|string|max:255',
            // 'leadership_responsibilities' => 'required|string',
            // 'comments_leadership_responsibilities' => 'required|string|max:255',
            // 'thinking_contribution' => 'required|string',
            // 'comments_thinking_contribution' => 'required|string|max:255',
            // 'informed_progress' => 'required|string',
            // 'comments_comments_informed_progress' => 'required|string|max:255',
            // 'ManagerTotalReview' => 'required|numeric|max:200',
            'financial_year' => [
                'required',
                Rule::unique('manager_review_tables', 'financial_year')->where(function ($query) use ($request) {
                    return $query->where('emp_id', $request->input('emp_id'));
                }),
            ],
        ], [
            'financial_year.unique' => 'You already submitted for this financial year.',
        ]);

        // 6. Store review
        $data = $request->only([
            'emp_id',
            'rate_employee_quality',
            'comments_rate_employee_quality',
            'organizational_goals',
            'comments_organizational_goals',
            'collaborate_colleagues',
            'comments_collaborate_colleagues',
            'demonstrated',
            'comments_demonstrated',
            'leadership_responsibilities',
            'comments_leadership_responsibilities',
            'thinking_contribution',
            'comments_thinking_contribution',
            'informed_progress',
            'comments_comments_informed_progress',
            'ManagerTotalReview',
            'financial_year'
        ]);

        ManagerReviewTable::create($data);

        return response()->json(['message' => 'Review submitted successfully!']);
    }

    public function client()
    {
        return view('delostyleUsers/client-dashboard');
    }



    public function viewClientDashBoard()
    {

        return view('delostyleUsers.client-dashboard');
    }

    // client data store
    public function clientReviewStore(Request $request)
    {
        $emp_id = $request->input('emp_id');
        $financial_year = $request->input('financial_year');

        // 1. Check if employee exists
        $employee = SuperAddUser::where('employee_id', $emp_id)->first();
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee record not found!'
            ], 404);
        }

        // 2. Check probation period
        if ($employee->probation_date && now()->lt(Carbon::parse($employee->probation_date))) {
            return response()->json([
                'success' => false,
                'message' => 'Your review cannot be submitted. Employee is still under probation period.'
            ], 403);
        }

        // 3. Financial year must match employee's record
        if ($employee->financial_year !== $financial_year) {
            return response()->json([
                'success' => false,
                'message' => 'This is not the current financial year. Try with the correct financial year.'
            ], 400);
        }

        // 4. Check if evaluation exists for same emp_id and financial_year
        $evaluation = evaluationTable::where('emp_id', $emp_id)
            ->where('financial_year', $financial_year)
            ->first();

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => "Cannot submit review. Evaluation must be submitted first for: $emp_id for financial year: $financial_year"
            ], 400);
        }

        // 5. Check if client review already exists for this emp_id and financial_year
        $reviewExists = ClientReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financial_year)
            ->exists();

        if ($reviewExists) {
            return response()->json([
                'success' => false,
                'message' => 'You already submitted a review for this employee for the selected financial year.'
            ], 409); // Conflict
        }

        try {
            // 6. Validate the request
            $validatedData = $request->validate([
                'emp_id' => 'required|string|max:255',
                'financial_year' => [
                    'required',
                    Rule::unique('client_review_tables', 'financial_year')->where(function ($query) use ($request) {
                        return $query->where('emp_id', $request->input('emp_id'));
                    }),
                ],
                'understand_requirements' => 'nullable|string|max:20',
                'comment_understand_requirements' => 'nullable|string|max:255',
                'business_needs' => 'nullable|string|max:20',
                'comments_business_needs' => 'nullable|string|max:255',
                'detailed_project_scope' => 'nullable|string|max:20',
                'comments_detailed_project_scope' => 'nullable|string|max:255',
                'responsive_reach_project' => 'nullable|string|max:20',
                'comments_responsive_reach_project' => 'nullable|string|max:255',
                'comfortable_discussing' => 'nullable|string|max:20',
                'comments_comfortable_discussing' => 'nullable|string|max:255',
                'regular_updates' => 'nullable|string|max:20',
                'comments_regular_updates' => 'nullable|string|max:255',
                'concerns_addressed' => 'nullable|string|max:20',
                'comments_concerns_addressed' => 'nullable|string|max:255',
                'technical_expertise' => 'nullable|string|max:20',
                'comments_technical_expertise' => 'nullable|string|max:255',
                'best_practices' => 'nullable|string|max:20',
                'comments_best_practices' => 'nullable|string|max:255',
                'suggest_innovative' => 'nullable|string|max:20',
                'comments_suggest_innovative' => 'nullable|string|max:255',
                'quality_code' => 'nullable|string|max:20',
                'comments_quality_code' => 'nullable|string|max:255',
                'encounter_issues' => 'nullable|string|max:20',
                'comments_encounter_issues' => 'nullable|string|max:255',
                'code_scalable' => 'nullable|string|max:20',
                'comments_code_scalable' => 'nullable|string|max:255',
                'solution_perform' => 'nullable|string|max:20',
                'comments_solution_perform' => 'nullable|string|max:255',
                'project_delivered' => 'nullable|string|max:20',
                'comments_project_delivered' => 'nullable|string|max:255',
                'communicated_handled' => 'nullable|string|max:20',
                'comments_communicated_handled' => 'nullable|string|max:255',
                'development_process' => 'nullable|string|max:20',
                'comments_development_process' => 'nullable|string|max:255',
                'unexpected_challenges' => 'nullable|string|max:20',
                'comments_unexpected_challenges' => 'nullable|string|max:255',
                'effective_workarounds' => 'nullable|string|max:20',
                'comments_effective_workarounds' => 'nullable|string|max:255',
                'bugs_issues' => 'nullable|string|max:20',
                'comments_bugs_issues' => 'nullable|string|max:255',
                'ClientTotalReview' => 'required|numeric|max:200'
            ], [
                'financial_year.unique' => 'You already submitted a review for this financial year.'
            ]);

            // 7. Role check
            $roles = json_decode($employee->user_roles, true);
            if (is_array($roles) && in_array('client', $roles)) {
                ClientReviewTable::create($validatedData);
                return response()->json(['message' => 'Review submitted successfully!'], 200);
            }

            return response()->json(['error' => 'You are not authorized to submit this review.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong! Check logs.'], 500);
        }
    }




    public function reviewUserReport($emp_id)
    {
        // Fetch review data
        $userData = [
            'superadduser' => DB::table('super_add_users')->where('employee_id', $emp_id)->first(),
            'managerReview' => DB::table('manager_review_tables')->where('emp_id', $emp_id)->first(),
            'adminReview' => DB::table('admin_review_tables')->where('emp_id', $emp_id)->first(),
            'hrReview' => DB::table('hr_review_tables')->where('emp_id', $emp_id)->first(),
            'clientReview' => DB::table('client_review_tables')->where('emp_id', $emp_id)->first(),
            'evaluation' => DB::table('evaluation_tables')->where('emp_id', $emp_id)->first(),
        ];

        // Debugging: Check if $userData is being retrieved
        if (collect($userData)->filter()->isEmpty()) {
            return redirect()->back()->with('error', 'No review data found for this employee.');
        }

        return view('delostyleUsers.user-review-report', compact('userData', 'emp_id'));
    }



    //View Reports
    public function evaluationDetails(Request $request, $emp_id)
    {
        $financialYear = $request->get('financial_year');

        $user = evaluationTable::where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->firstOrFail();
        return view('reports.evaluationReport', compact('user'));
    }



    public function managerReport(Request $request, $emp_id)
    {
        $financialYear = $request->get('financial_year');

        $user = ManagerReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->firstOrFail();
        return view('reports.managerReport', compact('user'));
    }

    public function adminReport(Request $request, $emp_id)
    {
        $financialYear = $request->get('financial_year');

        $user = AdminReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->firstOrFail();
        return view('reports.adminReport', compact('user'));
    }

    public function hrReport(Request $request, $emp_id)
    {

        $financialYear = $request->get('financial_year');

        $user = HrReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->firstOrFail();
        return view('reports.hrReport', compact('user'));
    }

    public function clientReport(Request $request, $emp_id)
    {

        $financialYear = $request->get('financial_year');

        $user = ClientReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->firstOrFail();
        return view('reports.clientReport', compact('user'));
    }

    public function loadReport(Request $request, $reportType, $emp_id)
    {
        // Check the report type and fetch the corresponding data
        $financialYear = $request->get('financial_year');
        switch ($reportType) {
            case 'evaluation':
                $user = evaluationTable::where('emp_id', $emp_id)
                    ->where('financial_year', $financialYear)->firstOrFail();
                return view('reports.evaluation', compact('user'));
            case 'managerReport':
                $user = ManagerReviewTable::where('emp_id', $emp_id)
                    ->where('financial_year', $financialYear)->firstOrFail();
                return view('reports.managerReport', compact('user'));
            case 'adminReport':
                $user = AdminReviewTable::where('emp_id', $emp_id)
                    ->where('financial_year', $financialYear)->firstOrFail();
                return view('reports.adminReport', compact('user'));
            case 'hrReport':
                $user = HrReviewTable::where('emp_id', $emp_id)
                    ->where('financial_year', $financialYear)->firstOrFail();
                return view('reports.hrReport', compact('user'));
            case 'clientReport':
                $user = ClientReviewTable::where('emp_id', $emp_id)
                    ->where('financial_year', $financialYear)->firstOrFail();
                return view('reports.clientReport', compact('user'));
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }
    }


    // public function getHrReviewsList(Request $request)
    // {
    //     $validEmployeeIds = HrReviewTable::pluck('emp_id')
    //         ->merge(evaluationTable::pluck('emp_id'))
    //         ->unique()
    //         ->toArray();


    //     $superAddUser = SuperAddUser::where('status', 1)
    //         ->whereIn('employee_id', $validEmployeeIds)
    //         ->get();


    //     $hrReviewTable = HrReviewTable::whereIn('emp_id', $validEmployeeIds)->get();
    //     $evaluation = evaluationTable::whereIn('emp_id', $validEmployeeIds)->get();

    //     return view('reports.hrReportView', compact('superAddUser', 'hrReviewTable', 'evaluation'));
    // }

    public function getHrReviewsList(Request $request)
    {
        // Step 1: Get all unique emp_ids from both tables
        $validEmployeeIds = HrReviewTable::pluck('emp_id')
            ->merge(evaluationTable::pluck('emp_id'))
            ->unique()
            ->toArray();

        // Step 2: Get active SuperAddUser records for these IDs
        $superAddUser = SuperAddUser::where('status', 1)
            ->whereIn('employee_id', $validEmployeeIds)
            ->get();

        // Step 3: Exclude employee_ids where user_type is 'admin'
        $nonAdminEmployeeIds = $superAddUser
            ->where('user_type', '!=', 'admin')  // Filter out admins
            ->pluck('employee_id')
            ->toArray();

        // Step 4: Get only those evaluations for non-admin users
        $hrReviewTable = HrReviewTable::whereIn('emp_id', $validEmployeeIds)->get();
        $evaluation = evaluationTable::whereIn('emp_id', $nonAdminEmployeeIds)->get();

        $superAddUser = $superAddUser->where('user_type', '!=', ['admin','manager'])->values();
        return view('reports.hrReportView', compact('superAddUser', 'hrReviewTable', 'evaluation'));
    }




    public function showDetailsHr($employee_id)
    {

        $financial_year = request()->query('financial_year');

        $employee = SuperAddUser::where('employee_id', $employee_id)->whereNotIn('user_type', ['hr', 'admin', 'client', 'manager'])->firstOrFail();

        $reviews = HrReviewTable::where('emp_id', $employee_id)
            ->when($financial_year, function ($query) use ($financial_year) {
                $query->where('financial_year', $financial_year);
            })
            ->get();

        if ($financial_year && $reviews->isEmpty()) {

            return response()->json(['message' => 'No data found for the selected financial year.']);
        }

        return view('reports.userDetailsHrView', compact('employee', 'reviews', 'employee_id', 'financial_year'));
    }


    public function showEvaluationDetails($employee_id)
    {
        $financial_year = request()->query('financial_year');

        $employee = SuperAddUser::where('employee_id', $employee_id)->whereNotIn('user_type', ['client', 'manager'])->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found');
        }

        $eval = evaluationTable::where('emp_id', $employee_id)
            ->when($financial_year, function ($query) use ($financial_year) {
                $query->where('financial_year', $financial_year);
            })
            ->get();

        if ($financial_year && $eval->isEmpty()) {
            return response()->json(['message' => 'No data found for the selected financial year.']);
        }

        return view('reports.userEvaluationDetails', compact('employee', 'eval', 'employee_id', 'financial_year'));
    }


    // public function getAdminReviewList(Request $request)
    // {
    //     $validEmployeeIds = AdminReviewTable::pluck('emp_id')
    //         ->merge(evaluationTable::pluck('emp_id'))
    //         ->unique()
    //         ->toArray();


    //     $superAddUser = SuperAddUser::where('status', 1)
    //         ->whereIn('employee_id', $validEmployeeIds)
    //         ->get();


    //         $nonAdminEmployeeIds = $superAddUser
    //         ->where('user_type', '!=', 'hr')  // Filter out admins
    //         ->pluck('employee_id')
    //         ->toArray();


    //     $adminReviewTable = AdminReviewTable::whereIn('emp_id', $validEmployeeIds)->get();
    //     // $evaluation = evaluationTable::whereIn('emp_id', $validEmployeeIds)->get();
    //     $evaluation = evaluationTable::whereIn('emp_id', $nonAdminEmployeeIds)->get();

    //     $superAddUser = $superAddUser->where('user_type', '!=', ['hr','manager'])->values();

    //     return view('reports.adminReportView', compact('superAddUser', 'adminReviewTable', 'evaluation'));
    // }


    public function getAdminReviewList(Request $request)
{
    // Step 1: Get all unique emp_ids from both tables
    $validEmployeeIds = AdminReviewTable::pluck('emp_id')
        ->merge(evaluationTable::pluck('emp_id'))
        ->unique()
        ->toArray();

    // Step 2: Get active SuperAddUser records for these IDs
    $superAddUser = SuperAddUser::where('status', 1)
        ->whereIn('employee_id', $validEmployeeIds)
        ->get();

    // Step 3: Exclude users with user_type 'hr' or 'manager'
    $nonHrManagerEmployeeIds = $superAddUser
        ->whereNotIn('user_type', ['hr', 'manager']) // Exclude hr and manager
        ->pluck('employee_id')
        ->toArray();

    // Step 4: Get only those evaluations for non-hr and non-manager users
    $adminReviewTable = AdminReviewTable::whereIn('emp_id', $validEmployeeIds)->get();
    $evaluation = evaluationTable::whereIn('emp_id', $nonHrManagerEmployeeIds)->get();

    // Step 5: Filter out HR and Manager users before sending to view
    $superAddUser = $superAddUser->whereNotIn('user_type', ['hr', 'manager'])->values();

    // Return view
    return view('reports.adminReportView', compact('superAddUser', 'adminReviewTable', 'evaluation'));
}






    public function showDetailsAdmin($employee_id)
    {
        $financial_year = request()->query('financial_year');

        $employee = SuperAddUser::where('employee_id', $employee_id)->whereNotIn('user_type', ['hr', 'admin', 'client', 'manager'])->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found']);
        }

        $reviews = AdminReviewTable::where('emp_id', $employee_id)
            ->when($financial_year, function ($query) use ($financial_year) {
                $query->where('financial_year', $financial_year);
            })
            ->get();

        if ($financial_year && $reviews->isEmpty()) {
            return response()->json(['message' => 'No data found for the selected financial year.']);
        }

        return view('reports.userDetailsAdminView', compact('employee', 'reviews', 'employee_id', 'financial_year'));
    }


    public function getManagerReviewList(Request $request)
    {
        $validEmployeeIds = ManagerReviewTable::pluck('emp_id')
            ->merge(evaluationTable::pluck('emp_id'))
            ->unique()
            ->toArray();

        $superAddUser = SuperAddUser::where('status', 1)
            ->whereIn('employee_id', $validEmployeeIds)
            ->get();


        $managerReviewTable = ManagerReviewTable::whereIn('emp_id', $validEmployeeIds)->get();


        $evaluation = evaluationTable::whereIn('emp_id', $validEmployeeIds)->get();

        return view('reports.managerReportView', compact('superAddUser', 'managerReviewTable', 'evaluation'));
    }


//     public function getManagerReviewList(Request $request)
// {
//     // Step 1: Get all unique emp_ids from both tables
//     $validEmployeeIds = ManagerReviewTable::pluck('emp_id')
//         ->merge(evaluationTable::pluck('emp_id'))
//         ->unique()
//         ->toArray();

//     // Step 2: Get active SuperAddUser records for these IDs
//     $superAddUser = SuperAddUser::where('status', 1)
//         ->whereIn('employee_id', $validEmployeeIds)
//         ->get();

//     // Step 3: Exclude users with user_type 'admin' or 'hr'
//     $nonAdminHrEmployeeIds = $superAddUser
//         ->whereNotIn('user_type', ['admin', 'hr']) // Filter out admin and hr
//         ->pluck('employee_id')
//         ->toArray();
//     // Step 4: Fetch only manager reviews and evaluations for the filtered users
//     $managerReviewTable = ManagerReviewTable::whereIn('emp_id', $nonAdminHrEmployeeIds)->get();
//     $evaluation = evaluationTable::whereIn('emp_id', $nonAdminHrEmployeeIds)->get();

//     // Step 5: Filter users to exclude admin and hr before sending to view
//     $superAddUser = $superAddUser->whereNotIn('user_type', ['admin', 'hr'])->values();

//     return view('reports.managerReportView', compact('superAddUser', 'managerReviewTable', 'evaluation'));
// }







    public function showDetailsManager($employee_id)
    {
        $financial_year = request()->query('financial_year');

        $employee = SuperAddUser::where('employee_id', $employee_id)->whereNotIn('user_type', ['hr', 'admin', 'client', 'manager'])->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found.']);
        }

        $reviews = ManagerReviewTable::where('emp_id', $employee_id)
            ->when($financial_year, function ($query) use ($financial_year) {
                $query->where('financial_year', $financial_year);
            })
            ->get();

        if ($financial_year && $reviews->isEmpty()) {
            return response()->json(['message' => 'No data found for the selected financial year.']);
        }

        return view('reports.userDetailsManagerView', compact('employee', 'reviews', 'employee_id', 'financial_year'));
    }





    public function getClientReviewList(Request $request)
    {

        $validEmployeeIds = ClientReviewTable::pluck('emp_id')
            ->unique()
            ->toArray();

        $superAddUser = SuperAddUser::where('status', 1)
            ->whereIn('employee_id', $validEmployeeIds)
            ->get();

        $clientReviewTable = ClientReviewTable::whereIn('emp_id', $validEmployeeIds)->get();

        return view('reports.clientReportView', compact('superAddUser', 'clientReviewTable'));
    }


    public function showDetailsClient($employee_id)
    {
        $financial_year = request()->query('financial_year');

        $employee = SuperAddUser::where('employee_id', $employee_id)->whereNotIn('user_type', ['hr', 'admin', 'client', 'manager'])->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found.']);
        }

        $reviews = ClientReviewTable::where('emp_id', $employee_id)
            ->when($financial_year, function ($query) use ($financial_year) {
                $query->where('financial_year', $financial_year);
            })
            ->get();

        if ($financial_year && $reviews->isEmpty()) {
            return response()->json(['message' => 'No data found for the selected financial year.']);
        }

        return view('reports.userDetailsClientView', compact('employee', 'reviews', 'employee_id', 'financial_year'));
    }






    //Handle User Review table in side User Review Report for Employee blade file
    public function getReviewScores(Request $request)
    {
        $empId = session('employee_id');
        $year = $request->query('financial_year');

        $financialData = FinancialData::where('emp_id', $empId)
            ->where('financial_year', $year)
            ->first();
        $evaluation = evaluationTable::where('emp_id', $empId)
            ->where('financial_year', $year)
            ->first();


        $user = SuperAddUser::where('employee_id', $empId)->first();
        $roles = json_decode($user?->user_roles ?? '[]', true);


        $showClient = in_array('client', $roles);

        //
        $response = [
            'admin' => $financialData?->admin_review,
            'hr' => $financialData?->hr_review,
            'manager' => $financialData?->manager_review,
            'total' => $evaluation?->total_scoring_system,
            'showClient' => $showClient,
        ];


        if ($showClient) {
            $response['client'] = $financialData?->client_review;
        }


        return response()->json($response);
    }
}
