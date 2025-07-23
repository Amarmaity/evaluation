<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\AdminReviewTable;
use App\Models\AllClient;
use App\Models\ClientReviewTable;
use App\Models\ClientReviewTables;
use App\Models\evaluationTable;
use App\Models\FinancialData;
use App\Models\HrReviewTable;
use App\Models\ManagerReviewTable;
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

    public function insertData(Request $request)
    {

        $data = [
            'email' => $request->input('email'),
            'user_type' => $request->input('user_type'),
            'password' => Hash::make($request->input('password')),
        ];

        $request = SuperUserTable::insert($data);
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


    // public function getAppraisalData(Request $request)
    // {
    //     $employeeQuery = trim($request->query('employee_query', ''));
    //     $financialYear = trim($request->query('financial_year', ''));
    //     $financialYear = str_replace('/', '-', $financialYear);

    //     // Validate financial year format
    //     if ($financialYear && preg_match('/(\d{4})-(\d{4})/', $financialYear, $matches)) {
    //         $startDate = "{$matches[1]}-04-01";
    //         $endDate = "{$matches[2]}-03-31";
    //     } else {
    //         return response()->json(['status' => 'error', 'message' => 'Invalid or missing financial year'], 400);
    //     }

    //     // Validate employee input
    //     if (empty($employeeQuery)) {
    //         return response()->json(['error' => 'Employee ID or Name is required'], 400);
    //     }

    //     // Build query
    //     $query = SuperAddUser::query();
    //     $query->where(function ($q) use ($employeeQuery) {
    //         $q->whereRaw("LOWER(TRIM(employee_id)) LIKE LOWER(?)", ["%{$employeeQuery}%"])
    //             ->orWhereRaw("LOWER(TRIM(fname)) LIKE LOWER(?)", ["%{$employeeQuery}%"])
    //             ->orWhereRaw("LOWER(TRIM(lname)) LIKE LOWER(?)", ["%{$employeeQuery}%"])
    //             ->orWhereRaw("LOWER(CONCAT(TRIM(fname), ' ', TRIM(lname))) LIKE LOWER(?)", ["%{$employeeQuery}%"]);
    //     });

    //     $employee = $query->first();

    //     if (!$employee) {
    //         Log::error("Employee not found with employee_query: $employeeQuery");
    //         return response()->json(['status' => 'error', 'message' => 'No employee found.'], 404);
    //     }

    //     $employeeIdentifier = $employee->emp_id ?? $employee->employee_id;

    //     $hasData = SuperAddUser::where('employee_id', $employeeIdentifier)
    //         ->where('financial_year', $financialYear)
    //         ->exists();

    //     if (!$hasData) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'No appraisal data found for the selected financial year.'
    //         ], 404);
    //     }

    //     $salary = $employee->salary ?? 0;
    //     $companyIncrementPercent = 20;
    //     $incrementAmount = ($salary * $companyIncrementPercent) / 100;

    //     // Reviews
    //     $adminReviewData = AdminReviewTable::join('super_add_users', function ($join) use ($financialYear) {
    //         $join->on('admin_review_tables.emp_id', '=', 'super_add_users.employee_id')
    //             ->where('admin_review_tables.financial_year', '=', $financialYear);
    //     })
    //         ->where('super_add_users.financial_year', $financialYear)
    //         ->where('super_add_users.employee_id', $employeeIdentifier)
    //         ->pluck('AdminTotalReview')
    //         ->toArray();

    //     $evaluationScore = evaluationTable::join('super_add_users', function ($join) use ($financialYear) {
    //         $join->on('evaluation_tables.emp_id', '=', 'super_add_users.employee_id')
    //             ->where('evaluation_tables.financial_year', '=', $financialYear);
    //     })
    //         ->where('super_add_users.financial_year', $financialYear)
    //         ->where('super_add_users.employee_id', $employeeIdentifier)
    //         ->pluck('total_scoring_system')
    //         ->toArray();

    //     $hrReviewData = HrReviewTable::join('super_add_users', function ($join) use ($financialYear) {
    //         $join->on('hr_review_tables.emp_id', '=', 'super_add_users.employee_id')
    //             ->where('hr_review_tables.financial_year', '=', $financialYear);
    //     })
    //         ->where('super_add_users.financial_year', $financialYear)
    //         ->where('super_add_users.employee_id', $employeeIdentifier)
    //         ->pluck('HrTotalReview')
    //         ->toArray();

    //     $managerReviewData = ManagerReviewTable::join('super_add_users', function ($join) use ($financialYear) {
    //         $join->on('manager_review_tables.emp_id', '=', 'super_add_users.employee_id')
    //             ->where('manager_review_tables.financial_year', '=', $financialYear);
    //     })
    //         ->where('super_add_users.financial_year', $financialYear)
    //         ->where('super_add_users.employee_id', $employeeIdentifier)
    //         ->pluck('ManagerTotalReview')
    //         ->toArray();



    //     // $clientReviewData = ClientReviewTable::join('super_add_users', function ($join) use ($financialYear) {
    //     //     $join->on('client_review_tables.emp_id', '=', 'super_add_users.employee_id')
    //     //         ->where('client_review_tables.financial_year', '=', $financialYear);
    //     // })
    //     //     ->where('super_add_users.financial_year', $financialYear)
    //     //     ->where('super_add_users.employee_id', $employeeIdentifier)
    //     //     ->pluck('ClientTotalReview')
    //     //     ->toArray();
    //     $clientReviewData = [];
    //     $numberOfClients = 0;

    //     $userRoles = json_decode($employee->user_roles, true);
    //     $hasClient = is_array($userRoles) && in_array('client', $userRoles);

    //     if ($hasClient) {
    //         $clientReviews = ClientReviewTable::where('emp_id', $employeeIdentifier)
    //             ->where('financial_year', $financialYear)
    //             ->get();

    //         if ($clientReviews->isNotEmpty()) {
    //             $clientScores = [];

    //             foreach ($clientReviews as $review) {
    //                 $clientScores[$review->client_id][] = $review->ClientTotalReview;
    //             }

    //             $numberOfClients = count($clientScores);

    //             // Average each client's reviews
    //             $averagedClientScores = array_map(function ($scores) {
    //                 $validScores = array_filter($scores, fn($s) => is_numeric($s) && $s > 0);
    //                 return count($validScores) ? array_sum($validScores) / count($validScores) : 'Pending';
    //             }, $clientScores);

    //             // Final average from valid client averages
    //             $validFinalScores = array_filter($averagedClientScores, fn($s) => is_numeric($s));
    //             $clientFinalAverage = count($validFinalScores)
    //                 ? round(array_sum($validFinalScores) / count($validFinalScores), 2)
    //                 : 'Pending';

    //             $clientReviewData[] = is_numeric($clientFinalAverage)
    //                 ? min(($clientFinalAverage / 100) * 100, 100)
    //                 : 'Pending';
    //         } else {
    //             $clientReviewData[] = 'Pending';
    //         }

    //         $reviewArrays['client'] = $clientReviewData;
    //     }


    //     // Map review scores
    //     $adminReviewData = !empty($adminReviewData) ?
    //         array_map(fn($score) => $score > 0 ? min(($score / 45) * 100, 100) : 'Pending', $adminReviewData)
    //         : ['Pending'];


    //     $hrReviewData = !empty($hrReviewData) ?
    //         array_map(fn($score) => $score > 0 ? min(($score / 30) * 100, 100) : 'Pending', $hrReviewData)
    //         : ['Pending'];

    //     $managerReviewData = !empty($managerReviewData) ?
    //         array_map(fn($score) => $score > 0 ? min(($score / 35) * 100, 100) : 'Pending', $managerReviewData)
    //         : ['Pending'];

    //     $evaluationScore = !empty($evaluationScore)
    //         ? array_map(fn($score) => $score > 0 ? min(($score / 100) * 100, 100) : 'Pending', $evaluationScore)
    //         : ['Pending'];

    //     $reviewArrays['evaluation'] = $evaluationScore;


    //     // Admin review — use raw score as-is
    //     // $adminReviewData = !empty($adminReviewData)
    //     //     ? array_map(fn($score) => $score > 0 ? $score : 'Pending', $adminReviewData)
    //     //     : ['Pending'];

    //     // // HR review — use raw score as-is
    //     // $hrReviewData = !empty($hrReviewData)
    //     //     ? array_map(fn($score) => $score > 0 ? $score : 'Pending', $hrReviewData)
    //     //     : ['Pending'];

    //     // // Manager review — use raw score as-is
    //     // $managerReviewData = !empty($managerReviewData)
    //     //     ? array_map(fn($score) => $score > 0 ? $score : 'Pending', $managerReviewData)
    //     //     : ['Pending'];

    //     // $reviewArrays = [
    //     //     'admin' => $adminReviewData,
    //     //     'hr' => $hrReviewData,
    //     //     'manager' => $managerReviewData,
    //     // ];

    //     // Client review
    //     $hasClient = false;
    //     $userRoles = json_decode($employee->user_roles, true);

    //     if (is_array($userRoles) && in_array('client', $userRoles)) {
    //         $hasClient = true;
    //         $clientReviewData = !empty($clientReviewData)
    //             ? array_map(fn($score) => $score > 0 ? min(($score / 100) * 100, 100) : 'Pending', $clientReviewData)
    //             : ['Pending'];
    //         $reviewArrays['client'] = $clientReviewData;
    //     }

    //     // dd($evaluationScore, $adminReviewData, $hrReviewData, $managerReviewData, $clientReviewData);
    //     $finalSalaries = [];
    //     $reviewCount = max(array_map(fn($arr) => count($arr), $reviewArrays));


    //     for ($i = 0; $i < $reviewCount; $i++) {
    //         $scores = [];
    //         foreach ($reviewArrays as $reviews) {
    //             if (isset($reviews[$i]) && is_numeric($reviews[$i])) {
    //                 $scores[] = $reviews[$i];
    //             }
    //         }
    //         // $avgScore = count($scores) > 0 ? array_sum($scores) / count($scores) : 0;
    //         // $appraisalAmount = ($incrementAmount * $avgScore) / 100;
    //         // $finalSalary = round($salary + $appraisalAmount, 2);
    //         // $finalSalaries[] = $finalSalary;
    //     }

    //     return response()->json([
    //         'employee_name' => "{$employee->fname} {$employee->lname}",
    //         'adminReviewData' => $adminReviewData,
    //         'hrReviewData' => $hrReviewData,
    //         'managerReviewData' => $managerReviewData,
    //         'clientReviewData' => $hasClient ? ($clientReviewData ?? ['Pending']) : [],
    //         'evaluationScore' => $evaluationScore,
    //         'salary' => $salary,
    //         'incrementAmount' => $incrementAmount,
    //         'finalSalaries' => $finalSalaries,
    //         'showClientColumn' => $hasClient,
    //         'status' => 'success'
    //     ]);
    // }



    public function getAppraisalData(Request $request)
    {
        $employeeQuery = trim($request->query('employee_query', ''));
        $financialYear = trim($request->query('financial_year', ''));
        $financialYear = str_replace('/', '-', $financialYear);

        // Validate financial year
        if ($financialYear && preg_match('/(\d{4})-(\d{4})/', $financialYear, $matches)) {
            $startDate = "{$matches[1]}-04-01";
            $endDate = "{$matches[2]}-03-31";
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid or missing financial year'], 400);
        }

        // Validate employee
        if (empty($employeeQuery)) {
            return response()->json(['error' => 'Employee ID or Name is required'], 400);
        }

        $query = SuperAddUser::query();
        // $query->where(function ($q) use ($employeeQuery) {
        //     $q->whereRaw("LOWER(TRIM(employee_id)) LIKE LOWER(?)", ["%{$employeeQuery}%"])
        //         ->orWhereRaw("LOWER(TRIM(fname)) LIKE LOWER(?)", ["%{$employeeQuery}%"])
        //         ->orWhereRaw("LOWER(TRIM(lname)) LIKE LOWER(?)", ["%{$employeeQuery}%"])
        //         ->orWhereRaw("LOWER(CONCAT(TRIM(fname), ' ', TRIM(lname))) LIKE LOWER(?)", ["%{$employeeQuery}%"]);
        // });
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

        // Collect Reviews
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

            // dd($managerReviewData);
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

        // Combine all scores
        $allScores = array_merge(
            $adminReviewData,
            $hrReviewData,
            $managerReviewData,
            $evaluationScore,
            $clientReviewData
        );

        $validScores = array_filter($allScores, fn($s) => is_numeric($s));
        $appraisalScore = count($validScores) > 0 ? round(array_sum($validScores) / count($validScores), 2) : 'Pending';
        // dd($appraisalScore);

        return response()->json([
            'employee_name' => "{$employee->fname} {$employee->lname}",
            'adminReviewData' => $adminReviewData,
            'hrReviewData' => $hrReviewData,
            'managerReviewData' => $managerReviewData,
            'clientReviewData' => $hasClient ? ($clientReviewData ?? ['Pending']) : [],
            'evaluationScore' => $evaluationScore,
            'showClientColumn' => $hasClient,
            'appraisal_score' => $appraisalScore,
            'status' => 'success'
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


    // public function getFinancialData(Request $request)
    // {
    //     try {
    //         // Get and sanitize inputs
    //         $searchInput = strtolower(trim($request->input('search')));
    //         $financialYear = trim($request->query('financial_year', ''));

    //         Log::info("Searching employee:", ['input' => $searchInput]);

    //         $employee = SuperAddUser::where(function ($query) use ($searchInput) {
    //             $searchInput = strtolower($searchInput);
    //             $query->whereRaw("LOWER(employee_id) LIKE ?", ["%{$searchInput}%"])
    //                 ->orWhereRaw("LOWER(CONCAT(fname, ' ', lname)) LIKE ?", ["%{$searchInput}%"]);
    //         })->first();

    //         if (!$employee) {
    //             return response()->json(['status' => 'error', 'message' => 'No employee found.'], 404);
    //         }

    //         $employeeIdentifier = $employee->emp_id ?? $employee->employee_id;

    //         // Check if any appraisal/review data exists for the employee for this financial year
    //         $hasData =
    //             evaluationTable::join('super_add_users', function ($join) use ($financialYear) {
    //                 $join->on('evaluation_tables.emp_id', '=', 'super_add_users.employee_id')
    //                     ->where('evaluation_tables.financial_year', '=', $financialYear);
    //             })
    //             ->where('super_add_users.financial_year', $financialYear)
    //             ->where('super_add_users.employee_id', $employeeIdentifier)
    //             ->exists() ||

    //             AdminReviewTable::join('super_add_users', function ($join) use ($financialYear) {
    //                 $join->on('admin_review_tables.emp_id', '=', 'super_add_users.employee_id')
    //                     ->where('admin_review_tables.financial_year', '=', $financialYear);
    //             })
    //             ->where('super_add_users.financial_year', $financialYear)
    //             ->where('super_add_users.employee_id', $employeeIdentifier)
    //             ->exists() ||

    //             HrReviewTable::join('super_add_users', function ($join) use ($financialYear) {
    //                 $join->on('hr_review_tables.emp_id', '=', 'super_add_users.employee_id')
    //                     ->where('hr_review_tables.financial_year', '=', $financialYear);
    //             })
    //             ->where('super_add_users.financial_year', $financialYear)
    //             ->where('super_add_users.employee_id', $employeeIdentifier)
    //             ->exists() ||

    //             ManagerReviewTable::join('super_add_users', function ($join) use ($financialYear) {
    //                 $join->on('manager_review_tables.emp_id', '=', 'super_add_users.employee_id')
    //                     ->where('manager_review_tables.financial_year', '=', $financialYear);
    //             })
    //             ->where('super_add_users.financial_year', $financialYear)
    //             ->where('super_add_users.employee_id', $employeeIdentifier)
    //             ->exists() ||

    //             ClientReviewTable::join('super_add_users', function ($join) use ($financialYear) {
    //                 $join->on('client_review_tables.emp_id', '=', 'super_add_users.employee_id')
    //                     ->where('client_review_tables.financial_year', '=', $financialYear);
    //             })
    //             ->where('super_add_users.financial_year', $financialYear)
    //             ->where('super_add_users.employee_id', $employeeIdentifier)
    //             ->exists();


    //             if (!$hasData) {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'No appraisal data found for the selected financial year.'
    //                 ], 404);
    //             }

    //             // Fetch review scores from different tables
    //             $adminReviewScores = AdminReviewTable::where('emp_id', $employeeIdentifier)
    //             ->where('financial_year', $financialYear)
    //             ->pluck('AdminTotalReview')
    //             ->toArray();

    //             $hrReviewScores = HrReviewTable::where('emp_id', $employeeIdentifier)
    //             ->where('financial_year', $financialYear)
    //             ->pluck('HrTotalReview')
    //             ->toArray();

    //             $managerReviewScores = ManagerReviewTable::where('emp_id', $employeeIdentifier)
    //             ->where('financial_year', $financialYear)
    //             ->pluck('ManagerTotalReview')
    //             ->toArray();

    //             $total_scoring_system = evaluationTable::where('emp_id', $employeeIdentifier)
    //             ->where('financial_year', $financialYear)
    //             ->pluck('total_scoring_system')
    //             ->toArray();

    //             // Decode user roles to check for client role
    //             $userRoles = json_decode($employee->user_roles, true);
    //             $hasClientRole = is_array($userRoles) && in_array('client', $userRoles);

    //             $clientReviewScores = [];
    //             if ($hasClientRole) {
    //                 $clientReviewScores = ClientReviewTable::where('emp_id', $employeeIdentifier)
    //                 ->where('financial_year', $financialYear)
    //                 ->pluck('ClientTotalReview')
    //                 ->toArray();
    //             }

    //             // Normalize scores to percentage scale
    //             $adminReviewData = array_map(fn($score) => min(($score / 45) * 100, 100), $adminReviewScores);
    //             $hrReviewData = array_map(fn($score) => min(($score / 30) * 100, 100), $hrReviewScores);
    //             $managerReviewData = array_map(fn($score) => min(($score / 35) * 100, 100), $managerReviewScores);
    //             $clientReviewData = $hasClientRole
    //             ? (!empty($clientReviewScores) ? array_map(fn($score) => min(($score / 100) * 100, 100), $clientReviewScores) : [0])
    //             : [];

    //             // Merge all review scores including total scoring system
    //             $totalReviewScores = array_merge($adminReviewData, $hrReviewData, $managerReviewData, $clientReviewData, $total_scoring_system);
    //             // dd($clientReviewData);

    //         // Calculate average percentage; fallback if no scores present
    //         $avgReviewPercentage = !empty($totalReviewScores) ? array_sum($totalReviewScores) / count($totalReviewScores) : 0;

    //         // Actually fetch total_scoring_system from evaluation table as main evaluation score
    //         $avgReviewPercentage = evaluationTable::where('emp_id', $employeeIdentifier)
    //             ->where('financial_year', $financialYear)
    //             ->value('total_scoring_system');

    //         if ($avgReviewPercentage === null) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'No evaluation score found for the selected financial year.'
    //             ], 404);
    //         }

    //         // Determine previous financial year to get base salary
    //         if (preg_match('/(\d{4})-(\d{4})/', $financialYear, $matches)) {
    //             $previousFinancialYear = ($matches[1] - 1) . '-' . $matches[1];
    //         } else {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid financial year format.'
    //             ], 400);
    //         }

    //         // ✔ Use final_salary from previous appraisal if available
    //         $previousAppraisal = FinancialData::where('emp_id', $employeeIdentifier)
    //             ->where('financial_year', $previousFinancialYear)
    //             ->orderByDesc('apprisal_date')
    //             ->first();

    //         $baseSalary = ($previousAppraisal && is_numeric($previousAppraisal->final_salary))
    //             ? (float) $previousAppraisal->final_salary
    //             : (float) $employee->salary;

    //         $companyPercentage = (float) $employee->company_percentage;

    //         // Calculate salary increments and final salary
    //         $updatedSalary = ($baseSalary * ($companyPercentage / 100));
    //         $appraisalAmount = $updatedSalary * ($avgReviewPercentage / 100);
    //         $finalSalary = $baseSalary + $updatedSalary + $appraisalAmount;

    //         // Round final salary
    //         $finalSalary = $this->roundSalary($finalSalary);

    //         // Update employee final salary
    //         $employee->update(['final_salary' => $finalSalary]);
    //         $isAlreadySaved = $employee->final_salary == $finalSalary;

    //         // Check if already appraised this year
    //         $alreadyAppraised = FinancialData::where('emp_id', $employeeIdentifier)
    //             ->whereYear('apprisal_date', now()->year)
    //             ->exists();

    //         return response()->json([
    //             'employee_name'       => "{$employee->fname} {$employee->lname}",
    //             'employee_id'         => $employee->employee_id,
    //             'evaluationScore'     => $avgReviewPercentage,
    //             'hrReviewData'        => $hrReviewData,
    //             'adminReviewData'     => $adminReviewData,
    //             'managerReviewData'   => $managerReviewData,
    //             'clientReviewData'    => $clientReviewData,
    //             'salary'              => $baseSalary,
    //             'company_percentage'  => $companyPercentage,
    //             'updatedSalary'       => $updatedSalary,
    //             'appraisalAmount'     => $appraisalAmount,
    //             'finalSalary'         => $finalSalary,
    //             'appraisalDate'       => now()->toDateString(),
    //             'isAlreadySaved'      => $isAlreadySaved,
    //             'alreadyAppraised'    => $alreadyAppraised
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error("Error fetching financial data:", ['error' => $e->getMessage()]);
    //         return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
    //     }
    // }




    public function getFinancialData(Request $request)
    {
        try {
            // Get and sanitize inputs
            $searchInput = strtolower(trim($request->input('search')));
            $financialYear = trim($request->query('financial_year', ''));

            Log::info("Searching employee:", ['input' => $searchInput]);

            // Fetch employee
            // $employee = SuperAddUser::where(function ($query) use ($searchInput) {
            //     $query->whereRaw("LOWER(employee_id) LIKE ?", ["%{$searchInput}%"])
            //         ->orWhereRaw("LOWER(CONCAT(fname, ' ', lname)) LIKE ?", ["%{$searchInput}%"]);
            // })->first();
            $employee = SuperAddUser::where(function ($query) use ($searchInput) {
                $lowerInput = strtolower(trim($searchInput));

                $query->whereRaw("LOWER(employee_id) = ?", [$lowerInput])
                    ->orWhereRaw("LOWER(CONCAT(TRIM(fname), ' ', TRIM(lname))) LIKE ?", ["%$lowerInput%"]);
            })->first();



            if (!$employee) {
                return response()->json(['status' => 'error', 'message' => 'No employee found.'], 404);
            }

            $employeeIdentifier = $employee->emp_id ?? $employee->employee_id;

            // Decode user roles to check for client role
            $userRoles = json_decode($employee->user_roles, true);
            $hasClientRole = is_array($userRoles) && in_array('client', $userRoles);

            // Prepare client review data
            $clientReviewDetails = ClientReviewTable::join('all_clients', 'client_review_tables.client_id', '=', 'all_clients.id')
                ->where('client_review_tables.emp_id', $employeeIdentifier)
                ->where('client_review_tables.financial_year', $financialYear)
                ->select(
                    'client_review_tables.client_id',
                    DB::raw('AVG(client_review_tables.ClientTotalReview) as avg_score')
                )
                ->groupBy('client_review_tables.client_id')
                ->get();

            // Check if any appraisal/review data exists
            $hasData =
                evaluationTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->exists() ||
                AdminReviewTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->exists() ||
                HrReviewTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->exists() ||
                ManagerReviewTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->exists() ||
                ($hasClientRole && $clientReviewDetails && $clientReviewDetails->isNotEmpty());

            if (!$hasData) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No appraisal data found for the selected financial year.'
                ], 404);
            }

            // Review Scores
            $adminReviewScores = AdminReviewTable::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $financialYear)
                ->pluck('AdminTotalReview')
                ->toArray();

            $hrReviewScores = HrReviewTable::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $financialYear)
                ->pluck('HrTotalReview')
                ->toArray();

            // $managerReviewScores = ManagerReviewTable::where('emp_id', $employeeIdentifier)
            //     ->where('financial_year', $financialYear)
            //     ->pluck('ManagerTotalReview')
            //     ->toArray();

            $managerReviewScores = ManagerReviewTable::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $financialYear)
                ->pluck('ManagerTotalReview');

            $avgManagerReview = $managerReviewScores->isNotEmpty()
                ? round(min(($managerReviewScores->avg() / 35) * 100, 100), 2)
                : 0;

            // Normalize scores
            $adminReviewData = array_map(fn($score) => min(($score / 45) * 100, 100), $adminReviewScores);
            $hrReviewData = array_map(fn($score) => min(($score / 30) * 100, 100), $hrReviewScores);
            // $avgManagerReview = array_map(fn($score) => min(($score / 35) * 100, 100), $managerReviewScores);


            // Use raw scores as-is
            // $adminReviewData = array_map(fn($score) => $score > 0 ? $score : 'Pending', $adminReviewScores);
            // $hrReviewData = array_map(fn($score) => $score > 0 ? $score : 'Pending', $hrReviewScores);
            // $managerReviewData = array_map(fn($score) => $score > 0 ? $score : 'Pending', $managerReviewScores);




            // ✅ Calculate overall average client review
            $totalClientReviewScore = $hasClientRole && $clientReviewDetails->isNotEmpty()
                ? round($clientReviewDetails->avg('avg_score'), 2)
                : 0;

            $clientReviewData = $totalClientReviewScore;

            // Get main evaluation score
            $avgReviewPercentage = evaluationTable::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $financialYear)
                ->value('total_scoring_system');

            if ($avgReviewPercentage === null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No evaluation score found for the selected financial year.'
                ], 404);
            }

            // Previous financial year
            if (preg_match('/(\d{4})-(\d{4})/', $financialYear, $matches)) {
                $previousFinancialYear = ($matches[1] - 1) . '-' . $matches[1];
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid financial year format.'
                ], 400);
            }

            // Base Salary
            $previousAppraisal = FinancialData::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $previousFinancialYear)
                ->orderByDesc('apprisal_date')
                ->first();

            $baseSalary = ($previousAppraisal && is_numeric($previousAppraisal->final_salary))
                ? (float) $previousAppraisal->final_salary
                : (float) $employee->salary;

            $companyPercentage = (float) $employee->company_percentage;

            // Salary Calculations
            $updatedSalary = (int)$baseSalary * ($companyPercentage / 100);
            $appraisalAmount = (int) $updatedSalary * ($avgReviewPercentage / 100);
            $finalSalary = (int)$this->roundSalary($baseSalary + $updatedSalary + $appraisalAmount);

            // Save final salary
            $employee->update(['final_salary' => $finalSalary]);
            $isAlreadySaved = $employee->final_salary == $finalSalary;

            $alreadyAppraised = FinancialData::where('emp_id', $employeeIdentifier)
                ->whereYear('apprisal_date', now()->year)
                ->exists();

            // ✅ Final JSON response
            return response()->json([
                'employee_name'       => "{$employee->fname} {$employee->lname}",
                'employee_id'         => $employee->employee_id,
                'evaluationScore'     => $avgReviewPercentage,
                'hrReviewData'        => $hrReviewData,
                'adminReviewData'     => $adminReviewData,
                'managerReviewData'   => $avgManagerReview,
                'clientReviewData'    => $clientReviewData,
                'salary'              => (int)$baseSalary,
                'company_percentage'  => $companyPercentage,
                'updatedSalary'       => (int)$updatedSalary,
                'appraisalAmount'     => (int)$appraisalAmount,
                'finalSalary'         => (int)$finalSalary,
                'appraisalDate'       => now()->toDateString(),
                'isAlreadySaved'      => $isAlreadySaved,
                'alreadyAppraised'    => $alreadyAppraised
            ]);

            // dd($response);
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
            $clientReview = ClientReviewTables::where('emp_id', $empId)
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
