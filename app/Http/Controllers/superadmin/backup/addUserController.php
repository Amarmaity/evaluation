<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\ApprisalTable;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAddUser;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\EvaluationCredentialMail;



class addUserController extends Controller

{
    //

    public function indexAddUser()
    {
        return view("admin/superAddUserDashBoard");
    }



    public function addUser(Request $request)
    {
        try {
            $isClient = strtolower($request->input('designation')) === 'client';

            // 1. Validation rules
            $rules = [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'dob' => 'required|date',
                'mobno' => 'required|digits:10',
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
                    'division' => 'required|string',
                    'designation' => 'required|string',
                    'user_type' => 'required|string',
                ]);
            }

            // 2. Validate the input data
            $validatedData = $request->validate(
                $rules,
                [
                    'salary.min' => 'Salary must be greater than zero.',
                    'email.unique' => 'This email is already registered.',
                    'employee_id.unique' => 'This Employee ID is already registered.',
                ],
                [
                    'mobno' => 'Mobile No',
                    'fname' => 'First Name',
                    'lname' => 'Last Name',
                    'dob' => 'Date of Birth',
                    'gender' => 'Gender',
                    'email' => 'Email',
                    'password' => 'Password',
                    'employee_id' => 'Employee ID',
                    'salary' => 'Salary',
                    'salary_grade' => 'Salary Grade',
                    'probation_date' => 'Probation Date',
                    'evaluation_purpose' => 'Evaluation Purpose',
                    'division' => 'Division',
                    'designation' => 'Designation',
                    'user_type' => 'User Type',
                ]
            );

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


            $managerId = $request->input('manager_id');
            $managerNameInput = trim($request->input('manager_name'));
            $managerNameFinal = $managerNameInput;

            // If manager_id is provided and valid, fetch full name from DB
            if (!empty($managerId)) {
                $manager = SuperAddUser::find($managerId);
                if ($manager) {
                    $managerNameFinal = $manager->fname . ' ' . $manager->lname;
                } else {
                    $managerId = null; // Fallback if ID is invalid
                }
            }

            // 5. Create the user
            $user = SuperAddUser::create([
                'fname' => $request->input('fname'),
                'lname' => $request->input('lname'),
                'dob' => $request->input('dob'),
                'gender' => $request->input('gender'),
                'mobno' => $request->input('mobno'),
                'employee_id' => $isClient ? null : $request->input('employee_id'),
                'evaluation_purpose' => $isClient ? null : $request->input('evaluation_purpose'),
                'division' => $request->input('division'),
                'manager_id' => $managerId ?: null,
                // 'manager_name' => $request->input('manager_name'),
                 'manager_name' => $managerNameFinal,
                'designation' => $request->input('designation'),
                'user_type' => $request->input('user_type'),
                'user_roles' => json_encode($request->input('user_roles')),
                'salary' => $isClient ? null : $request->input('salary'),
                'salary_grade' => $isClient ? null : $request->input('salary_grade'),
                'email' => trim($request->input('email')),
                'client_id' => json_encode($request->input('client_id')),
                'password' => Hash::make($request->input('password')),
                'probation_date' => $probationDate,
                'employee_status' => $employeeStatus,
                'company_percentage' => $companyPercentage,
                'financial_year' => $financialYear,
                'status' => 1
            ]);

            if ($user) {
                Mail::to($user->email)->send(new EvaluationCredentialMail($user, $request->input('password')));
            }

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



    //Get Manager Name
    public function getManagers(Request $request)
    {
        $search = $request->get('term', '');

        $managers = SuperAddUser::where('designation', 'Manager')
            ->where(function ($query) use ($search) {
                $query->where('fname', 'like', "%{$search}%")
                    ->orWhere('lname', 'like', "%{$search}%");
            })
            ->selectRaw("CONCAT(fname, ' ', lname) as full_name")
            ->pluck('full_name');

        return response()->json($managers);
    }
}
