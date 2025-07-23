<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAddUser extends Model
{
    use HasFactory;

    protected $table = 'super_add_users';
    protected $fillable = [
        'fname',
        'lname',
        'dob',
        'gender',
        'mobno',
        'employee_id',
        'evaluation_purpose',
        'division',
        'manager_name',
        'manager_id',
        'department',
        'designation',
        'user_type',
        'user_roles',
        'salary',
        'email',
        'client_id',
        'salary_grade',
        'password',
        'company_percentage',
        'financial_year',
        'status',
        'probation_date',
        'employee_status'
    ];

    public function financialData()
    {
        return $this->hasOne(FinancialData::class, 'emp_id', 'employee_id');
    }


    // Who is my manager?
    public function manager()
    {
        return $this->belongsTo(SuperAddUser::class, 'manager_id');
    }

}
