<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperUserTable extends Model
{
    use HasFactory;
    protected $table = 'super_user_tables';

     protected $fillable = [
        'email',
        'password',
        'user_type',
    ];
}
