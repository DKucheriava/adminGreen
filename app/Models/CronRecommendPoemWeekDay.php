<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronRecommendPoemWeekDay extends Model
{
    use HasFactory;
    protected $table = 'cron_recommend_poem_week_day';
    protected $fillable = ['userid','email','type','mobile_token','status'];
}
