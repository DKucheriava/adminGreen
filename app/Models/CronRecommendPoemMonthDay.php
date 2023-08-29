<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronRecommendPoemMonthDay extends Model
{
    use HasFactory;
    protected $table = 'cron_recommend_poem_month_day';
    protected $fillable = ['userid','email','mobile_token','type','status'];
}
