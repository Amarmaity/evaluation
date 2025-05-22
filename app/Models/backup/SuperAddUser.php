<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAddUser extends Model
{
    //

    protected $table = 'super_add_users'; 
    protected $fillable = [
        'fname',
        'lname',
        'dob',
        'gender',
        'mobno',
        'employee_id',
        'designation',
        'user_type',
        'user_roles',
        'salary',
        'email',
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
}
