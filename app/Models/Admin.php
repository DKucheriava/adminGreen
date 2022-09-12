<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory;
    protected $table = 'admins';
    protected $fillable = ['name','email','password','country_id','address','image','security_code'];

    protected $hidden = [
        'password', 'remember_token',
    ];

}



