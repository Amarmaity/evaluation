<?php

namespace App\Http\Controllers;

use App\Models\FinancialData;
use App\Models\SuperAddUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FinancialYearController extends Controller
{
    //

    public function storeFinancialData(Request $request)
    {
        $employeeData = $request->input('employees');
        if (!$employeeData || count($employeeData) === 0) {
            return response()->json(['message' => 'No employee data provided!'], 400);
        }

        // Insert data into the financial_data table
        $dataToInsert = [];
        foreach ($employeeData as $index => $empData) {
            // dd($empData);
            // Check if this employee has already been appraised within the current year
            $employee = FinancialData::where('emp_id', $empData['emp_id'])->first();
            if ($employee) {
                $lastAppraisalDate = $employee->apprisal_date; // Assuming 'apprisal_date' is a column in the 'financial_data' table
                $lastAppraisalYear = Carbon::parse($lastAppraisalDate)->year;
                $currentYear = Carbon::now()->year;

                // If appraisal was done in the current year, show an error message
                if ($lastAppraisalYear === $currentYear) {
                    return response()->json([
                        'message' => 'Employee ' . $empData['employee_name'] . ', appraisal has already been done. Please wait until next year.'
                    ], 400);
                }
            }

            // Prepare the data for insertion
            $dataToInsert[] = [
                'employee_name' => $empData['employee_name'] ?? null,
                'emp_id' => $empData['emp_id'],
                'hr_review' => $empData['hr_review'] ?? 0,
                'admin_review' => $empData['admin_review'] ?? 0,
                'manager_review' => $empData['manager_review'] ?? 0,
                'clint_review' => $empData['clint_review'] ?? 0,
                'apprisal_score' => $empData['apprisal_score'] ?? 0,
                'current_salary' => $empData['current_salary'] ?? 0,
                'percentage_given' => $empData['percentage_given'] ?? 0,
                'update_salary' => $empData['update_salary'] ?? 0,
                'final_salary' => $empData['final_salary'] ?? 0,
                'apprisal_date' => $empData['apprisal_date'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

       
        // Insert all data in one query
        FinancialData::insert($dataToInsert);
// dd($data);
        // Return success message
        return response()->json(['message' => 'Financial data saved successfully!']);
    }


    public function financialTableView(Request $request)
    {
        // Retrieve all financial data where the associated employee's status is 1
        $financialData = FinancialData::join('super_add_users', 'financial_data.emp_id', '=', 'super_add_users.employee_id')
            ->where('super_add_users.status', 1)
            ->get(['financial_data.*', 'super_add_users.fname', 'super_add_users.lname']); // You can add more fields if needed
    
        return view('admin.FinancialTable', compact('financialData'));
    }
    


    public function searchEmployee(Request $request)
    {
        // Retrieve the search query and type (either 'id' or 'name')
        $query = $request->input('query');
        $searchType = $request->input('type');
        
        // Ensure query and searchType are provided
        if (empty($query) || empty($searchType)) {
            return response()->json(['error' => 'Invalid search parameters.'], 400);
        }
        
        // Search for the financial data based on employee ID or name
        $queryBuilder = FinancialData::join('super_add_users', 'financial_data.emp_id', '=', 'super_add_users.employee_id');
        
        if ($searchType === 'id') {
            // Search by employee ID (using the correct column)
            $financialData = $queryBuilder->where('super_add_users.employee_id', $query)
                ->get(['financial_data.*', 'super_add_users.fname', 'super_add_users.lname']);
        } elseif ($searchType === 'name') {
            // Search by employee name (first and last name)
            $financialData = $queryBuilder->where(function ($subQuery) use ($query) {
                $subQuery->whereRaw("CONCAT(super_add_users.fname, ' ', super_add_users.lname) LIKE ?", ['%' . $query . '%']);
            })->get(['financial_data.*', 'super_add_users.fname', 'super_add_users.lname']);
        } else {
            // Invalid search type
            return response()->json(['error' => 'Invalid search type.'], 400);
        }
    
        // Return the data in JSON format
        return response()->json(['financialData' => $financialData]);
    }
    
    
    


}
