<?php

namespace App\Http\Controllers\userController;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\AdminReviewTable;
use App\Models\AllClient;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;



class allUserController extends Controller
{
    //

    public function indexUserLogin()
    {
        $superUser = null;
        //  $output1 = Artisan::output();

        return view("loginusers/userlogin", compact('superUser'));
    }


    // public function loginUserAutenticacaon(Request $request)
    // {
    //     $validated = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:4',
    //         'user_type' => 'required|string',
    //     ]);

    //     // Check each user type
    //     $user = SuperAddUser::where('email', $validated['email'])->first();
    //     if (!$user) {
    //         $user = SuperUserTable::where('email', $validated['email'])->first();
    //     }
    //     if (!$user) {
    //         $user = AllClient::where('client_email', $validated['email'])->first();
    //     }

    //     // if($user instanceof SuperUserTable && $user -> status == 0){
    //     //     return response()->json([
    //     //         'status' => 'error',
    //     //         'message' => 'Your account is inactive. Please contact support.'
    //     //     ]);
    //     // }

    //     if ($user instanceof AllClient && $user->status == 0) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Your account is inactive. Please contact support.',
    //         ]);
    //     }

    //     if (!$user) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Invalid email address!',
    //         ]);
    //     }

    //     // Normalize email check for AllClient
    //     $userEmail = $user instanceof AllClient ? $user->client_email : $user->email;

    //     if ($user->user_type !== $validated['user_type']) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Incorrect user type!',
    //         ]);
    //     }

    //     if (!Hash::check($validated['password'], $user->password)) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Incorrect password!',
    //         ]);
    //     }

    //     $otp = random_int(100000, 999999);

    //     Session::put('user_email', $userEmail);
    //     Session::put('user_type', $user->user_type);
    //     Session::put('employee_id', $user->employee_id ?? null);
    //     Session::put('otp', $otp);
    //     Session::put('otp_email', $userEmail);
    //     Session::put('otp_sent_time', now());

    //     // If it's a client, store client_id
    //     if ($user instanceof AllClient) {
    //         Session::put('client_id', $user->id);
    //     }

    //     try {
    //         Mail::to($userEmail)->send(new OtpMail($otp));

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'OTP has been sent to your email!',
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Failed to send OTP email. Please try again later.',
    //             'debug' => env('APP_DEBUG') ? $e->getMessage() : null,
    //         ]);
    //     }
    // }




    
public function loginUserAutenticacaon(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:4',
            'user_type' => 'required|string',
        ]);
 
        $user = SuperAddUser::where('email', $validated['email'])->first();
 
        if (!$user) {
            $user = SuperUserTable::where('email', $validated['email'])->first();
        }
 
        if (!$user) {
            $user = AllClient::where('client_email', $validated['email'])->first();
        }
 
        // ✅ Status Check for SuperAddUser
        if ($user instanceof SuperAddUser && $user->status == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is inactive. Please contact support.',
            ]);
        }
 
        // ✅ Status Check for AllClient
        if ($user instanceof AllClient && $user->status == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is inactive. Please contact support.',
            ]);
        }
 
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email address!',
            ]);
        }
 
        $userEmail = $user instanceof AllClient ? $user->client_email : $user->email;
 
        if ($user->user_type !== $validated['user_type']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect user type!',
            ]);
        }
 
        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect password!',
            ]);
        }
 
        $otp = random_int(100000, 999999);
 
        Session::put('user_email', $userEmail);
        Session::put('user_type', $user->user_type);
        Session::put('employee_id', $user->employee_id ?? null);
        Session::put('otp', $otp);
        Session::put('otp_email', $userEmail);
        Session::put('otp_sent_time', now());
 
        if ($user instanceof AllClient) {
            Session::put('client_id', $user->id);
        }
 
        try {
            Mail::to($userEmail)->send(new OtpMail($otp));
 
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



    public function loginUserverifyOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|integer',
        ]);

        // Retrieve OTP info from session
        $otpSession = Session::get('otp');
        $otpEmail = Session::get('otp_email');
        $otpSentTime = Session::get('otp_sent_time');

        if ($otpSentTime && now()->diffInMinutes($otpSentTime) > 10) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP has expired. Please request a new one.',
            ]);
        }

        if ($validated['otp'] == $otpSession && $validated['email'] == $otpEmail) {

            // Try fetching user from all tables
            $user = SuperAddUser::where('email', $validated['email'])->first();
            $superUser = SuperUserTable::where('email', $validated['email'])->first();
            $client = AllClient::where('client_email', $validated['email'])->first();

            if ($user) {
                // For SuperAddUser
                Session::put('user_email', $user->email);
                Session::put('user_type', $user->user_type);
                Session::put('user_id', $user->id);

                // Define redirects based on user_type
                $redirectRoute = match ($user->user_type) {
                    'Super User' => route('super-admin-view'),
                    'admin' => route('admin-dashboard'),
                    'hr' => route('hr-dashboard'),
                    'users' => route('users-dashboard'),
                    'manager' => route('manager-dashboard'),
                    default => route('all-user-login'),
                };
            } elseif ($superUser) {
                // For SuperUserTable
                Session::put('user_email', $superUser->email);
                Session::put('user_type', $superUser->user_type);
                Session::put('user_id', $superUser->id);

                $redirectRoute = match ($superUser->user_type) {
                    'Super User' => route('super-admin-view'),
                    'admin' => route('admin-dashboard'),
                    'hr' => route('hr-dashboard'),
                    'users' => route('users-dashboard'),
                    'manager' => route('manager-dashboard'),
                    default => route('all-user-login'),
                };
            } elseif ($client) {
                // For AllClient
                Session::put('user_email', $client->client_email);
                Session::put('user_type', $client->user_type);
                Session::put('client_id', $client->id);  // Store client id

                $redirectRoute = match ($client->user_type) {
                    'client' => route('client-dashboard'),
                    default => route('login'),
                };
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                ]);
            }


            // *** RUN YOUR COMMANDS HERE AFTER SUCCESSFUL LOGIN ***
            $outPut1 = Artisan::call('apply:probation-appraisal');
            $outPut2 = Artisan::call('employee:update-status');
            $outPut3 = Artisan::call('email:send-anniversaries');

            Log::info('Command outputs:', [
                'apply:probation-appraisal' => Artisan::output(),
                'employee:update-status' => Artisan::output(),
                'email:send-anniversaries' => Artisan::output(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully!',
                'redirect' => $redirectRoute
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

        // Get current user's type from session
        $currentUserType = session('user_type');

        if (!$currentUserType) {
            return response()->json([
                'success' => false,
                'message' => 'User type not found in session!'
            ]);
        }

        $query = SuperAddUser::query();

        // Role-based logic
        if ($currentUserType === 'hr') {
            // HR can search Admin and Manager
            $query->whereIn('user_type', ['admin', 'manager', 'users']);
        } elseif ($currentUserType === 'admin') {
            // Admin can search HR and Manager
            $query->whereIn('user_type', ['hr', 'manager', 'users']);
        } else {
            // Other roles: exclude hr, admin, manager
            $query->whereNotIn('user_type', ['admin', 'hr', 'manager']);
        }



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
            'emp_id' => 'required|string',
            'demonstrated_attendance' => 'required|numeric',
            'comments_demonstrated_attendance' => 'max:255',
            'employee_manage_shift' => 'required|numeric',
            'comments_employee_manage_shift' => 'max:255',
            'documentation_neatness' => 'required|numeric',
            'comments_documentation_neatness' => 'max:255',
            'followed_instructions' => 'required|numeric',
            'comments_followed_instructions' => 'max:255',
            'productive' => 'required|numeric',
            'comments_productive' => 'max:255',
            'changes_schedules' => 'required|numeric',
            'comments_changes_schedules' => 'max:255',
            'leave_policy' => 'required|numeric',
            'comments_leave_policy' => 'max:255',
            'salary_deduction' => 'required|numeric',
            'comments_salary_deduction' => 'max:255',
            'interact_housekeeping' => 'required|numeric',
            'comments_interact_housekeeping' => 'max:255',
            'AdminTotalReview' => 'numeric',
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
            'emp_id' => 'required|string',
            'adherence_hr' => 'required|numeric',
            'comments_adherence_hr' => 'max:255',
            'professionalism_positive' => 'required|numeric',
            'comments_professionalism' => 'max:255',
            'respond_feedback' => 'required|numeric',
            'comments_respond_feedback' => 'max:255',
            'initiative' => 'required|numeric',
            'comments_initiative' => 'max:255',
            'interest_learning' => 'required|numeric',
            'comments_interest_learning' => 'max:255',
            'company_leave_policy' => 'required|numeric',
            'comments_company_leave_policy' => 'max:255',
            'HrTotalReview' => 'numeric',
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
        $evaluation = evaluationTable::where('emp_id', $emp_id)
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
            'emp_id' => 'required|string',
            'rate_employee_quality' => 'required|numeric',
            'comments_rate_employee_quality' => 'max:255',
            'organizational_goals' => 'required|numeric',
            'comments_organizational_goals' => 'max:255',
            'collaborate_colleagues' => 'required|numeric',
            'comments_collaborate_colleagues' => 'max:255',
            'demonstrated' => 'required|numeric',
            'comments_demonstrated' => 'max:255',
            'leadership_responsibilities' => 'required|numeric',
            'comments_leadership_responsibilities' => 'max:255',
            'thinking_contribution' => 'required|numeric',
            'comments_thinking_contribution' => 'max:255',
            'informed_progress' => 'required|numeric',
            'comments_comments_informed_progress' => 'max:255',
            'ManagerTotalReview' => 'numeric|max:200',
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

        // 4. Check if evaluation exists
        $evaluation = evaluationTable::where('emp_id', $emp_id)
            ->where('financial_year', $financial_year)
            ->first();

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => "Cannot submit review. Evaluation must be submitted first for: $emp_id for financial year: $financial_year"
            ], 400);
        }

        $client_id = Session::get('client_id');

        // 5. Check for existing review
        $reviewExists = ClientReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financial_year)
            ->where('client_id', $client_id)
            ->exists();

        if ($reviewExists) {
            return response()->json([
                'success' => false,
                'message' => 'You already submitted a review for this employee for the selected financial year.'
            ], 409);
        }

        try {
            // 6. Validate the request
            $validatedData = $request->validate([
                'emp_id' => 'required|string',
                'financial_year' => [
                    'required',
                    Rule::unique('client_review_tables', 'financial_year')->where(function ($query) use ($request, $client_id) {
                        return $query->where('emp_id', $request->input('emp_id'))
                            ->where('client_id', $client_id);
                    }),
                ],

                // Rating fields
                'understand_requirements' => 'numeric|max:20',
                'business_needs' => 'numeric|max:20',
                'detailed_project_scope' => 'numeric|max:20',
                'responsive_reach_project' => 'numeric|max:20',
                'comfortable_discussing' => 'numeric|max:20',
                'regular_updates' => 'numeric|max:20',
                'concerns_addressed' => 'numeric|max:20',
                'technical_expertise' => 'numeric|max:20',
                'best_practices' => 'numeric|max:20',
                'suggest_innovative' => 'numeric|max:20',
                'quality_code' => 'numeric|max:20',
                'encounter_issues' => 'numeric|max:20',
                'code_scalable' => 'numeric|max:20',
                'solution_perform' => 'numeric|max:20',
                'project_delivered' => 'numeric|max:20',
                'communicated_handled' => 'numeric|max:20',
                'development_process' => 'numeric|max:20',
                'unexpected_challenges' => 'numeric|max:20',
                'effective_workarounds' => 'numeric|max:20',
                'bugs_issues' => 'numeric|max:20',
                'ClientTotalReview' => 'numeric|max:200',

                // Comment fields (nullable + max 255)
                'comment_understand_requirements' => 'nullable|string|max:255',
                'comments_business_needs' => 'nullable|string|max:255',
                'comments_detailed_project_scope' => 'nullable|string|max:255',
                'comments_responsive_reach_project' => 'nullable|string|max:255',
                'comments_comfortable_discussing' => 'nullable|string|max:255',
                'comments_regular_updates' => 'nullable|string|max:255',
                'comments_concerns_addressed' => 'nullable|string|max:255',
                'comments_technical_expertise' => 'nullable|string|max:255',
                'comments_best_practices' => 'nullable|string|max:255',
                'comments_suggest_innovative' => 'nullable|string|max:255',
                'comments_quality_code' => 'nullable|string|max:255',
                'comments_encounter_issues' => 'nullable|string|max:255',
                'comments_code_scalable' => 'nullable|string|max:255',
                'comments_solution_perform' => 'nullable|string|max:255',
                'comments_project_delivered' => 'nullable|string|max:255',
                'comments_communicated_handled' => 'nullable|string|max:255',
                'comments_development_process' => 'nullable|string|max:255',
                'comments_unexpected_challenges' => 'nullable|string|max:255',
                'comments_effective_workarounds' => 'nullable|string|max:255',
                'comments_bugs_issues' => 'nullable|string|max:255',
            ], [
                'financial_year.unique' => 'You already submitted a review for this financial year.'
            ]);

            // 7. Role check
            $roles = json_decode($employee->user_roles, true);
            if (is_array($roles) && in_array('client', $roles)) {
                // Add client_id from session to the validated data
                $validatedData['client_id'] = Session::get('client_id');
                ClientReviewTable::create($validatedData);
                return response()->json(['message' => 'Review submitted successfully!'], 200);
            }

            return response()->json(['error' => 'You are not authorized to submit this review.'], 403);
        } catch (\Exception $e) {
            Log::error('❌ Client review submission failed: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong! Check logs.'], 500);
        }
    }



    public function reviewUserReport($emp_id)
    {

        // Fetch client reviews with client names
        $clientReviews = DB::table('client_review_tables')
            ->join('all_clients', 'client_review_tables.client_id', '=', 'all_clients.id')
            ->where('client_review_tables.emp_id', $emp_id)
            ->select('client_review_tables.*', 'all_clients.client_name')
            ->get();


        // Fetch review data
        $userData = [
            'superadduser' => DB::table('super_add_users')->where('employee_id', $emp_id)->first(),
            'managerReview' => DB::table('manager_review_tables')->where('emp_id', $emp_id)->first(),
            'adminReview' => DB::table('admin_review_tables')->where('emp_id', $emp_id)->first(),
            'hrReview' => DB::table('hr_review_tables')->where('emp_id', $emp_id)->first(),
            'clientReview' => DB::table('client_review_tables')->where('emp_id', $emp_id)->first(),
            'evaluation' => DB::table('evaluation_tables')->where('emp_id', $emp_id)->first(),
        ];

        $user_roles = json_decode(optional($userData['superadduser'])->user_roles ?? '[]', true);

        // Check if user_roles are empty
        if (!array_filter($user_roles)) {
            return redirect()->back()->with('error', 'No review data found for this employee.');
        }


        // Debugging: Check if $userData is being retrieved
        if (collect($userData)->filter()->isEmpty()) {
            return redirect()->back()->with('error', 'No review data found for this employee.');
        }

        return view('delostyleUsers.user-review-report', compact('userData', 'emp_id', 'clientReviews', 'user_roles'));
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
        $clientId = $request->get('client_id');

        // $user = ClientReviewTable::where('emp_id', $emp_id)
        //     ->where('financial_year', $financialYear)
        //     ->firstOrFail();
        $user = ClientReviewTable::with('client') // <-- Eager load the client relationship
            ->where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->where('client_id', $clientId)
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


    public function getHrReviewsList(Request $request)
    {
        // Step 1: Get all unique emp_ids from both tables
        $validEmployeeIds = HrReviewTable::pluck('emp_id',)
            ->merge(evaluationTable::pluck('emp_id'))
            ->unique()
            ->toArray();

        // Step 2: Get active SuperAddUser records for these IDs
        $superAddUser = SuperAddUser::where('status', 1)
            ->whereIn('employee_id', $validEmployeeIds)
            ->get();

        // Step 3: Exclude employee_ids where user_type is 'admin'
        $nonAdminEmployeeIds = $superAddUser
            ->whereNotIn('user_type',  ['manager'])  // Filter out admins
            ->pluck('employee_id')
            ->toArray();

        // Step 4: Get only those evaluations for non-admin users
        $hrReviewTable = HrReviewTable::whereIn('emp_id', $validEmployeeIds)->get();
        $evaluation = evaluationTable::whereIn('emp_id', $nonAdminEmployeeIds)->get();
        // dd($evaluation);

        // $superAddUser = $superAddUser->where('user_type', '!=', ['admin', 'manager'])->values();
        return view('reports.hrReportView', compact('superAddUser', 'hrReviewTable', 'evaluation'));
    }




    public function showDetailsHr($employee_id)
    {

        $financial_year = request()->query('financial_year');

        $employee = SuperAddUser::where('employee_id', $employee_id)->whereNotIn('user_type', ['hr', 'client'])->firstOrFail();

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

        $employee = SuperAddUser::where('employee_id', $employee_id)->whereNotIn('user_type', ['client',])->first();

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


    public function getAdminReviewList(Request $request)
    {
        // Step 1: Get all unique emp_ids from both tables
        $validEmployeeIds = evaluationTable::pluck('emp_id')
            ->merge(AdminReviewTable::pluck('emp_id'))
            ->unique()
            ->toArray();

        // Step 2: Get active SuperAddUser records for these IDs
        $superAddUser = SuperAddUser::where('status', 1)
            ->whereIn('employee_id', $validEmployeeIds)
            ->get();

        // Step 3: Exclude users with user_type 'hr' or 'manager'
        $nonHrManagerEmployeeIds = $superAddUser
            ->whereNotIn('user_type', ['manager']) // Exclude hr and manager
            ->pluck('employee_id')
            ->toArray();

        // Step 4: Get only those evaluations for non-hr and non-manager users
        $adminReviewTable = AdminReviewTable::whereIn('emp_id', $validEmployeeIds)->get();
        $evaluation = evaluationTable::whereIn('emp_id', $nonHrManagerEmployeeIds)->get();

        // Step 5: Filter out HR and Manager users before sending to view
        // $superAddUser = $superAddUser->whereNotIn('user_type', ['hr', 'manager'])->values();

        // dd($evaluation);
        // Return view
        return view('reports.adminReportView', compact('superAddUser', 'adminReviewTable', 'evaluation'));
    }






    public function showDetailsAdmin($employee_id)
    {
        $financial_year = request()->query('financial_year');

        $employee = SuperAddUser::where('employee_id', $employee_id)->whereNotIn('user_type', ['admin', 'client',])->first();

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


    // public function getManagerReviewList(Request $request)
    // {
    //     $validEmployeeIds = ManagerReviewTable::pluck('emp_id')
    //         ->merge(evaluationTable::pluck('emp_id'))
    //         ->unique()
    //         ->toArray();

    //     $superAddUser = SuperAddUser::where('status', 1)
    //         ->whereIn('employee_id', $validEmployeeIds)
    //         ->get();


    //     $managerReviewTable = ManagerReviewTable::whereIn('emp_id', $validEmployeeIds)->get();


    //     $evaluation = evaluationTable::whereIn('emp_id', $validEmployeeIds)->get();

    //     return view('reports.managerReportView', compact('superAddUser', 'managerReviewTable', 'evaluation'));
    // }







    public function getManagerReviewList(Request $request)
    {
        // Step 1: Get all emp_ids from Manager and Evaluation tables
        $validEmployeeIds = ManagerReviewTable::pluck('emp_id')
            ->merge(evaluationTable::pluck('emp_id'))
            ->unique()
            ->toArray();

        // Step 2: Get active users from SuperAddUser
        $superAddUser = SuperAddUser::where('status', 1)
            ->whereIn('employee_id', $validEmployeeIds)
            ->get();

        // Step 3: Exclude HR and Admin users
        $nonHrAdminEmployeeIds = $superAddUser
            ->whereNotIn('user_type', ['hr', 'admin'])
            ->pluck('employee_id')
            ->toArray();

        // Step 4: Get only manager reviews and evaluations for allowed users
        $managerReviewTable = ManagerReviewTable::whereIn('emp_id', $nonHrAdminEmployeeIds)->get();
        $evaluation = evaluationTable::whereIn('emp_id', $nonHrAdminEmployeeIds)->get();

        // Optional: pass filtered users only
        $superAddUser = $superAddUser->whereIn('employee_id', $nonHrAdminEmployeeIds)->values();

        return view('reports.managerReportView', compact('superAddUser', 'managerReviewTable', 'evaluation'));
    }






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





    // public function getClientReviewList(Request $request)
    // {

    //     $validEmployeeIds = ClientReviewTable::pluck('emp_id')
    //         ->unique()
    //         ->toArray();

    //     $superAddUser = SuperAddUser::where('status', 1)
    //         ->whereIn('employee_id', $validEmployeeIds)
    //         ->get();

    //     $clientReviewTable = ClientReviewTable::whereIn('emp_id', $validEmployeeIds)->get();

    //     return view('reports.clientReportView', compact('superAddUser', 'clientReviewTable'));
    // }


    public function getClientReviewList(Request $request)
    {
        // 1. Get the client_id from session
        $targetClientId = session('client_id');

        // 2. If session doesn't have client_id, return error
        if (!$targetClientId) {
            return back()->with('error', 'Client session expired or not logged in.');
        }

        // 3. Get all employee IDs that have reviews
        $validEmployeeIds = ClientReviewTable::pluck('emp_id')
            ->unique()
            ->toArray();

        // 4. Filter employees:
        $superAddUser = SuperAddUser::where('status', 1)
            ->whereIn('employee_id', $validEmployeeIds)
            ->where('client_id', 'like', '%"' . $targetClientId . '"%')  // ✅ Compatible with MySQL < 5.7
            ->get();

        // 5. Filter reviews for these employees
        $filteredEmployeeIds = $superAddUser->pluck('employee_id')->toArray();

        $clientReviewTable = ClientReviewTable::whereIn('emp_id', $filteredEmployeeIds)->get();

        // 6. Return to view
        return view('reports.clientReportView', compact('superAddUser', 'clientReviewTable'));
    }






















    public function showDetailsClient($employee_id)
    {
        $financial_year = request()->query('financial_year');

        $sessionClientId = Session::get('client_id');

        if (!$sessionClientId) {
            return response()->json(['message' => 'Client ID is missing.']);
        }

        $employee = SuperAddUser::where('employee_id', $employee_id)->whereNotIn('user_type', ['hr', 'admin', 'client', 'manager'])->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found.']);
        }

        $reviews = ClientReviewTable::where('emp_id', $employee_id)
            ->where('client_id', $sessionClientId)
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
        // $empId = session('employee_id');
        $empId = $request->input('emp_id') ?? $request->input('employee_id');
        $year = $request->query('financial_year');

        $user = SuperAddUser::where('employee_id', $empId)->first();
        $roles = json_decode($user?->user_roles ?? '[]', true);
        $showClient = in_array('client', $roles);

        $evaluation = evaluationTable::where('emp_id', $empId)
            ->where('financial_year', $year)
            ->first();

        $adminReview = AdminReviewTable::where('emp_id', $empId)
            ->where('financial_year', $year)
            ->first();

        $hrReview = HrReviewTable::where('emp_id', $empId)
            ->where('financial_year', $year)
            ->first();

        $managerReview = ManagerReviewTable::where('emp_id', $empId)
            ->where('financial_year', $year)
            ->first();

        $clientReview = null;
        if ($showClient) {
            $clientReview = ClientReviewTable::where('emp_id', $empId)
                ->where('financial_year', $year)
                ->first();
        }

        $response = [
            'admin' => $adminReview?->AdminTotalReview,
            'hr' => $hrReview?->HrTotalReview,
            'managerTotal' => $managerReview?->ManagerTotalReview,
            'total' => $evaluation?->total_scoring_system,
            'showClient' => $showClient,
        ];


        if ($showClient) {
            $response['clientTotal'] = $clientReview?->ClientTotalReview;
        }


        return response()->json($response);
    }
}
