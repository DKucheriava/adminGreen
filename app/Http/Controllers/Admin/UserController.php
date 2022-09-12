<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Country;
use App\Traits\ImagesTrait;


class UserController extends Controller
{
    use ImagesTrait;

    protected $label;

    public function __construct(){
        $this->label = 'User';
        $this->middleware('auth:admin');
    }

    function addUser(Request $request){
        if($request->isMethod('post')){
            $input = $request->all();
            User::create([
                    'user_name'                  =>$request->user_name,
                    'uemail'                     =>$request->uemail,
                    'uregistration_time'         =>date('Y-m-d H:i:s'),
                    'ucountry_id'                =>@$request->ucountry_id,
                    'password'                   =>Hash::make($request->password)
            ]);

            if($profile){
                 return redirect('/admin/dashboard')->with('success','profile update sucessfully');
            }else{
                 Session::flash('error','Something went wrong');
                 return redirect()->back();
            }
        }
        $label = $this->label;
        $countries = Country::get();
        return view('backend.user.adduser',compact('countries','label'));     
    }

    function getUserList(Request $request){
        $label = $this->label;
        $userList = User::with('countries')
                        ->select('user_name','uemail','uregistration_time','ucountry_id','created_at')
                        ->orderBy('userid','desc')
                        ->get();

        return view('backend.user.list',compact('userList','label'));
    }

    public function editAdminUser(Request $request,$id){

        if($request->isMethod('post')){
            $input = $request->all();

            $profile =  User::where('userid',$id)->update(['full_name'=> @$input['full_name'],
                                    'uemail'            =>@$input['email'],
                                    'address'           =>@$input['address'],
                                    'country_id'        =>@$input['country_id'],
                                    'image'             =>@$image,
                                    'hourly_price'      =>@$input['hourly_price']
                          ]);

            if($profile){
                 return redirect('/admin/dashboard')->with('success','profile update sucessfully');
            }else{
                 Session::flash('error','Something went wrong');
                 return redirect()->back();
            }
        }

        $userList   = User::with('myCountry')->where('id',$id)->first();
        $countries  = Country::get();
        return view('backend.edituser',compact('userList','countries','id'));
    }

    public function deleteAdminUser($id, Request $request)
    {   
        $data= User::where('userid', base64_decode($id))->first();
        if(!empty($data)){
            User::where('userid', base64_decode($id))->delete();
            Session::flash('success', 'User deleted successfully');
          return $response = array('status'=>'ok');
        }
    }


}
