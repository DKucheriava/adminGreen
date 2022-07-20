<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionTable extends Model
{
    use HasFactory;

    protected $table = 'sessions';
    
    protected $fillable = [
        'logged_in',   
        'userid',  
        'sstart',  
        'send',    
        'sduration',   
        'snumitems',  
        'scountry',    
        'smobile',    
        'sapp',
        'timezone',
        'svertical'
    ];
}


