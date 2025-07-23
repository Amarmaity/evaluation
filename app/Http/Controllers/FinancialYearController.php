<?php

namespace App\Http\Controllers;

use App\Models\ApprisalTable;
use App\Models\FinancialData;
use App\Models\SuperAddUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class FinancialYearController extends Controller
{

    // public function storeFinancialData(Request $request)
    // {
    //     $employeeData = $request->input('employees');
    //     if (!$employeeData || count($employeeData) === 0) {
    //         return response()->json(['message' => 'No employee data provided!'], 400);
    //     }

    //     $dataToInsert = [];

    //     foreach ($employeeData as $index => $empData) {

    //         $empId = $empData['emp_id'];
    //         $financialYear = $empData['financial_year'];

    //         if (!$empId || !$financialYear) {
    //             return response()->json([
    //                 'message' => 'Missing emp_id or financial_year for one of the employees.'
    //             ], 400);
    //         }

    //         // Check if this employee already has an appraisal for this financial year
    //         $existingRecord = FinancialData::where('emp_id', $empId)
    //         ->where('financial_year', $financialYear)
    //         ->first();

    //         if ($existingRecord) {
    //             return response()->json([
    //                 'message' => 'Employee ' . ($empData['employee_name'] ?? '') . ' already has an appraisal for financial year ' . $financialYear . '. Please wait until next year.'
    //             ], 400);
    //         }


    //         $dataToInsert[] = [
    //             'employee_name' => $empData['employee_name'] ?? null,
    //             'emp_id' => $empData['emp_id'],
    //             'evaluation_score' => $empData['evaluation_score'] ?? 0,
    //             'hr_review' => $empData['hr_review'] ?? 0,
    //             'admin_review' => $empData['admin_review'] ?? 0,
    //             'manager_review' => $empData['manager_review'] ?? 0,
    //             'clint_review' => $empData['client_review'] ?? 0,
    //             'apprisal_score' => $empData['apprisal_score'] ?? 0,
    //             'current_salary' => $empData['current_salary'] ?? 0,
    //             'percentage_given' => $empData['percentage_given'] ?? 0,
    //             'update_salary' => $empData['update_salary'] ?? 0,
    //             'final_salary' => $empData['final_salary'] ?? 0,
    //             'apprisal_date' => $empData['apprisal_date'] ?? null,
    //             'financial_year' => $empData['financial_year'] ?? null,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ];
    //         // dd('hi');
    //     }
    //     // DD($dataToInsert);


    //     // Insert all data in one query
    //     FinancialData::insert($dataToInsert);


    //     // ✅ Now apply your requirement: update SuperAddUser table
    //     foreach ($employeeData as $empData) {
    //         $empId = $empData['emp_id'];

    //         // Get latest financial year and percentage from AppraisalTable (global, not by emp_id)
    //         $latestAppraisal = ApprisalTable::orderBy('created_at', 'desc')->first();

    //         if ($latestAppraisal) {
    //             $superUser = SuperAddUser::where('employee_id', $empId)->first(); // Make sure this is the correct field
    //             if ($superUser) {
    //                 // Only update if different
    //                 if (
    //                     $superUser->financial_year !== $latestAppraisal->financial_year ||
    //                     $superUser->company_percentage != $latestAppraisal->company_percentage
    //                 ) {
    //                     $superUser->financial_year = $latestAppraisal->financial_year;
    //                     $superUser->company_percentage = $latestAppraisal->company_percentage;
    //                     $superUser->save();
    //                 }
    //             }
    //         }
    //         return response()->json(['message' => 'Financial data saved successfully!']);
    //     }
    // }


    public function storeFinancialData(Request $request)
    {
        $employeeData = $request->input('employees');
        if (!$employeeData || count($employeeData) === 0) {
            return response()->json(['message' => 'No employee data provided!'], 400);
        }

        $dataToInsert = [];

        foreach ($employeeData as $index => $empData) {

            $empId = $empData['emp_id'];
            $financialYear = $empData['financial_year'];

            if (!$empId || !$financialYear) {
                return response()->json([
                    'message' => 'Missing emp_id or financial_year for one of the employees.'
                ], 400);
            }

            // Check if this employee already has an appraisal for this financial year
            $existingRecord = FinancialData::where('emp_id', $empId)
                ->where('financial_year', $financialYear)
                ->first();

            if ($existingRecord) {
                return response()->json([
                    'message' => 'Employee ' . ($empData['employee_name'] ?? '') . ' already has an appraisal for financial year ' . $financialYear . '. Please wait until next year.'
                ], 400);
            }

            // Calculate salary grade based on final salary
            $finalSalary = $empData['final_salary'] ?? 0;
            $salaryGrade = $this->determineSalaryGrade($finalSalary);

            // Update salary_grade in SuperAddUser
            $superUser = SuperAddUser::where('employee_id', $empId)->first();
            if ($superUser && $superUser->salary_grade !== $salaryGrade) {
                $superUser->salary_grade = $salaryGrade;
                $superUser->save();
            }

            $dataToInsert[] = [
                'employee_name' => $empData['employee_name'] ?? null,
                'emp_id' => $empData['emp_id'],
                'evaluation_score' => $empData['evaluation_score'] ?? 0,
                'hr_review' => $empData['hr_review'] ?? 0,
                'admin_review' => $empData['admin_review'] ?? 0,
                'manager_review' => $empData['manager_review'] ?? 0,
                'clint_review' => $empData['client_review'] ?? 0,
                'apprisal_score' => $empData['apprisal_score'] ?? 0,
                'current_salary' => $empData['current_salary'] ?? 0,
                'percentage_given' => $empData['percentage_given'] ?? 0,
                'update_salary' => $empData['update_salary'] ?? 0,
                'final_salary' => $finalSalary,
                'apprisal_date' => $empData['apprisal_date'] ?? null,
                'financial_year' => $empData['financial_year'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all financial data
        FinancialData::insert($dataToInsert);

        // Update SuperAddUser financial year & company percentage
        foreach ($employeeData as $empData) {
            $empId = $empData['emp_id'];

            $latestAppraisal = ApprisalTable::orderBy('created_at', 'desc')->first();

            if ($latestAppraisal) {
                $superUser = SuperAddUser::where('employee_id', $empId)->first();
                if ($superUser) {
                    if (
                        $superUser->financial_year !== $latestAppraisal->financial_year ||
                        $superUser->company_percentage != $latestAppraisal->company_percentage
                    ) {
                        $superUser->financial_year = $latestAppraisal->financial_year;
                        $superUser->company_percentage = $latestAppraisal->company_percentage;
                        $superUser->save();
                    }
                }
            }
        }

        return response()->json(['message' => 'Financial data saved successfully!']);
    }

    //Healper Function
    private function determineSalaryGrade($salary)
    {
        if ($salary >= 150000) return 'A';
        if ($salary >= 100000)  return 'B';
        if ($salary >= 70000)  return 'C';
        if ($salary >= 40000)  return 'D';
        if ($salary >= 20000)  return 'E';
        return 'F';
    }

    public function financialTableView(Request $request)
    {
        $selectedYear = $request->input('financial_year');

        if ($selectedYear) {
            $financialData = FinancialData::where('financial_year', $selectedYear)->get();
        } else {
            $financialData = FinancialData::all();
        }

        // Get distinct years for the dropdown
        $availableYears = FinancialData::select('financial_year')->distinct()->pluck('financial_year');


        // $financialData = FinancialData::all();

        return view('admin.FinancialTable', compact('financialData', 'availableYears', 'selectedYear'));

        // dd($financialData);
    }











    public function searchEmployee(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            // Return all records from financial_data if input is empty
            $financialData = FinancialData::all();
        } else {
            $financialData = FinancialData::where('emp_id', $query)
                ->orWhere('employee_name', 'LIKE', '%' . $query . '%')
                ->get();
        }
        if ($financialData->isEmpty()) {
            return response()->json(['financialData' => []]); // No error code
        }

        return response()->json(['financialData' => $financialData]);
    }





    //View Setting 
    public function getSettingView(Request $request)
    {
        $allowPercentage = ApprisalTable::get()->all();

        return view('admin.setting', compact('allowPercentage'));
    }

    // public function setApprisalPercentage(Request $request)
    // {
    //     $request->validate([
    //         'financial_year' => 'required|string',
    //         'company_percentage' => 'required|numeric|min:0|max:100',
    //     ]);

    //     $financialYear = $request->input('financial_year');
    //     $companyPercentage = $request->input('company_percentage');

    //     // Step 1: Check for duplicate entry in ApprisalTable
    //     $existing = ApprisalTable::where('financial_year', $financialYear)->first();
    //     if ($existing) {
    //         return response()->json([
    //             'message' => 'Data already exists for this financial year',
    //             'status' => 'duplicate'
    //         ]);
    //     }

    //     // Step 2: Calculate previous financial year
    //     [$newStart, $newEnd] = explode('-', $financialYear);
    //     $previousYear = ((int)$newStart - 1) . '-' . ((int)$newEnd - 1);

    //     // Step 3: Get emp_ids who completed appraisal in previous year
    //     $completedEmpIds = FinancialData::where('financial_year', $previousYear)
    //         ->pluck('emp_id')
    //         ->toArray();

    //     // Step 4: Update SuperAddUser for eligible employees
    //     SuperAddUser::where('employee_status', 'Employee')
    //         ->whereIn('employee_id', $completedEmpIds)
    //         ->update([
    //             'financial_year' => $financialYear,
    //             'company_percentage' => $companyPercentage,
    //         ]);

    //     // Step 5: Create ApprisalTable record
    //     ApprisalTable::create([
    //         'financial_year' => $financialYear,
    //         'company_percentage' => $companyPercentage,
    //     ]);

    //     // Step 6: Return success response
    //     return response()->json([
    //         'message' => 'Data submitted successfully',
    //         'status' => 'success'
    //     ]);
    // }





    public function setApprisalPercentage(Request $request)
    {
        Log::info('HIT setApprisalPercentage', $request->all());
        // Step 1: Validate input
        $request->validate([
            'financial_year' => 'required|string',
            'company_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $financialYear = $request->input('financial_year');
        $companyPercentage = $request->input('company_percentage');

        // Step 2: Validate financial year format
        if (!preg_match('/^\d{4}-\d{4}$/', $financialYear)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid financial year format. Use YYYY-YYYY',
            ], 400);
        }

        // Step 3: Check if record already exists
        $existing = ApprisalTable::where('financial_year', $financialYear)->first();
        if ($existing) {
            return response()->json([
                'status' => false,
                'message' => 'Data already exists for this financial year',
            ], 409);
        }

        // Step 4: Calculate previous financial year
        [$newStart, $newEnd] = explode('-', $financialYear);
        $previousYear = ((int)$newStart - 1) . '-' . ((int)$newEnd - 1);

        // Step 5: Get emp_ids who completed appraisal in previous year
        $completedEmpIds = FinancialData::where('financial_year', $previousYear)
            ->pluck('emp_id')
            ->toArray();

        // Step 6: Update SuperAddUser for eligible employees
        SuperAddUser::where('employee_status', 'Employee')
            ->whereIn('employee_id', $completedEmpIds)
            ->update([
                'financial_year' => $financialYear,
                'company_percentage' => $companyPercentage,
            ]);

        // Step 7: Insert record into ApprisalTable
        ApprisalTable::create([
            'financial_year' => $financialYear,
            'company_percentage' => $companyPercentage,
        ]);

        // Step 8: Return success response
        return response()->json([
            'status' => true,
            'message' => 'Appraisal data saved and eligible users updated successfully',
            'data' => [
                'financial_year' => $financialYear,
                'company_percentage' => $companyPercentage,
            ]
        ], 200);
    }


    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'company_percentage' => 'required|numeric|min:0|max:100',
    //     ]);

    //     // Get the existing appraisal record
    //     $record = ApprisalTable::findOrFail($id);
    //     $financialYear = $record->financial_year;

    //     // Check if the FinancialData already contains this financial year
    //     $exists = FinancialData::where('financial_year', $financialYear)->exists();

    //     if (!$exists) {
    //         $record->company_percentage = $request->company_percentage;
    //         $record->save();

    //         return response()->json(['status' => 'success']);
    //     } else {
    //         return response()->json(['error'], 409);
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'company_percentage' => 'required|numeric|min:0|max:100',
    //     ]);

    //     // Step 1: Find the ApprisalTable record
    //     $record = ApprisalTable::findOrFail($id);
    //     $financialYear = $record->financial_year;

    //     // Step 2: Check if FinancialData exists for that year
    //     $exists = FinancialData::where('financial_year', $financialYear)->exists();

    //     // dd($exists);

    //     if (!$exists) {
    //         // Step 3: Update the ApprisalTable company_percentage
    //         $record->company_percentage = $request->company_percentage;
    //         $record->save();

    //         // Step 4: Get emp_ids that already exist in financial_data
    //         $empIdsWithFinancialData = DB::table('financial_data')
    //             ->pluck('emp_id')
    //             ->toArray();

    //         // Step 5: Update company_percentage in SuperAddUser
    //         SuperAddUser::where('financial_year', $financialYear)
    //             ->where('employee_status', 'Employee')
    //             ->whereNotIn('employee_id', $empIdsWithFinancialData)
    //             ->update([
    //                 'company_percentage' => $request->company_percentage
    //             ]);

    //         return response()->json(['status' => 'success']);
    //     } else {
    //         return response()->json(['error' => 'FinancialData already exists for this year.'], 409);
    //     }
    // }

    public function update(Request $request, $id)
{
    $request->validate([
        'company_percentage' => 'required|numeric|min:0|max:100',
    ]);

    // Step 1: Find the ApprisalTable record
    $record = ApprisalTable::findOrFail($id);
    $financialYear = $record->financial_year;

    // Step 2: Check if FinancialData already has entries for this financial year
    $financialDataExists = FinancialData::where('financial_year', $financialYear)->exists();

    if ($financialDataExists) {
        return response()->json([
            'error' => 'FinancialData already exists for this year. Cannot update company percentage.',
        ], 409);
    }

    // Step 3: Update ApprisalTable company_percentage
    $record->company_percentage = $request->company_percentage;
    $record->save();

    // Step 4: Get employee IDs who have already completed their appraisal
    $empIdsWithFinancialData = FinancialData::where('financial_year', $financialYear)
        ->pluck('emp_id')
        ->toArray();

    // Step 5: Update only eligible SuperAddUser entries
    SuperAddUser::where('employee_status', 'Employee')
        ->where('financial_year', $financialYear)
        ->whereNotIn('employee_id', $empIdsWithFinancialData)
        ->update([
            'company_percentage' => $request->company_percentage
        ]);

    return response()->json(['status' => 'success']);
}

}
