<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\ApprisalTable;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAddUser;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class addUserController extends Controller

{
    //

    public function indexAddUser()
    {
        return view("admin/superAddUserDashBoard");
    }

    // public function addUser(Request $request)
    // {
    //     try {
    //         // 1. Validate request
    //         $validatedData = $request->validate([
    //             'salary' => 'required|numeric',
    //             'email' => [
    //                 'required',
    //                 'email',
    //                 Rule::unique('super_add_users', 'email'),
    //             ],
    //             'employee_id' => [
    //                 'required',
    //                 Rule::unique('super_add_users', 'employee_id'),
    //             ],
    //             'probation_date' => 'required|date',
    //         ], [
    //             'salary.min' => 'Salary must be greater than zero.',
    //             'email.unique' => 'This email is already registered.',
    //             'employee_id.unique' => 'This Employee ID is already registered.',
    //         ]);


    //         $existingUser = SuperAddUser::where('email', $request->email)
    //             ->where('employee_id', $request->employee_id)
    //             ->first();

    //         if ($existingUser) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'errors' => ['email' => 'This email ID is already assigned to this Employee ID.']
    //             ], 422);
    //         }

    //         // 3. Setup probation logic
    //         $probationDate = Carbon::parse($request->input('probation_date'));
    //         $today = Carbon::today();

    //         // Default employee status
    //         $employeeStatus = $probationDate->lte($today) ? 'Employee' : 'Probation Period';


    //         $companyPercentage = null;
    //         $financialYear = null;

    //         $appraisal = ApprisalTable::latest()->first();

    //         if ($appraisal && $appraisal->company_percentage && $appraisal->financial_year) {
    //             [$startYear, $endYear] = explode('/', $appraisal->financial_year);
    //             $startDate = Carbon::createFromDate($startYear, 4, 1)->startOfDay();
    //             $endDate = Carbon::createFromDate($endYear, 3, 31)->endOfDay();
    //             // dd($probationDate->between($startDate, $endDate) , $probationDate->lte($today));
    //             if (($probationDate->between($startDate, $endDate) ||  $probationDate->lte($today)) && $employeeStatus == 'Employee') {
    //                 $companyPercentage = $appraisal->company_percentage;
    //                 $financialYear = $appraisal->financial_year;
    //             }
    //         }

    //         // 4. Create user
    //         SuperAddUser::create([
    //             'fname' => $request->input('fname'),
    //             'lname' => $request->input('lname'),
    //             'dob' => $request->input('dob'),
    //             'gender' => $request->input('gender'),
    //             'mobno' => $request->input('mobno'),
    //             'employee_id' => $request->input('employee_id'),
    //             'evaluation_purpose' =>$request->input('evaluation_purpose'),
    //             'division' => $request->input('division'),
    //             'manager_name' => $request->input('manager_name'),
    //             'department' => $request->input('department'),
    //             'designation' => $request->input('designation'),
    //             'user_type' => $request->input('user_type'),
    //             'user_roles' => json_encode($request->input('user_roles')),
    //             'salary' => $request->input('salary'),
    //             'email' => trim($request->input('email')),
    //             'salary_grade' => $request->input('salary_grade'),
    //             'password' => Hash::make($request->input('password')),
    //             'probation_date' => $probationDate,
    //             'employee_status' => $employeeStatus,
    //             'company_percentage' => $companyPercentage,
    //             'financial_year' => $financialYear,
    //             'status' => 1
    //         ]);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'User saved successfully with probation data!',
    //         ], 200);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $ex) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An unexpected error occurred.',
    //             'debug' => $ex->getMessage()
    //         ], 500);
    //     }
    // }

    // public function addUser(Request $request)
    // {
    //     try {
    //         $isClient = strtolower($request->input('designation')) === 'client';

    //         // 1. Validation rules
    //         $rules = [
    //             'fname' => 'required|string|max:255',
    //             'lname' => 'required|string|max:255',
    //             'dob' => 'required|date',
    //             'gender' => 'required|string',
    //             'email' => [
    //                 'required',
    //                 'email',
    //                 Rule::unique('super_add_users', 'email'),
    //             ],
    //             'password' => 'required|string',
    //         ];

    //         if (!$isClient) {
    //             $rules = array_merge($rules, [
    //                 'employee_id' => [
    //                     'required',
    //                     Rule::unique('super_add_users', 'employee_id'),
    //                 ],
    //                 'salary' => 'required|numeric|min:0',
    //                 'salary_grade' => 'required',
    //                 'probation_date' => 'required|date',
    //                 'evaluation_purpose' => 'required|string',
    //                 'fname' => 'required|string|max:255',
    //                 'lname' => 'required|string|max:255',
    //                 'dob' => 'required|date',
    //                 'gender' => 'required|string',
    //                 'mobno' => 'required|string',
    //                 'division' => 'required|string',
    //                 // 'manager_name' => 'required|string',
    //                 // 'department' => 'required|string',
    //                 'designation' => 'required|string',
    //                 'user_type' => 'required|string',
    //                 // 'user_roles' => 'required|array',
    //                 'email' => [
    //                     'required',
    //                     'email',
    //                     Rule::unique('super_add_users', 'email'),
    //                 ],
    //                 'password' => 'required|string',
    //             ]);
    //         }

    //         // 2. Validate
    //         $validatedData = $request->validate($rules, [
    //             'salary.min' => 'Salary must be greater than zero.',
    //             'email.unique' => 'This email is already registered.',
    //             'employee_id.unique' => 'This Employee ID is already registered.',
    //         ]);

    //         // 3. Check for duplicate email + employee ID (non-clients only)
    //         if (!$isClient) {
    //             $existingUser = SuperAddUser::where('email', $request->email)
    //                 ->where('employee_id', $request->employee_id)
    //                 ->first();

    //             if ($existingUser) {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'errors' => ['email' => 'This email ID is already assigned to this Employee ID.']
    //                 ], 422);
    //             }
    //         }

    //         // 4. Probation & appraisal logic (non-clients only)
    //         $probationDate = $isClient ? null : Carbon::parse($request->input('probation_date'));
    //         $employeeStatus = $isClient ? 'Client' : ($probationDate->lte(Carbon::today()) ? 'Employee' : 'Probation Period');

    //         $companyPercentage = null;
    //         $financialYear = null;

    //         if (!$isClient) {
    //             $appraisal = ApprisalTable::latest()->first();
    //             if ($appraisal && $appraisal->company_percentage && $appraisal->financial_year) {
    //                 [$startYear, $endYear] = explode('/', $appraisal->financial_year);
    //                 $startDate = Carbon::createFromDate($startYear, 4, 1)->startOfDay();
    //                 $endDate = Carbon::createFromDate($endYear, 3, 31)->endOfDay();

    //                 if (($probationDate->between($startDate, $endDate) || $probationDate->lte(Carbon::today())) && $employeeStatus == 'Employee') {
    //                     $companyPercentage = $appraisal->company_percentage;
    //                     $financialYear = $appraisal->financial_year;
    //                 }
    //             }
    //         }

    //         // 5. Create user
    //         SuperAddUser::create([
    //             'fname' => $request->input('fname'),
    //             'lname' => $request->input('lname'),
    //             'dob' => $request->input('dob'),
    //             'gender' => $request->input('gender'),
    //             'mobno' => $request->input('mobno'),
    //             'employee_id' => $isClient ? null : $request->input('employee_id'),
    //             'evaluation_purpose' => $isClient ? null : $request->input('evaluation_purpose'),
    //             'division' => $request->input('division'),
    //             'manager_name' => $request->input('manager_name'),
    //             // 'department' => $request->input('department'),
    //             'designation' => $request->input('designation'),
    //             'user_type' => $request->input('user_type'),
    //             'user_roles' => json_encode($request->input('user_roles')),
    //             'salary' => $isClient ? null : $request->input('salary'),
    //             'salary_grade' => $isClient ? null : $request->input('salary_grade'),
    //             'email' => trim($request->input('email')),
    //             'password' => Hash::make($request->input('password')),
    //             'probation_date' => $probationDate,
    //             'employee_status' => $employeeStatus,
    //             'company_percentage' => $companyPercentage,
    //             'financial_year' => $financialYear,
    //             'status' => 1
    //         ]);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'User saved successfully!',
    //         ], 200);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $ex) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An unexpected error occurred.',
    //             'debug' => $ex->getMessage()
    //         ], 500);
    //     }
    // }


    // public function addUser(Request $request)
    // {
    //     try {
    //         // Check if the user is a client
    //         $isClient = strtolower($request->input('designation')) === 'client';

    //         // 1. Validation rules
    //         $rules = [
    //             'fname' => 'required|string|max:255',
    //             'lname' => 'required|string|max:255',
    //             'dob' => 'required|date',
    //             'gender' => 'required|string',
    //             'email' => [
    //                 'required',
    //                 'email',
    //                 Rule::unique('super_add_users', 'email'),
    //             ],
    //             'password' => 'required|string',
    //         ];

    //         // Additional validation for non-clients
    //         if (!$isClient) {
    //             $rules = array_merge($rules, [
    //                 'employee_id' => [
    //                     'required',
    //                     Rule::unique('super_add_users', 'employee_id'),
    //                 ],
    //                 'salary' => 'required|numeric|min:0',
    //                 'salary_grade' => 'required',
    //                 'probation_date' => 'required|date',
    //                 'evaluation_purpose' => 'required|string',
    //                 'fname' => 'required|string|max:255',
    //                 'lname' => 'required|string|max:255',
    //                 'dob' => 'required|date',
    //                 'gender' => 'required|string',
    //                 'mobno' => 'required|string',
    //                 'division' => 'required|string',
    //                 'designation' => 'required|string',
    //                 'user_type' => 'required|string',
    //                 'email' => [
    //                     'required',
    //                     'email',
    //                     Rule::unique('super_add_users', 'email'),
    //                 ],
    //                 'password' => 'required|string',
    //             ]);
    //         }

    //         // 2. Validate the input data
    //         $validatedData = $request->validate($rules, [
    //             'salary.min' => 'Salary must be greater than zero.',
    //             'email.unique' => 'This email is already registered.',
    //             'employee_id.unique' => 'This Employee ID is already registered.',
    //         ]);

    //         // 3. Check for duplicate email + employee ID (non-clients only)
    //         if (!$isClient) {
    //             $existingUser = SuperAddUser::where('email', $request->email)
    //                 ->where('employee_id', $request->employee_id)
    //                 ->first();

    //             if ($existingUser) {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'errors' => ['email' => 'This email ID is already assigned to this Employee ID.']
    //                 ], 422);
    //             }
    //         }

    //         // 4. Probation & appraisal logic (non-clients only)
    //         $probationDate = $isClient ? null : Carbon::parse($request->input('probation_date'));
    //         $employeeStatus = $isClient ? 'Client' : ($probationDate->lte(Carbon::today()) ? 'Employee' : 'Probation Period');

    //         $companyPercentage = null;
    //         $financialYear = null;

    //         // Check for company percentage and financial year for non-clients
    //         if (!$isClient) {
    //             $appraisal = ApprisalTable::latest()->first();
    //             if ($appraisal && $appraisal->company_percentage && $appraisal->financial_year) {
    //                 [$startYear, $endYear] = explode('-', $appraisal->financial_year);
    //                 $startDate = Carbon::createFromDate($startYear, 4, 1)->startOfDay();
    //                 $endDate = Carbon::createFromDate($endYear, 3, 31)->endOfDay();

    //                 if (($probationDate->between($startDate, $endDate) || $probationDate->lte(Carbon::today())) && $employeeStatus == 'Employee') {
    //                     $companyPercentage = $appraisal->company_percentage;
    //                     $financialYear = $appraisal->financial_year;
    //                 }
    //             }
    //         }

    //         $probationDate = null;

    //         if (!$isClient) {
    //             $probationInput = $request->input('probation_date');

    //             if (!$probationInput || !strtotime($probationInput)) {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'Invalid probation date format.',
    //                     'debug' => 'Probation date could not be parsed.',
    //                 ], 422);
    //             }

    //             $probationDate = Carbon::parse($probationInput);
    //         }

    //         $employeeStatus = $isClient ? 'Client' : ($probationDate->lte(Carbon::today()) ? 'Employee' : 'Probation Period');

    //         // 5. Create the user in the database
    //         SuperAddUser::create([
    //             'fname' => $request->input('fname'),
    //             'lname' => $request->input('lname'),
    //             'dob' => $request->input('dob'),
    //             'gender' => $request->input('gender'),
    //             'mobno' => $request->input('mobno'),
    //             'employee_id' => $isClient ? null : $request->input('employee_id'),
    //             'evaluation_purpose' => $isClient ? null : $request->input('evaluation_purpose'),
    //             'division' => $request->input('division'),
    //             'manager_name' => $request->input('manager_name'),
    //             'designation' => $request->input('designation'),
    //             'user_type' => $request->input('user_type'),
    //             'user_roles' => json_encode($request->input('user_roles')),
    //             'salary' => $isClient ? null : $request->input('salary'),
    //             'salary_grade' => $isClient ? null : $request->input('salary_grade'),
    //             'email' => trim($request->input('email')),
    //             'password' => Hash::make($request->input('password')),
    //             'probation_date' => $probationDate,
    //             'employee_status' => $employeeStatus,
    //             'company_percentage' => $companyPercentage,
    //             'financial_year' => $financialYear,
    //             'status' => 1
    //         ]);

    //         // Return success response
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'User saved successfully!',
    //         ], 200);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         // Return validation errors if any
    //         return response()->json([
    //             'status' => 'error',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $ex) {
    //         // Return unexpected error message
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An unexpected error occurred.',
    //             'debug' => $ex->getMessage()
    //         ], 500);
    //     }
    // }
































    public function addUser(Request $request)
{
    try {
        $isClient = strtolower($request->input('designation')) === 'client';

        // 1. Validation rules
        $rules = [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('super_add_users', 'email'),
            ],
            'password' => 'required|string',
        ];

        if (!$isClient) {
            $rules = array_merge($rules, [
                'employee_id' => [
                    'required',
                    Rule::unique('super_add_users', 'employee_id'),
                ],
                'salary' => 'required|numeric|min:0',
                'salary_grade' => 'required',
                'probation_date' => 'required|date',
                'evaluation_purpose' => 'required|string',
                'mobno' => 'required|string',
                'division' => 'required|string',
                'designation' => 'required|string',
                'user_type' => 'required|string',
            ]);
        }

        // 2. Validate the input data
        $validatedData = $request->validate($rules, [
            'salary.min' => 'Salary must be greater than zero.',
            'email.unique' => 'This email is already registered.',
            'employee_id.unique' => 'This Employee ID is already registered.',
        ]);

        // 3. Check for duplicate email + employee ID (non-clients only)
        if (!$isClient) {
            $existingUser = SuperAddUser::where('email', $request->email)
                ->where('employee_id', $request->employee_id)
                ->first();

            if ($existingUser) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['email' => 'This email ID is already assigned to this Employee ID.']
                ], 422);
            }
        }

        // 4. Probation & appraisal logic
        $probationDate = $isClient ? null : Carbon::parse($request->input('probation_date'));
        $employeeStatus = $isClient ? 'Client' : ($probationDate->lte(Carbon::today()) ? 'Employee' : 'Probation Period');

        $companyPercentage = null;
        $financialYear = null;

        if (!$isClient) {
            $appraisal = ApprisalTable::latest()->first();

            if (
                $appraisal &&
                $appraisal->company_percentage &&
                $appraisal->financial_year &&
                strpos($appraisal->financial_year, '-') !== false
            ) {
                $fyParts = explode('-', $appraisal->financial_year);

                if (count($fyParts) === 2) {
                    [$startYear, $endYear] = $fyParts;

                    $startDate = Carbon::createFromDate($startYear, 4, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($endYear, 3, 31)->endOfDay();

                    if (
                        ($probationDate->between($startDate, $endDate) || $probationDate->lte(Carbon::today())) &&
                        $employeeStatus === 'Employee'
                    ) {
                        $companyPercentage = $appraisal->company_percentage;
                        $financialYear = $appraisal->financial_year;
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid financial year format.',
                        'debug' => 'Expected format YYYY-YYYY, found: ' . $appraisal->financial_year
                    ], 500);
                }
            }
        }

        // Redundant parsing safety check
        $probationDate = null;

        if (!$isClient) {
            $probationInput = $request->input('probation_date');

            if (!$probationInput || !strtotime($probationInput)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid probation date format.',
                    'debug' => 'Probation date could not be parsed.',
                ], 422);
            }

            $probationDate = Carbon::parse($probationInput);
        }

        $employeeStatus = $isClient ? 'Client' : ($probationDate->lte(Carbon::today()) ? 'Employee' : 'Probation Period');

        // 5. Create the user
        SuperAddUser::create([
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'dob' => $request->input('dob'),
            'gender' => $request->input('gender'),
            'mobno' => $request->input('mobno'),
            'employee_id' => $isClient ? null : $request->input('employee_id'),
            'evaluation_purpose' => $isClient ? null : $request->input('evaluation_purpose'),
            'division' => $request->input('division'),
            'manager_name' => $request->input('manager_name'),
            'designation' => $request->input('designation'),
            'user_type' => $request->input('user_type'),
            'user_roles' => json_encode($request->input('user_roles')),
            'salary' => $isClient ? null : $request->input('salary'),
            'salary_grade' => $isClient ? null : $request->input('salary_grade'),
            'email' => trim($request->input('email')),
            'password' => Hash::make($request->input('password')),
            'probation_date' => $probationDate,
            'employee_status' => $employeeStatus,
            'company_percentage' => $companyPercentage,
            'financial_year' => $financialYear,
            'status' => 1
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User saved successfully!',
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $ex) {
        return response()->json([
            'status' => 'error',
            'message' => 'An unexpected error occurred.',
            'debug' => $ex->getMessage()
        ], 500);
    }
}

}
