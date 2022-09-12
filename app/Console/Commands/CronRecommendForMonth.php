<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronRecommendPoemMonthDay;
use App\Models\User;
use Mail, Hash, Auth;
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
    public function handle()
    {
        \Log::info("Cron is working fine!");
        
        $data = CronRecommendPoemMonthDay::where('status',1)
                                       ->get();
                // dd($data);                        
                $message = [
                           'message' => 'Hello, did you know that moving services to the Edge is complicated but Section makes it easy. ',
                       ];

                $key = array_rand($message);
                $value = $message[$key];
                foreach ($data as $key => $val) {
                       // dd($val,$val['recommend_poem_email'],$val['other_email']);
                   switch ($val['type']) {
                       case 1:
                               $currentUser = User::where('userid',$val['userid'])->first();
                               if($val['recommend_poem_email']==1){
                                   Mail::raw("{$key} -> {$value}", function ($mail) use ($currentUser) {
                                       $mail->from('demou844@gmail.com');
                                       $mail->to($currentUser['other_email'])
                                           ->subject('Section Edge');
                                   });        
                               }else{
                                   Mail::raw("{$key} -> {$value}", function ($mail) use ($currentUser) {
                                       $mail->from('demou844@gmail.com');
                                       $mail->to($currentUser['uemail'])
                                           ->subject('Section Edge');
                                   });        
                               }
                               CronRecommendPoemMonthDay::where('id',$val->id)->update([
                                   'status'=>2
                               ]);
                           
                           break;
                       case 2:
                           CronRecommendPoemMonthDay::where('id',$val->id)->update([
                               'status'=>2
                           ]);
                           break;
                       default:

                           $currentUser = User::where('userid',$val['userid'])->first();
                           if($val['recommend_poem_email']==1){
                               Mail::raw("{$key} -> {$value}", function ($mail) use ($currentUser) {
                                   $mail->from('demou844@gmail.com');
                                   $mail->to($currentUser['other_email'])
                                       ->subject('Section Edge');
                               });        
                           }else{
                               Mail::raw("{$key} -> {$value}", function ($mail) use ($currentUser) {
                                   $mail->from('demou844@gmail.com');
                                   $mail->to($currentUser['uemail'])
                                       ->subject('Section Edge');
                               });        
                           }
                           CronRecommendPoemMonthDay::where('id',$val->id)->update([
                               'status'=>2
                           ]);
                           
                           break;
                   }     
                }                           


        $this->info('Cron is working fine! for day');
    }
}
