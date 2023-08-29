<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests,DB,Session,Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Traits\ImagesTrait;
use App\Models\Country;
use App\Models\Admin;
use App\Models\User;
use Date;
use Mail, Auth;

class UserController extends Controller{

    use ImagesTrait;
    protected $label;

    public function __construct(){
        $this->label = 'User';
        $this->middleware('auth:admin');
    }

    function addUser(Request $request){
        if($request->isMethod('post')){
            $input = $request->all();
            $check_email_exists = User::where('uemail',$input['uemail'])->first();

            if ($check_email_exists) {
               Session::flash('error','Email already exists');  
               return redirect()->back();
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
                    'security_code'              =>@$security_code,   
                    'email_verification_status'  =>1
             ])->id;

            $project_name        = 'Green Pheasants';
            $user_name           = ucfirst($request->user_name);
            $email               = $request->uemail;
            $password            = $request->password;

            // $credential['uemail']    = $request->uemail;
            // $credential['user_name'] = $request->user_name;
            // $credential['password']  = $request->password;

            $set_password_url = 'http://3.238.14.13/#/home';

            Mail::send('backend.emails.userVerifiedBackend',[
                'name'=>$user_name,
                'email'=>$email,
                'password'=>$password
            ],function($message) use($email,$project_name){
                $message->to($email,$project_name)->subject('Credentials Created by admin');
            });

            if($user_id){
                 return redirect('/admin/user/list')->with('success','User added sucessfully');
            }else{
                 Session::flash('error','Something went wrong');
                 return redirect()->back();
            }
        }

        $countries = Country::get();
        return view('backend.user.adduser',compact('countries'));     
    }

    function getUserList(Request $request){
        $label = $this->label;
        $userList = User::with('countries')
                        ->select('userid','user_name','uemail','uregistration_time','ucountry_id','created_at')
                        ->orderBy('userid','desc')
                        ->get();
                       
        return view('backend.user.list',compact('userList','label'));
    }

    public function viewUser(Request $request,$id){

        $userList   = User::with('countries')->where('userid',$id)->first();
        $countries  = Country::get();
        return view('backend.user.viewUser',compact('userList','countries','id'));
    }

    public function deleteAdminUser($id, Request $request){   
        $data= User::where('userid', base64_decode($id))->first();
        if(!empty($data)){
            User::where('userid', base64_decode($id))->delete();
            Session::flash('success', 'User deleted successfully');
          return $response = array('status'=>'ok');
        }
    }

    // public function changeUserStatus($id, Request $request){
    //    $user = User::where('userid', base64_decode($id))->first();
    //    $user->status = $request->status;
    //    $user->save();
 
    //    return response()->json(['success'=>'Status change successfully.']);
    // }


}
