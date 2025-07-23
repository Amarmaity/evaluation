<?php

namespace App\Models;

use App\Models\SuperAddUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ApprisalTable extends Model
{
    use HasFactory;

    protected $table = 'apprisal_tables';
    protected $fillable = [
        'emp_id',
        'company_percentage',
        'financial_year'
    ];


}
