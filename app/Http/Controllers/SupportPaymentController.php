<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use AppHttpRequestsRegisterAuthRequest;
use TymonJWTAuthExceptionsJWTException;
use App\Services\SessionService;
use App\Mail\forgotPasswordMail;
use App\Console\Commands\DemoCron;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ImagesTrait;
use Illuminate\Support\Str;
use IlluminateHttpRequest;
use App\Models\SupportPayment;
use App\Models\User;
use Mail, Hash, Auth;
use JWTAuth,Session;
use App\Common;
use Validator;
use DateTime;
use date;
use DB;
use Stripe;
use Carbon\Carbon;

class SupportPaymentController extends Controller
{
    private $toEmail;
   
   
    public function __construct()
    {
        $this->toEmail = 'info@greenpheasants.com';
    }


    const BASE_URL = 'https://api.stripe.com';
    const SECRET_KEY = 'sk_test_51LndPHSHphW5nYkGGICZoTVtXZ0SF9ObfuC9SQd85dQiZvk3DcTrIlHcdU8W458N0gHHO0crGfFH2XVF5hAkPyPl00PgK5qo3G';

    function paymentPaypal(Request $request){

        SupportPayment::create([
            'userid'         =>$request->userid =="null"? 0 :$request->userid,
            'payment_type'   =>0,
            'payment_method' =>1,
            'payment_id'    =>$request->payment_id,
            'payer_id'      =>$request->payer_id,
            'payer_email'   =>$request->payer_email_address,
            'payment_by'    =>'direct', 
            'amount'        =>$request->amount,
            'currency'      =>$request->currency_code,
            'status'        =>'success'
        ]);


            if($request->userid){
         $summCount =  SupportPayment::where('userid',$request->userid)->sum('amount');

         User::where('userid',$request->userid)
              ->update([
                'udonate_sum'    =>(int)$summCount,                      
                'udonate_recent_time' =>Carbon::now()->format('Y-m-d h:i:s'),                   
              ]);
            }
       

        $user_name = $request->user_name ? $request->user_name : "Guest";
        $email = $request->user_email;
        $project_name = "Green Pheasants";
            Mail::send('backend.emails.thanksforsupport',['name'=>$user_name,'email'=>$email],function($message) use($email,$project_name){
                $message->to($email,$project_name)->subject('Green Pheasants - Thank You For Supporting Us');
                $message->from($this->toEmail,"Green Pheasants");
            });

        return response()->json(['status' => true,'message'=>'Payment done successfuly','code'=>200]);
    }


     function paymentPaypalmonth(Request $request){

        SupportPayment::create([
                            'userid'         =>$request->userid =="null"? 0 :$request->userid,
                            'payment_type'   =>1,
                            'payment_method' =>1,
                            'payment_id'    =>$request->payment_id,
                            'payer_email'   =>$request->payer_email,
                            'payment_by'    =>'direct', 
                            'amount'        =>$request->plan_amount,
                            'currency'      =>$request->plan_currency,
                            'subscription_id'       =>$request->subscription_id,
                            'plan_id'               =>$request->plan_id,
                            'plan_amount'           =>$request->plan_amount,
                            'plan_currency'         =>$request->plan_currency,
                            'plan_interval'         =>$request->plan_interval,
                            'current_period_start'  =>$request->current_period_start,
                            'current_period_end'    =>$request->current_period_start,
                             'status'        =>'success'
        ]);

        $user_name = $request->user_name ? $request->user_name : "Guest";
        $email = $request->user_email;
        $project_name = "Green Pheasants";
            Mail::send('backend.emails.thanksforsupport',['name'=>$user_name,'email'=>$email],function($message) use($email,$project_name){
                $message->to($email,$project_name)->subject('Green Pheasants - Thank You For Supporting Us');
                $message->from($this->toEmail,"Green Pheasants");
            });

        return response()->json(['status' => true,'message'=>'Your Subscription Payment has been Successful','code'=>200]);
    }
    
    public function stripePost(Request $request){

        $response           = [];
        $stripe = Stripe\Stripe::setApiKey(config('app.STRIPE_TEST_SECRET'));
        if($request->payment_type == "monthly"){
            try { 
                $plan = \Stripe\Plan::create(array( 
                    "product" => [ 
                        "name" => 'day Subscription' 
                    ], 
                    "amount"   => 50, 
                    "currency" => 'USD', 
                    "interval" => 'day', 
                    "interval_count" => 1 
                )); 

            }catch(Exception $e) { 
                $api_error = $e->getMessage(); 
                return response()->json(['status' => false,'message' => $api_error, 'code' => 400]); 
            } 


            if(empty($api_error) && $plan){ 
               // Creates a new subscription 
                try { 
                    $customer = Stripe\Customer::create([
                                           'email'         => $request->email,
                                           'name'          => 'deepak',
                                           'source'        => $request->stripeToken,
                                           'description'   => 'test',
                                       ]);
                    $subscription = \Stripe\Subscription::create(array( 
                       "customer" => $customer['id'],
                       "items" => array( 
                           array( 
                               "plan" => $plan->id, 
                           ), 
                       ), 
                   )); 

                }catch(Exception $e) { 
                   $api_error = $e->getMessage(); 
                   return response()->json(['status' => false,'message' => $api_error, 'code' => 400]); 
                } 
                

               if(empty($api_error) && $subscription){ 
                   // Retrieve subscription data 
                   $subsData = $subscription->jsonSerialize(); 
                   // Check whether the subscription activation is successful 
                   if($subsData['status'] == 'active'){ 

                        $subscrID     = $subsData['id']; 
                        $custID       = $subsData['customer']; 
                        $planID       = $subsData['plan']['id']; 
                        $planAmount   = ($subsData['plan']['amount']/100); 
                        $planCurrency = $subsData['plan']['currency']; 
                        $planinterval = $subsData['plan']['interval']; 
                        $planIntervalCount = $subsData['plan']['interval_count']; 
                        $created           = date("Y-m-d H:i:s", $subsData['created']); 
                        $current_period_start = date("Y-m-d H:i:s", $subsData['current_period_start']); 
                        $current_period_end   = date("Y-m-d H:i:s", $subsData['current_period_end']); 
                        $status               = $subsData['status']; 
                        
                        SupportPayment::create([
                            'userid'                =>$request->userid =="null"? 0 :$request->userid,
                            'payment_type'          =>1,
                            'payment_method'        =>2,
                            'amount'                =>($request->price!=null ) ? $request->price*100 : $request->other_amount*100,
                            'currency'              =>$request->currency_code,
                            'subscription_id'       =>$subscrID,
                            'customer_id'           =>$custID,
                            'plan_id'               =>$planID,
                            'plan_amount'           =>$planAmount,
                            'plan_currency'         =>$planCurrency,
                            'plan_interval'         =>$planinterval,
                            'plan_interval_count'   =>$planIntervalCount,
                            'current_period_start'  =>$current_period_start,
                            'current_period_end'    =>$current_period_end,
                            'payment_by'            =>'card', 
                            'status'                =>'success'
                        ]);
                        
                       $ordStatus = 'success'; 
                       $statusMsg = 'Your Subscription Payment has been Successful!'; 
                        $user_name = $request->user_name ? $request->user_name : "Guest";
                  $email = $request->user_email;
                  $project_name = "Green Pheasants";
            Mail::send('backend.emails.thanksforsupport',['name'=>$user_name,'email'=>$email],function($message) use($email,$project_name){
                $message->to($email,$project_name)->subject('Green Pheasants - Thank You For Supporting Us');
                $message->from($this->toEmail,"Green Pheasants");
            });
                       return response()->json(['status' => true,'message' => $statusMsg, 'code' => 200]);
                   }else{ 
                       $statusMsg = "Subscription activation failed!"; 
                       return response()->json(['status' => false,'message' => $statusMsg, 'code' => 400]);
                   } 
               }else{ 
                   $statusMsg = "Subscription creation failed! ".$api_error; 
                   return response()->json(['status' => false,'message' => $statusMsg, 'code' => 400]);
               } 
            }else{ 
                $statusMsg = "Plan creation failed! ".$api_error;
                return response()->json(['status' => false,'message' => $statusMsg, 'code' => 400]); 
            } 
        }else{

            $customer = Stripe\Customer::create([
                            'email'         => $request->email,
                            'name'          => 'deepak',
                            'source'        => $request->stripeToken,
                            'description'   => 'test',
                        ]);

            // dd($request->all());
            $checkChargeStripe = Stripe\Charge::create([
                                    "customer"    => $customer['id'],
                                    "amount"      => ($request->price!=null ) ? $request->price*100 : $request->other_amount*100,
                                    "currency"    => $request->currency_code,
                                    "description" => "testttt",
                                ]);

            SupportPayment::create([
                'userid'         =>$request->userid =="null"? 0 :$request->userid,
                'payment_type'   =>0,
                'charge_id'      =>$checkChargeStripe['id'],
                'customer_id'    =>$checkChargeStripe['customer'],
                'payment_method' =>2,
                'payment_by'     =>'card', 
                'amount'         =>($request->price!=null ) ? $request->price : $request->other_amount,
                'currency'       =>$request->currency_code,
                'card'           =>$request->card,
                'email'          =>$request->email, 
                'status'         =>'success'
            ]);
            
            if($checkChargeStripe['status']=='succeeded'){
               $user_name = $request->user_name ? $request->user_name : "Guest";
        $email = $request->user_email;
        $project_name = "Green Pheasants";
            Mail::send('backend.emails.thanksforsupport',['name'=>$user_name,'email'=>$email],function($message) use($email,$project_name){
                $message->to($email,$project_name)->subject('Green Pheasants - Thank You For Supporting Us');
                $message->from($this->toEmail,"Green Pheasants");
            });
            
                return response()->json(['status' => true,'message'  => 'Payment done Successfuly', 'code' => 200]);
            }else{
                return response()->json(['status' => false,'message' => 'Payment is not completed', 'code' => 400]);
            }
        }

        return response()->json(['status' => false,'message' => 'Something went wrong', 'code' => 400]);
    }


}





















