<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;
    protected $table = 'about_us';
    
    protected $fillable = [
        'initiator_name',
        'developer_name',
        'initiator_image',
        'developer_image',
        'story_by_initiator',
        'story_by_developer'
    ];
}
