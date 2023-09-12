<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronRecommendPoemOneDay;
use App\Models\User;
use App\Models\PoemText;
use Mail, Hash, Auth;
use Carbon\Carbon;

class CronRecommendForDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:recommend:for:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending out poem emails to application users';

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
    public function handle()
    {
        \Log::info("Cron is working fine!");
        // Write your database logic we bellow:
              // CronRecommendPoemOneDay::where('email','gurjeetkaurindiit@gmail.com')->update([
              //               'status'=>2
              //           ]);


        $data = CronRecommendPoemOneDay::get();
        //  $message = [
        //             'message' => 'Hello, did you know that moving services to the Edge is complicated but Section makes it easy. ',
        //         ];
        // $key   = array_rand($message);
        // $value = $message[$key];
        foreach ($data as $key => $val) {
            if ($val['type'] == 1) {
                $currentUser = User::where('userid', $val['userid'])->first();
                if ($currentUser['recommend_poem_email'] == 1) {
                    $tokengen = rand();
                    User::where('userid', $val['userid'])
                        ->update([
                            'email_token'     => $tokengen,
                            'urec_email_time' => Carbon::now()->format('h:i'),
                            'urec_push_time'  => Carbon::now()->format('h:i'),
                        ]);
                    // Get poemText from Python script
                    $poemRandom      = $this->getPythonScriptData();
                    $project_name    = 'Green Pheasants';
                    $user_name       = $currentUser['user_name'];
                    $email           = $val['email'];
                    $poemDetail      = $poemRandom['itext'];
                    $poemtitle       = $poemRandom['ititle'];
                    $poemauthor      = $poemRandom['cname'];
                    $poempublication = $poemRandom['iyear'];
                    $other           = 'daily';
                    Mail::send('backend.emails.sendEmailPerDay',
                        [
                            'name'            => $user_name,
                            'email'           => $email,
                            'poemDetail'      => $poemDetail,
                            'poemtitle'       => $poemtitle,
                            'poemauthor'      => $poemauthor,
                            'poempublication' => $poempublication,
                            'user_id'         => $val['userid'],
                            'token'           => $tokengen,
                            'other'           => $other
                        ], function($message) use($email,$project_name) {
                            $message->to($email,$project_name)->subject('Your daily recommended poem');
                            $message->from('info@greenpheasants.com',"Green Pheasants");
                        }
                    );
                } else {
                    $tokengen = rand();
                    User::where('userid',$val['userid'])->update(
                        [
                            'email_token'     => $tokengen,
                            'urec_email_time' => Carbon::now()->format('h:i'),
                            'urec_push_time'  => Carbon::now()->format('h:i'),
                        ]);

                    // Get poemText from Python script
                    $poemRandom      = $this->getPythonScriptData();
                    $project_name    = 'Green Pheasants';
                    $user_name       = $currentUser['user_name'];
                    $email           = $val['email'];
                    $poemDetail      = $poemRandom['itext'];
                    $poemtitle       = $poemRandom['ititle'];
                    $poemauthor      = $poemRandom['cname'];
                    $poempublication = $poemRandom['iyear'];
                    $other           = 'daily';

                    Mail::send('backend.emails.sendEmailPerDay',['name' => $user_name, 'email' => $email, 'poemDetail' => $poemDetail, 'poemtitle' => $poemtitle, 'poemauthor' => $poemauthor, 'poempublication' => $poempublication, 'user_id' => $val['userid'], 'token' => $tokengen, 'other' => $other],
                        function($message) use($email,$project_name) {
                                $message->to($email,$project_name)->subject('Your daily recommended poem');
                                $message->from('info@greenpheasants.com',"Green Pheasants");
                    });
                }

                CronRecommendPoemOneDay::where('id', $val->id)->update(['status' => 2]);
            } else if($val['type' == 2]) {
                if ($val['mobile_token']) {
                    // Get poemText from Python script
                    $poemfulldata = $this->getPythonScriptData();
                    $curl = curl_init();

                    curl_setopt_array($curl,
                        array(
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
                        )
                    );

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                        // echo "cURL Error #:" . $err;
                    } else {
                        // echo $response;
                    }
                }

                CronRecommendPoemOneDay::where('id',$val->id)->update(['status' => 2]);
            } else {
                if ($val['mobile_token']) {
                    // Get poemText from Python script
                    $poemfulldata = $this->getPythonScriptData();
                    $curl = curl_init();

                    curl_setopt_array($curl,
                        array(
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
                            )
                    );

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                        // echo "cURL Error #:" . $err;
                    } else {
                        // echo $response;
                    }
                }

                $currentUser = User::where('userid', $val['userid'])->first();
                if ($currentUser['recommend_poem_email'] == 1) {
                    $tokengen = rand();
                    User::where('userid', $val['userid'])->update(
                        [
                            'email_token' => $tokengen,
                            'urec_email_time' => Carbon::now()->format('h:i'),
                            'urec_push_time' => Carbon::now()->format('h:i'),
                        ]);
                    // Get poemText from Python script
                    $poemRandom      = $this->getPythonScriptData();
                    $project_name    = 'Green Pheasants';
                    $user_name       = $currentUser['user_name'];
                    $email           = $val['email'];
                    $poemDetail      = $poemRandom['itext'];
                    $poemtitle       = $poemRandom['ititle'];
                    $poemauthor      = $poemRandom['cname'];
                    $poempublication = $poemRandom['iyear'];
                    $other           = 'daily';

                    Mail::send('backend.emails.sendEmailPerDay',['name'=>$user_name,'email'=>$email,'poemDetail'=>$poemDetail,'poemtitle'=>$poemtitle,'poemauthor'=>$poemauthor,'poempublication'=>$poempublication,'user_id'=>$val['userid'],'token'=> $tokengen, 'other' => $other],
                        function($message) use($email,$project_name){
                            $message->to($email,$project_name)->subject('Your daily recommended poem');
                            $message->from('info@greenpheasants.com',"Green Pheasants");
                    });
                } else {
                    $tokengen = rand();
                    User::where('userid',$val['userid'])->update(
                        [
                            'email_token' => $tokengen,
                            'urec_email_time' => Carbon::now()->format('h:i'),
                            'urec_push_time' => Carbon::now()->format('h:i'),
                        ]);

                    // Get poemText from Python script
                    $poemRandom      = $this->getPythonScriptData();
                    $project_name    = 'Green Pheasants';
                    $user_name       = $currentUser['user_name'];
                    $email           = $val['email'];
                    $poemDetail      = $poemRandom['itext'];
                    $poemtitle       = $poemRandom['ititle'];
                    $poemauthor      = $poemRandom['cname'];
                    $poempublication = $poemRandom['iyear'];
                    $other           = 'daily';

                    Mail::send('backend.emails.sendEmailPerDay',['name'=>$user_name,'email'=>$email,'poemDetail'=>$poemDetail,'poemtitle'=>$poemtitle,'poemauthor'=>$poemauthor,'poempublication'=>$poempublication,'user_id'=>$val['userid'],'token'=> $tokengen, 'other' => $other],
                        function($message) use($email,$project_name){
                            $message->to($email,$project_name)->subject('Your daily recommended poem');
                            $message->from('info@greenpheasants.com',"Green Pheasants");
                    });
                }

                CronRecommendPoemOneDay::where('id',$val->id)->update(['status' => 2]);
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


            // }
        }

        $this->info('Cron is working fine! for day');
    }

    public function getPythonScriptData()
    {
        //Path to anaconda environment
        $condaEnvironment = "/opt/homebrew/Caskroom/miniconda/base/envs/adminGreen";

        // Run the second script choose_item_online_visitor with 2 parameters: theme, mood
        $choosingItemOnlineVisitorScrypt = "../python_scripts/choose_items_many_offline_users.py";

        //Create a command with anaconda environment
        $choosingItemOnlineVisitor = "$condaEnvironment/bin/python $choosingItemOnlineVisitorScrypt 2>&1";

        //Get the result in json format and decode it
        exec($choosingItemOnlineVisitor, $output);

        $jsonResult = end($output);
        $result = json_decode($jsonResult, true);

        //Get the poemDetail from Item table by recommended_item
        return PoemText::where('itemid', $result[0]['recommended_item'])->first();
    }
}
