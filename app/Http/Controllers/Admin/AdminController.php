<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests, DB, Session, Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\Admin;
use App\Models\User;
use App\Models\Poem;
use App\Traits\ImagesTrait;
use DataTables;
use Exception;
use Config;
use Crypt;
use Auth;

class AdminController extends Controller
{
    use ImagesTrait;
    public function loginAdmin(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            if ($request->isMethod('post')) {
                if (isset($request->email)&&isset($request->password)) {
                    $admin = Admin::where('email',$request->email)->first();
                    if(isset($admin) && !empty($admin)){
                        $password = $admin->password;
                        if (Hash::check($request->password,$admin->password)) {
                                if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->has('remember') ? true : false)){       
                                    // Session::flash('success','Welcome '.$admin['name'].' to Green Pheasants');
                                    Session::flash('success','Login successfully');
                                    return redirect('admin/dashboard');
                                }
                        } else {
                            Session::flash('error','wrong password');
                            return redirect()->back();  
                        }
                    } else {
                        Session::flash('error','Email or Password is incorrect');
                        return redirect()->back(); 
                    }
                } else {
                    Session::flash('error',Config::get(ADMN_MSGS.'.session.login.error.required_fields'));
                    return redirect()->back();
                }
            }
            return view('backend.login');
        } else {
            return redirect('admin/dashboard');
        }
        return view('backend.login');
    }

    public function forgotPassword(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();
            $email = $data['email'];
            $user = Admin::where('email',$email)->first();
            $project_name = 'Green Pheasants Admin';
            if(empty($user)){
                return redirect('/login')->with('error','Invalid email-id');
            } else{
                $user_id    = base64_encode($user->id);
                $user_name  = ucfirst($user->name);
                $random_no  = rand(111111, 999999);
                $code       = $random_no.time();
                $security_code = base64_encode(convert_uuencode($code));
                $user->security_code = $security_code;
                $user->save();
                $set_password_url = url('set-password/'.$security_code.'/'.$user_id);
                Mail::send('backend.emails.forgotPasswordMail',['name'=>$user_name,'email'=>$email,'set_password_url'=>$set_password_url],function($message) use($email,$project_name){
                    $message->to($email,$project_name)->subject('Forgot password');
                });
                return redirect('/login')->with('success','Email sent successfully on registered email.');
            }
        }
        return view('backend.forgotPassword');
    }

    public function set_password(Request $request, $security_code, $user_id){
        if(!Auth::check()){
            $user_id = base64_decode($user_id);
            $user = Admin::where(['id'=>$user_id,'security_code'=>$security_code])
                            ->first();
            if(!empty($user->security_code)){
                $email = $user->email;

                return view('backend.changePassword', compact('email','security_code','user_id'));
            } else{
                return redirect('/login')->with('error','Link expired');
            }
        } else{
            return redirect('/')->with('error','Please logout your profile');
        }
    }

    public function changePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            if($request->isMethod('post')){
                $data = $request->all();
                if(!empty($data['new_pw']) && !empty($data['confirm_pw'])){
                    if($data['new_pw'] == $data['confirm_pw']){
                        $update['password'] = Hash::make($data['confirm_pw']);
                        Admin::where('id',1)
                            ->update([
                                        'password'        =>$update['password'],
                                        'security_code'   =>null
                                    ]);
                        return redirect('/login')->with('success','Password changed successfully');
                    } else{
                        return redirect()->back()->with('error',"Password and confirm password doesn't matched");   
                    }
                } else{
                    return redirect()->back()->with('error','Please enter password to change');
                }
            }

            if($updatePassword){
                return response()->json(['success' => true,'message' => 'Password change successfully', 'code' => 200]);
            }else{
                return response()->json(['error' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
            }
         }
    }

    public function dashboard(){
        if (Auth::guard('admin')->check()) {
        $userList = User::with('countries')
                        ->select('user_name','uemail','uregistration_time','ucountry_id','created_at')
                        ->get();
                        
        $userCount = User::count();
        $poemCount = Poem::count();
                    
        }else{
            Session::flash('error','Please login to access the page');
            return redirect('/');
        }

        return view('backend.index',compact('userList','userCount','poemCount'));        
    }

    public function admin_profile(Request $request){

        if($request->isMethod('post')){
            $input = $request->all();
            $admin =   Auth::guard('admin')->user();
            // dd($input);
            if(isset($input['image'])){
                $image1 = isset($input['image']) && !empty($input['image']) ? $input['image']:'';
                if($request->file('image')){ 
                    $directory ='admin/images/profile';
                    $type = 'logo';
                    $imagedata1 = $this->uploadimage($directory,$type, $request->file('image'), '');
                    if(isset($imagedata1) && $imagedata1 != ''){
                        $adminImage = $imagedata1['image'];
                    }
                }
                
                if($admin['image'] && file_exists(public_path('admin/images/profile/'.$admin['image']))){
                    unlink(base_path('public/admin/images/profile/'.$admin['image']));
                }

                Admin::where('id',Auth::guard('admin')->user()->id)
                      ->update([
                        'image' => @$adminImage
                      ]);
  
            }

            $profile =  Admin::where('id',Auth::guard('admin')->user()->id)
                                ->update([
                                   'name'           =>@$input['name'],
                                   'email'          =>@$input['email'],
                                   'address'        =>@$input['address'],
                                   'country_id'     =>@$input['country_id']
                            ]);

            // if($profile){
                return redirect('/admin/profile')
                        ->with('success','Profile update sucessfully');
            // }else{
            //     Session::flash('error','Something went wrong');
            //     return redirect()->back();
            // }
        }

        $userList = Auth::guard('admin')->user();
        $countries = Country::get(); 
        return view('backend.admin.profile',compact('userList','countries'));
    }

    public function changePasswordAdmin(Request $request) {
        if($request->isMethod('post')){
           $input = $request->all();
           $adminData = Admin::where('id',Auth::guard('admin')->user()->id)
                            ->update(['password'=>Hash::make($input['password'])]);
           Session::flash('success','Password changed successfully');
           return redirect('admin/dashboard');
        }
        return view('backend.admin.changepassword');
    }

    public function contactUsQueryList(Request $request){
       $contactUs = ContactUs::orderBy('id','desc')
                        ->get();
        $label = 'Queries';
       return view('backend.contacts',compact('contactUs','label'));
    }

    public function adminLogout(){
        // Auth::logout();
        // Auth::guard('admin')->logout();
        Session::flush();
        return redirect('/login')->with('success','Logged out successfully');
    }

}
