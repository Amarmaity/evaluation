<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientReviewTable;
use App\Models\SuperAddUser;



class AllClient extends Model
{
  use HasFactory;

  protected $table = "all_clients";

  protected $fillable = [
    'client_name',
    'company_name',
    'client_mobno',
    'client_email',
    'password',
    'status',
    'user_type'
  ];

  public function reviews()
  {
    return $this->hasMany(ClientReviewTable::class, 'client_id', 'id');
  }

  public function users()
  {
    return $this->belongsToMany(SuperAddUser::class, 'client_user', 'client_id', 'user_id');
  }
}
