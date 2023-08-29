<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\CronRecommendPoemMonthDay;
use App\Models\PoemText;
use App\Models\User;
use Mail; 
use Hash;
use Auth;
use Carbon\Carbon;

class CronRecommendForMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:recommend:for:month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
 public function handle(){ 
        \Log::info("Cron is working fine!");
        // Write your database logic we bellow:

         $data = CronRecommendPoemMonthDay::get();                   
         $message = [
                    'message' => 'Hello, did you know that moving services to the Edge is complicated but Section makes it easy. ',
                ];
        $key   = array_rand($message);
        $value = $message[$key];
        foreach ($data as $key => $val) {
           if($val['type'] == 1) {
                        $currentUser = User::where('userid',$val['userid'])->first();
                        if($currentUser['recommend_poem_email']==1){ 
                              $tokengen = rand();
                          User::where('userid',$val['userid'])
                          ->update([
                            'email_token' =>$tokengen,
                            'urec_email_time' =>Carbon::now()->format('h:i'),
                            'urec_push_time' =>Carbon::now()->format('h:i'),
                          ]);
                            $poemRandom   = PoemText::inRandomOrder()->first();
                            $project_name = 'Green Pheasants';
                            $user_name    =   $currentUser['user_name'];
                            $email        =   $currentUser['other_email'];
                            $poemDetail   =   $poemRandom['itext'];
                              $poemtitle   =   $poemRandom['ititle'];
                            $poemauthor   =   $poemRandom['cname'];
                            $poempublication   =   $poemRandom['iyear'];
$other   =   'monthly';
                            Mail::send('backend.emails.sendEmailPerDay',['name'=>$user_name,'email'=>$email,'poemDetail'=>$poemDetail,'poemtitle'=>$poemtitle,'poemauthor'=>$poemauthor,'poempublication'=>$poempublication,'user_id'=>$val['userid'],'token'=> $tokengen, 'other' => $other],function($message) use($email,$project_name){
                                $message->to($email,$project_name)->subject('Your monthly recommended poem');
                                $message->from('info@greenpheasants.com',"Green Pheasants");
                            });
                        }else{
                             $tokengen = rand();
                          User::where('userid',$val['userid'])
                          ->update([
                            'email_token' =>$tokengen,
                            'urec_email_time' =>Carbon::now()->format('h:i'),
                            'urec_push_time' =>Carbon::now()->format('h:i'),
                          ]);
                            $poemRandom   = PoemText::inRandomOrder()->first();                       
                            $project_name = 'Green Pheasants';
                            $user_name    =   $currentUser['user_name'];
                            $email        =   $currentUser['uemail'];
                            $poemDetail   =   $poemRandom['itext'];
                              $poemtitle   =   $poemRandom['ititle'];
                            $poemauthor   =   $poemRandom['cname'];
                            $poempublication   =   $poemRandom['iyear'];
$other   =   'monthly';
                            Mail::send('backend.emails.sendEmailPerDay',['name'=>$user_name,'email'=>$email,'poemDetail'=>$poemDetail,'poemtitle'=>$poemtitle,'poemauthor'=>$poemauthor,'poempublication'=>$poempublication,'user_id'=>$val['userid'],'token'=> $tokengen, 'other' => $other],function($message) use($email,$project_name){
                                $message->to($email,$project_name)->subject('Your monthly recommended poem');
                                $message->from('info@greenpheasants.com',"Green Pheasants");
                            });
                        }

                        CronRecommendPoemMonthDay::where('id',$val->id)->update([
                            'status'=>2
                        ]);
                    
                  }
                  else if($val['type'] == 2){
                  if($val['mobile_token']){
                          $poemfulldata = PoemText::inRandomOrder()->where('approved_by_admin',1)->first();

                  $curl = curl_init();

                  curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\r\n\r\n \"notification\": {\r\n\r\n  \"title\": \"".$poemfulldata->ititle."\",\r\n\r\n  \"body\": \"".$poemfulldata->itext."\",\r\n   \"click_action\": \"https://www.greenpheasants.com/#/poem/".$poemfulldata->itemid."\"\r\n\r\n },\r\n\r\n \"to\" : \"".$val['mobile_token']."\"\r\n\r\n}",
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
                    CronRecommendPoemMonthDay::where('id',$val->id)->update([
                        'status'=>2
                    ]);
                  }

                 

                    // $currentUser = User::where('userid',$val['userid'])->first();
                    // if($currentUser['recommend_poem_email']==1){
                    //     Mail::raw("{$key} -> {$value}", function ($mail) use ($currentUser) {
                    //         $mail->from('deepakindiit@gmail.com');
                    //         $mail->to($currentUser['other_email'])
                    //             ->subject('Section Edge');
                    //     });        
                    // }else{
                    //     Mail::raw("{$key} -> {$value}", function ($mail) use ($currentUser) {
                    //         $mail->from('deepakindiit@gmail.com');
                    //         $mail->to($currentUser['uemail'])
                    //             ->subject('Section Edge');
                    //     });        
                    // }
else{
   if($val['mobile_token']){
        $poemfulldata = PoemText::inRandomOrder()->where('approved_by_admin',1)->first();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\r\n\r\n \"notification\": {\r\n\r\n  \"title\": \"".$poemfulldata->ititle."\",\r\n\r\n  \"body\": \"".$poemfulldata->itext."\",\r\n   \"click_action\": \"https://www.greenpheasants.com/#/poem/".$poemfulldata->itemid."\"\r\n\r\n },\r\n\r\n \"to\" : \"".$val['mobile_token']."\"\r\n\r\n}",
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

                 $currentUser = User::where('userid',$val['userid'])->first();
                        if($currentUser['recommend_poem_email']==1){ 
                             $tokengen = rand();
                          User::where('userid',$val['userid'])
                          ->update([
                            'email_token' =>$tokengen,
                            'urec_email_time' =>Carbon::now()->format('h:i'),
                            'urec_push_time' =>Carbon::now()->format('h:i'),
                          ]);
                            $poemRandom   = PoemText::inRandomOrder()->first();
                            $project_name = 'Green Pheasants';
                            $user_name    =   $currentUser['user_name'];
                            $email        =   $currentUser['other_email'];
                            $poemDetail   =   $poemRandom['itext'];
                              $poemtitle   =   $poemRandom['ititle'];
                            $poemauthor   =   $poemRandom['cname'];
                            $poempublication   =   $poemRandom['iyear'];
$other   =   'monthly';
                            Mail::send('backend.emails.sendEmailPerDay',['name'=>$user_name,'email'=>$email,'poemDetail'=>$poemDetail,'poemtitle'=>$poemtitle,'poemauthor'=>$poemauthor,'poempublication'=>$poempublication,'user_id'=>$val['userid'],'token'=> $tokengen, 'other' => $other],function($message) use($email,$project_name){
                                $message->to($email,$project_name)->subject('Your monthly recommended poem');
                                $message->from('info@greenpheasants.com',"Green Pheasants");

                            });
                        }else{
                             $tokengen = rand();
                          User::where('userid',$val['userid'])
                          ->update([
                            'email_token' =>$tokengen,
                            'urec_email_time' =>Carbon::now()->format('h:i'),
                            'urec_push_time' =>Carbon::now()->format('h:i'),
                          ]);
                            $poemRandom   = PoemText::inRandomOrder()->first();                       
                            $project_name = 'Green Pheasants';
                            $user_name    =   $currentUser['user_name'];
                            $email        =   $currentUser['uemail'];
                            $poemDetail   =   $poemRandom['itext'];
                              $poemtitle   =   $poemRandom['ititle'];
                            $poemauthor   =   $poemRandom['cname'];
                            $poempublication   =   $poemRandom['iyear'];
$other   =   'monthly';
                            Mail::send('backend.emails.sendEmailPerDay',['name'=>$user_name,'email'=>$email,'poemDetail'=>$poemDetail,'poemtitle'=>$poemtitle,'poemauthor'=>$poemauthor,'poempublication'=>$poempublication,'user_id'=>$val['userid'],'token'=> $tokengen, 'other' => $other],function($message) use($email,$project_name){
                                $message->to($email,$project_name)->subject('Your monthly recommended poem');
                                $message->from('info@greenpheasants.com',"Green Pheasants");
                            });
                        }
                    CronRecommendPoemMonthDay::where('id',$val->id)->update([
                        'status'=>2
                    ]);
}
              
                  
        }                     

       $this->info('Cron is working fine! for day');

    }
}
