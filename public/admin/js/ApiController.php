<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use JWTAuth,Session;
use Validator;
use IlluminateHttpRequest;
use AppHttpRequestsRegisterAuthRequest;
use TymonJWTAuthExceptionsJWTException;
use SymfonyComponentHttpFoundationResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Mail, Hash, Auth;
use App\Traits\ImagesTrait;
use App\Models\Country;
use App\Models\Notification;
use App\Models\CompanyService;
use App\Models\Company;
use App\Models\CompanyImage;
use App\Models\Job;
use App\Models\TypeOfWork;
use App\Models\Language;
use App\Models\Service;
use App\Models\JobServiceProvide;
use App\Models\JobImage;

class ApiController extends Controller
{
    use ImagesTrait;

    public function user_registration(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'full_name'     => 'required',
                'password'      => 'required',
                'email'         => 'required|email'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        $check_email_exists = User::where('email', $data['email'])->first();
        if (!empty($check_email_exists)) {
            return response()->json(['error' => 'This Email is already exists.'], 200);
        }

        $user                       = new User();
        $user->full_name            = $data['full_name'];
        $user->email                = $data['email'];
        $user->decrypt_password     = $data['password'];
        $hash_password              = Hash::make($data['password']);
        $user->password             = str_replace("$2y$", "$2a$", $hash_password);
        if ($user->save()) {
            $email                      = $data['email'];
            return response()->json(['status' => true,'code'=>200,'message' => 'Registration successfully','data' => $user], Response::HTTP_OK);
        } else {
            return response()->json(['status' => false,'code'=>400, 'message' => 'Something went wrong, Please try again later.']);
        }
    }
 
    public function user_login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make(
            $request->all(),
            [
                'email'      => 'required|email',
                'password'   => 'required'
            ]
        );
        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = $validator->errors()->first();
            $response['message'] = "missing parameters";
            return response()->json($response);
        }

        $token = JWTAuth::attempt($credentials);
        if ($token) {
            $user = auth()->userOrFail();
            return response()->json(['status' => true,'message' => 'User login Successfuly', 'token' => $token, 'data' => $user, 'code' => 200]);
        } else {
            return response()->json(['status' => false,'message' => 'Something went wrong', 'code' => 400]);
        }
    }

    
    public function forgot_password(Request $request)
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

        // $check_email_exists->email_verification_token   =  rand(1111, 9999);
      
        $check_email_exists->email_verification_token = $this->generateRandomString(32);
        // User::where('email',$data['email'])->update([
                                                // 'email_verification_token'=>$key
                                            // ]);

        if ($check_email_exists->save()) {
            $project_name = env('App_name');
            $email = $request['email'];
            try {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    Mail::send('frontend.emails.user_forgot_password_api', ['name' => ucfirst($check_email_exists['full_name']), 'otp' => $check_email_exists['email_verification_token']], function ($message) use ($email, $project_name) {
                        $message->to($email, $project_name)->subject('User Forgot Password');
                    });
                }
            } catch (Exception $e) {
            }
            return response()->json(['status' => true, 'message' => 'Email sent on registered Email-id.','code' => 200], Response::HTTP_OK);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong, Please try again later.','code' => 400]);
        }
    }

    public function reset_password(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'email_verification_token'  => 'required|numeric',
                'email'                     => 'required|email',
                'password'                  => 'required',
                'confirm_password'          => 'required_with:password|same:password'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }
        
        $email = $data['email'];
        $check_email = User::where('email', $email)->first();
        if (empty($check_email['email_verification_token'])) {
            return response()->json(['status' => false,'code'=>400, 'message' => 'Something went wrong, Please try again later.']);
        }
        if (empty($check_email)) {
            return response()->json(['status' => false,'code'=>400, 'message' => 'This Email-id is not exists.']);
        } else {
            if ($check_email['email_verification_token'] == $data['email_verification_token']) {
                $check_email->decrypt_password         = $data['password'];
                $hash_password                  = Hash::make($data['password']);
                $check_email->password          = str_replace("$2y$", "$2a$", $hash_password);
                $check_email->email_verification_token               = null;
                if ($check_email->save()) {
                    return response()->json(['status' => true, 'code'=>200, 'message' => 'Password changed successfully.']);
                } else {
                    return response()->json(['status' => false,'code'=>400, 'message' =>'Something went wrong, Please try again later.']);
                }
            } else {
                return response()->json(['status' => false,'code'=>400, 'message' => 'email verification token mismatch']);
            }
        }
    }
    
    public function logout()
    {
        Auth::guard('api')->logout();
        Session::flush();
        return response()->json(['status' => true,'message' => 'logout successfully', 'code' => 200]);
    }
    

    public function profile(Request $request)
    {
        try {
            $user = auth()->userOrFail();
            $decrypt_password = base64_encode($user['decrypt_password']);
            $countryName = Country::where('id',$user['country_id'])->first();
            return response()->json(['status' => true, 'message' => 'User Profile','countryDetail'=>$countryName,'decrypt_password'=>$decrypt_password, 'data' => $user, 'code' => 200]);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
        }
    }

    public function getCountries(Request $request)
    {
        try {
            $country = Country::get();
            return response()->json(['status' => true,'message' => 'All Country List', 'data' => $country, 'code' => 200]);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['status' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
        }
    }

    public function getDashboard(Request $request) {
        try {
            $userData =   auth()->userOrFail();
            $notifications = Notification::where('reciever_id',$userData->id)->orderBy('id','DESC')->get();
        return response()->json(['status' => true,'message' => 'Notification List', 'data' => $notifications, 'code' => 200]);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
        }
    }

    public function changePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $validator = Validator::make(
                $request->all(),
                [
                   'password'                  => 'required',
                   'confirm_password'          => 'required_with:password|same:password'
                ]
            );

            if ($validator->fails()) {
                $response['code']    = 404;
                $response['status']  = $validator->errors()->first();
                $response['message'] = "missing parameters";
                return response()->json($response);
            }
            $user =   auth()->userOrFail();

            $update['password'] = Hash::make($data['password']);
            $updatePassword = User::where('id',$user['id'])->update([
                                                        'password'=>$update['password'],
                                                        'decrypt_password'=>$data['password']
                                                                ]);
    
            if($updatePassword){
                return response()->json(['success' => true,'message' => 'Password change successfully', 'code' => 200]);
            }else{
                return response()->json(['error' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
            }
         }
    }
    
    public function updateProfile(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make(
            $request->all(),
            [
               'full_name'         =>'required',
               'country_id'        =>'required|numeric',
               'city_name'         =>'required',
               'address'           =>'required',
               'hourly_price'      =>'required|numeric'
            ]
        );

        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = $validator->errors()->first();
            $response['message'] = "missing parameters";
            return response()->json($response);
        }

        $user =   auth()->userOrFail();
        $user->full_name         = $data['full_name'];
        $user->country_id        = $data['country_id'];
        $user->city_name         = $data['city_name'];
        $user->address           = $data['address'];
        $user->hourly_price      = $data['hourly_price'];
        // print_r($user); die(); 

        if(isset($data['image'])){
           $image = isset($data['image']);
           $directory = 'frontend/images/profile';
           $type = 'logo';
           $imagedata = $this->uploadimage($directory,$type, $image, '');
             if(isset($imagedata) && $imagedata != ''){
                 $user->image = $imagedata['image'];
             }
        }
        $user->save();
        $countryName = Country::where('id',$user['country_id'])->first();
        // print_r($countryName); die();
        return response()->json(['status' => true,'message' => 'Profile updated successfully','countryDetail'=>$countryName,'data' => $user, 'code' => 200]);
    }

    public function updateProfilePhoto(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make(
            $request->all(),
            [
               'image'   => 'required'
            ]
        );
        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = $validator->errors()->first();
            $response['message'] = "missing parameters";
            return response()->json($response);
        }
        $user =   auth()->userOrFail();
        if($request->hasfile('image')) { 
            $file = $request->file('image');
            $destination_path = 'frontend/images/profile';
            $extension = $file->getClientOriginalExtension();
            $filename =time().'.'.$extension;
            $file->move($destination_path, $filename);
            $data['image'] = $filename;
        }
        if($user['image'] != null && file_exists('frontend/images/profile'.'/'.$user['image']) ) {
            unlink('frontend/images/profile'.'/'.$user['image']);
        }
        $user = User::where('id',Auth::user()->id)->update($data);
        return response()->json(['status' => true,'message' => 'Profile photo uploaded successfully','data' => $user, 'code' => 200]);
    }

    public function getCompanyProfile(Request $request)
    {
        try {
            $user             = auth()->userOrFail();
            $companyDetail    = Company::where('user_id',$user['id'])->first();
            if ($companyDetail) {
                $companyImage = CompanyImage::where('company_id',$companyDetail['id'])->get();
                $companyService = CompanyService::where('company_id',$companyDetail['id'])->get();
            }else{
                return response()->json(['status' => false,'message' => 'comapny data does not exit', 'code' => 400]);
            }
            return response()->json(['status' => true, 'message' => 'Company Profile','companyService'=>$companyService,'companyImage'=>$companyImage,'companyDetail'=>$companyDetail,'code' => 200]);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
        }
    }
    
    public function updateCompanyProfile(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $request->all(),
            [
               'company_name'        =>'required',
               'company_description' =>'required',
            ]
        );

        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = $validator->errors()->first();
             $response['message'] = "missing parameters";
            return response()->json($response);
        }
        if($request->isMethod('post')){
            $input = $request->all();
            $user =   auth()->userOrFail();
            Company::where('user_id',$user['id'])
                                ->update(['company_name'   => @$input['company_name'],
                                 'company_description'     => @$input['company_description']
                         ]);
            $companyExist = Company::where('user_id',$user['id'])->first();

            if($companyExist){
                if(isset($input['service_name'])){
                    CompanyService::where('company_id',$companyExist['id'])->delete();
                    foreach ($input['service_name'] as $val) {
                            CompanyService::create([
                               'company_id'        =>$companyExist['id'],
                               'service_name'      =>$val
                            ]);
                    }
                }
            
                if(isset($input['images'])){
                    $product_img_del = CompanyImage::where('company_id',$companyExist['id'])->get();
                    foreach ($product_img_del as $key => $value) {
                        $destination     = base_path().'/'.asset('frontend/images/company/');
                        if (file_exists(public_path('frontend/images/company/'.$value['image']))){
                            unlink(public_path('frontend/images/company/'.$value['image']));
                        }
                    }
                    CompanyImage::where('company_id',$companyExist['id'])->delete();
                    foreach ($input['images'] as $key => $val) {
                        $image = isset($val) && !empty($val) ? $val:'';  
                        if($image){ 
                          $directory = 'frontend/images/company';
                          $type = 'logo';
                          $imagedata = $this->uploadimage($directory,$type, $image, '');
                            if(isset($imagedata) && $imagedata != ''){
                                $image = $imagedata['image'];
                            }
                        }
                        CompanyImage::create([
                           'company_id'      => $companyExist['id'],
                           'image'       => $image
                        ]);
                    }
                }
                $company= Company::where('id',$companyExist['id'])->get();
                $companyService= CompanyService::where('company_id',$companyExist['id'])->get();
                $companyImage= CompanyImage::where('company_id',$companyExist['id'])->get();

                return response()->json(['status'=>'true','message' => 'Company profile update successfully', 'code' => 200,'companyImages'=>$companyImage,'companyData'=>$company,'companyServices'=>$companyService]);
            }                   
            $companyId =  Company::create(['company_name'  => $input['company_name'],
                                 'company_description'     => $input['company_description'],
                                 'user_id'                 => $user['id']
                         ])->id;
            $company= Company::where('id',$companyId)->get();

            if($companyId){
                foreach ($input['service_name'] as $val) {
                    CompanyService::create([
                       'company_id'        =>$companyId,
                       'service_name'      =>$val
                    ]);
                }
                $companyService= CompanyService::where('company_id',$companyId)->get();
                foreach ($input['images'] as $key => $val) {
                    $image = isset($val) &&  !empty($val) ? $val:'';  
                    if($image){ 
                      $directory = 'frontend/images/company';
                      $type = 'logo';
                      $imagedata = $this->uploadimage($directory,$type, $image, '');
                        if(isset($imagedata) && $imagedata != ''){
                            $image = $imagedata['image'];
                        }
                    }
                    CompanyImage::create([
                       'company_id'      => $companyId,
                       'image'       => $image
                    ]);
                }
            $companyImage= CompanyImage::where('company_id',$companyId)->get();
            return response()->json(['message' => 'Company profile created successfully', 'code' => 200,'companyImages'=>$companyImage,'companyData'=>$company,'companyServices'=>$companyService]);
            }else{
            return response()->json(['status' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);

            }
        }
    }
    
    public function getAllJobsList(Request $request) {
        $countries = Country::get();
        $user =   auth()->userOrFail(); 
        $allJobs = Job::with('jobImages','jobServiceProvides','jobCountry','user')->where('user_id','!=',$user['id'])->get()->toArray();
        // print_r($allJobs); die();
        if($allJobs){
            return response()->json(['status' => true,'message' => 'All jobs list', 'code' => 200,'myJobs'=>$allJobs]);
        }else{
            return response()->json(['status' => false,'message' => 'No job posted yet.', 'code' => 400]);
        }
    }

    public function getMyJobsList(Request $request) {
        $countries = Country::get();
        $user =   auth()->userOrFail(); 

        $allJobs = Job::with('jobImages','jobServiceProvides','jobCountry','user')->where('user_id',$user['id'])->get()->toArray();
        if($allJobs){
            return response()->json(['status' => true,'message' => 'My jobs list', 'code' => 200,'myJobs'=>$allJobs]);
        }else{
            return response()->json(['status' => false,'message' => 'No job posted yet.', 'code' => 400]);
        }
    }

    
    public function getWorkType(Request $request) {
        $typeOfWorks = TypeOfWork::get();
        if($typeOfWorks){
            return response()->json(['status' => true,'message' => 'Work list', 'code' => 200,'workList'=>$typeOfWorks]);
        }else{
            return response()->json(['status' => false,'message' => 'No work list found yet.', 'code' => 400]);
        }
    }

    public function getLanguage(Request $request) {
        $Languages = Language::get();
        if($Languages){
            return response()->json(['status' => true,'message' => 'Language List', 'code' => 200,'Languages'=>$Languages]);
        }else{
            return response()->json(['status' => false,'message' => 'No language found yet.', 'code' => 400]);
        }
    }

    public function addJob(Request $request) {
        $input = $request->all();
        $validator = Validator::make(
            $request->all(),
            [
               'job_title'        =>'required',
               'work_id'          =>'required|numeric',
               'job_description'  =>'required',
               'address'          =>'required',
               // 'continent_id'     =>'required',
               'country_id'       =>'required|numeric',
               'city'             =>'required',
               'quantity'         =>'required|numeric',
               'severe'           =>'required',
               'start_date'       =>'required',
               'language_id'      =>'required|numeric',
               'price'            =>'required|numeric'
            ]
        );

        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = $validator->errors()->first();
             $response['message'] = "missing parameters";
            return response()->json($response);
        }
        $countries   = Country::get();
        // $continents  = Continent::get();
        $typeOfWorks = TypeOfWork::get();
        $languages   = Language::get();
        $services    = Service::get();

        if($request->isMethod('post')){
            $input = $request->all();
            // print_r($input); die();
            // print_r($input);die();
            $user =   auth()->userOrFail(); 
            $jobId =  Job::create(['job_title'      =>$input['job_title'],
                                 'work_id'          =>$input['work_id'],
                                 'job_description'  =>$input['job_description'],
                                 'address'          =>$input['address'],
                                 // 'continent_id'     =>$input['continent_id'],
                                 'country_id'       =>$input['country_id'],
                                 'city'             =>$input['city'],
                                 'quantity'         =>$input['quantity'],
                                 'severe'           =>$input['severe'],
                                 'start_date'       =>$input['start_date'],
                                 'language_id'      =>$input['language_id'],
                                 'user_id'          =>$user['id'],
                                 'price'            =>$input['price']
                         ])->id;

            if($jobId){
                foreach ($input['service_provided'] as $val) {
                    JobServiceProvide::create([
                       'job_id'            =>$jobId,
                       'service_name'      =>$val
                    ]);
                }
                foreach ($input['images'] as $key => $val) {
                    $image = isset($val) && !empty($val) ? $val:'';
                    if($image){
                      $directory = 'frontend/images/jobs';
                      $type = 'logo';
                      $imagedata = $this->uploadimage($directory,$type, $image, '');
                        if(isset($imagedata) && $imagedata != ''){
                            $image = $imagedata['image'];
                        }
                    }
                    JobImage::create([
                       'job_id'      => $jobId,
                       'image'       => $image
                    ]);
                }
                return response()->json(['status' => true,'message' => 'Job added sucessfully', 'code' => 200]);
            }else{
            return response()->json(['status' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
            }
        }
    }

    public function deleteJob($id){
        // print_r($id);die();
        $job = Job::where('id',$id)->first();
        if($job){
            JobImage::where('id', $id)->delete();
            $jobDetail = Job::where('id',$id)
                              ->delete();

            JobServiceProvide::where('job_id',$id)->delete();
            $product_img_del = JobImage::where('job_id',$id)->get();
            foreach ($product_img_del as $key => $value) {
                $destination     = base_path().'/'.asset('frontend/images/jobs/');
                if (file_exists('frontend/images/jobs/'.$value['image'])){
                    unlink('frontend/images/jobs/'.$value['image']);
                }
            }
            JobImage::where('job_id',$id)->delete();
            return response()->json(['status' => true,'message' => 'Job deleted sucessfully', 'code' => 200]);
        }else{
            return response()->json(['status' => false,'message' => 'Job not found', 'code' => 400]);
        }
    }

    public function editJob(Request $request,$id) {

         if($request->isMethod('post')){
             $input = $request->all();
             $validator = Validator::make(
                 $request->all(),
                 [
                    'job_title'        =>'required',
                    'work_id'          =>'required|numeric',
                    'job_description'  =>'required',
                    'address'          =>'required',
                    // 'continent_id'     =>'required',
                    'country_id'       =>'required|numeric',
                    'city'             =>'required',
                    'quantity'         =>'required|numeric',
                    'severe'           =>'required',
                    'start_date'       =>'required',
                    'language_id'      =>'required|numeric',
                    'price'            =>'required|numeric'
                 ]
             );

             if ($validator->fails()) {
                 $response['code'] = 404;
                 $response['status'] = $validator->errors()->first();
                  $response['message'] = "missing parameters";
                 return response()->json($response);
             }
             
             $user =   auth()->userOrFail(); 
             
             $jobId =  Job::where('id',$id)->update(['job_title'      =>$input['job_title'],
                               'work_id'          =>$input['work_id'],
                               'job_description'  =>$input['job_description'],
                               'address'          =>$input['address'],
                               // 'continent_id'     =>$input['continent_id'],
                               'country_id'       =>$input['country_id'],
                               'city'             =>$input['city'],
                               'quantity'         =>$input['quantity'],
                               'severe'           =>$input['severe'],
                               'start_date'       =>$input['start_date'],
                               'language_id'      =>$input['language_id'],
                               'user_id'          =>$user['id'],
                               'price'            =>$input['price']
                          ]);

                 if ($jobId){
                    foreach ($input['service_provided'] as $key => $value) {
                        if(is_numeric($value) ==''){
                            JobServiceProvide::where('job_id',$id)->update([
                                'service_id'  =>$value
                             ]);
                        }
                    }
                    $jobImageCount = JobImage::where('job_id',$id)->count();
                    if($jobImageCount<5){
                        if(isset($input['images'])){

                             foreach ($input['images'] as $key => $val) {
                                 $image = isset($val) && !empty($val) ? $val:'';

                                 if($image){
                                   $directory = 'frontend/images/jobs';
                                   $type = 'logo';
                                   $imagedata = $this->uploadimage($directory,$type, $image, '');
                                     if(isset($imagedata) && $imagedata != ''){
                                         $image = $imagedata['image'];
                                     }
                                 }

                                JobImage::create([
                                    'job_id'      => $id,
                                    'image'       => $image
                                 ]);
                            }
                        }
                   }
                  return response()->json(['status' => true,'message' => 'Job updated sucessfully', 'code' => 200]);

                  }else{
                    return response()->json(['status' => false,'message' => 'Something went wrong, Please try again later.', 'code' => 400]);
                   }
             }
     }

    private function generateRandomString($length) {
        $characters = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return strtoupper($randomString);
    }


}
