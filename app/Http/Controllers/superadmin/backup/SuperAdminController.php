<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\AdminReviewTable;
use App\Models\ApprisalTable;
use App\Models\ClientReviewTable;
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


    // public function testEmail()
    // {
    //     $otp = rand(100000, 999999);  // Generate a random OTP for testing

    //     try {
    //         // Sending the email
    //         Mail::to('amar.maity@delostylestudio.com')->send(new OtpMail($otp));
    //         return 'Test email sent!';
    //     } catch (\Exception $e) {
    //         return 'Failed to send email: ' . $e->getMessage();
    //     }
    // }    



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
        $employeeId = trim($request->query('employee_id', ''));
        $employeeName = trim($request->query('employee_name', ''));
        $financialYear = trim($request->query('financial_year', ''));
        $financialYear = str_replace('/', '-', $financialYear);

        // dd($financialYear);

        // Validate the financial year format
        if ($financialYear && preg_match('/(\d{4})-(\d{4})/', $financialYear, $matches)) {
            $startDate = "{$matches[1]}-04-01";
            $endDate = "{$matches[2]}-03-31";
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid or missing financial year'], 400);
        }

        // Validate the presence of employee ID or name
        if (empty($employeeId) && empty($employeeName)) {
            return response()->json(['error' => 'Employee ID or Name is required'], 400);
        }

        // Build query to find the employee
        $query = SuperAddUser::query();

        if (!empty($employeeId)) {
            $query->whereRaw("LOWER(TRIM(employee_id)) LIKE LOWER(?)", ["%{$employeeId}%"]);
        }

        if (!empty($employeeName)) {
            $query->where(function ($q) use ($employeeName) {
                $q->whereRaw("LOWER(TRIM(fname)) LIKE LOWER(?)", ["%{$employeeName}%"])
                    ->orWhereRaw("LOWER(TRIM(lname)) LIKE LOWER(?)", ["%{$employeeName}%"])
                    ->orWhereRaw("LOWER(CONCAT(TRIM(fname), ' ', TRIM(lname))) LIKE LOWER(?)", ["%{$employeeName}%"]);
            });
        }

        // Fetch the employee data
        $employee = $query->first();

        if (!$employee) {
            Log::error("Employee not found with employee_id: $employeeId");
            return response()->json(['status' => 'error', 'message' => 'No employee found.'], 404);
        }

        $employeeIdentifier = $employee->emp_id ?? $employee->employee_id;

        // Check if the employee has data for the selected financial year
        $hasData = SuperAddUser::where('employee_id', $employeeIdentifier)
            ->where('financial_year', $financialYear)
            ->exists();

        if (!$hasData) {
            return response()->json([
                'status' => 'error',
                'message' => 'No appraisal data found for the selected financial year.'
            ], 404);
        }

        // Fetch salary details
        $salary = $employee->salary ?? 0;
        $companyIncrementPercent = 20;
        $incrementAmount = ($salary * $companyIncrementPercent) / 100;

        // Fetch the review data from different tables
        $adminReviewData = AdminReviewTable::join('super_add_users', function ($join) use ($financialYear) {
            $join->on('admin_review_tables.emp_id', '=', 'super_add_users.employee_id')
                ->where('admin_review_tables.financial_year', '=', $financialYear);
        })
            ->where('super_add_users.financial_year', $financialYear)
            ->where('super_add_users.employee_id', $employeeIdentifier)
            ->pluck('AdminTotalReview')
            ->toArray();

        // evalluation score
        $evaluationScore = evaluationTable::join('super_add_users', function ($join) use ($financialYear) {
            $join->on('evaluation_tables.emp_id', '=', 'super_add_users.employee_id')
                ->where('evaluation_tables.financial_year', '=', $financialYear);
        })
            ->where('super_add_users.financial_year', $financialYear)
            ->where('super_add_users.employee_id', $employeeIdentifier)
            ->pluck('total_scoring_system')
            ->toArray();

        $hrReviewData = HrReviewTable::join('super_add_users', function ($join) use ($financialYear) {
            $join->on('hr_review_tables.emp_id', '=', 'super_add_users.employee_id')
                ->where('hr_review_tables.financial_year', '=', $financialYear);
        })
            ->where('super_add_users.financial_year', $financialYear)
            ->where('super_add_users.employee_id', $employeeIdentifier)
            ->pluck('HrTotalReview')
            ->toArray();

        $managerReviewData = ManagerReviewTable::join('super_add_users', function ($join) use ($financialYear) {
            $join->on('manager_review_tables.emp_id', '=', 'super_add_users.employee_id')
                ->where('manager_review_tables.financial_year', '=', $financialYear);
        })
            ->where('super_add_users.financial_year', $financialYear)
            ->where('super_add_users.employee_id', $employeeIdentifier)
            ->pluck('ManagerTotalReview')
            ->toArray();

        $clientReviewData = ClientReviewTable::join('super_add_users', function ($join) use ($financialYear) {
            $join->on('client_review_tables.emp_id', '=', 'super_add_users.employee_id')
                ->where('client_review_tables.financial_year', '=', $financialYear);
        })
            ->where('super_add_users.financial_year', $financialYear)
            ->where('super_add_users.employee_id', $employeeIdentifier)
            ->pluck('ClientTotalReview')
            ->toArray();

        // Check if the employee has a client
        $hasClient = !empty($employee->client_id); // Modify based on how you store client data


        // Map the review data with proper values
        $adminReviewData = !empty($adminReviewData) ?
            array_map(fn($score) => $score > 0 ? min(($score / 45) * 100, 100) : 'Pending', $adminReviewData)
            : ['Pending'];

        $hrReviewData = !empty($hrReviewData) ?
            array_map(fn($score) => $score > 0 ? min(($score / 30) * 100, 100) : 'Pending', $hrReviewData)
            : ['Pending'];

        $managerReviewData = !empty($managerReviewData) ?
            array_map(fn($score) => $score > 0 ? min(($score / 35) * 100, 100) : 'Pending', $managerReviewData)
            : ['Pending'];

        // Prepare the review arrays for response
        $reviewArrays = [
            'admin' => $adminReviewData,
            'hr' => $hrReviewData,
            'manager' => $managerReviewData,
        ];



        // Always handle the client review if the employee has a client
        $hasClient = false;
        $userRoles = json_decode($employee->user_roles, true);

        if (is_array($userRoles) && in_array('client', $userRoles)) {
            $hasClient = true;

            $clientReviewData = !empty($clientReviewData)
                ? array_map(fn($score) => $score > 0 ? min(($score / 100) * 100, 100) : 'Pending', $clientReviewData)
                : ['Pending'];

            $reviewArrays['client'] = $clientReviewData;
        }

        // dd($clientReviewData);

        $finalSalaries = [];
        $reviewCount = 1;

        for ($i = 0; $i < $reviewCount; $i++) {
            $scores = [];

            foreach ($reviewArrays as $reviews) {
                if (isset($reviews[$i]) && is_numeric($reviews[$i])) {
                    $scores[] = $reviews[$i];
                }
            }

            $avgScore = count($scores) > 0 ? array_sum($scores) / count($scores) : 0;
            $appraisalAmount = ($incrementAmount * $avgScore) / 100;
            $finalSalary = round($salary + $appraisalAmount, 2);
            $finalSalaries[] = $finalSalary;
        }

        // Return the response
        return response()->json([
            'employee_name' => "{$employee->fname} {$employee->lname}",
            'adminReviewData' => $adminReviewData,
            'hrReviewData' => $hrReviewData,
            'managerReviewData' => $managerReviewData,
            'clientReviewData' => $hasClient ? ($clientReviewData ?? ['Pending']) : [],
            'evaluationScore' => $evaluationScore,
            'salary' => $salary,
            'incrementAmount' => $incrementAmount,
            'finalSalaries' => $finalSalaries,
            'showClientColumn' => $hasClient, // This flag will help to display the client column
            'status' => 'success'
        ]);
    }





    //Financial Year DropDown Handle In Appraisal Page

    // public function validateFinancialYear(Request $request)
    // {
    //     $financialYear = trim($request->query('financial_year', ''));

    //     // Parse the financial year and determine the start and end date
    //     if ($financialYear && preg_match('/(\d{4})\/(\d{4})/', $financialYear, $matches)) {
    //         $startDate = "{$matches[1]}-04-01";
    //         $endDate = "{$matches[2]}-03-31";
    //     } else {
    //         return response()->json(['status' => 'error', 'message' => 'Invalid financial year format.'], 400);
    //     }

    //     // Check if the financial year exists in each required table
    //     $superAddUserExists = SuperAddUser::where('financial_year', $financialYear)->exists();
    //     $managerReviewExists = ManagerReviewTable::where('financial_year', $financialYear)->exists();
    //     $hrReviewExists = HrReviewTable::where('financial_year', $financialYear)->exists();
    //     $clientReviewExists = ClientReviewTable::where('financial_year', $financialYear)->exists();
    //     $adminReviewExists = AdminReviewTable::where('financial_year', $financialYear)->exists();

    //     // If any of the tables does not have data for the selected financial year
    //     if (!$superAddUserExists || !$managerReviewExists || !$hrReviewExists || !$clientReviewExists || !$adminReviewExists) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Data mismatch: The selected financial year does not have data in all required tables.'
    //         ], 400);
    //     }

    //     // If everything is fine
    //     return response()->json(['status' => 'success']);
    // }



    //Action Column
    public function toggleStatus($id)
    {
        $user = SuperAddUser::where('employee_id', $id)->first();

        if (!$user) {
            Log::error("User not found: ID $id");
            return response()->json(['success' => false, 'error' => 'User not found'], 404);
        }

        // Toggle status
        $user->status = !$user->status;
        $user->save();

        Log::info("User status updated", [
            'user_id' => $id,
            'new_status' => $user->status
        ]);

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
            $employeeId = trim($request->employee_id);
            $employeeName = trim($request->employee_name);
            // $financialYear = trim($request->financial_year);
            $financialYear = trim($request->query('financial_year', ''));
            $financialYear = str_replace('/', '-', $financialYear);

            // if ($financialYear && preg_match('/(\d{4})\/(\d{4})/', $financialYear, $matches)) {
            //     $startDate = "{$matches[1]}-04-01";
            //     $endDate = "{$matches[2]}-03-31";
            // } else {
            //     return response()->json(['status' => 'error', 'message' => 'Invalid or missing financial year'], 400);
            // }


            if ($financialYear && preg_match('/(\d{4})-(\d{4})/', $financialYear, $matches)) {
                $startDate = "{$matches[1]}-04-01";
                $endDate = "{$matches[2]}-03-31";
            } else {
                return response()->json(['status' => 'error', 'message' => 'Invalid or missing financial year'], 400);
            }



            Log::info("Fetching financial data for:", ['employee_id' => $employeeId, 'employee_name' => $employeeName]);

            // Search employee by ID or concatenated name
            $employee = SuperAddUser::when($employeeId, function ($query, $id) {
                return $query->where('employee_id', $id);
            })
                ->when($employeeName, function ($query, $name) {
                    return $query->whereRaw("CONCAT(fname, ' ', lname) LIKE ?", ["%{$name}%"]);
                })
                ->first();

            if (!$employee) {
                Log::error("Employee not found with employee_id: $employeeId");
                return response()->json(['status' => 'error', 'message' => 'No employee found.'], 404);
            }

            Log::info("Employee Found:", ['employee_id' => $employee->employee_id, 'emp_id' => $employee->emp_id]);

            $employeeIdentifier = $employee->emp_id ?? $employee->employee_id;

            $hasData =

                evaluationTable::join('super_add_users', function ($join) use ($financialYear) {
                    $join->on('evaluation_tables.emp_id', '=', 'super_add_users.employee_id')
                        ->where('evaluation_tables.financial_year', '=', $financialYear);
                })
                ->where('super_add_users.financial_year', $financialYear)
                ->where('super_add_users.employee_id', $employeeIdentifier)
                ->exists() ||


                AdminReviewTable::join('super_add_users', function ($join) use ($financialYear) {
                    $join->on('admin_review_tables.emp_id', '=', 'super_add_users.employee_id')
                        ->where('admin_review_tables.financial_year', '=', $financialYear);
                })
                ->where('super_add_users.financial_year', $financialYear)
                ->where('super_add_users.employee_id', $employeeIdentifier)
                ->exists() ||

                HrReviewTable::join('super_add_users', function ($join) use ($financialYear) {
                    $join->on('hr_review_tables.emp_id', '=', 'super_add_users.employee_id')
                        ->where('hr_review_tables.financial_year', '=', $financialYear);
                })
                ->where('super_add_users.financial_year', $financialYear)
                ->where('super_add_users.employee_id', $employeeIdentifier)
                ->exists() ||

                ManagerReviewTable::join('super_add_users', function ($join) use ($financialYear) {
                    $join->on('manager_review_tables.emp_id', '=', 'super_add_users.employee_id')
                        ->where('manager_review_tables.financial_year', '=', $financialYear);
                })
                ->where('super_add_users.financial_year', $financialYear)
                ->where('super_add_users.employee_id', $employeeIdentifier)
                ->exists() ||

                ClientReviewTable::join('super_add_users', function ($join) use ($financialYear) {
                    $join->on('client_review_tables.emp_id', '=', 'super_add_users.employee_id')
                        ->where('client_review_tables.financial_year', '=', $financialYear);
                })
                ->where('super_add_users.financial_year', $financialYear)
                ->where('super_add_users.employee_id', $employeeIdentifier)
                ->exists();

            if (!$hasData) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No appraisal data found for the selected financial year.'
                ], 404);
            }

            // Fetch Review Scores
            $adminReviewScores = AdminReviewTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->pluck('AdminTotalReview')->toArray();
            $hrReviewScores = HrReviewTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->pluck('HrTotalReview')->toArray();
            $managerReviewScores = ManagerReviewTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->pluck('ManagerTotalReview')->toArray();
            $total_scoring_system = evaluationTable::where('emp_id', $employeeIdentifier)->where('financial_year', $financialYear)->pluck('total_scoring_system')->toArray();


            $userRoles = json_decode($employee->user_roles, true);
            $hasClientRole = is_array($userRoles) && in_array('client', $userRoles);

            $clientReviewScores = ClientReviewTable::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $financialYear)
                ->pluck('ClientTotalReview')
                ->toArray();

            $adminReviewData = array_map(fn($score) => min(($score / 45) * 100, 100), $adminReviewScores);
            $hrReviewData = array_map(fn($score) => min(($score / 30) * 100, 100), $hrReviewScores);
            $managerReviewData = array_map(fn($score) => min(($score / 35) * 100, 100), $managerReviewScores);

            if ($hasClientRole) {
                $clientReviewData = !empty($clientReviewScores)
                    ? array_map(fn($score) => min(($score / 100) * 100, 100), $clientReviewScores)
                    : [0];
            } else {
                $clientReviewData = [];
            }

            $totalReviewScores = array_merge($adminReviewData, $hrReviewData, $managerReviewData, $clientReviewData, $total_scoring_system);
            $avgReviewPercentage = !empty($totalReviewScores) ? array_sum($totalReviewScores) / count($totalReviewScores) : 0;

            $avgReviewPercentage = evaluationTable::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $financialYear)
                ->value('total_scoring_system');

            if ($avgReviewPercentage === null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No evaluation score found for the selected financial year.'
                ], 404);
            }

            // Check previous year's final salary for base
            $previousFinancialYear = ($matches[1] - 1) . '-' . $matches[1];
            $previousAppraisal = FinancialData::where('emp_id', $employeeIdentifier)
                ->where('financial_year', $previousFinancialYear)
                ->orderByDesc('apprisal_date')
                ->first();

            $baseSalary = $previousAppraisal ? (float) $previousAppraisal->final_salary : (float) $employee->salary;

            $companyPercentage = (float) $employee->company_percentage;

            // Step 1: Calculate Increment Amount
            $updatedSalary = ($baseSalary * ($companyPercentage / 100));

            // Step 2: Correct Calculation of Appraisal Amount
            $appraisalAmount = $updatedSalary * ($avgReviewPercentage / 100);

            // Step 3: Final Salary Calculation
            $finalSalary = $baseSalary + $updatedSalary + $appraisalAmount;

            // Step 4: Round Salary
            $finalSalary = $this->roundSalary($finalSalary);

            // Update Final Salary in Database
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
                'managerReviewData'   => $managerReviewData,
                'clientReviewData'    => $clientReviewData,
                'salary'              => $baseSalary,
                'company_percentage'  => $companyPercentage,
                'updatedSalary'       => $updatedSalary,
                'appraisalAmount'     => $appraisalAmount,
                'finalSalary'         => $finalSalary,
                'appraisalDate'       => now()->toDateString(),
                'isAlreadySaved'      => $isAlreadySaved,
                'alreadyAppraised'    => $alreadyAppraised
            ]);

            // dd(response());
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


        $users = SuperAddUser::where('probation_date', '<=', $currentDate)->orWhere('designation', 'Client')->get();

        // $users = SuperAddUser::whereDate('probation_date', '<=', $currentDate)->get();

        return view('admin.userList', compact('users'));
    }




    public function viewDetailsAll($emp_id)
    {
        $users = [
            'evaluation' => DB::table('evaluation_tables')->where('emp_id', $emp_id)->first(),
            'managerReview' => DB::table('manager_review_tables')->where('emp_id', $emp_id)->first(),
            'adminReview' => DB::table('admin_review_tables')->where('emp_id', $emp_id)->first(),
            'hrReview' => DB::table('hr_review_tables')->where('emp_id', $emp_id)->first(),
            'clientReview' => DB::table('client_review_tables')->where('emp_id', $emp_id)->first(),
            'superAddUser' => DB::table('super_add_users')->where('employee_id', $emp_id)->first()
        ];

        $user_roles = json_decode($users['superAddUser']->user_roles, true);

        if (!array_filter($users)) {
            return redirect()->back()->with('error', 'No review data found for this employee.');
        }

        return view('review/viewDetails', compact('users', 'emp_id', 'user_roles'));
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

        $user = ClientReviewTable::where('emp_id', $emp_id)
            ->where('financial_year', $financialYear)
            ->firstOrFail();

        return view('reports/clientReport', compact('user'));
    }


    //View Probation Period
    public function getProbationPeriod()
    {
        // $user = SuperAddUser::get()->all();
        $user = SuperAddUser::where('Designation', '!=', 'Client')->get();

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
}
