<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Mail\SuperUserNotification;
use App\Models\AdminReviewTable;
use App\Models\AllClient;
use App\Models\ClientReviewTable;
use App\Models\evaluationTable;
use App\Models\FinancialData;
use App\Models\HrReviewTable;
use App\Models\ManagerReviewTable;
use App\Models\PendingUser;
use App\Models\SuperAddUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Models\SuperUserTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;




class SuperAdminController extends Controller
{
    public function index()
    {
        return view("admin/loginForm");
    }


    public function testPageShow()
    {
        return view("test");
    }



    // public function insertData(Request $request)
    // {
    //     $superUsers = SuperUserTable::where('user_type', 'Super User')->get();

    //      foreach ($superUsers as $user) {
    //     Mail::to($user->email)->send(new SuperUserNotification($request->input('email')));
    // }

    //     $data = [
    //         'email' => $request->input('email'),
    //         'user_type' => $request->input('user_type'),
    //         'password' => Hash::make($request->input('password')),
    //     ];

    //     $request = SuperUserTable::insert($data);

    //     return response()->json(['message' => 'Data inserted and emails sent.']);
    // }



    public function insertData(Request $request)
{
    // Generate a token for approval link
    $token = Str::random(40);

    // Save to pending_users table
    $pending = PendingUser::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'user_type' => $request->input('user_type'),
        'password' => Hash::make($request->input('password')),
        'token' => $token,
    ]);

    // Get Super Users (from users table where user_type = 'Super User')
    $superUsers = SuperUserTable::where('user_type', 'Super User')->get();

    // Send email with approval link
    foreach ($superUsers as $superUser) {
        Mail::to($superUser->email)->send(new SuperUserNotification($pending));
    }

    return response()->json(['message' => 'Awaiting Super User approval.']);
}




// public function approveUser($token)
// {
//     //  dd("Function is being called with token: $token");

//     $pendingUser = PendingUser::where('token', $token)->first();

//     if (!$pendingUser) {
//         return response()->json(['message' => 'Invalid or expired token.'], 404);
//     }

//     // Insert into users table
//     SuperUserTable::create([
//         'email' => $pendingUser->email,
//         'password' => $pendingUser->password,
//         'email_verified_at' => now(),
//         'user_type' => $pendingUser->user_type,
//     ]);

//     // Delete from pending_users
//     $pendingUser->delete();

//     return response()->json(['message' => 'User approved and added to system.']);
// }
public function approveUser($token)
{
    $pendingUser = PendingUser::where('token', $token)->first();

    if (!$pendingUser) {
        return response("<h3>Invalid or expired approval link.</h3>", 404)
               ->header('Content-Type', 'text/html');
    }

    SuperUserTable::create([
        'email' => $pendingUser->email,
        'password' => $pendingUser->password,
        'user_type' => $pendingUser->user_type,
    ]);

    $pendingUser->delete();

    return response("
    <html>
        <head><title>Approval Success</title></head>
        <body style='font-family:sans-serif; text-align:center; padding-top:50px;'>
            <h2>User has been successfully approved by Super Admin and added to the system.</h2>
            <p>You may now close this tab.</p>
        </body>
    </html>
", 200)->header('Content-Type', 'text/html');
}














    public function loginAutenticacao(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
            'user_type' => 'required|string',
            'password' => 'required|string|min:4'
        ]);

        // Check if the user exists by email
        $userLogin = SuperUserTable::where('email', $validated['email'])->first();

        if (!$userLogin) {
            return response("Failed to send OTP:\nEmail does not match!", 401)
                ->header('Content-Type', 'text/plain');
        }

        // Check if the user type matches
        if ($userLogin->user_type !== $validated['user_type']) {
            return response("Failed to send OTP:\nIncorrect User Type!", 401)
                ->header('Content-Type', 'text/plain');
        }

        // Check if the password is correct
        if (!Hash::check($validated['password'], $userLogin->password)) {
            return response("Failed to send OTP:\nPassword is incorrect!", 401)
                ->header('Content-Type', 'text/plain');
        }
        // OTP Generation
        $otp = random_int(100000, 999999);
        Session::put('otp', $otp);
        Session::put('otp_sent_time', now());
        Session::put('user_type', $userLogin->user_type);
        Session::put('otp_email', $validated['email']);

        try {
            Mail::to($validated['email'])->send(new OtpMail($otp));

            return response()->json([
                'status' => 'success',
                'message' => 'OTP has been sent to your email!',
            ]);
        } catch (\Exception $e) {
            Log::error('OTP Email sending failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP email. Please try again later.',
                'debug' => env('APP_DEBUG') ? $e->getMessage() : null,
            ]);
        }
    }

    // OTP verification
    public function verifyOtp(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|integer',
        ]);

        // Check if OTP exists in session
        $otpSession = Session::get('otp');
        $otpEmail = Session::get('otp_email');
        $otpSentTime = Session::get('otp_sent_time');

        // Check if the OTP is expired (valid for 5 minutes)
        if ($otpSentTime && now()->diffInMinutes($otpSentTime) > 10) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP has expired. Please request a new one.',
                'redirect' => route('super-admin-view')
            ]);
        }

        // Verify OTP and email match
        if ($validated['otp'] == $otpSession && $validated['email'] == $otpEmail) {
            // Remove OTP from session after verification
            Session::forget('otp');
            Session::forget('otp_email');
            Session::forget('otp_sent_time');

            // Retrieve the user from database
            $user = SuperUserTable::where('email', $validated['email'])->first();

            if ($user) {
                // Set user_type in session for logged-in user
                Session::put('user_type', $user->id);
                Session::put('user_email', $user->email);

                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP verified successfully. You are now logged in!',
                    'redirect' => route('super-admin-dashboard')
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP. Please try again.',
            ]);
        }
    }


    //Super Admin Dash Board view
    public function indexSuperAdminDashBoard()
    {
        return view('admin/SuperAdminDashbord');
    }


    // Retrieve the logged-in user's email from the session
    public function showDashboard()
    {

        $userEmail = Session::get('user_email');


        if ($userEmail) {
            return view('admin.SuperAdminDashbord', compact('userEmail'));
        } else {
            return redirect()->route('super-admin-view');  // Redirect to login if no session data
        }
    }

    //view All Review's 
    public function searchUser()
    {

        $currentDate = Carbon::now()->toDateString();

        $employees = SuperAddUser::where('probation_date', '<=', $currentDate)->get();
        return view('admin.superView', compact('employees'));
    }

    //View details of view all reviews
    public function showEvaluationReview($id)
    {
        $employee = evaluationTable::where('emp_id', $id)->first(); // Fetch employee details

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        return view('review.evaluationDetails', compact('employee')); // Pass employee data to view
    }


    //Fetching Data form mention table 
    public function superAdminSearchUser(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $employeeName = $request->input('employee_name');

        Log::info('Received Employee Search Request', [
            'employee_id' => $employeeId,
            'employee_name' => $employeeName
        ]);

        $query = SuperAddUser::query();

        // Search by Employee ID (Exact match)
        if (!empty($employeeId)) {
            $query->where('employee_id', $employeeId);
        }

        // Search by First Name or Last Name
        if (!empty($employeeName)) {
            $query->where(function ($q) use ($employeeName) {
                $q->where('fname', 'LIKE', "%$employeeName%")
                    ->orWhere('lname', 'LIKE', "%$employeeName%");
            });
        }

        $users = $query->get(); // Fetch all matching users


        Log::info('Search Result', ['users' => $users]);

        if ($users->count() > 0) {
            return response()->json([
                'success' => true,
                'users' => $users->map(function ($user) {
                    return [
                        'full_name' => $user->fname . ' ' . $user->lname,
                        'email' => $user->email,
                        'employee_id' => $user->employee_id,
                        'designation' => $user->designation,
                    ];
                })
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        // $user = $query->first();

        if ($user) {
            Log::info('User Found:', ['user' => $user]);

            // Fetching evaluation, HR, manager, and admin reviews
            $evaluationData = evaluationTable::where('emp_id', $user->employee_id)->first();
            $hrReviewTable = HrReviewTable::where('emp_id', $user->employee_id)->first();
            $managerReviewTable = ManagerReviewTable::where('emp_id', $user->employee_id)->first();
            $adminReviewTable = AdminReviewTable::where('emp_id', $user->employee_id)->first();
            $clientReviewTable = ClientReviewTable::where('emp_id', $user->employee_id)->first();

            return response()->json([
                'success' => true,
                'user' => [
                    'full_name' => $user->fname . ' ' . $user->lname,
                    'fname' => $user->fanme,
                    'lname' => $user->lname,
                    'email' => $user->email,
                    'employee_id' => $user->employee_id,
                    'designation' => $user->designation,
                ],
                'evaluation' => $evaluationData ? 'Completed' : 'Pending for Review',
                'hr_review' => $hrReviewTable ? 'Completed' : 'Pending for Review',
                'manager_review' => $managerReviewTable ? 'Completed' : 'Pending for Review',
                'admin_review' => $adminReviewTable ? 'Completed' : 'Pending for Review',
                'client_review' => $clientReviewTable ? 'Completed' : 'Pending for Review',
            ]);
        } else {

            Log::warning('User Not Found', [
                'employee_id' => $employeeId,
                'employee_name' => $employeeName
            ]);

            return response()->json([
                'success' => false,
                'message' => 'User not found!'
            ]);
        }
    }


    //Apprisal View
    public function appraisalView()
    {
        $users = SuperAddUser::all();

        return view('admin.apprisal', compact('users')); // Looks for resources/views/apprisal.blade.php
    }



    public function getAppraisalData(Request $request)
    {
        $employeeQuery = trim($request->query('employee_query', ''));
        $financialYear = trim($request->query('financial_year', ''));
        $financialYear = str_replace('/', '-', $financialYear);

        if ($financialYear && preg_match('/(\d{4})-(\d{4})/', $financialYear, $matches)) {
            $startDate = "{$matches[1]}-04-01";
            $endDate = "{$matches[2]}-03-31";
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid or missing financial year'], 400);
        }

        if (empty($employeeQuery)) {
            return response()->json(['error' => 'Employee ID or Name is required'], 400);
        }

        $query = SuperAddUser::query();
        $query->where(function ($q) use ($employeeQuery) {
            $q->orWhere('employee_id', $employeeQuery)
                ->orWhereRaw("LOWER(TRIM(fname)) LIKE LOWER(?)", ["%{$employeeQuery}%"])
                ->orWhereRaw("LOWER(TRIM(lname)) LIKE LOWER(?)", ["%{$employeeQuery}%"])
                ->orWhereRaw("LOWER(CONCAT(TRIM(fname), ' ', TRIM(lname))) LIKE LOWER(?)", ["%{$employeeQuery}%"]);
        });

        $employee = $query->first();

        if (!$employee) {
            Log::error("Employee not found with employee_query: $employeeQuery");
            return response()->json(['status' => 'error', 'message' => 'No employee found.'], 404);
        }

        $employeeIdentifier = $employee->emp_id ?? $employee->employee_id;

        $hasData = SuperAddUser::where('employee_id', $employeeIdentifier)
            ->where('financial_year', $financialYear)
            ->exists();

        if (!$hasData) {
            return response()->json([
                'status' => 'error',
                'message' => 'No appraisal data found for the selected financial year.'
            ], 404);
        }

        $adminReviewData = AdminReviewTable::where('emp_id', $employeeIdentifier)
            ->where('financial_year', $financialYear)
            ->pluck('AdminTotalReview')
            ->map(fn($score) => $score > 0 ? min(($score / 45) * 100, 100) : null)
            ->filter()
            ->toArray();

        $hrReviewData = HrReviewTable::where('emp_id', $employeeIdentifier)
            ->where('financial_year', $financialYear)
            ->pluck('HrTotalReview')
            ->map(fn($score) => $score > 0 ? min(($score / 30) * 100, 100) : null)
            ->filter()
            ->toArray();

        $managerReviewData = ManagerReviewTable::where('emp_id', $employeeIdentifier)
            ->where('financial_year', $financialYear)
            ->pluck('ManagerTotalReview')
            ->map(fn($score) => $score > 0 ? min(($score / 35) * 100, 100) : null)
            ->filter()
            ->toArray();

        $evaluationScore = evaluationTable::where('emp_id', $employeeIdentifier)
            ->where('financial_year', $financialYear)
            ->pluck('total_scoring_system')
            ->map(fn($score) => $score > 0 ? min(($score / 100) * 100, 100) : null)
            ->filter()
            ->toArray();

        // Optional: Client Review
        $clientReviewData = [];
        $userRoles = json_decode($employee->user_roles, true);
        $hasClient = is_array($userRoles) && in_array('client', $userRoles);

        if ($hasClient) {
            $clientReviews = ClientReviewTable::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $financialYear)
                ->get();

            if ($clientReviews->isNotEmpty()) {
                $clientScores = [];

                foreach ($clientReviews as $review) {
                    $clientScores[$review->client_id][] = $review->ClientTotalReview;
                }

                $averagedClientScores = array_map(function ($scores) {
                    $valid = array_filter($scores, fn($s) => is_numeric($s) && $s > 0);
                    return count($valid) ? array_sum($valid) / count($valid) : null;
                }, $clientScores);

                $validClientScores = array_filter($averagedClientScores, fn($s) => is_numeric($s));
                $clientAverage = count($validClientScores) ? round(array_sum($validClientScores) / count($validClientScores), 2) : null;

                if (is_numeric($clientAverage)) {
                    $clientReviewData[] = min(($clientAverage / 100) * 100, 100);
                }
            }
        }

        // Average all component scores
        $eval = count($evaluationScore) ? array_sum($evaluationScore) / count($evaluationScore) : null;
        $admin = count($adminReviewData) ? array_sum($adminReviewData) / count($adminReviewData) : null;
        $hr = count($hrReviewData) ? array_sum($hrReviewData) / count($hrReviewData) : null;
        $manager = count($managerReviewData) ? array_sum($managerReviewData) / count($managerReviewData) : null;
        $client = count($clientReviewData) ? array_sum($clientReviewData) / count($clientReviewData) : null;

        $userType = strtolower($employee->user_type ?? 'user');
        $appraisalScore = 'Pending';

        if ($userType === 'admin' && is_numeric($eval) && is_numeric($hr)) {
            $appraisalScore = round(($eval + $hr) / 2, 2);
        } elseif ($userType === 'hr' && is_numeric($eval) && is_numeric($admin)) {
            $appraisalScore = round(($eval + $admin) / 2, 2);
        } elseif ($userType === 'manager' && is_numeric($eval) && is_numeric($admin) && is_numeric($hr)) {
            $appraisalScore = round(($eval + $admin + $hr) / 3, 2);
        } elseif ($userType === 'user' && is_numeric($eval) && is_numeric($admin) && is_numeric($hr) && is_numeric($manager)) {
            if (is_numeric($client)) {
                $appraisalScore = round(($eval + $admin + $hr + $manager + $client) / 5, 2);
            } else {
                $appraisalScore = round(($eval + $admin + $hr + $manager) / 4, 2);
            }
        }

        return response()->json([
            'employee_name' => "{$employee->fname} {$employee->lname}",
            'adminReviewData' => $adminReviewData,
            'hrReviewData' => $hrReviewData,
            'managerReviewData' => $managerReviewData,
            'clientReviewData' => $hasClient ? ($clientReviewData ?? ['Pending']) : [],
            'evaluationScore' => $evaluationScore,
            'showClientColumn' => $hasClient,
            'appraisal_score' => $appraisalScore,
            'status' => 'success',
            'showEvaluation' => !empty($evaluationScore),
            'showAdmin' => !empty($adminReviewData),
            'showHR' => !empty($hrReviewData),
            'showManager' => !empty($managerReviewData),
            'showClient' => $hasClient && !empty($clientReviewData),
        ]);
    }


    public function toggleStatus($user_type, $identifier)
    {
        $user = $user_type === 'client'
            ? SuperAddUser::where('user_type', 'client')->where('id', $identifier)->first()
            : SuperAddUser::where('user_type', $user_type)->where('employee_id', $identifier)->first();

        if (!$user) {
            return response()->json(['success' => false, 'error' => 'User not found'], 404);
        }

        $user->status = $user->status ? 0 : 1;
        $user->save();

        return response()->json([
            'success' => true,
            'new_status' => $user->status
        ]);
    }


    public function getActiveUsers()
    {
        try {
            $users = SuperAddUser::where('status', 1)->get();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Search users by Employee ID or Name (Show active/inactive when searching)
    public function searchEmployee(Request $request)
    {
        try {
            $query = trim($request->input('query'));
            $type = $request->input('type');

            if (!$query) {
                return response()->json(['message' => 'Query is required']);
            }

            $usersQuery = SuperAddUser::query();

            if ($type === "id") {
                $usersQuery->where('employee_id', 'like', "%$query%");
            } elseif ($type === "name") {
                $usersQuery->where(function ($q) use ($query) {
                    $q->where('fname', 'like', "%$query%")
                        ->orWhere('lname', 'like', "%$query%");
                });
            }


            $users = $usersQuery->get(['employee_id', 'fname', 'lname', 'designation', 'salary', 'mobno', 'email', 'status']);

            Log::info("Search Results:", $users->toArray()); // Debugging log

            if ($users->isEmpty()) {
                return response()->json(['message' => 'User not found']);
            }

            return response()->json($users);
        } catch (\Exception $e) {
            Log::error("Search Error: " . $e->getMessage());
            return response()->json(['error' => 'Server error', 'details' => $e->getMessage()], 500);
        }
    }


    //Financial View
    public function financialView()
    {
        return view('admin.financial');
    }

    public function getFinancialData(Request $request)
    {
        try {
            $searchInput = strtolower(trim($request->input('search')));
            $financialYear = trim($request->query('financial_year', ''));

            Log::info("Searching employee:", ['input' => $searchInput]);

            $employee = SuperAddUser::where(function ($query) use ($searchInput) {
                $query->whereRaw("LOWER(employee_id) = ?", [$searchInput])
                    ->orWhereRaw("LOWER(CONCAT(TRIM(fname), ' ', TRIM(lname))) LIKE ?", ["%$searchInput%"]);
            })->first();

            if (!$employee) {
                return response()->json(['status' => 'error', 'message' => 'No employee found.'], 404);
            }

            $employeeIdentifier = $employee->emp_id ?? $employee->employee_id;
            $userType = strtolower($employee->user_type);

            // Client Review
            $clientReviewDetails = ClientReviewTable::join('all_clients', 'client_review_tables.client_id', '=', 'all_clients.id')
                ->where('client_review_tables.emp_id', $employeeIdentifier)
                ->where('client_review_tables.financial_year', $financialYear)
                ->select('client_review_tables.client_id', DB::raw('AVG(client_review_tables.ClientTotalReview) as avg_score'))
                ->groupBy('client_review_tables.client_id')
                ->get();

            $hasClientReview = $clientReviewDetails->isNotEmpty();

            // Check if any appraisal data exists
            $hasData = evaluationTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->exists() ||
                AdminReviewTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->exists() ||
                HrReviewTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->exists() ||
                ManagerReviewTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->exists() ||
                $hasClientReview;

            if (!$hasData) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No appraisal data found for the selected financial year.'
                ], 404);
            }

            // Review scores
            $adminReviewData = AdminReviewTable::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $financialYear)
                ->pluck('AdminTotalReview')
                ->map(fn($score) => min(($score / 45) * 100, 100))
                ->toArray();

            $hrReviewData = HrReviewTable::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $financialYear)
                ->pluck('HrTotalReview')
                ->map(fn($score) => min(($score / 30) * 100, 100))
                ->toArray();

            $managerReviewScores = ManagerReviewTable::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $financialYear)
                ->pluck('ManagerTotalReview');

            $avgManagerReview = $managerReviewScores->isNotEmpty()
                ? round(min(($managerReviewScores->avg() / 35) * 100, 100), 2)
                : 0;

            $clientReviewData = $hasClientReview
                ? round($clientReviewDetails->avg('avg_score'), 2)
                : 0;

            $avgReviewPercentage = evaluationTable::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $financialYear)
                ->value('total_scoring_system');

            if ($avgReviewPercentage === null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No evaluation score found for the selected financial year.'
                ], 404);
            }

            // Previous Year Base Salary
            if (preg_match('/(\d{4})-(\d{4})/', $financialYear, $matches)) {
                $previousFinancialYear = ($matches[1] - 1) . '-' . $matches[1];
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid financial year format.'
                ], 400);
            }

            $previousAppraisal = FinancialData::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $previousFinancialYear)
                ->orderByDesc('apprisal_date')
                ->first();

            $baseSalary = ($previousAppraisal && is_numeric($previousAppraisal->final_salary))
                ? (float) $previousAppraisal->final_salary
                : (float) $employee->salary;

            $companyPercentage = (float) $employee->company_percentage;

            // Compute averages
            $adminAvg = !empty($adminReviewData) ? array_sum($adminReviewData) / count($adminReviewData) : 0;
            $hrAvg = !empty($hrReviewData) ? array_sum($hrReviewData) / count($hrReviewData) : 0;
            $evaluationScore = (float) $avgReviewPercentage;
            $managerAvg = $avgManagerReview;
            $clientAvg = $clientReviewData;

            // Determine final review score
            if ($hasClientReview) {
                $finalReviewScore = ($evaluationScore + $adminAvg + $hrAvg + $managerAvg + $clientAvg) / 5;
            } elseif ($userType === 'manager') {
                $finalReviewScore = ($evaluationScore + $adminAvg + $hrAvg) / 3;
            } elseif ($userType === 'admin') {
                $finalReviewScore = ($evaluationScore + $hrAvg) / 2;
            } elseif ($userType === 'hr') {
                $finalReviewScore = ($evaluationScore + $adminAvg) / 2;
            } else {
                // Default: user or unknown
                $finalReviewScore = ($evaluationScore + $adminAvg + $hrAvg + $managerAvg) / 4;
            }

            // Salary calculations
            $updatedSalary = (int) $baseSalary * ($companyPercentage / 100);
            $appraisalAmount = (int) $updatedSalary * ($finalReviewScore / 100);
            $finalSalary = (int) $this->roundSalary($baseSalary + $updatedSalary + $appraisalAmount);

            $employee->update(['final_salary' => $finalSalary]);
            $isAlreadySaved = $employee->final_salary == $finalSalary;

            $alreadyAppraised = FinancialData::where('emp_id', $employeeIdentifier)
                ->whereYear('apprisal_date', now()->year)
                ->exists();

            return response()->json([
                'employee_name'       => "{$employee->fname} {$employee->lname}",
                'employee_id'         => $employee->employee_id,
                'evaluationScore'     => $avgReviewPercentage,
                'hrReviewData'        => $hrReviewData,
                'adminReviewData'     => $adminReviewData,
                'managerReviewData'   => $avgManagerReview,
                'clientReviewData'    => $clientReviewData,
                'salary'              => (int) $baseSalary,
                'company_percentage'  => $companyPercentage,
                'updatedSalary'       => (int) $updatedSalary,
                'appraisalAmount'     => (int) $appraisalAmount,
                'finalSalary'         => (int) $finalSalary,
                'appraisalDate'       => now()->toDateString(),
                'isAlreadySaved'      => $isAlreadySaved,
                'alreadyAppraised'    => $alreadyAppraised,
                'appraisalScore'      => round($finalReviewScore, 2),
                'user_type' =>  $userType
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching financial data:", ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
        }
    }


    private function roundSalary($amount)
    {
        return round($amount * 20) / 20;
    }



    public function userListView()
    {
        $currentDate = Carbon::now()->toDateString();

        $users = SuperAddUser::where('probation_date', '<=', $currentDate)
            ->orWhere('designation', 'Client')
            ->orderByRaw("CASE WHEN probation_date = ? THEN 0 ELSE 1 END", [$currentDate])
            ->orderBy('probation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.userList', compact('users'));
    }


    public function viewDetailsAll($emp_id)
    {

        // Fetch client reviews with client names
        $clientReviews = DB::table('client_review_tables')
            ->join('all_clients', 'client_review_tables.client_id', '=', 'all_clients.id')
            ->where('client_review_tables.emp_id', $emp_id)
            ->select('client_review_tables.*', 'all_clients.client_name')
            ->get();



        $users = [
            'evaluation' => DB::table('evaluation_tables')->where('emp_id', $emp_id)->first(),
            'managerReview' => DB::table('manager_review_tables')->where('emp_id', $emp_id)->first(),
            'adminReview' => DB::table('admin_review_tables')->where('emp_id', $emp_id)->first(),
            'hrReview' => DB::table('hr_review_tables')->where('emp_id', $emp_id)->first(),
            // 'clientReview' => DB::table('client_review_tables')->where('emp_id', $emp_id)->first(),
            'clientReview' => DB::table('client_review_tables')->where('emp_id', $emp_id)->get(),
            'superAddUser' => DB::table('super_add_users')->where('employee_id', $emp_id)->first(),
            'AllClient' => DB::table('all_clients')->get(),
        ];

        $user_roles = json_decode($users['superAddUser']->user_roles, true);

        if (!array_filter($users)) {
            return redirect()->back()->with('error', 'No review data found for this employee.');
        }

        return view('review/viewDetails', compact('users', 'emp_id', 'user_roles', 'clientReviews'));
    }

    public function getSuperAdminEvaluationView(Request $request, $emp_id)
    {
        $financialYear = $request->get('financial_year');

        $user = evaluationTable::where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->firstOrFail();

        return view('reports/evaluationReport', compact('user'));
    }

    public function getSuperAdminHrReview(Request $request, $emp_id)
    {
        $financialYear = $request->get('financial_year');

        $user = HrReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->firstOrFail();

        return view('reports/hrReport', compact('user'));
    }


    public function getSuperAdminManagerReview(Request $request, $emp_id)
    {
        $financialYear = $request->get('financial_year');

        $user = ManagerReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->firstOrFail();
        return view('reports/managerReport', compact('user'));
    }

    public function getSuperAdminAdminReview(Request $request, $emp_id)
    {
        $financialYear = $request->get('financial_year');

        $user = AdminReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->firstOrFail();
        return view('reports/adminReport', compact('user'));
    }

    public function getSuperAdminClientReview(Request $request, $emp_id)
    {
        $financialYear = $request->get('financial_year');
        $clientId = $request->get('client_id');

        $user = ClientReviewTable::with('client') // <-- Eager load the client relationship
            ->where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->where('client_id', $clientId)
            ->firstOrFail();


        // dd($user->client);

        return view('reports/clientReport', compact('user'));
    }



    public function getProbationPeriod()
    {
        $currentDate = Carbon::now()->toDateString();

        $user = SuperAddUser::where('Designation', '!=', 'Client')
            ->orderByRaw("CASE WHEN probation_date = ? THEN 0 ELSE 1 END", [$currentDate])
            ->orderBy('probation_date', 'desc')
            ->orderBy('fname', 'asc')
            ->orderBy('lname', 'asc')
            ->get();

        return view('admin.probation', compact('user'));
    }







    public function getPendingAppraisalView(Request $request)
    {
        $users = SuperAddUser::where('user_type', '!=', 'client')->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('financial_data')
                ->whereColumn('financial_data.emp_id', 'super_add_users.employee_id')
                ->whereColumn('financial_data.financial_year', 'super_add_users.financial_year');
        })->get();
        // dd($users);                                    
        return view('admin.appraisalPendingList', compact('users'));
    }

    public function filterByFinancialYear(Request $request)
    {

        $yearRange = trim($request->input('financial_year'));


        if (!$yearRange) {
            return response()->json(['data' => []]);
        }
        $empIdsInFinancialData = FinancialData::pluck('emp_id')->toArray();

        $users = SuperAddUser::whereNotIn('employee_id', $empIdsInFinancialData)
            ->where('status', '!=', 0)
            ->where('financial_year', $yearRange)
            ->get();


        if ($users->isEmpty()) {
            return response()->json(['data' => []]);
        }
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                $user->fname . ' ' . $user->lname, // Full Name
                $user->employee_id,                // Employee ID
                $user->designation,
                $user->email,                 // Designation
                $user->dob,                         // Date of Birth
                $user->financial_year,              // Financial Year
                $user->probation_date ?? 'Not Set', // Probation Date
            ];
        }

        return response()->json(['data' => $data]);
    }



    public function filterByFinancialYearEmployeeReview(Request $request)
    {
        $yearRange = trim($request->input('financial_year'));

        if (!$yearRange) {
            return response()->json(['data' => []]);
        }

        $empIdsInFinancialData = FinancialData::pluck('emp_id')->toArray();

        $users = SuperAddUser::whereNotIn('employee_id', $empIdsInFinancialData)
            ->where('status', '!=', 0)
            ->where('financial_year', $yearRange)
            ->get();

        if ($users->isEmpty()) {
            return response()->json(['data' => []]);
        }

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'employee_id' => $user->employee_id,
                'full_name' => $user->fname . ' ' . $user->lname,
                'email' => $user->email,
                'designation' => $user->designation,
                'financial_year' => $user->financial_year,
            ];
        }

        return response()->json(['data' => $data]);
    }


    public function getReviewScoresSuperAdmin(Request $request)
    {
        // Get from JSON body
        $empId = $request->input('emp_id') ?? $request->input('employee_id');
        // $empId = $request->input('emp_id');
        $year = $request->input('financial_year');

        // Debug (optional)
        // var_dump($empId, $year); exit;

        // Get user info
        $user = SuperAddUser::where('employee_id', $empId)->first();
        $roles = json_decode($user?->user_roles ?? '[]', true);
        $showClient = in_array('client', $roles);

        // Fetch total scores from individual review tables
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

        // Build response
        $response = [
            'total' => $evaluation?->total_scoring_system,
            'adminTotal' => $adminReview?->AdminTotalReview,
            'hrTotal' => $hrReview?->HrTotalReview,
            'managerTotal' => $managerReview?->ManagerTotalReview,
            'showClient' => $showClient,
        ];

        if ($showClient) {
            $response['clientTotal'] = $clientReview?->ClientTotalReview;
        }

        return response()->json($response);
    }


    public function viewAddClient(Request $request)
    {

        return view('admin/addClient');
    }


    public function createClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name'   => 'nullable|string|max:50',
            'company_name'  => 'nullable|string|max:50',
            'client_mobno'  => 'nullable|regex:/^[\d\s\-\+\(\)]+$/|max:20',
            'client_email'  => 'nullable|email|max:50',
            'password'      => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Check if a similar client already exists
        $exists = AllClient::where('client_email', $validated['client_email'])->first();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Client already exists with this email.'
            ]);
        }

        // Store client (hash password if stored)
        AllClient::create([
            'client_name' => $validated['client_name'],
            'company_name' => $validated['company_name'],
            'client_mobno' => $validated['client_mobno'],
            'client_email' => $validated['client_email'],
            'password' => bcrypt($validated['password']),
            'user_type' => $request->input('user_type'),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Client added successfully.'
        ]);
    }

    public function getClients()
    {
        $clients = AllClient::select('id', 'client_name', 'client_email')->get();

        return response()->json($clients);
    }

    public function getManager()
    {
        $managers = SuperAddUser::where('user_type', 'manager')
            ->select('id', 'fname', 'lname')
            ->get();

        $formatted = $managers->map(function ($manager) {
            $fullName = trim($manager->fname . ' ' . $manager->lname);

            return [
                'id' => $manager->id,
                'text' => $fullName,
            ];
        });

        return response()->json($formatted);
    }



    //Edit User
    public function editUserView(Request $request, $id)
    {
        // Get the user
        $user = SuperAddUser::findOrFail($id);

        // Decode JSON client IDs to array
        $clientIds = json_decode($user->client_id, true) ?? [];

        // Fetch selected clients for display (optional)
        $clients = AllClient::whereIn('id', $clientIds)->get();

        // Fetch all clients for the dropdown
        // $allClients = AllClient::select('id', 'client_name')->get();

        // Decode user_roles JSON field to array
        $userRoles = json_decode($user->user_roles, true) ?? [];


        // Get user_type (this assumes it's a field in SuperAddUser)
        $userType = $user->user_type;

        // dd($user,$clients,$clientIds,  $userRoles, $userType);
        return view('admin.editUser', compact('user', 'clients', 'clientIds',  'userRoles', 'userType'));
    }

    public function updateUser(Request $request, $id)
    {
        // dd($request);


        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'mobno' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'employee_id' => 'nullable|string|max:255',
            'dob' => 'required|date',
            'gender' => 'nullable|in:male,female,other',
            'designation' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'manager_name' => 'nullable|string|max:255',
            'manager_id' => 'nullable|integer|exists:super_add_users,id',
            'user_type' => 'required|string|in:admin,hr,users,manager',
            'probation_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'password' => 'nullable|string|confirmed|min:6',
            'client_id' => 'nullable|array',
            'client_id.*' => 'integer|exists:all_clients,id',
            'user_roles' => 'nullable|array',
            'user_roles.*' => 'string',
        ]);
        // var_dump($request);

        $user = SuperAddUser::findOrFail($id);
        // Update base user fields
        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->mobno = $request->mobno;
        $user->email = $request->email;
        $user->employee_id = $request->employee_id; // if editable
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->designation = $request->designation;
        $user->division = $request->division;
        $user->manager_name = $request->manager_name;
        $user->manager_id = $request->manager_id;
        $user->user_type = $request->user_type;
        $user->probation_date = $request->probation_date;
        $user->salary = $request->salary;

        // Save JSON fields
        $user->client_id = json_encode($request->client_id ?? []);
        $user->user_roles = json_encode($request->user_roles ?? []);

        // Update password only if provided
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return redirect()->back()->with('success', 'User updated successfully!');
    }









    public function search(Request $request)
    {
        $search = $request->get('q');

        $clients = AllClient::where('client_name', 'like', '%' . $search . '%')
            ->select('id', 'client_name', 'company_name')
            ->limit(20)
            ->get();

        return response()->json($clients);
    }



    //Client Managemrnt page
    public function viewClints(Request $request)
    {

        $allClients = AllClient::get();

        return view('admin/clientManagement', compact('allClients'));
    }


    public function clientToggleStatus($id)
    {

        // Find client by ID
        $client = AllClient::find($id);

        if (!$client) {
            return response()->json(['success' => false, 'error' => 'Client not found'], 404);
        }

        // Toggle status (assuming status is 0 or 1)
        $client->status = $client->status ? 0 : 1;
        $client->save();

        return response()->json([
            'success' => true,
            'new_status' => $client->status
        ]);
    }
}
