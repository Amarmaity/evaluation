<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialData extends Model
{
    //
    protected $table = 'financial_data';

    // Define the fillable fields (mass assignable)
    protected $fillable = [
        'employee_name', 'emp_id', 'hr_review', 'admin_review', 'manager_review', 'clint_review', 'apprisal_score',
        'current_salary', 'percentage_given', 'update_salary', 'final_salary', 'apprisal_date','financial_year'
    ];



    public function employee()
    {
        return $this->belongsTo(SuperAddUser::class, 'employee_id', 'emp_id');
    }



}
