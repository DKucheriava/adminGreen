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
use App\Common;
use Validator;
use DB;
use date;
use DateTime;
use App\Mail\forgotPasswordMail;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use App\Console\Commands\DemoCron;
use App\Models\CronRecommendPoemMonthDay;
use App\Models\CronRecommendPoemOneDay;
use App\Models\CronRecommendPoemWeekDay;

class ApiController extends Controller
{
    use ImagesTrait;

    public function getRegistration(Request $request){
        dd('here');
    }

    public function getAllCountry(Request $request){
        $countries = Country::orderby('id','asc')->get();
        return response()->json(['status' => true,'message' => 'User login Successfuly','data' => $countries,'code' => 200]);
    }
    
     function userEmailVerification(Request $request,$userid){
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

            if ($check_email_exists) {
                return response()->json(['status' => false,'message' => 'This Email is already exists', 'code' => 400]);
            }
            
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
                    'security_code'              =>@$security_code   
             ])->id;

            $project_name = 'Green Pheasants';
            $user_name  = ucfirst($request->user_name);

            $set_password_url = 'http://localhost:4200/#/email-verification/'.$user_id;
            $email = $request->uemail;
            // Mail::send('backend.emails.userVerificationMail',['name'=>$user_name,'email'=>$email,'set_password_url'=>$set_password_url],function($message) use($email,$project_name){
            //     $message->to($email,$project_name)->subject('Email Verification');
            // });
            
            return response()->json(['status'=>true,'code'=>200,'message'=>'User registration successfully']);
                
        }catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
        }
    }

      public function userLogin(Request $request){
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
            if($checkDataEmail){
                if($checkDataEmail->email_verification_status == 1){
                    if((Hash::check($request->password, $checkDataEmail->password))){
                        $token = JWTAuth::attempt(['uemail' => $checkDataEmail->uemail, 'password' => $request->password ]);

                        User::where('uemail',$checkDataEmail->uemail)
                            ->update([
                                        'jwt_token'        =>$token,
                                        'ulast_visit_time' =>date('Y-m-d H:i:s'),    
                                    ]);

                        return response()->json(['status' => true,'message' => 'User login Successfuly','data' => $checkDataEmail,'token' => $token,'code' => 200]);
                    }else{
                        return response()->json(['status' => false,'message' => 'Password did not match', 'code' => 400]);
                    }
                }else{
                    return response()->json(['status' => false,'message' => 'Email verification is pending', 'code' => 400]);
                }
            }else{
                return response()->json(['status' => false,'message' => 'Email did not exist in database', 'code' => 400]);
            }  


        }catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
        }
    }

    public function resetPassword(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'password'      => 'required'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        $check_otp_exists = PasswordReset::where('token',$input['token'])->first();

        if ($check_otp_exists) {
            User::where('userid', $check_otp_exists['user_id'])
                  ->update([
                            'password' => Hash::make($input['password'])
                    ]);
            PasswordReset::where('user_id',$check_otp_exists['user_id'])->delete();
                  
            return response()->json(['status' => true,'code'=>200,'message' => 'Password reset successfully']);
        }else{
            return response()->json(['status' => false, 'message' => 'Token does not match.','code' => 400]);
        }
    }

    public function contactUs(Request $request){
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

        return response()->json(['status' => true,'code'=>200,'message' => 'Message send successfully']);

    }

    public function forgotPassword(Request $request){
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
            
        $mailData['link'] ='https://dev.indiit.solutions/greenPheasantFrontend/#/reset-password'.'/'.$email_verification_token;
       
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

    public function getFaqList(Request $request){
        $getFaq = Faq::orderby('id','desc')->get();
        return response()->json(['status' => true,'message'=>'Get Faq list Successfuly','data' =>$getFaq,'code' => 200]);
    }

    public function getTermCondtion(Request $request){
        $getTermCondtion = Terms::orderby('id','desc')->first();
        return response()->json(['status' => true,'message'=>'Get terms & condition data successfuly','data' =>$getTermCondtion,'code' => 200]);
    }

    public function getPrivacyPolicy(Request $request){
        $getPrivacyPolicy = PrivacyPolicy::orderby('id','desc')->first();
        // dd($request->all());
        return response()->json(['status' => true,'message'=>'Get privacy & policy data successfuly','data' =>$getPrivacyPolicy,'code' => 200]);
    }

    public function getAboutUsData(Request $request){
        $getAboutUsData = AboutUs::orderby('id','desc')->first();
        return response()->json(['status' => true,'message'=>'Get about us data successfuly','data' =>$getAboutUsData,'code' => 200]);
    }

    // public function getProfileData(Request $request){
    //     if(isset($request->sessionid)){
    //         if($request->userid){
    //             // SessionTable 
    //             $userData = User::where('userid',$request->userid)->first();
    //             return response()->json(['status' => true,'message'=>'Get user data successfuly','data' =>$userData,'code' =>200]);
    //         }else{
    //             $getData  = SessionService::updateSession($request);
    //             $userData='';
    //             return response()->json(['status' => true,'message'=>'Get user data successfuly','data' =>$userData,'sessionData'=>$getData,'code' =>200]);
    //         }
    //     }else{
    //          // SessionTable 
    //         if($request->userid!=0){
    //             $getData  = SessionService::createSession($request);
    //             // dd('in');
    //             $userData =  User::where('userid',$request->userid)->first();
    //             return response()->json(['status' => true,'message'=>'Get user data successfuly','data' =>$userData,'sessionData'=>$getData,'code' =>200]);
    //         }else{
    //             $getData  = SessionService::createSession($request);
    //             $userData='';
    //             return response()->json(['status' => true,'message'=>'Get user data successfuly','data' =>$userData,'sessionData'=>$getData,'code' =>200]);
    //         }
    //     }
    // }

    public function getSessionData(Request $request){
        if(isset($request->sessionid)){
            $getData  = SessionService::updateSession($request);
            return response()->json(['status' => true,'message'=>'Update session data successfuly','data' =>$getData,'code' =>200]);
        }else{
            $getData  = SessionService::createSession($request);
            return response()->json(['status' => true,'message'=>'Get session data successfuly','data' =>$getData,'code' =>200]);
        }
    }

    public function getProfileData(Request $request){
        if($request->userid){
            $userData =  User::where('userid',$request->userid)->first();
            return response()->json(['status' => true,'message'=>'Get user data successfuly','data' =>$userData,'code' =>200]);
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

        User::where('userid',$userId)
              ->update([
                'user_name'    =>$request->user_name,                      
                'subscribe_me' =>($request->subscribe_me == "true" || $request->subscribe_me == 1) ? 1: 0,   
                'send_recommened_poem'  =>($request->send_recommened_poem == "true" || $request->send_recommened_poem == 1) ?1 : 0,                  
                'send_notification'     =>($request->send_notification == "true" || $request->send_notification == 1)  ? 1: 0                      
              ]);

        return response()->json(['status' => true,'message'=>'Profile updated successfuly','data'=>$request->user_name,'code' =>200]);
    }

    public function updatePassword(Request $request,$userId){
        User::where('userid',$userId)
              ->update([
                'password' =>Hash::make($request->password),
              ]);
        return response()->json(['status' => true,'message'=>'Password updated successfuly','code' =>200]);
    }

    public function getPoemMoodListData(Request $request){

        $getPoemMood = PoemMood::get();
        return response()->json(['status' => true,'message'=>'Get Poem Mood list Successfuly','data' =>$getPoemMood,'code' => 200]);
    }

    public function getPoemThemeListData(Request $request){
        $getPoemTheme = PoemTheme::get();
        return response()->json(['status' => true,'message'=>'Get Poem Theme list Successfuly','data' =>$getPoemTheme,'code' => 200]);
    }

    public function getAllPoemList(Request $request){
        $search          = $request->searchedValue;
        $result  = Item::leftjoin('poem_texts','poem_texts.itemid','items.itemid')
                        ->select('items.*','poem_texts.ititle as title','poem_texts.cname as cretor_name','poem_texts.itext as description')
                        ->where('items.userid','!=',$request->user_id)
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
        return response()->json(['status' => true,'message'=>'Get All Poem list Successfuly','data'=> $result,'code' => 200]);
    }

    public function getPoemList(Request $request){
        $user_id         = $request->user_id;
        if(isset($request->searchedValue)){
            $search          = $request->searchedValue;

            $data['result']  =  Collection::where('user_id',$user_id)
                                            ->with(['poemFullDetail1' => function ($query)use ($search) {
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

            $data['result']  =  Collection::where('user_id',$user_id)
                                            ->leftjoin('poem_texts','poem_texts.itemid','collections.item_id')
                                            ->select('collections.*','poem_texts.ititle as title','poem_texts.cname as cretor_name','poem_texts.itext as description')
                                            ->orderby('id','desc')
                                            ->take(4)
                                            ->get();                    

            $result1 =  Collection::where('user_id',$user_id)
                                            ->leftjoin('poem_texts','poem_texts.itemid','collections.item_id')
                                            ->select('collections.*','poem_texts.ititle as title','poem_texts.cname as cretor_name','poem_texts.itext as description')
                                         ->orderby('id','desc')
                                         ->skip(4)
                                         ->take(4)
                                         ->get();

            $data['next']     = count($result1);
            $data['offset']   = $request->offset;
        }
        return response()->json(['status' => true,'code'=>200,'data'=>$data,'message' => 'Get Poem list Successfuly']);
    }

    public function load_more_poem(Request $request){
        $input   = $request->all();
        $offset  = $request->offset;
        $user_id = $request->user_id;
        
        $data['result']  =  Collection::where('user_id',$user_id)
                                    ->leftjoin('poem_texts','poem_texts.itemid','collections.item_id')
                                    ->select('collections.*','poem_texts.ititle as title','poem_texts.cname as cretor_name','poem_texts.itext as description')
                                    ->orderby('id','desc')
                                   ->skip($offset)
                                   ->take(4)
                                   ->get();
        
        $result22   =   Collection::where('user_id',$user_id)
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
                      ->where('approved_by_admin',2)
                      ->orderby('itemid','desc')
                      ->first();
                      
        if($request->userid && $request->userid!=0){
            $collection  = Collection::where('item_id', $poemId)
                                    ->where('user_id',$request->userid)
                                    ->exists();
            $poem->collection_status = $collection;     
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

            return response()->json(['status' => true,'message'=>'Poem deleted from collection successfuly','code' => 200]);
        }else{
            return response()->json(['status' => false,'message'=>'Poem not found','code' => 400]);
        }                               
    }

    public function removePoem(Request $request, $poemId){
        $checkPoemIdExist =  Collection::where('item_id',$poemId)
                                ->first();

        if($checkPoemIdExist){
            Collection::where('item_id',$poemId)->delete();
        
            return response()->json(['status' => true,'message'=>'Poem removed from collection successfuly','code' => 200]);
        }else{
            return response()->json(['status' => false,'message'=>'Poem not found','code' => 400]);
        }                               
    }

    public function getAllCreatorList(){
        $creators = Creator::selectRaw('creatorid as id , cname as name')
                            ->orderby('creatorid','asc')
                            ->get();
                            
        return response()->json(['status' => true,'data'=> $creators,'message'=>'Get Creator list  successfuly','code' => 200]);
    }

    function recommendPoem(Request $request){
        $selected_term  =   $request->poemType;
        $flag = $request->flag;

        if($request->userid && $request->userid!=0){
            $getpoem = Item::with('poemFullDetail')   
                        ->where(function ($query) use($selected_term,$flag ) {
                            switch ($flag) {
                                case 1:
                                    break;
                                case 2:
                                    return $query->where('itheme1',$selected_term)
                                            ->orWhere('itheme2',$selected_term)
                                            ->orWhere('itheme3',$selected_term)
                                            ->orWhere('itheme4',$selected_term)
                                            ->orWhere('itheme5',$selected_term);
                                    break;    
                                default:
                                    return $query->Where('imood1',$selected_term)
                                            ->orWhere('imood2',$selected_term)
                                            ->orWhere('imood3',$selected_term);
                                    break;
                            }
                        })             
                        ->where('approved_by_admin',2)
                        ->where('userid','!=',$request->userid)
                        ->inRandomOrder()
                        ->first();
        }else{
            $getpoem = Item::with('poemFullDetail')   
                        ->where(function ($query) use($selected_term,$flag ) {
                            switch ($flag) {
                                case 1:
                                    break;
                                case 2:
                                    return $query->where('itheme1',$selected_term)
                                            ->orWhere('itheme2',$selected_term)
                                            ->orWhere('itheme3',$selected_term)
                                            ->orWhere('itheme4',$selected_term)
                                            ->orWhere('itheme5',$selected_term);
                                    break;    
                                default:
                                    return $query->Where('imood1',$selected_term)
                                            ->orWhere('imood2',$selected_term)
                                            ->orWhere('imood3',$selected_term);
                                    break;
                            }
                        })             
                        ->where('approved_by_admin',2)
                        ->inRandomOrder()
                        ->first();
        }
    
        if($getpoem){
            return response()->json(['status' => true,'data'=>$getpoem,'message'=>'Get recommend poem list  successfuly','code' => 200]);
        }else{
            return response()->json(['status' => false,'message'=>'No record found','code' => 400]);
        }            
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

        $inum_lines           = substr_count($request->description, "\n" );
        // dd($str_word_count,$inum_lines);
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

        $poemId =   Item::create([
                        'creatorid'         =>$request->poet!=null ? $checkPoet['creatorid']:$creator_id,
                        'userid'        =>$request->user_id,
                        'cname'         =>$request->poet ? $request->poet:$request->otherPoet,
                        'ititle'        =>@$request->title,
                        'iyear'         =>@$request->iyear,
                        'itheme1'       =>@$request->poem_theme_selected[0]['item_text'],
                        'itheme2'       =>@$request->poem_theme_selected[1]['item_text'],
                        'itheme3'       =>@$request->poem_theme_selected[2]['item_text'],
                        'itheme4'       =>@$request->poem_theme_selected[3]['item_text'],
                        'itheme5'       =>@$request->poem_theme_selected[4]['item_text'],
                        'imood1'        =>@$request->poem_mood_selected[0]['item_text'],
                        'imood2'        =>@$request->poem_mood_selected[1]['item_text'],
                        'imood3'        =>@$request->poem_mood_selected[2]['item_text'],
                        'icontent_url'             =>@$request->source,
                        'curl'                     =>@$request->source,
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
            'icontent_url'  =>@$request->source,
            'itext'         =>@$request->description
        ]);

        $userData = User::where('userid',$request->user_id)->first();

        Interaction::create([
            'userid'             =>$request->user_id,
            'ucountry_id'        =>$userData['ucountry_id'],
            // 'visitorid'       =>$request->description,
            // 'vcountry'        =>$request->description,
            'itemid'             =>$poemId,
            // 'creatorid'       =>$request->description,
            'iyear'              =>@$request->iyear,
            'itheme_ids'  =>implode(",", array_column($request->poem_theme_selected, "id")),
            'imood_ids'   =>implode(",", array_column($request->poem_mood_selected, "id")),
            'inum_words'          =>$str_word_count,
            'inum_words_bin'      =>$check_words_bin,
            'inum_lines'          =>$inum_lines,
            'inum_words_per_line'      =>(int)$inum_words_per_line,
            'inum_words_per_line_bin'  =>$inum_words_per_line_bin,
            // 'rtheme'                    =>$request->description,
            // 'rmood'                     =>$request->description,
            // 'received_email'            =>$request->description,
            // 'received_push'             =>$request->description,
            // 'received_online'           =>$request->description,
            // 'view_num'                  =>$request->description,
            // 'last_view_start'           =>$request->description,
            // 'last_view_end'             =>$request->description,
            // 'last_view_duration'        =>$request->description,
            // 'collection'                =>$request->description,
            // 'register'                  =>$request->description
        ]);
        

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

        return response()->json(['status' => true,'message'=>'Poem added Successfuly','code' => 200]);
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
            
            return response()->json(['status' => true,'message'=>'Poem added to collection successfuly','code' => 200]);                                    
    }   

    function removeFromCollection(Request $request){
        $checkCollection = Collection::where('user_id',$request->user_id)
                                       ->where('item_id',$request->item_id)
                                       ->first();

        Collection::where('user_id',$request->user_id)
                   ->where('item_id',$request->item_id)
                   ->delete();

        return response()->json(['status' => true,'message'=>'Poem removed from collection successfuly','code' =>200]);                                       
    }
    
    function sendMePoemEmail(Request $request){
        
        $input = $request->all();
         User::where('userid',$request->userid)
                   ->update([
                       'send_me_poems' =>$request['send_me_poems']
                       ]);
        
        return response()->json(['status' => true,'message'=>'Preference added  successfuly','code' =>200]);            
    }
    
    function sendMePoem(Request $request){

        switch ($request->sendPoemVia) {
            case 'via_email':
                $data = User::where('userid',$request->user_id)
                            ->update([
                                'urec_email_freq'       =>$request->urec_email_freq,
                                'recommend_poem'        =>0,
                                'recommend_poem_email'  =>$request->recommend_poem_email,
                                'other_email'           =>($request->recommend_poem_email==1)? $request->other_email :null
                            ]);
                
                 ///send email              
                switch ($request->urec_email_freq) {
                    case 0:
                        CronRecommendPoemOneDay::create([
                            'userid'  => $request->user_id,
                            'email'   => ($request->recommend_poem_email==1)? $request->other_email :null,
                            'type'    => 1
                        ]);
                        break;
                    case 1:
                        CronRecommendPoemWeekDay::create([
                            'userid'  => $request->user_id,
                            'email'   => ($request->recommend_poem_email==1)? $request->other_email :null,
                            'type'    => 1
                        ]);
                        break;
                    default:
                        CronRecommendPoemMonthDay::create([
                            'userid'  => $request->user_id,
                            'email'   => ($request->recommend_poem_email==1)? $request->other_email :null,
                            'type'    => 1
                        ]);
                        break;
                }
                
                return response()->json(['status' => true,'data'=>$data,'message'=>'Recommend poem via email activated  successfuly','code' => 200]);           
                break;

            ///send notification    
            case 'via_mobile':
                User::where('userid',$request->user_id)
                    ->update([
                        'urec_push_freq'        =>$request->urec_push_freq,
                        'recommend_poem'        =>2
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

                return response()->json(['status' => true,'message'=>'Recommend poem via mobile phone activated successfuly','code' => 200]);           
                break;
            default:

                //send notification and email to both
                if($request->firstForm){
                    User::where('userid',$request['firstForm']['user_id'])
                        ->update([
                            'urec_email_freq'       =>$request['firstForm']['urec_email_freq'],
                            'recommend_poem'        =>2,
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
                            'recommend_poem'        =>2
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

                return response()->json(['status' => true,'message'=>'Recommend poem via both email and mobile phone activated successfuly','code' => 200]);           

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
