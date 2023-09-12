<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use AppHttpRequestsRegisterAuthRequest;
use TymonJWTAuthExceptionsJWTException;
use JWTAuth,Session;
use IlluminateHttpRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\PrivacyPolicy;
use App\Models\ContactUs;
use App\Models\AboutUs;
use App\Models\Terms;
use App\Models\Faq;
use App\Models\Poem;
use App\Models\PoemMood;
use App\Models\PoemMoodSelected;
use App\Models\PoemTheme;
use App\Models\PoemThemeSelected;
use App\Models\AdditionalLinkForPoem;
use App\Models\Collection;
use App\Models\Country;
use App\Models\Creator;
use App\Models\Interaction;
use App\Models\Item;
use App\Models\PoemText;
use App\Models\SessionTable;
use App\Services\SessionService;
use Mail, Hash, Auth;
use App\Traits\ImagesTrait;
use App\Mail\forgotPasswordMail;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use App\Console\Commands\DemoCron;
use App\Models\CronRecommendPoemMonthDay;
use App\Models\CronRecommendPoemOneDay;
use App\Models\CronRecommendPoemWeekDay;
use App\Common;
use Validator;
use DateTime;
use date;
use DB;
use Carbon\Carbon;

class ApiController extends Controller
{
    use ImagesTrait;

    private $toEmail;


    public function __construct()
    {
        $this->toEmail = 'developerindiit@gmail.com';
    }

    public function getRegistration(Request $request)
    {
        dd('here');
    }

    public function getAllCountry(Request $request)
    {
        $countries = Country::orderby('id','asc')->get();
        return response()->json(['status' => true,'message' => 'User login successful','data' => $countries,'code' => 200]);
    }

    function userEmailVerification(Request $request,$userid)
    {
        $user = User::where('userid',$userid)
                    ->first();
        if($user->security_code!=null){
            $email = $user->uemail;
            $updateUser = User::where('userid',$userid)
                                ->update([
                                        'security_code'=>'',
                                        'email_verification_status'=>1,
                                    ]);
           return response()->json(['status'=>true,'code'=>200,'message'=>'Email verified successfully']);
        } else{
            return response()->json(['status'=>false,'message'=>'Link expired','code'=>400]);
        }
    }

    function update_session(Request $request)
    {
        if(!empty($request->logged_in)){
            $updateUser2 = SessionTable::create([
                                        'logged_in'=>$request->logged_in,
                                        'userid'=>$request->userid,
                                        'sstart'=>$request->sstart,
                                        'smobile'=>$request->smobile,
                                        'svertical'=>$request->svertical,
                                        'scountry'=>$request->country_code,
                                        'snumitems'=>$request->snumitems,
                                    ])->sessionid;
            $lastActivity = SessionTable::latest()->first();
            return response()->json(['status'=>true,'code'=>200,'sdata' => $lastActivity->sessionid,'message'=>'Session Create successfully']);
        } else{
            return response()->json(['status'=>false,'message'=>'Link expired','code'=>400]);
        }
    }

    function update_sessiononpause(Request $request)
    {
        $updateUser1 = SessionTable::where('sessionid',$request->id)->update(['send' => $request->send, 'sduration' => $request->sduration]);
        if ($updateUser1) {
            return response()->json(['status' => true,'code'=>200,'message'=>'Session update successfully']);
        } else{
            return response()->json(['status' => false,'message' => 'Link expired','code' => 400]);
        }
    }

     function update_sessionapp(Request $request)
     {
         $updateUser1 = SessionTable::where('sessionid',$request->id)
                                ->update(['sapp' => $request->sapp]);
         if($updateUser1){
             return response()->json(['status'=>true,'code'=>200,'message'=>'Session update successfully']);
         } else {
            return response()->json(['status'=>false,'message'=>'Link expired','code'=>400]);
         }
     }

      function update_sessionoitems(Request $request)
      {
          $updateUser1 = SessionTable::where('sessionid',$request->id)
                                ->update(['snumitems' => $request->snumitems]);

          if ($updateUser1) {
              return response()->json(['status'=>true,'code'=>200,'message'=>'Session items update successfully']);
          } else {
              return response()->json(['status'=>false,'message'=>'Link expired','code'=>400]);
          }
      }

      function get_sessiononpause(Request $request)
      {
          $ugetsession1 = SessionTable::where('sessionid',$request->id)
                                ->first();
          if($ugetsession1){
              return response()->json(['status'=>true,'code'=>200,'dataa'=>$ugetsession1,'message'=>'Session items update successfully']);
          } else{
              return response()->json(['status'=>false,'message'=>'Link expired','code'=>400]);
          }
      }

      function updateviewdatainter(Request $request)
      {
          $moodarr = [];
          $themearrr = [];
          $ugetinter1 = Interaction::where('itemid',$request->id)->first();
          $vnum1 =  1;

          $userData = User::where('userid',$request->user_id)->first();
          $PoemData = Item::where('itemid',$request->id)->first();

          // echo $PoemData;
          $countries = is_null($userData) ? null : Country::where('id',$userData['ucountry_id'])->first();
          $sdate = date('Y-m-d H:i:s', strtotime($request->last_view_start));

          if ($PoemData['itheme1']) {
              array_push($themearrr, $PoemData['itheme1']);
          }

          if ($PoemData['itheme2']) {
              array_push($themearrr, $PoemData['itheme2']);
          }

          if ($PoemData['itheme3']) {
              array_push($themearrr, $PoemData['itheme3']);
          }

          if ($PoemData['itheme4']) {
              array_push($themearrr, $PoemData['itheme4']);
          }

          if ($PoemData['itheme5']) {
              array_push($themearrr, $PoemData['itheme5']);
          }

          if($PoemData['imood1']) {
              array_push($moodarr, $PoemData['imood1']);
          }

          if ($PoemData['imood2']) {
              array_push($moodarr, $PoemData['imood2']);
          }

          if ($PoemData['imood3']) {
              array_push($moodarr, $PoemData['imood3']);
          }

          $updateintr1 =  Interaction::create([
              'userid'                  => $request->user_id,
              'ucountry_id'             => is_null($userData) ? null : $userData['ucountry_id'],
              'visitorid'               => $request->user_id,
              'vcountry'                => is_null($countries) ? null : $countries['iso'],
              'itemid'                  => $request->id,
              'creatorid'               => $PoemData['creatorid'],
              'iyear'                   => $PoemData['iyear'],
              'itheme1'                 => $PoemData['itheme1'],
              'itheme2'                 => $PoemData['itheme2'],
              'itheme3'                 => $PoemData['itheme3'],
              'itheme4'                 => $PoemData['itheme4'],
              'itheme5'                 => $PoemData['itheme5'],
              'imood1'                  => $PoemData['imood1'],
              'imood2'                  => $PoemData['imood2'],
              'imood3'                  => $PoemData['imood3'],
              'itheme_ids'              => implode(",",$themearrr),
              'imood_ids'               => implode(",",$moodarr),
              'inum_words'              => $PoemData['inum_words'],
              'inum_words_bin'          => $PoemData['inum_words_bin'],
              'inum_lines'              => $PoemData['inum_lines'],
              'inum_words_per_line'     => $PoemData['inum_words_per_line'],
              'inum_words_per_line_bin' => $PoemData['inum_words_per_line_bin'],
              'rtheme'                  => implode(",",$themearrr),
              'rmood'                   => implode(",",$moodarr),
              'received_email'          => is_null($userData) ? null : $userData['urec_email'] == 0 ? 0 : 1,
              'received_push'           => is_null($userData) ? null : $userData['urec_push'] == 0 ? 0 : 1,
              'received_online'         => is_null($userData) ? null : ($userData['urec_email'] == 1 || $userData['urec_push'] == 1) ? 0 : 1,
              'view_num'                => $vnum1,
              'last_view_start'         => $sdate,
              // 'last_view_end'        =>$request->description,
              // 'last_view_duration'   =>$request->description,
              // 'collection'           =>$request->description,
              // 'register'             =>$request->description
          ]);

          if ($updateintr1) {
              $lastActivity = Interaction::latest()->first();

              return response()->json(['status'=>true,'code'=>200,'dataa'=>$lastActivity->id,'message'=>'Interaction view update successfully']);
          } else{
              return response()->json(['status'=>false,'message'=>'Error','code'=>400]);
          }
      }

     function updateviewdatainterend(Request $request){

           $ugetinter11 = Interaction::where('id',$request->id)
                                ->first();
                  $edate = date('Y-m-d H:i:s', strtotime($request->last_view_end));
                  $newenddaqte =  Carbon::parse($edate);
                  $stdate = is_null($ugetinter11) ? null : Carbon::parse($ugetinter11['last_view_start']);
                   $differenceInSeconds = $newenddaqte->diffInSeconds($stdate);

                                 $updateintr1 = Interaction::where('id',$request->id)
                                ->update([
                                        'last_view_end'=>$edate,
                                        'last_view_duration'=>$differenceInSeconds,
                                    ]);
    if ($updateintr1) {
           return response()->json(['status'=>true,'code'=>200,'dataa'=>$updateintr1,'message'=>'Interaction view update successfully']);
        } else{
            return response()->json(['status'=>false,'message'=>'Error','code'=>400]);
        }
    }

   public function unsubscribeemailverification(Request $request){
        $user = User::where('userid',$request->userid)
                    ->first();
        if($user->email_token!=null){
            $email = $user->uemail;
            $updateUser = User::where('userid',$request->userid)
                                ->update([
                                        'email_token'=>'',
                                        'send_recommened_poem'=>0,
                                        'recommend_poem'=>NULL,
                                    ]);
           return response()->json(['status'=>true,'code'=>200,'message'=>'You were successfully unsubscribed from email recommendations.']);
        } else{
            return response()->json(['status'=>false,'message'=>'Link expired','code'=>400]);
        }
    }

    //  function userEmailVerification(Request $request,$userid){
    //     $user = PasswordReset::where('userid',$userid)
    //                 ->first();
    //     // dd($user);
    //     if($user->token!=null){
    //         $email = $user->uemail;
    //         $updateUser = PasswordReset::where('userid',$userid)
    //                             ->update([
    //                                     'token'=>'',
    //                                     // 'email_verification_status'=>1,
    //                                 ]);
    //        return response()->json(['status'=>true,'code'=>200,'message'=>'Email verified successfully']);
    //     } else{
    //         return response()->json(['status'=>false,'message'=>'Link expired','code'=>400]);
    //     }
    // }

    public function userRegistration(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make(
                $request->all(),
                [
                    'user_name'     => 'required',
                    'password'      => 'required',
                    'uemail'         => 'required|email'
                ]
            );

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 200);
            }

            $check_email_exists = User::where('uemail',$input['uemail'])->first();
            $check_uname_exists = User::where('user_name',$input['user_name'])->first();
                 if($check_uname_exists) {
                return response()->json(['status' => false,'message' => 'Username already exists, please try a different one', 'code' => 400]);
            }
            if ($check_email_exists) {
                return response()->json(['status' => false,'message' => 'This Email already exists', 'code' => 400]);
            }

            else{
                $random_no  = rand(111111, 999999);
            $code       = $random_no.time();
            $security_code = base64_encode(convert_uuencode($code));

            $user_id =User::create([
                    'user_name'                  =>$request->user_name,
                    'uemail'                     =>$request->uemail,
                    'uregistration_time'         =>date('Y-m-d H:i:s'),
                    'ucountry_id'                =>@$request->ucountry_id,
                    'ulast_visit_time'           =>@$request->ulast_visit_time,
                    // 'ul7'                        =>@$request->ul7,
                    // 'ul30'                       =>@$request->ul30,
                    'urec_email'     =>($request->send_recommened_poem == "true") ? 1 : 0,
                    'urec_email_freq'            =>@$request->urec_email_freq,
                    'urec_email_time'            =>@$request->urec_email_time,
                    'urec_push'      =>($request->send_notification == "true")  ? 1: 0,
                    'urec_push_freq'             =>@$request->urec_push_freq,
                    'urec_push_time'             =>@$request->urec_push_time,
                    'ucollection_num'            =>@$request->ucollection_num,
                    'ucollection_recent_time'    =>@$request->ucollection_recent_time,
                    'udonate_sum'                =>@$request->udonate_sum,
                    'udonate_recent_time'        =>@$request->udonate_recent_time,
                    'uupload_old_recent_time'    =>@$request->uupload_old_recent_time,
                    'password'                   =>Hash::make($request->password),
                    'remember_me'                =>@$request->remember_me,
                    'subscribe_me'               =>@$request->subscribe_me,
                    'send_recommened_poem'       =>@$request->send_recommened_poem,
                    'send_notification'          =>@$request->send_notification,
                    'security_code'              =>$security_code
             ])->id;

            $project_name = 'Green Pheasants';
            $user_name  = $request->user_name;

            $set_password_url = 'https://www.greenpheasants.com/#/email-verification/'.$user_id.'/afterregister';
            $email = $request->uemail;
            Mail::send('backend.emails.userVerificationMail',['name'=>$user_name,'email'=>$email,'set_password_url'=>$set_password_url],function($message) use($email,$project_name){
                $message->to($email,$project_name)->subject('Green Pheasants email verification');
                $message->from($this->toEmail,"Green Pheasants");
            });

            return response()->json(['status'=>true,'code'=>200,'message'=>'User registration successful']);
            }



        }catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
        }
    }

      public function sendwelcomemail(Request $request){
        $userdata = User::where('userid',$request->user_id)->first();
          $project_name = 'Green Pheasants';
            $user_name  = $userdata->user_name;
            $email  = $userdata->uemail;
            Mail::send('backend.emails.welcome',['name'=>$user_name,'email'=>$email],function($message) use($email,$project_name){
                $message->to($email,$project_name)->subject('Green Pheasants - Registration confirmation');
                $message->from($this->toEmail,"Green Pheasants");
            });
      }

 public function sendreasonmail(Request $request){
          $project_name = 'Green Pheasants';
            $user_name  = $request->user_name;
            $user_email  = $request->user_email;
            $reason  = 'Reason:- '.$request->content;
             $heading  = 'Detail of deleted account user:-';
            $email = 'delete_account@greenpheasants.com';
            Mail::send('backend.emails.reasonmail',['name'=>$user_name,'user_email'=>$user_email,'email'=>$email,'reason'=>$reason,'heading'=> $heading],function($message) use($email,$project_name){
                $message->to($email,$project_name)->subject('Green Pheasants - Feedback');
                $message->from($this->toEmail,"Green Pheasants");
            });
            return response()->json(['status'=>true,'code'=>200,'message'=>'Email send successfully']);
      }




        public function updatefcmtoken(Request $request){
          User::where('userid',$request->id)
                            ->update([
                                        'fcm_token'        =>$request->fcm_token,
                                    ]);
 return response()->json(['status'=>true,'code'=>200,'message'=>'Token Updated successfully']);
      }



        public function sendbothpoemonfcm(Request $request){
            $common_model = new Common();
         $userdata = User::where('userid',$request->id)->first();

        if($request->mobile_tym == 0){
            // echo $userdata->fcm_token;
                    CronRecommendPoemOneDay::create([
                            'userid'  => $request->id,
                            'mobile_token'   => $userdata->fcm_token ? $userdata->fcm_token : '',
                            'type'    => 3
                        ]);
        }

        if($request->mobile_tym == 1){
                     CronRecommendPoemWeekDay::create([
                            'userid'  => $request->id,
                            'mobile_token'   => $userdata->fcm_token ? $userdata->fcm_token : '',
                            'type'    => 3
                        ]);
        }

         if($request->mobile_tym == 2){
                    CronRecommendPoemMonthDay::create([
                            'userid'  => $request->id,
                            'mobile_token'   => $userdata->fcm_token ? $userdata->fcm_token : '',
                            'type'    => 3
                        ]);
        }

            if($request->email_tym == 0){
                    CronRecommendPoemOneDay::create([
                            'userid'  => $request->id,
                            'email'   => $request->email,
                            'type'    => 3
                        ]);
        }

           if($request->email_tym == 1){
                     CronRecommendPoemWeekDay::create([
                            'userid'  => $request->id,
                            'email'   => $request->email,
                            'type'    => 3
                        ]);
        }

         if($request->email_tym == 2){
                    CronRecommendPoemMonthDay::create([
                            'userid'  => $request->id,
                            'email'   => $request->email,
                            'type'    => 3
                        ]);
        }

         return response()->json(['status'=>true,'code'=>200,'userdata' => $userdata,'message'=>'Poem Send successfully on your email and mobile']);


    }

        public function sendpoemonfcm(Request $request){
            $common_model = new Common();
         $userdata = User::where('userid',$request->id)->first();
       $getpoem = Item::with('poemFullDetail')
                        ->where('approved_by_admin',1)
                        ->where('userid','!=',$request->id)
                        ->inRandomOrder()
                        ->first();

        if($request->cron_val == 0){
                    CronRecommendPoemOneDay::create([
                            'userid'  => $request->id,
                            'mobile_token'   => $userdata->fcm_token ? $userdata->fcm_token : '',
                            'type'    => 2
                        ]);
        }

          if($request->cron_val == 1){
                     CronRecommendPoemWeekDay::create([
                            'userid'  => $request->id,
                            'mobile_token'   => $userdata->fcm_token ? $userdata->fcm_token : '',
                            'type'    => 2
                        ]);
        }

          if($request->cron_val == 2){
                    CronRecommendPoemMonthDay::create([
                            'userid'  => $request->id,
                            'mobile_token'   => $userdata->fcm_token ? $userdata->fcm_token : '',
                            'type'    => 2
                        ]);
        }

    // echo $poemfulldata;
//      if($userdata->fcm_token){
//         $poemfulldata = PoemText::inRandomOrder()->where('approved_by_admin',1)->first();

// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "POST",
//   CURLOPT_POSTFIELDS => "{\r\n\r\n \"notification\": {\r\n\r\n  \"title\": \"$poemfulldata->ititle\",\r\n\r\n  \"body\": \"$poemfulldata->itext\",\r\n   \"click_action\": \"https://www.greenpheasants.com/#/poem/$poemfulldata->itemid\"\r\n\r\n },\r\n\r\n \"to\" : \"$userdata->fcm_token\"\r\n\r\n}",
//   CURLOPT_HTTPHEADER => array(
//     "authorization: key=AAAAVmzbmX0:APA91bG-k3Ff6nZ4y3RWq6dhy6ihnukSPL3ic8PJreOc4wdL7MtrIQpbiDEk0IEeZW5_MFMapff_p6_OuG28YqY3wLFPsEN_FtGgSTrdzjkEXTY5oudrJNiR_8JBpKRvhxYOXMFVq07w",
//     "cache-control: no-cache",
//     "content-type: application/json",
//     "postman-token: af98a951-d6d7-6926-f0da-7a7601ff1082"
//   ),
// ));

// $response = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

// if ($err) {
//   // echo "cURL Error #:" . $err;
// } else {
//   // echo $response;
// }
//          }
 return response()->json(['status'=>true,'code'=>200,'userdata' => $userdata,'poemdata' => $getpoem,'message'=>'Poem Send successfully on your mobile']);
      }



       public function userLogin(Request $request)
       {
            try {
                $credentials = $request->only('uemail', 'password');
                $input = $request->all();
                $validator = Validator::make(
                    $request->all(),
                    [
                        'uemail'      => 'required',
                        'password'   => 'required'
                    ]
                );

                if ($validator->fails()) {
                    $response['code'] = 404;
                    $response['status'] = $validator->errors()->first();
                    $response['message'] = "missing parameters";
                    return response()->json($response);
                }

                $checkDataEmail = User::where('uemail',$input['uemail'])
                                    ->orWhere('user_name',$input['uemail'])
                                    ->first();
                if ($checkDataEmail){
                    if ($checkDataEmail->email_verification_status == 1){
                        if ((Hash::check($request->password, $checkDataEmail->password))){
                            $token = JWTAuth::attempt(['uemail' => $checkDataEmail->uemail, 'password' => $request->password ]);

                            User::where('uemail',$checkDataEmail->uemail)
                                ->update([
                                            'jwt_token'        =>$token,
                                            'ulast_visit_time' =>date('Y-m-d H:i:s'),
                                        ]);

                            return response()->json(['status' => true,'message' => 'Welcome, '.$checkDataEmail->user_name,'data' => $checkDataEmail,'token' => $token,'code' => 200]);
                        } else {
                            return response()->json(['status' => false,'message' => 'Password did not match', 'code' => 400]);
                        }
                    } else {
                        return response()->json(['status' => false,'message' => 'Email verification is pending', 'code' => 400]);
                    }
                } else {
                    return response()->json(['status' => false,'message' => 'Email did not exist in database', 'code' => 400]);
                }

            } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
                return response()->json(['error' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
            }
       }

       public function resetPassword(Request $request)
       {
           $input = $request->all();

            $validator = Validator::make(
                $request->all(),
                [
                    'password' => 'required'
                ]
            );

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 200);
            }

            $check_otp_exists = PasswordReset::where('token',$input['token'])->first();
            // dd($check_otp_exists,$input);
            if ($check_otp_exists) {
                User::where('userid', $check_otp_exists['user_id'])
                      ->update([
                                'password' => Hash::make($input['password'])
                        ]);
                PasswordReset::where('user_id',$check_otp_exists['user_id'])->delete();

                return response()->json(['status' => true,'code'=>200,'message' => 'Password reset successfully']);
            } else {
                return response()->json(['status' => false, 'message' => 'Token does not match.','code' => 400]);
            }
       }

        public function contactUs(Request $request)
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'     => 'required',
                    'message'  => 'required',
                    'email'    => 'required|email'
                ]
            );

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 200);
            }

            ContactUs::create([
                'name'      =>$request->name,
                'email'     =>$request->email,
                'message'   =>$request->message
            ]);

            $project_name = 'Green Pheasants';
            $user_name  = $request->name;
            $user_email  = $request->email;
            $reason  = 'Query:- '.$request->message;
            $heading  = 'User detail and query:-';
            $email = 'contact_form@greenpheasants.com';

            Mail::send('backend.emails.reasonmail', ['name'=>$user_name,'user_email'=>$user_email,'email'=>$email,'reason'=>$reason,'heading' => $heading], function ($message) use ($email, $project_name) {
                $message->to($email, $project_name)->subject('Green Pheasants - Contact Us');
                $message->from($this->toEmail,"Green Pheasants");
            });

            return response()->json(['status' => true,'code'=>200,'message' => 'Message send successfully']);
        }

        public function forgotPassword(Request $request)
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'uemail'      => 'required|email',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 200);
            }

            $email_verification_token = Str::random(64);

            $check_email_exists = User::where('uemail',$request['uemail'])->first();

            if (empty($check_email_exists)) {
                return response()->json(['status' => false,'message' => 'Email not exists.','code' => 400]);
            }

            PasswordReset::create([
                'user_id'  =>$check_email_exists['userid'],
                'email'    =>$request['uemail'],
                'token'    =>$email_verification_token
            ]);

            $project_name   = env('App_name');
            $email          = $request['uemail'];

            $mailData['link'] ='http://3.238.14.13/#/reset-password'.'/'.$email_verification_token;

            $replace_with     = ['user_name'=>$check_email_exists['user_name'],'uemail'=>$check_email_exists['uemail'],'email_verification_link'=>$mailData['link']];

            try {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    Mail::send('emails.forgotPasswordMail', ['data' => $replace_with], function ($message) use ($email, $project_name) {
                        $message->to($email, $project_name)->subject('Reset Password - Green Pheasants');
                    });
                }
            } catch (Exception $e) {
                dd('here');
            }

            return response()->json(['status' => true, 'message' => 'Reset password link has been sent on your registered email, Please check.','code' => 200], Response::HTTP_OK);
        }

        public function getFaqList(Request $request)
        {
            $getFaq = Faq::orderby('id','desc')->get();

            return response()->json(['status' => true,'message'=>'Get Faq list successfully','data' =>$getFaq,'code' => 200]);
        }

    public function getTermCondtion(Request $request){
        $getTermCondtion = Terms::orderby('id','desc')->first();
        return response()->json(['status' => true,'message'=>'Get terms & condition data successfully','data' =>$getTermCondtion,'code' => 200]);
    }

    public function getPrivacyPolicy(Request $request){
        $getPrivacyPolicy = PrivacyPolicy::orderby('id','desc')->first();
        // dd($request->all());
        return response()->json(['status' => true,'message'=>'Get privacy & policy data successfully','data' =>$getPrivacyPolicy,'code' => 200]);
    }

    public function getAboutUsData(Request $request){
        $getAboutUsData = AboutUs::orderby('id','desc')->first();
        return response()->json(['status' => true,'message'=>'Get about us data successfully','data' =>$getAboutUsData,'code' => 200]);
    }

    // public function getProfileData(Request $request){
    //     if(isset($request->sessionid)){
    //         if($request->userid){
    //             // SessionTable
    //             $userData = User::where('userid',$request->userid)->first();
    //             return response()->json(['status' => true,'message'=>'Get user data successfully','data' =>$userData,'code' =>200]);
    //         }else{
    //             $getData  = SessionService::updateSession($request);
    //             $userData='';
    //             return response()->json(['status' => true,'message'=>'Get user data successfully','data' =>$userData,'sessionData'=>$getData,'code' =>200]);
    //         }
    //     }else{
    //          // SessionTable
    //         if($request->userid!=0){
    //             $getData  = SessionService::createSession($request);
    //             // dd('in');
    //             $userData =  User::where('userid',$request->userid)->first();
    //             return response()->json(['status' => true,'message'=>'Get user data successfully','data' =>$userData,'sessionData'=>$getData,'code' =>200]);
    //         }else{
    //             $getData  = SessionService::createSession($request);
    //             $userData='';
    //             return response()->json(['status' => true,'message'=>'Get user data successfully','data' =>$userData,'sessionData'=>$getData,'code' =>200]);
    //         }
    //     }
    // }

    public function getSessionData(Request $request){
        if(isset($request->sessionid)){
            $getData  = SessionService::updateSession($request);
            return response()->json(['status' => true,'message'=>'Update session data successfully','data' =>$getData,'code' =>200]);
        }else{
            $getData  = SessionService::createSession($request);
            return response()->json(['status' => true,'message'=>'Get session data successfully','data' =>$getData,'code' =>200]);
        }
    }

    public function getProfileData(Request $request){
        if($request->userid){
            $userData =  User::where('userid',$request->userid)->first();
            return response()->json(['status' => true,'message'=>'Get user data successfully','data' =>$userData,'code' =>200]);
        }else{
            return response()->json(['status' => false, 'message' => 'Something went wrong, Please try again later.','code' => 400]);
        }
    }

    public function updateProfileData(Request $request,$userId){

        $checkUserNameExist = User::where('userid','!=',$userId)
                                  ->where('user_name',$request->user_name)
                                  ->first();

        if($checkUserNameExist) {
            return response()->json(['status'=>false,'message'=>'User name already exist,kindly choose another one','code'=>400]);
        }
        // dd($request->all(),$userId);
        User::where('userid',$userId)
              ->update([
                'user_name'    =>$request->user_name,
                'subscribe_me' =>($request->subscribe_me == 'true') ? 1: 0,
                'send_recommened_poem'  =>($request->send_recommened_poem == 'true') ? 1 : 0,
                'send_notification'     =>($request->send_notification == 'true')  ? 1: 0

                // 'subscribe_me' =>($request->subscribe_me == true || $request->subscribe_me == 1) ? 1: 0,
                // 'send_recommened_poem'  =>($request->send_recommened_poem == true || $request->send_recommened_poem == 1) ?1 : 0,
                // 'send_notification'     =>($request->send_notification == true || $request->send_notification == 1)  ? 1: 0
              ]);
              // $tokengen = rand();
              // User::where('userid',$userId)
              // ->update([
              //   'email_token' =>$tokengen,
              // ]);
              //   $currentUser = User::where('userid',$userId)->first();
              //   $poemRandom   = PoemText::inRandomOrder()->first();
              //               $project_name = 'Green Pheasants';
              //               $user_name    =   ucfirst($currentUser['user_name']);
              //               $email        =   $currentUser['uemail'];
              //               $poemDetail   =   $poemRandom['itext'];
              //               Mail::send('backend.emails.sendEmailPerDay',['name'=>$user_name,'email'=>$email,'poemDetail'=>$poemDetail,'user_id'=>$userId,'token'=> $tokengen],function($message) use($email,$project_name){
              //                   $message->to($email,$project_name)->subject('Recommend poem for you on daily basis');
              //                   $message->from('deepakindiit@gmail.com',"Green Pheasants");
              //               });

        return response()->json(['status' => true,'message'=>'Profile updated successfully','data'=>$request->user_name,'code' =>200]);
    }

    public function updatePassword(Request $request,$userId){
        User::where('userid',$userId)
              ->update([
                'password' =>Hash::make($request->password),
              ]);
        return response()->json(['status' => true,'message'=>'Password updated successfully','code' =>200]);
    }

    public function getPoemMoodListData(Request $request){

        $getPoemMood = PoemMood::get();
        return response()->json(['status' => true,'message'=>'Get Poem Mood list successfully','data' =>$getPoemMood,'code' => 200]);
    }

    public function getPoemThemeListData(Request $request){
        $getPoemTheme = PoemTheme::get();
        return response()->json(['status' => true,'message'=>'Get Poem Theme list successfully','data' =>$getPoemTheme,'code' => 200]);
    }

    public function getAllPoemList(Request $request){
        $search  = $request->searchedValue;
        $result  = Item::leftjoin('poem_texts','poem_texts.itemid','items.itemid')
                        ->select('items.*','poem_texts.ititle as title','poem_texts.cname as cretor_name','poem_texts.itext as description')
                        // ->where('items.userid',$request->user_id)
                        ->where('items.approved_by_admin',1)
                        ->where(function ($query) use ($search) {
                            if($search){
                                $query->where('poem_texts.itext', 'LIKE', '%'.$search.'%');
                                $query->orWhere('poem_texts.ititle', 'LIKE', '%'.$search.'%');
                                 $query->orWhere('poem_texts.cname', 'LIKE', '%'.$search.'%');
                            }
                         })
                        ->orderby('id','desc')
                        ->get();
        // dd($result);
        return response()->json(['status' => true,'message'=>'Get All Poem list successfully','data'=> $result,'code' => 200]);
    }

    public function getPoemList(Request $request){
        $user_id         = $request->user_id;
        if(isset($request->searchedValue)){
            $search          = $request->searchedValue;

            $data['result']  =  Collection::where('user_id',$user_id)
                                            ->with('poemDetail',['poemFullDetail1' => function ($query)use ($search) {
                                                if($search){
                                                    $query->where('ititle', 'LIKE', '%'.$search.'%');
                                                    $query->orWhere('cname', 'LIKE', '%'.$search.'%');
                                                    $query->orWhere('itext', 'LIKE', '%'.$search.'%');
                                                }
                                            }])
                                            ->orderby('id','desc')
                                            ->orWhere('user_id',$user_id)
                                            ->get();
        }else{
            $input  = $request->all();

            $data['result']  =  Collection::with(['poemDetail' => function($q) {
                                                $q->where('approved_by_admin','=',1); // '=' is optional
                                            }])
                                            // with('poemDetail')
                                            ->where('user_id',$user_id)
                                            ->leftjoin('items','items.itemid','collections.item_id')
                                            ->leftjoin('poem_texts','poem_texts.itemid','collections.item_id')
                                            ->select('collections.*','poem_texts.ititle as title','poem_texts.cname as cretor_name','poem_texts.itext as description')
                                            ->where('items.approved_by_admin',1)
                                            ->orderby('id','desc')
                                            ->take(4)
                                            ->get();
                                        // dd($data['result']);
            $result1 =  Collection::with(['poemDetail' => function($q) {
                                            $q->where('approved_by_admin','=',1); // '=' is optional
                                        }])
                                        // with('poemDetail')
                                        ->where('user_id',$user_id)
                                        ->leftjoin('items','items.itemid','collections.item_id')
                                        ->leftjoin('poem_texts','poem_texts.itemid','collections.item_id')
                                        ->select('collections.*','poem_texts.ititle as title','poem_texts.cname as cretor_name','poem_texts.itext as description')
                                        ->where('items.approved_by_admin',1)
                                        ->orderby('id','desc')
                                        ->skip(4)
                                        ->take(4)
                                        ->get();

            $data['next']     = count($result1);
            $data['offset']   = $request->offset;
        }
        return response()->json(['status' => true,'code'=>200,'data'=>$data,'message' => 'Get Poem list successfully']);
    }

    public function load_more_poem(Request $request){
        $input   = $request->all();
        $offset  = $request->offset;
        $user_id = $request->user_id;

        $data['result']  =  Collection::with(['poemDetail' => function($q) {
                                            $q->where('approved_by_admin','=',1); // '=' is optional
                                        }])
                                    // with('poemDetail')
                                    ->where('user_id',$user_id)
                                    ->leftjoin('poem_texts','poem_texts.itemid','collections.item_id')
                                    ->select('collections.*','poem_texts.ititle as title','poem_texts.cname as cretor_name','poem_texts.itext as description')
                                   ->orderby('id','desc')
                                   ->skip($offset)
                                   ->take(4)
                                   ->get();

        $result22   =   Collection::with(['poemDetail' => function($q){
                                        $q->where('approved_by_admin','=',1); // '=' is optional
                                    }])
                                    ->where('user_id',$user_id)
                                    ->leftjoin('poem_texts','poem_texts.itemid','collections.item_id')
                                    ->select('collections.*','poem_texts.ititle as title','poem_texts.cname as cretor_name','poem_texts.itext as description')
                                    ->orderby('id','desc')
                                    ->skip($offset+4)
                                    ->take(4)
                                    ->get();

        $data['offset'] = $offset+4;
        $data['next']   = count($result22);

        return response()->json(['status' => true,'code'=>200,'data'=>$data,'message' => 'Get Poem data list']);
    }

    public function getPoemDetail(Request $request,$poemId){
        $poem = Item::where('itemid',$poemId)
                      ->with('poemFullDetail')
                      ->where('approved_by_admin',1)
                      // ->orderby('itemid','desc')
                      ->first();


        if($request->userid && $request->userid!=0){
            $collection  = Collection::with('poemDetail')
                                    ->where('item_id', $poemId)
                                    ->where('user_id',$request->userid)
                                    ->exists();
            $poem->collection_status = $collection;

             $userdata = User::where('userid',$request->userid)
                      ->first();

           $poem->is_user_recommend = $userdata->recommend_poem;
        }

        return response()->json(['status' => true,'message'=>'Get Poem Details','data' =>$poem,'code' => 200]);
    }

    public function deletePoem(Request $request, $poemId){
        $checkPoemIdExist =  Collection::where('item_id',$poemId)
                                ->first();
        if($checkPoemIdExist){
            Collection::where('item_id',$poemId)->delete();

            Item::where('itemid',$poemId)->delete();
            PoemText::where('itemid',$poemId)->delete();
            Interaction::where('itemid',$poemId)->delete();

            return response()->json(['status' => true,'message'=>'Poem deleted from collection successfully','code' => 200]);
        }else{
            return response()->json(['status' => false,'message'=>'Poem not found','code' => 400]);
        }
    }

    public function removePoem(Request $request, $poemId){
        $checkPoemIdExist =  Collection::where('item_id',$poemId)
                                ->first();

        if($checkPoemIdExist){
            Collection::where('item_id',$poemId)->delete();

            return response()->json(['status' => true,'message'=>'Poem removed from collection successfully','code' => 200]);
        }else{
            return response()->json(['status' => false,'message'=>'Poem not found','code' => 400]);
        }
    }

     public function deleteaccount(Request $request){

        if($request->userid){
              $userdata = User::where('userid',$request->userid)->first();
            User::where('userid',$request->userid)->delete();


             // print_r($userdata);
          $project_name = 'Green Pheasants';
            $user_name  = $userdata->user_name;
            $email  = $userdata->uemail;
            $content  = 'Your account has been deleted!';
            Mail::send('backend.emails.sorrytosee',['name'=>$user_name,'email'=>$email,'content'=>$content],function($message) use($email,$project_name){
                $message->to($email,$project_name)->subject('Green Pheasants account deletion');
                $message->from($this->toEmail,"Green Pheasants");
            });

            return response()->json(['status' => true,'message'=>'User deleted successfully','code' => 200]);
        }else{
            return response()->json(['status' => false,'message'=>'Error while deleting your account, please try again!','code' => 400]);
        }
    }

    public function getAllCreatorList(){
        $creators = Creator::selectRaw('creatorid as id , cname as name')
                            ->orderby('creatorid','asc')
                            ->get();

        return response()->json(['status' => true,'data'=> $creators,'message'=>'Get Creator list  successfully','code' => 200]);
    }

     function recommendPoem(Request $request)
     {
         $selected_term = $request->poemType;
         $flag = $request->flag;

         $theme = 'all';
         $mood = 'all';

         //Get the mood ot theme
         switch ($flag) {
             case 1:
                 break;
             case 2:
                 $theme = $selected_term;
                 break;
             case 3:
                 $mood = $selected_term;
                 break;
         }

         if ($request -> userid && $request -> userid != 0) {
             // Get result from python script
             $getpoem = $this->runPythonScript($theme, $mood, 'user');
         } else {
             // Get result from python script
             $getpoem = $this->runPythonScript($theme, $mood, 'visitor');
         }

         if ($getpoem) {
             return response()->json(['status' => true,'data' => $getpoem,'message' => 'Get recommend poem list  successfully','code' => 200]);
         } else {
             return response()->json(['status' => false,'message' => 'No record found','code' => 400]);
         }
     }

    public function runPythonScript($theme, $mood, $userType)
    {
        //Path to anaconda environment
        $condaEnvironment = "/opt/homebrew/Caskroom/miniconda/base/envs/adminGreen";

        // Run the second script with 2 parameters: theme, mood
        $scriptRoute = "../python_scripts/choose_item_online_$userType.py $theme $mood";

        //Create a command with anaconda environment
        $command = "$condaEnvironment/bin/python $scriptRoute 2>&1";

        //Get the result in json format and decode it
        exec($command, $output);

        $jsonResult = end($output);

        $result = json_decode($jsonResult, true);

        //Get the poemDetail from Item table by recommended_item
        return Item::with('poemFullDetail')->where('itemid', $result[0]['recommended_item'])->first();
    }

    public function addPoem(Request $request){
        $check_words_bin='';
        // dd($request->all());
        $str_word_count = str_word_count($request->description);

        if($str_word_count<=19){
            $check_words_bin ='1-19';
        }elseif($str_word_count<=49){
            $check_words_bin ='20-49';
        }elseif($str_word_count<=99){
            $check_words_bin ='50-99';
        }elseif($str_word_count<=149){
            $check_words_bin ='100-149';
        }elseif($str_word_count<=199){
            $check_words_bin ='150-199';
        }elseif($str_word_count<=249){
            $check_words_bin ='200-249';
        }elseif($str_word_count<=299){
            $check_words_bin ='250-299';
        }elseif($str_word_count<=399){
            $check_words_bin ='300-399';
        }elseif($str_word_count<=599){
            $check_words_bin ='400-599';
        }elseif($str_word_count<=999){
            $check_words_bin ='600-999';
        }elseif($str_word_count<=1999){
            $check_words_bin ='1000-1999';
        }else{
            $check_words_bin ='2000+';
        }

        // $inum_lines  = substr_count($request->description, "\n" );

        $inum_lines     = substr_count( $request->description, "\n" ) +1;

        // dd($str_word_count,$inum_lines,$countwe);

        $inum_words_per_line  = $str_word_count/$inum_lines;

        $inum_words_per_line_bin='';

        if($inum_words_per_line<=3){
            $inum_words_per_line_bin ='0-3';
        }elseif($inum_words_per_line<=7){
            $inum_words_per_line_bin ='4-7';
        }elseif($inum_words_per_line<=10){
            $inum_words_per_line_bin ='8-10';
        }elseif($inum_words_per_line<=14){
            $inum_words_per_line_bin ='11-14';
        }elseif($inum_words_per_line<=19){
            $inum_words_per_line_bin ='15-19';
        }else{
            $inum_words_per_line_bin ='20+';
        }

        if($request->poet){
            $checkPoet = Creator::where('cname',$request->poet)->first();
        }else{
           $creator_id = Creator::create([
                                    'cname' =>$request->otherPoet,
                                ])->id;
        }
        // dd($request->all());
        $poemId =   Item::create([
                        'creatorid'     =>$request->poet!=null ? $checkPoet['creatorid'] : $creator_id,
                        'userid'        =>$request->user_id,
                        'cname'         =>$request->poet ? $request->poet:$request->otherPoet,
                        'ititle'        =>@$request->title,
                        'iyear'         =>@$request->iyear,
                        'notify_by'     =>@$request->notify_via,
                        'itheme1'       =>@$request->poem_theme_selected[0]['item_text'],
                        'itheme2'       =>@$request->poem_theme_selected[1]['item_text'],
                        'itheme3'       =>@$request->poem_theme_selected[2]['item_text'],
                        'itheme4'       =>@$request->poem_theme_selected[3]['item_text'],
                        'itheme5'       =>@$request->poem_theme_selected[4]['item_text'],
                        'imood1'        =>@$request->poem_mood_selected[0]['item_text'],
                        'imood2'        =>@$request->poem_mood_selected[1]['item_text'],
                        'imood3'        =>@$request->poem_mood_selected[2]['item_text'],
                        'icontent_url'             =>@$request->source_link,
                        'curl'                     =>@$request->source_link,
                        'ctext'                     =>@$request->source,
                        'item_text1'               =>@$request->additional_links[0]['itext'],
                        'item_text2'               =>@$request->additional_links[1]['itext'],
                        'item_text3'               =>@$request->additional_links[2]['itext'],
                        'iadd_url_1'               =>@$request->additional_links[0]['url'],
                        'iadd_url_2'               =>@$request->additional_links[1]['url'],
                        'iadd_url_3'               =>@$request->additional_links[2]['url'],
                        'inum_words'               =>$str_word_count,
                        'inum_words_bin'           =>$check_words_bin,
                        'inum_lines'               =>$inum_lines,
                        'inum_words_per_line'      =>(int)$inum_words_per_line,
                        'inum_words_per_line_bin'  =>$inum_words_per_line_bin
                    ])->id;

        PoemText::create([
            'itemid'        =>$poemId,
            'ititle'        =>@$request->title,
            'creatorid'     =>$request->poet!=null ? $checkPoet['creatorid']:$creator_id,
            'cname'         =>$request->poet ? $request->poet:$request->otherPoet,
            'iyear'         =>@$request->iyear,
            'icontent_url'  =>@$request->source_link,
            'ctext'         =>@$request->source,
            'itext'         =>@$request->description
        ]);

        $userData = User::where('userid',$request->user_id)->first();
         $countries = Country::where('id',$userData['ucountry_id'])->first();
        // Interaction::create([
        //     'userid'             =>$request->user_id,
        //     'ucountry_id'        =>$userData['ucountry_id'],
        //     'visitorid'       =>$request->user_id,
        //     'vcountry'        =>$countries['iso'],
        //     'itemid'             =>$poemId,
        //     'creatorid'           =>$request->poet!=null ? $checkPoet['creatorid']:$creator_id,
        //     'iyear'              =>@$request->iyear,
        //     'itheme1'       =>@$request->poem_theme_selected[0]['item_text'],
        //                 'itheme2'       =>@$request->poem_theme_selected[1]['item_text'],
        //                 'itheme3'       =>@$request->poem_theme_selected[2]['item_text'],
        //                 'itheme4'       =>@$request->poem_theme_selected[3]['item_text'],
        //                 'itheme5'       =>@$request->poem_theme_selected[4]['item_text'],
        //                 'imood1'        =>@$request->poem_mood_selected[0]['item_text'],
        //                 'imood2'        =>@$request->poem_mood_selected[1]['item_text'],
        //                 'imood3'        =>@$request->poem_mood_selected[2]['item_text'],
        //     'itheme_ids'  =>$request->poem_theme_selected ? implode(",", array_column($request->poem_theme_selected, "item_text")) : null,
        //     'imood_ids'   =>$request->poem_mood_selected ? implode(",", array_column($request->poem_mood_selected, "item_text")) : null,
        //     'inum_words'          =>$str_word_count,
        //     'inum_words_bin'      =>$check_words_bin,
        //     'inum_lines'          =>$inum_lines,
        //     'inum_words_per_line'      =>(int)$inum_words_per_line,
        //     'inum_words_per_line_bin'  =>$inum_words_per_line_bin,
        //     'rtheme'                    =>$request->poem_theme_selected ? implode(",", array_column($request->poem_theme_selected, "item_text")) : null,
        //     'rmood'                     =>$request->poem_mood_selected ? implode(",", array_column($request->poem_mood_selected, "item_text")) : null,
        //     'received_email'            =>$userData['urec_email'] == 0 ? 0 : 1,
        //     'received_push'             =>$userData['urec_push'] == 0 ? 0 : 1,
        //     'received_online'           =>($userData['urec_email'] == 1 || $userData['urec_push'] == 1) ? 0 : 1,
        //     // 'view_num'                  =>$request->description,
        //     // 'last_view_start'           =>$request->description,
        //     // 'last_view_end'             =>$request->description,
        //     // 'last_view_duration'        =>$request->description,
        //     // 'collection'                =>$request->description,
        //     // 'register'                  =>$request->description
        // ]);


        // if(isset($request->additional_links)){
        //     foreach ($request->additional_links as $key => $val1) {
        //         AdditionalLinkForPoem::create([
        //             'poem_id'       =>$poemId,
        //             'text'          =>$val1['text'],
        //             'url'           =>$val1['url']
        //         ]);
        //     }
        // }

        Collection::create([
            'user_id'=>$request->user_id,
            'item_id'=>$poemId
        ]);

        $poemCount =  Item::where('userid',$request->user_id)->count();

         User::where('userid',$request->user_id)
              ->update([
                'uupload_old_num'    =>(int)$poemCount,
                'uupload_old_recent_time' =>Carbon::now()->format('Y-m-d h:i:s'),
              ]);

        return response()->json(['status' => true,'message'=>'Please wait until admin approves your poem to be displayed in your collection and publically','code' => 200]);
    }

   public function getpaypl_plan(Request $request){

     $curl = curl_init();
              curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.sandbox.paypal.com/v1/catalogs/products",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "{\"name\":\"Monthly Package\",\r\n\"description\":\"Monthly Package\",\r\n\"type\":\"SERVICE\",\r\n\"category\":\"SOFTWARE\"\r\n}",
              CURLOPT_HTTPHEADER => array(
                "authorization: Basic QVd3TVNkQlVQMHltN25RdjJGV3M2OEFDRnpNVG51T2x3eGFHdmlkV1o1ODZ1STFBR1NiQTFyazVGc1JfV0dlQXE3XzRUVmx1WF9iSnd5dVk6RUpBZ2V1QW0xMy1xLTkwRjRMN0NWVmhUMUV3NjI4REM2M2U0TkdlWU14MUt6Z2VYaU5JeHNRLTVMSGJPUTRlTXBsOXAwQlNVLUV3Zk1NeWg=",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: d015881d-4778-792e-5261-f33c03d1098e",
                "token: Bearer"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
               $cdata = json_decode($response, true);
            //   echo $cdata;
              $prodid = $cdata['id'];
              // echo $prodid;
            }

        if($response){

               // $curl = curl_init();
               //  curl_setopt_array($curl, array(
               //    CURLOPT_URL => "https://api.sandbox.paypal.com/v1/billing/plans",
               //    CURLOPT_RETURNTRANSFER => true,
               //    CURLOPT_ENCODING => "",
               //    CURLOPT_MAXREDIRS => 10,
               //    CURLOPT_TIMEOUT => 30,
               //    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               //    CURLOPT_CUSTOMREQUEST => "POST",
               //    CURLOPT_POSTFIELDS => "{\r\n\"product_id\": \"$prodid\",\r\n\"name\": \"Basic Plan\",\r\n\"description\": \"Basic plan\",\r\n\"billing_cycles\": [\r\n\r\n{\r\n\"frequency\": {\r\n\"interval_unit\": \"MONTH\",\r\n\"interval_count\": $request->interval\r\n},\r\n\"tenure_type\": \"REGULAR\",\r\n\"sequence\": 1,\r\n\"total_cycles\": 1,\r\n\"pricing_scheme\": {\r\n\"fixed_price\": {\r\n\"value\": \"$request->amount\",\r\n\"currency_code\": \"$request->currencycode\"\r\n}\r\n}\r\n}\r\n],\r\n\"payment_preferences\": {\r\n\"service_type\": \"PREPAID\",\r\n\"auto_bill_outstanding\": true,\r\n\"setup_fee\": {\r\n\"value\": \"0.1\",\r\n\"currency_code\": \"$request->currencycode\"\r\n},\r\n\"setup_fee_failure_action\": \"CONTINUE\",\r\n\"payment_failure_threshold\": 3\r\n},\r\n\"quantity_supported\": true,\r\n\"taxes\": {\r\n\"percentage\": \"0.1\",\r\n\"inclusive\": false\r\n}\r\n}",
               //    CURLOPT_HTTPHEADER => array(
               //      "authorization: Basic QVd3TVNkQlVQMHltN25RdjJGV3M2OEFDRnpNVG51T2x3eGFHdmlkV1o1ODZ1STFBR1NiQTFyazVGc1JfV0dlQXE3XzRUVmx1WF9iSnd5dVk6RUpBZ2V1QW0xMy1xLTkwRjRMN0NWVmhUMUV3NjI4REM2M2U0TkdlWU14MUt6Z2VYaU5JeHNRLTVMSGJPUTRlTXBsOXAwQlNVLUV3Zk1NeWg=",
               //      "cache-control: no-cache",
               //      "content-type: application/json",
               //      "postman-token: 86e8c581-83ed-ba52-1b47-c70e9ed52335",
               //      "token: Bearer"
               //    ),
               //  ));

            $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sandbox.paypal.com/v1/billing/plans",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\r\n\"product_id\": \"$prodid\",\r\n\"name\": \"Basic Plan\",\r\n\"description\": \"Basic plan\",\r\n\"billing_cycles\": [\r\n\r\n{\r\n\"frequency\": {\r\n\"interval_unit\": \"MONTH\",\r\n\"interval_count\": 1\r\n},\r\n\"tenure_type\": \"REGULAR\",\r\n\"sequence\": 1,\r\n\"total_cycles\": 1,\r\n\"pricing_scheme\": {\r\n\"fixed_price\": {\r\n\"value\": \"$request->amount\",\r\n\"currency_code\": \"$request->currencycode\"\r\n}\r\n}\r\n}\r\n],\r\n\"payment_preferences\": {\r\n\"service_type\": \"PREPAID\",\r\n\"auto_bill_outstanding\": true,\r\n\"setup_fee\": {\r\n\"value\": \"0.1\",\r\n\"currency_code\": \"$request->currencycode\"\r\n},\r\n\"setup_fee_failure_action\": \"CONTINUE\",\r\n\"payment_failure_threshold\": 3\r\n},\r\n\"quantity_supported\": true,\r\n\"taxes\": {\r\n\"percentage\": \"0.1\",\r\n\"inclusive\": false\r\n}\r\n}",
  CURLOPT_HTTPHEADER => array(
    "authorization: Basic QVd3TVNkQlVQMHltN25RdjJGV3M2OEFDRnpNVG51T2x3eGFHdmlkV1o1ODZ1STFBR1NiQTFyazVGc1JfV0dlQXE3XzRUVmx1WF9iSnd5dVk6RUpBZ2V1QW0xMy1xLTkwRjRMN0NWVmhUMUV3NjI4REM2M2U0TkdlWU14MUt6Z2VYaU5JeHNRLTVMSGJPUTRlTXBsOXAwQlNVLUV3Zk1NeWg=",
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: 7415bf1b-584a-42b8-6bb4-d12a9c1e5733",
    "token: Bearer"
  ),
));

                $response1 = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                    $plandata = json_decode($response1, true);
                    // print_r($plandata);
                //   echo gettype($plandata);
                }

        }

        if($plandata){
            return response()->json(['status' => true,'plandata'=>$plandata,'message'=>'Plan get successfully','code' => 200]);
        }else{
            return response()->json(['status' => false,'message'=>'No record found','code' => 400]);
        }

    }


        function update_intraction(Request $request){

           Interaction::where('itemid',$request->id)
                            ->update([
                                'register'       =>1
                            ]);

        return response()->json(['status' => true,'message'=>'Data Updated','code' =>200]);
        }


        function addToCollection(Request $request){

        $checkCollection = Collection::where('user_id',$request->user_id)
                               ->where('item_id',$request->item_id)
                               ->first();

            Collection::create([
                            'user_id'  =>$request->user_id,
                            'item_id'  =>$request->item_id,
                            'added_by' =>1
                            ]);

          Interaction::where('itemid',$request->item_id)
                            ->update([
                                'collection'       =>1
                            ]);

                //    $checkitem = Item::where('itemid',$request->item_id)->first();
                //   $curl = curl_init();
                //   curl_setopt_array($curl, array(
                //   CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
                //   CURLOPT_RETURNTRANSFER => true,
                //   CURLOPT_ENCODING => "",
                //   CURLOPT_MAXREDIRS => 10,
                //   CURLOPT_TIMEOUT => 30,
                //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //   CURLOPT_CUSTOMREQUEST => "POST",
                //   CURLOPT_POSTFIELDS => "{\r\n\r\n \"notification\": {\r\n\r\n  \"title\": \"Green Pheasants - Poem added to collection\",\r\n\r\n  \"body\": \"10 users added your poem $checkitem->ititle to their collection\",\r\n  \"click_action\": \"https://www.greenpheasants.com/#/poem/$request->item_id\"\r\n\r\n },\r\n\r\n \"to\" : \"cxVnMNQorXT7qhplZq12NS:APA91bGzLs83VCxWwt8zfLcxk5O49XNSKbgxezpBd4KdBMYhE4XIgKY_ctb5Mt05zSbpkSczvbpffIQ-lltCo97sByosght0s61fwSmYLJqsi8aD9gUr5FSa3ndW80jiEg3yi046KQ2x\"\r\n\r\n}",
                //   CURLOPT_HTTPHEADER => array(
                //     "authorization: key=AAAAVmzbmX0:APA91bG-k3Ff6nZ4y3RWq6dhy6ihnukSPL3ic8PJreOc4wdL7MtrIQpbiDEk0IEeZW5_MFMapff_p6_OuG28YqY3wLFPsEN_FtGgSTrdzjkEXTY5oudrJNiR_8JBpKRvhxYOXMFVq07w",
                //     "cache-control: no-cache",
                //     "content-type: application/json",
                //     "postman-token: af98a951-d6d7-6926-f0da-7a7601ff1082"
                //   ),
                // ));

                // $response = curl_exec($curl);
                // $err = curl_error($curl);

                // curl_close($curl);

                // if ($err) {
                //   // echo "cURL Error #:" . $err;
                // } else {
                //   // echo $response;
                // }
                             $poemfulldata = PoemText::inRandomOrder()->where('approved_by_admin',1)->first();
                             $textplan  =strip_tags($poemfulldata->itext);
                             $html = html_entity_decode($textplan, ENT_QUOTES, 'UTF-8');
                  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\r\n\r\n \"notification\": {\r\n\r\n  \"title\": \"".$poemfulldata->ititle."\",\r\n\r\n  \"body\": \"".$html."\",\r\n   \"click_action\": \"https://www.greenpheasants.com/#/poem/".$poemfulldata->itemid."\"\r\n\r\n },\r\n\r\n \"to\" : \"cxVnMNQorXT7qhplZq12NS:APA91bGzLs83VCxWwt8zfLcxk5O49XNSKbgxezpBd4KdBMYhE4XIgKY_ctb5Mt05zSbpkSczvbpffIQ-lltCo97sByosght0s61fwSmYLJqsi8aD9gUr5FSa3ndW80jiEg3yi046KQ2x\"\r\n\r\n}",
  CURLOPT_HTTPHEADER => array(
    "authorization: key=AAAAVmzbmX0:APA91bG-k3Ff6nZ4y3RWq6dhy6ihnukSPL3ic8PJreOc4wdL7MtrIQpbiDEk0IEeZW5_MFMapff_p6_OuG28YqY3wLFPsEN_FtGgSTrdzjkEXTY5oudrJNiR_8JBpKRvhxYOXMFVq07w",
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: af98a951-d6d7-6926-f0da-7a7601ff1082"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  // echo "cURL Error #:" . $err;
} else {
  // echo $response;
}

            $checkitem = Item::where('itemid',$request->item_id)->first();
            $itemCount =  Collection::where('item_id',$request->item_id)->count();
            $collectionCount =  Collection::where('user_id',$request->user_id)->where('added_by',1)->count();

                 User::where('userid',$request->user_id)
                          ->update([
                            'ucollection_num' => (int)$collectionCount,
                            'ucollection_recent_time' =>Carbon::now()->format('h:i'),
                          ]);
            if($checkitem->notify_by != 0){
               if($itemCount==10){
                 if($checkitem->userid != 0){
                if($checkitem->notify_by == 1){
                    // echo "mail send";
                $poem_name_query = Item::where('itemid',$request->item_id)->first();
                $poem_name = $poem_name_query->ititle;
                $user_email_query = User::where('userid',$poem_name_query->userid)->first();
                $user_email = $user_email_query->uemail;
                $user_name = $user_email_query->user_name;
                $project_name = 'Green Pheasants';
                $subject = 'Ten users have added the poem';
                if($user_email != "") {
                    Mail::send('backend.emails.poemCollectionMail',['poem_name'=>$poem_name,'user_name'=>$user_name],function($message) use($user_email,$project_name,$subject){
                        $message->to($user_email,$project_name)->subject($subject);
                        $message->from('info@greenpheasants.com',"Green Pheasants");
                    });
                }
             }
             if($checkitem->notify_by == 2){
                $user_data = User::where('userid',$checkitem->userid)->first();
                //mobile integration
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "{\r\n\r\n \"notification\": {\r\n\r\n  \"title\": \"Green Pheasants - Poem added to collection\",\r\n\r\n  \"body\": \"10 users added your poem $checkitem->ititle to their collection\",\r\n  \"click_action\": \"https://www.greenpheasants.com/#/poem/$request->item_id\"\r\n\r\n },\r\n\r\n \"to\" : \"$user_data->fcm_token\"\r\n\r\n}",
                  CURLOPT_HTTPHEADER => array(
                    "authorization: key=AAAAVmzbmX0:APA91bG-k3Ff6nZ4y3RWq6dhy6ihnukSPL3ic8PJreOc4wdL7MtrIQpbiDEk0IEeZW5_MFMapff_p6_OuG28YqY3wLFPsEN_FtGgSTrdzjkEXTY5oudrJNiR_8JBpKRvhxYOXMFVq07w",
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "postman-token: af98a951-d6d7-6926-f0da-7a7601ff1082"
                  ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  // echo "cURL Error #:" . $err;
                } else {
                  // echo $response;
                }
             }
             if($checkitem->notify_by == 3){
                // $user_data = User::where('userid',$checkitem->userid)->first();
                //both mobile and email
                 $poem_name_query = Item::where('itemid',$request->item_id)->first();
                $poem_name = $poem_name_query->ititle;
                $user_email_query = User::where('userid',$poem_name_query->userid)->first();
                $user_email = $user_email_query->uemail;
                $user_name = $user_email_query->user_name;
                $project_name = 'Green Pheasants';
                $subject = 'Ten users have added the poem';
                if($user_email != "") {
                    Mail::send('backend.emails.poemCollectionMail',['poem_name'=>$poem_name,'user_name'=>$user_name],function($message) use($user_email,$project_name,$subject){
                        $message->to($user_email,$project_name)->subject($subject);
                         $message->from('info@greenpheasants.com',"Green Pheasants");
                    });
                }

                 $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "{\r\n\r\n \"notification\": {\r\n\r\n  \"title\": \"Green Pheasants - Poem added to collection\",\r\n\r\n  \"body\": \"10 users added your poem $poem_name to their collection\",\r\n  \"click_action\": \"https://www.greenpheasants.com/#/poem/$request->item_id\"\r\n\r\n },\r\n\r\n \"to\" : \"$user_email_query->fcm_token\"\r\n\r\n}",
                  CURLOPT_HTTPHEADER => array(
                    "authorization: key=AAAAVmzbmX0:APA91bG-k3Ff6nZ4y3RWq6dhy6ihnukSPL3ic8PJreOc4wdL7MtrIQpbiDEk0IEeZW5_MFMapff_p6_OuG28YqY3wLFPsEN_FtGgSTrdzjkEXTY5oudrJNiR_8JBpKRvhxYOXMFVq07w",
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "postman-token: af98a951-d6d7-6926-f0da-7a7601ff1082"
                  ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  // echo "cURL Error #:" . $err;
                } else {
                  // echo $response;
                }
             }
         }
         else{
              $common_model = new Common();
               $poem_name_query = Item::where('itemid',$request->item_id)->first();
                $poem_name = $poem_name_query->ititle;
                $user_email_query = $common_model->getfirst('admins',array('id' => 1));
                $user_email = $user_email_query->email;
                $project_name = 'Green Pheasants';
                $subject = 'Poem added to collection';
                if($user_email != "") {
                    Mail::send('backend.emails.poemCollectionMail',['poem_name'=>$poem_name],function($message) use($user_email,$project_name,$subject){
                        $message->to($user_email,$project_name)->subject($subject);
                    });
                }
         }
           }
            }

            return response()->json(['status' => true,'message'=>'Poem added to collection successfully','code' => 200]);
    }

    function removeFromCollection(Request $request){
                    Collection::where('user_id',$request->user_id)
                   ->where('item_id',$request->item_id)
                   ->delete();

        return response()->json(['status' => true,'message'=>'Poem removed from collection successfully','code' =>200]);
    }

    function sendMePoemEmail(Request $request){

        $input = $request->all();
         User::where('userid',$request->userid)
                   ->update([
                       'send_me_poems' =>$request['send_me_poems']
                       ]);

        return response()->json(['status' => true,'message'=>'Preference added successfully','code' =>200]);
    }

    function sendMePoem(Request $request){
        // echo $request->recommend_poem;
        $test =[];
        switch ($request->sendPoemVia) {
            case 'via_email':
                $data = User::where('userid',$request->user_id)
                            ->update([
                                'urec_email_freq'       =>$request->urec_email_freq,
                                'recommend_poem'        =>$request->recommend_poem,
                                'recommend_poem_email'  =>$request->recommend_poem_email,
                                'other_email'           =>($request->recommend_poem_email==1)? $request->other_email :null
                            ]);

                ///send email
                switch ($request->urec_email_freq) {
                    case 0:
                        $userDat = User::where('userid',$request->user_id)->first();
                        // CronRecommendPoemOneDay::where('userid',$request->user_id)->delete();
                        // CronRecommendPoemWeekDay::where('userid',$request->user_id)->delete();
                        // CronRecommendPoemMonthDay::where('userid',$request->user_id)->delete();

                        CronRecommendPoemOneDay::create([
                            'userid'  => $request->user_id,
                            'email'   => ($request->recommend_poem_email==1)? $request->other_email : $userDat['uemail'],
                            'type'    => 1
                        ]);
                        if($request->recommend_poem_email==1){
                            $test = ['message'=>'Poems will be recommended and sent on the entered email on Daily basis now.'];
                        }else{
                            $test = ['message'=>'Poems will be recommended and sent on your registered email on Daily basis now.'];
                        }

                        break;
                    case 1:

                        $userDat = User::where('userid',$request->user_id)->first();
                        // CronRecommendPoemOneDay::where('userid',$request->user_id)->delete();
                        // CronRecommendPoemWeekDay::where('userid',$request->user_id)->delete();
                        // CronRecommendPoemMonthDay::where('userid',$request->user_id)->delete();

                        CronRecommendPoemWeekDay::create([
                            'userid'  => $request->user_id,
                            'email'   => ($request->recommend_poem_email==1)? $request->other_email : $userDat['uemail'],
                            'type'    => 1
                        ]);

                        if($request->recommend_poem_email==1){
                            $test = ['message'=>'Poems will be recommended and sent on the entered email on Weekly basis now.'];
                        }else{
                            $test = ['message'=>'Poems will be recommended and sent on your registered email on  Weekly basis now.'];
                        }

                        break;

                    default:
                        $userDat = User::where('userid',$request->user_id)->first();
                        // CronRecommendPoemOneDay::where('userid',$request->user_id)->delete();
                        // CronRecommendPoemWeekDay::where('userid',$request->user_id)->delete();
                        // CronRecommendPoemMonthDay::where('userid',$request->user_id)->delete();

                        CronRecommendPoemMonthDay::create([
                            'userid'  => $request->user_id,
                            'email'   => ($request->recommend_poem_email==1)? $request->other_email : $userDat['uemail'],
                            'type'    => 1
                        ]);

                        if($request->recommend_poem_email==1){
                            $test = ['message'=>'Poems will be recommended and sent on the entered email on Monthly basis now.'];
                        }else{
                            $test = ['message'=>'Poems will be recommended and sent on your registered email on  Monthly basis now.'];
                        }

                        break;
                }

         if($request->recommend_poem_email==1){

                $project_name = 'Green Pheasants';
            $user_name  = $userDat->user_name;

            $set_password_url = 'https://www.greenpheasants.com/#/email-verification/'.$request->user_id.'/confirm';
            $email = $request->other_email;
            Mail::send('backend.emails.userVerificationMail',['name'=>$user_name,'email'=>$email,'set_password_url'=>$set_password_url],function($message) use($email,$project_name){
                $message->to($email,$project_name)->subject('Green Pheasants email verification');
                $message->from($this->toEmail,"Green Pheasants");
            });
        }
                return response()->json(['status' => true,'data'=>$data,'testData'=>$test,'message'=>'Recommend poem via email activated successfully','code' => 200]);
                break;

            ///send notification
            case 'via_mobile':
                User::where('userid',$request->user_id)
                    ->update([
                        'urec_push_freq'        =>$request->urec_push_freq,
                        'recommend_poem'        =>$request->recommend_poem
                    ]);

                switch ($request->urec_push_freq) {
                    case 0:
                        CronRecommendPoemOneDay::create([
                            'userid'  => $request->user_id,
                            'type'    => 2
                        ]);
                        break;
                    case 1:
                        CronRecommendPoemWeekDay::create([
                            'userid'  => $request->user_id,
                            'type'    => 2
                        ]);
                        break;
                    default:
                        CronRecommendPoemMonthDay::create([
                            'userid'  => $request->user_id,
                            'type'    => 2
                        ]);
                        break;
                }

                return response()->json(['status' => true,'message'=>'Recommend poem via mobile phone activated successfully','code' => 200]);
                break;
            default:
              $userDat1 = User::where('userid',$request['firstForm']['user_id'])->first();
                //send notification and email to both
                if($request->firstForm){
                    User::where('userid',$request['firstForm']['user_id'])
                        ->update([
                            'urec_email_freq'       =>$request['firstForm']['urec_email_freq'],
                            'recommend_poem'        =>$request->recommend_poem,
                            'recommend_poem_email'  =>$request['firstForm']['recommend_poem_email'],
                            'other_email'           =>($request['firstForm']['recommend_poem_email']==1)? $request['firstForm']['other_email'] :null
                        ]);

                    switch ($request['firstForm']['urec_email_freq']) {
                        case 0:
                            CronRecommendPoemOneDay::create([
                                'userid'  => $request['firstForm']['user_id'],
                                'email'   => ($request['firstForm']['recommend_poem_email']==1)? $request['firstForm']['other_email'] :null,
                                'type'    => 3
                            ]);
                            break;
                        case 1:
                            CronRecommendPoemWeekDay::create([
                                'userid'  => $request['firstForm']['user_id'],
                                'email'   => ($request['firstForm']['recommend_poem_email']==1)? $request['firstForm']['other_email'] :null,
                                'type'    => 3
                            ]);
                            break;
                        default:
                            CronRecommendPoemMonthDay::create([
                                'userid'  => $request['firstForm']['user_id'],
                                'email'   => ($request['firstForm']['recommend_poem_email']==1)? $request['firstForm']['other_email'] :null,
                                'type'    => 3
                            ]);
                            break;
                    }
                }

                if($request->secondForm){
                    User::where('userid',$request['secondForm']['user_id'])
                        ->update([
                            'urec_push_freq'        =>$request['secondForm']['urec_push_freq'],
                            'recommend_poem'        =>$request->recommend_poem
                        ]);

                    switch ($request['secondForm']['urec_push_freq']) {
                        case 0:
                            CronRecommendPoemOneDay::create([
                                'userid'  => $request['secondForm']['user_id'],
                                'type'    => 3
                            ]);
                            break;
                        case 1:
                            CronRecommendPoemWeekDay::create([
                                'userid'  => $request['secondForm']['user_id'],
                                'type'    => 3
                            ]);
                            break;
                        default:
                            CronRecommendPoemMonthDay::create([
                                'userid'  => $request['secondForm']['user_id'],
                                'type'    => 3
                            ]);
                            break;
                    }

                }

                if($request['firstForm']['recommend_poem_email']==1){

                $project_name = 'Green Pheasants';
            $user_name  = $userDat1['user_name'];

            $set_password_url = 'https://www.greenpheasants.com/#/email-verification/'.$request->user_id.'/confirm';
            $email = $request['firstForm']['other_email'];
            Mail::send('backend.emails.userVerificationMail',['name'=>$user_name,'email'=>$email,'set_password_url'=>$set_password_url],function($message) use($email,$project_name){
                $message->to($email,$project_name)->subject('Green Pheasants email verification');
                $message->from($this->toEmail,"Green Pheasants");
            });
        }

                return response()->json(['status' => true,'message'=>'Recommend poem via both email and mobile phone activated successfully','code' => 200]);

                break;
        }

        return response()->json(['status' => true,'message' => 'cron recommend for one day', 'code' => 200]);
    }

    public function logout(){
        if(Auth::check()){
            Auth::guard('api')->logout();
        }
        Session::flush();
        return response()->json(['status' => true,'message' => 'logout successfully', 'code' => 200]);
    }

}
