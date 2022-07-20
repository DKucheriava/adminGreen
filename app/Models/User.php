<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
    * Get the identifier that will be stored in the subject claim of the JWT.
    *
    * @return mixed
    */
    public function getJWTIdentifier(){
      return $this->getKey();
    }

    /**
    * Return a key value array, containing any custom claims to be added to the JWT.
    *
    * @return array
    */
    public function getJWTCustomClaims(){
        return [];
    }

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    protected $fillable = [
        'user_name',
        'uemail',
        'uregistration_time',
        'ucountry_id',
        'ulast_visit_time',
        'ul7',
        'ul30',
        'urec_email',
        'urec_email_freq',
        'urec_email_time',
        'urec_push',
        'urec_push_freq',
        'urec_push_time',
        'ucollection_num',
        'ucollection_recent_time',
        'udonate_sum',
        'udonate_recent_time',
        'uupload_old_recent_time',
        'password',
        'remember_me',
        'subscribe_me',
        'send_recommened_poem',
        'send_notification'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
