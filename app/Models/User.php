<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
'id', 'id_agency', 'id_region', 'reg_code', 'agency_loc', 'first_name', 'middle_name', 'last_name', 'ext_name', 'sex', 'date_of_birth', 'account_status', 'emp_status', 'position', 'contact_no', 'complete_address', 'brgy_code', 'mun_code', 'province_code', 'region_code', 'email', 'email_verified_at', 'username', 'password', 'remember_token', 'api_token', 'user_role', 'created_at', 'updated_at'    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
'id', 'id_agency', 'id_region', 'reg_code', 'agency_loc', 'first_name', 'middle_name', 'last_name', 'ext_name', 'sex', 'date_of_birth', 'account_status', 'emp_status', 'position', 'contact_no', 'complete_address', 'brgy_code', 'mun_code', 'province_code', 'region_code', 'email', 'email_verified_at', 'username', 'password', 'remember_token', 'api_token', 'user_role', 'created_at', 'updated_at'    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
