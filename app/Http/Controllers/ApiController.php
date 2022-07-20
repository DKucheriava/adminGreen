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
            // dd($request->all());
            $check_email_exists = User::where('uemail',$input['uemail'])->first();

            if ($check_email_exists) {
                return response()->json(['status' => false,'message' => 'This Email is already exists', 'code' => 400]);
            }

        
            User::create([
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
                    'send_notification'          =>@$request->send_notification
            ]);


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
                    'uemail'      => 'required|email',
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
                                    ->first();

            if($checkDataEmail){
                if((Hash::check($request->password, $checkDataEmail->password))){
                    $token = JWTAuth::attempt($credentials);
                    User::where('uemail',$input['uemail'])
                            ->update([
                                    'jwt_token'        =>$token,
                                    'ulast_visit_time' =>date('Y-m-d H:i:s'),    
                                ]);

                    return response()->json(['status' => true,'message' => 'User login Successfuly','data' => $checkDataEmail,'token' => $token,'code' => 200]);
                }else{
                    return response()->json(['status' => false,'message' => 'Password did not match', 'code' => 400]);
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
                'otp'           => 'required',
                'email'         => 'required',
                'password'      => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        $check_otp_exists = User::where('otp', $input['otp'])
                                ->where('email', $input['email'])
                                ->first();

        if ($check_otp_exists) {
            User::where('otp', $input['otp'])
                  ->where('email', $input['email'])
                  ->update([
                            'password' => Hash::make($input['password'])
                    ]);

            return response()->json(['status' => true,'code'=>200,'message' => 'Password reset successfully']);
        }else{
            return response()->json(['status' => false, 'message' => 'Otp does not match.','code' => 400]);
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

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'      => 'required|email',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        $check_email_exists = User::where('email', $request['email'])->first();
        
        if (empty($check_email_exists)) {
            return response()->json(['status' => false,'message' => 'Email not exists.','code' => 400]);
        }

        $check_email_exists->email_verification_token = $this->generateRandomString(32);
        
        if ($check_email_exists->save()) {
            $project_name = env('App_name');
            $email = $request['email'];
            
            $encodeKey  = base64_encode($check_email_exists->email_verification_token);
            $encodeId   = base64_encode($check_email_exists['id']);
            // send email confirmation link to user's email
            
            $mailData['link'] = url('/reset-password/').'/'.$encodeKey.'/'.$encodeId;

            $replace_with = ['full_name'=>$check_email_exists['user_name'],'email'=>$check_email_exists['email'],'email_verification_link'=>$mailData['link']];      
            try {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    Mail::send('frontend.emails.user_forgot_password_api', ['data' => $replace_with], function ($message) use ($email, $project_name) {
                        $message->to($email, $project_name)->subject('Reset Password - Hagel');
                    });
                }
            } catch (Exception $e) {
            }
            return response()->json(['status' => true, 'message' => 'Reset password link has been sent on your registered email, Please check.','code' => 200], Response::HTTP_OK);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong, Please try again later.','code' => 400]);
        }
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

        User::where('userid',$userId)
              ->update([
                'user_name'=>$request->user_name,
                // 'uemail'   =>$request->uemail,                      
                'subscribe_me' =>($request->subscribe_me == "true" || $request->subscribe_me == 1) ? 1: 0,   
                'send_recommened_poem'=>($request->send_recommened_poem == "true" || $request->send_recommened_poem == 1) ?1 : 0,                  
                'send_notification'=>($request->send_notification == "true" || $request->send_notification == 1)  ? 1: 0                      
              ]);
        
        return response()->json(['status' => true,'message'=>'Profile updated successfuly','code' =>200]);
    }

    public function updatePassword(Request $request,$userId){
        User::where('id',$userId)
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

    // public function getPoemList(Request $request){
    //     $poemList = Poem::with('poemMoodSelected','poemMoodSelected.poemMood','poemThemeSelected','additionalLinkForPoems','poemThemeSelected.poemTheme')->where('approved_by_admin',2)->orderby('id','desc')->get();
    //     return response()->json(['status' => true,'message'=>'Get Poem list Successfuly','data' =>$poemList,'code' => 200]);
    // }

    public function getAllPoemList(Request $request){
        $search          = $request->searchedValue;
        $result  = Poem::select('*')
                                ->with('poemMoodSelected','poemMoodSelected.poemMood','poemThemeSelected','additionalLinkForPoems','poemThemeSelected.poemTheme')
                                ->where(function ($query) use ($search) {
                                    if($search){
                                        $query->where('title', 'LIKE', '%'.$search.'%');
                                        $query->orWhere('poet', 'LIKE', '%'.$search.'%');
                                        $query->orWhere('description', 'LIKE', '%'.$search.'%');
                                    }
                                })->orderby('id','desc')
                                ->get();   

        return response()->json(['status' => true,'message'=>'Get All Poem list Successfuly','data'=> $result,'code' => 200]);
    }

    //////////////////
    public function getPoemList(Request $request){
        if(isset($request->searchedValue)){
            $search          = $request->searchedValue;
            $user_id         = $request->user_id;
            $data['result']  = Poem::select('*')
                                    ->with('poemMoodSelected','poemMoodSelected.poemMood','poemThemeSelected','additionalLinkForPoems','poemThemeSelected.poemTheme')
                                    ->where(function ($query) use ($search) {
                                        if($search){
                                            $query->where('title', 'LIKE', '%'.$search.'%');
                                            $query->orWhere('poet', 'LIKE', '%'.$search.'%');
                                            $query->orWhere('description', 'LIKE', '%'.$search.'%');
                                        }
                                    })->orderby('id','desc')
                                    ->orWhere('user_id',$user_id)
                                     ->get();

            // $data['result']  = Collection::with(['poemDetail','poemDetail.poemMoodSelected','poemDetail.poemMoodSelected.poemMood','poemDetail.poemThemeSelected','poemDetail.additionalLinkForPoems','poemDetail.poemThemeSelected.poemTheme'])
            //                      ->whereHas('poemDetail', function($query) use($search) {
            //                            if($search){
            //                             $query->where('poemDetail.title', 'LIKE', '%'.$search.'%');
            //                         }
            //                     })
            //                   ->orderby('id','desc')

            //                   ->orWhere('user_id',$user_id)
            //                   ->orderby('id','desc')
            //                   ->take(4)
            //                   ->get();

            // dd($search,);                        
            // dd($data);                        
                                      
        }else{
            $input  = $request->all();

            $data['result']  = Collection::with('poemDetail.poemMoodSelected','poemDetail.poemMoodSelected.poemMood','poemDetail.poemThemeSelected','poemDetail.additionalLinkForPoems','poemDetail.poemThemeSelected.poemTheme')
                                  // ->where('user_id',$request->user_id)
                                  ->orderby('id','desc')
                                  ->take(4)
                                  ->get();
      
            $result1 =  Collection::with('poemDetail.poemMoodSelected','poemDetail.poemMoodSelected.poemMood','poemDetail.poemThemeSelected','poemDetail.additionalLinkForPoems','poemDetail.poemThemeSelected.poemTheme')
                              // ->where('user_id',$request->user_id)
                              ->orderby('id','desc')
                              ->skip(4)
                              ->take(4)
                              ->get();

        
            $data['next']     = count($result1);
            $data['offset']   = $request->offset;
        }
        return response()->json(['status' => true,'code'=>200,'data'=>$data,'message' => 'Get Poem list Successfuly']);
    }

    ///
    public function load_more_poem(Request $request){
        $input = $request->all();
        $offset = $request->offset;

        $data['result']  =  Collection::with('poemDetail.poemMoodSelected','poemDetail.poemMoodSelected.poemMood','poemDetail.poemThemeSelected','poemDetail.additionalLinkForPoems','poemDetail.poemThemeSelected.poemTheme')
                              ->where('user_id',$request->user_id)
                              ->select('*')
                              ->orderby('id','desc')
                              ->skip($offset)
                              ->take(4)
                              ->get();

        $result22  =  Collection::with('poemDetail.poemMoodSelected','poemDetail.poemMoodSelected.poemMood','poemDetail.poemThemeSelected','poemDetail.additionalLinkForPoems','poemDetail.poemThemeSelected.poemTheme')
                          ->where('user_id',$request->user_id)
                          ->select('*')
                          ->orderby('id' , 'desc')
                          ->skip($offset+4)
                          ->take(4)
                          ->get();

        $data['offset'] = $offset+4;
        $data['next']   = count($result22);

        return response()->json(['status' => true,'code'=>200,'data'=>$data,'message' => 'Get Poem data list']);
    }
    ///

    public function getPoemDetail(Request $request, $poemId){
        $poem = Poem::where('id',$poemId)
                    ->with('poemMoodSelected','poemMoodSelected.poemMood','poemThemeSelected','additionalLinkForPoems','poemThemeSelected.poemTheme')
                    ->where('approved_by_admin',2)
                    ->orderby('id','desc')
                    ->first();

        return response()->json(['status' => true,'message'=>'Get Poem Details','data' =>$poem,'code' => 200]);
    }

    public function deletePoem(Request $request, $poemId){
        $checkPoemIdExist =  Collection::where('poem_id',$poemId)
                                ->first();
        if($checkPoemIdExist){
            Collection::where('poem_id',$poemId)
                  ->delete();
            return response()->json(['status' => true,'message'=>'Poem deleted from collection successfuly','code' => 200]);
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

    public function addPoem(Request $request){
        $check_words_bin='';

        $str_word_count = str_word_count($request->description);
        // dd($request->all(),   str_word_count($request->description),$str_word_count,'in');

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
            // dd($checkPoet);           
        }else{
           $creator_id = Creator::create([
                                    'cname' =>$request->otherPoet,
                                ])->id;
        }  
        // dd($checkPoet['creatorid']);

        $poemId =   Item::create([
                        'creatorid'         =>$request->poet!=null ? $checkPoet['creatorid']:$creator_id,
                        'cname'             =>$request->poet ? $request->poet:$request->otherPoet,
                        'ititle'            =>@$request->title,
                        'iyear'             =>@$request->iyear,
                        // 'itheme_ids'        =>implode(",", array_column($request->poem_theme_selected, "id")),
                        // 'imood_ids'         =>implode(",", array_column($request->poem_mood_selected, "id")),
                        'itheme1'       =>@$request->poem_theme_selected[0]['item_text'],
                        'itheme2'       =>@$request->poem_theme_selected[1]['item_text'],
                        'itheme3'       =>@$request->poem_theme_selected[2]['item_text'],
                        'itheme4'       =>@$request->poem_theme_selected[3]['item_text'],
                        'itheme5'       =>@$request->poem_theme_selected[4]['item_text'],
                        'imood1'        =>@$request->poem_mood_selected[0]['item_text'],
                        'imood2'        =>@$request->poem_mood_selected[1]['item_text'],
                        'imood3'        =>@$request->poem_mood_selected[2]['item_text'],
                        'icontent_url'  =>@$request->source,
                        // 'curl'                =>@$request->title,
                        'inum_words'          =>$str_word_count,
                        'inum_words_bin'      =>$check_words_bin,
                        'inum_lines'          =>$inum_lines,
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
        

        // if($request->notify_via=='notify_via_email'){
        //     $notify_via=1;
        // }elseif($request->notify_via=='notify_via_mobile_phone'){
        //     $notify_via=2;
        // }elseif($request->notify_via=='notify_via_both'){
        //     $notify_via=3;
        // }else{
        //     $notify_via=4;
        // }    

        // $poemId = Poem::create([
        //                 'title'                 =>@$request->title,
        //                 'poet'                  =>@$request->poet,
        //                 'description'           =>@$request->description,
        //                 'source'                =>@$request->source,
        //                 'notify_via'            =>$notify_via,
        //                 'poem_in_public_domain' =>@$request->poem_in_public_domain,
        //                 'user_id'               =>1
        //             ])->id;
    

        // if(isset($request->poem_mood_selected)){
        //     $moodData ='';
        //     foreach ($request->poem_mood_selected as $key => $value) {
        //             $moodData .=$value['id'].',';
        //             // PoemMoodSelected::create([
        //             //     'poem_id'       =>$poemId,
        //             //     'poem_mood_id'  =>$value['id']
        //             // ]);
        //     }
        // }
        // dd($moodData);
        // if(isset($request->poem_theme_selected)){
        //     foreach ($request->poem_theme_selected as $key => $val) {
        //         PoemThemeSelected::create([
        //             'poem_id'       =>$poemId,
        //             'poem_theme_id' =>$val['id']
        //         ]);
        //     }
        // }

        if(isset($request->additional_links)){
            foreach ($request->additional_links as $key => $val1) {
                AdditionalLinkForPoem::create([
                    'poem_id'       =>$poemId,
                    'text'          =>$val1['text'],
                    'url'           =>$val1['url']
                ]);
            }
        }

        Collection::create([
            'user_id'=>$request->user_id,
            'poem_id'=>$poemId
        ]);
        
        return response()->json(['status' => true,'message'=>'Poem added Successfuly','code' => 200]);
    }

    public function logout(){
        if(Auth::check()){
            Auth::guard('api')->logout();
        }
        Session::flush();
        return response()->json(['status' => true,'message' => 'logout successfully', 'code' => 200]);
    }


}
