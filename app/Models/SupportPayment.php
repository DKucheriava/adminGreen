<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportPayment extends Model
{
    use HasFactory;
    
    protected $table = 'support_payments';
    protected $fillable = ['userid','payment_type','payment_by','payment_id','payer_id','payer_email','amount','currency','status','payment_method','charge_id','customer_id','email','card','subscription_id','plan_id','plan_amount','plan_currency','plan_interval','plan_interval_count','current_period_start','current_period_end'];


    public function userDetail(){
        return $this->hasOne('App\Models\User','userid','userid');
    }

}








