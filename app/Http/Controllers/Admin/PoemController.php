<?php

namespace App\Http\Controllers\Admin;

// dd('namespace HelloWorld');

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Item;
use App\Models\Country;
use App\Models\Creator;
use App\Models\Collection;
use App\Models\PoemTheme;
use App\Models\Mood;
use App\Traits\ImagesTrait;
use App\Models\PoemText;
use App\Models\Interaction;
use App\Models\User;
use Mail, Hash, Auth;
use JWTAuth,Session;

class PoemController extends Controller
{
    use ImagesTrait;

    protected $label;

    public function __construct(){
        $this->label = 'Poem';
        $this->middleware('auth:admin');
    }

    function getPoemList(Request $request){
        $label = $this->label;
        $poemList = Item::with('itemAddedByUser','itemDetail','poemFullDetail')
                        ->orderBy('itemid','desc')
                        ->get();

        return view('backend.poem.list',compact('poemList','label'));
    }

    public function addPoem(Request $request){
        if ($request->isMethod('post')) {
            // dd('here',$request->all());

            $check_words_bin='';
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

            if($request->poet!='other'){
                $checkPoet = Creator::where('cname',$request->poet)->first();         
            }else{
               $creator_id = Creator::create([
                                        'cname' =>$request->otherPoet,
                                    ])->id;
            }  
            // dd($request->all(),$request->poem_theme_selected[0]);    
            $poemId =   Item::create([
                            'creatorid'         =>$request->poet!='other' ? $checkPoet['creatorid']:$creator_id,
                            'userid'             =>0,
                            'cname'         =>$request->poet!='other' ? $request->poet:$request->otherPoet,
                            'ititle'        =>@$request->title,
                            'iyear'         =>@$request->iyear,
                            'notify_by' =>@$request->notify_via,
                            'itheme1'       =>@$request->poem_theme_selected[0],
                            'itheme2'       =>@$request->poem_theme_selected[1],
                            'itheme3'       =>@$request->poem_theme_selected[2],
                            'itheme4'       =>@$request->poem_theme_selected[3],
                            'itheme5'       =>@$request->poem_theme_selected[4],
                            'imood1'        =>@$request->poem_mood_selected[0],
                            'imood2'        =>@$request->poem_mood_selected[1],
                            'imood3'        =>@$request->poem_mood_selected[2],
                            'icontent_url'             =>@$request->source_link,
                            'curl'                     =>@$request->source_link,
                            'ctext'  =>@$request->source,
                            'item_text1'               =>@$request->price_firsrt_append_div[1]['itext'],
                            'item_text2'               =>@$request->price_firsrt_append_div[2]['itext'],
                            'item_text3'               =>@$request->price_firsrt_append_div[3]['itext'],
                            'iadd_url_1'               =>@$request->price_firsrt_append_div[1]['url'],
                            'iadd_url_2'               =>@$request->price_firsrt_append_div[2]['url'],
                            'iadd_url_3'               =>@$request->price_firsrt_append_div[3]['url'],
                            'inum_words'               =>$str_word_count,
                            'inum_words_bin'           =>$check_words_bin,
                            'inum_lines'               =>$inum_lines,
                            'inum_words_per_line'      =>(int)$inum_words_per_line,
                            'inum_words_per_line_bin'  =>$inum_words_per_line_bin,
                            'is_admin'                 =>1,
                            'approved_by_admin'                 =>1,
                         ])->id;

            PoemText::create([
                'itemid'        =>$poemId,
                'ititle'        =>@$request->title,
                'creatorid'     =>$request->poet!='other' ? $checkPoet['creatorid']:$creator_id,
                'cname'         =>$request->poet!='other' ? $request->poet:$request->otherPoet,
                'iyear'         =>@$request->iyear,
                'icontent_url'  =>@$request->source_link,
                'ctext'  =>@$request->source,
                'itext'         =>@$request->description
            ]);

            $adminData = Admin::where('id',1)->first();
            $countries = Country::where('id',$adminData['country_id'])->first();
            // Interaction::create([
            //     'userid'             =>0,
            //     'ucountry_id'        =>0,
            //    'visitorid'       =>0,
            //    'vcountry'        =>$countries['iso'],
            //     'itemid'             =>$poemId,
            //     'creatorid'       =>$request->poet!='other' ? $checkPoet['creatorid']:$creator_id,
            //     'iyear'              =>@$request->iyear,
            //      'itheme1'       =>@$request->poem_theme_selected[0],
            //                 'itheme2'       =>@$request->poem_theme_selected[1],
            //                 'itheme3'       =>@$request->poem_theme_selected[2],
            //                 'itheme4'       =>@$request->poem_theme_selected[3],
            //                 'itheme5'       =>@$request->poem_theme_selected[4],
            //                 'imood1'        =>@$request->poem_mood_selected[0],
            //                 'imood2'        =>@$request->poem_mood_selected[1],
            //                 'imood3'        =>@$request->poem_mood_selected[2],
            //     'itheme_ids'        =>$request->poem_theme_selected ? implode(",", $request->poem_theme_selected) : null,
            //     'imood_ids'         =>$request->poem_mood_selected ? implode(",", $request->poem_mood_selected) : null,
            //     'inum_words'          =>$str_word_count,
            //     'inum_words_bin'      =>$check_words_bin,
            //     'inum_lines'          =>$inum_lines,
            //     'inum_words_per_line'      =>(int)$inum_words_per_line,
            //     'inum_words_per_line_bin'  =>$inum_words_per_line_bin,
            //     'is_admin'                 =>1,
            //     'rtheme'                    =>$request->poem_theme_selected ? implode(",", $request->poem_theme_selected) : null,
            //     'rmood'                     =>$request->poem_mood_selected ? implode(",", $request->poem_mood_selected) : null,
            //     'received_email'            =>0,
            //     'received_push'             =>0,
            //     'received_online'           =>1,
            //     // 'view_num'                  =>$request->description,
            //     // 'last_view_start'           =>$request->description,
            //     // 'last_view_end'             =>$request->description,
            //     // 'last_view_duration'        =>$request->description,
            //     // 'collection'                =>$request->description,
            //     // 'register'                  =>$request->description
            // ]);
            

            return redirect('admin/poem/list')->with('success','Poem added  successfully.'); 
        }
        
        $themeList = array (
              array('item_id'=> 1, 'item_text'=> 'Love'),
              array('item_id'=> 2, 'item_text'=> 'Loss'),
              array('item_id'=> 3, 'item_text'=> 'Relationships'),
              array('item_id'=> 4, 'item_text'=> 'Religion or Spirituality'),
              array('item_id'=> 5, 'item_text'=> 'Society')
        );

        $moodList = array (
              array('item_id'=> 2, 'item_text'=> 'Sunny'),
              array('item_id'=> 3, 'item_text'=> 'Gloomy'),
              array('item_id'=> 4, 'item_text'=> 'Reflective')
        );

        $creators   = Creator::get();

        $label = 'Add poem';
        return view('backend.poem.addPoem',compact('label','creators','moodList','themeList'));
    }

    public function viewPoem(Request $request,$id){
        $itemData = Item::with('itemAddedByUser','itemDetail')->where('itemid',$id)->first();
        // dd($itemData);

        $selected_array = [];

        if(!empty($itemData['itheme1'])){
            array_push($selected_array, $itemData['itheme1']);
        }

        if(!empty($itemData['itheme2'])){
            array_push($selected_array, $itemData['itheme2']);
        }

        if(!empty($itemData['itheme3'])){
            array_push($selected_array, $itemData['itheme3']);
        }

        if(!empty($itemData['itheme4'])){
            array_push($selected_array, $itemData['itheme4']);
        }

        if(!empty($itemData['itheme5'])){
            array_push($selected_array, $itemData['itheme5']);
        }

        $selected_mood_array = [];

        if(!empty($itemData['imood1'])){
            array_push($selected_mood_array, $itemData['imood1']);
        }

        if(!empty($itemData['imood2'])){
            array_push($selected_mood_array, $itemData['imood2']);
        }

        if(!empty($itemData['imood3'])){
            array_push($selected_mood_array, $itemData['imood3']);
        }


       
        $themeList = array (
              array('item_id'=> 1, 'item_text'=> 'Love'),
              array('item_id'=> 2, 'item_text'=> 'Loss'),
              array('item_id'=> 3, 'item_text'=> 'Relationships'),
              array('item_id'=> 4, 'item_text'=> 'Religion or Spirituality'),
              array('item_id'=> 5, 'item_text'=> 'Society')
        );

        $moodList = array (
              array('item_id'=> 2, 'item_text'=> 'Sunny'),
              array('item_id'=> 3, 'item_text'=> 'Gloomy'),
              array('item_id'=> 4, 'item_text'=> 'Reflective')
        );


        $creators   = Creator::get();
        $label = 'Edit poem';
        return view('backend.poem.viewPoem',compact('label','creators','moodList','themeList','itemData', 'selected_array','selected_mood_array','id'));
    }

    public function editPoem(Request $request,$id){
        // dd($itemData);
        if($request->isMethod('post')) {
            $payload = $request->except('_token');
             $check_words_bin='';
            $str_word_count = str_word_count($payload['description']);
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
            $inum_lines     = substr_count( $payload['description'], "\n" ) +1;
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

            if($payload['creatorid'] != 'other'){
                $checkPoet =  $payload['creatorid'];         
                $checkPoet_name = Creator::where('creatorid',$payload['creatorid'])->first(); 
            }else{
               $creator_id = Creator::create([
                                        'cname' =>$payload['cname'],
                                    ])->id;
            }
            $i = 0;
            $addition_url = array();
            if(!empty($payload['price_firsrt_append_div'])) {
                foreach ($payload['price_firsrt_append_div'] as $value) {
                    $addition_url[$i]['itext'] = $value['itext'];
                    $addition_url[$i]['url'] = $value['url'];
                    $i = $i + 1;
                }
            }
            Item::where('itemid',$id)->update([
                            'creatorid'         =>$payload['creatorid']!='other' ? $checkPoet:$creator_id,
                            'cname'         =>$payload['creatorid']!='other' ? $checkPoet_name['cname']:$payload['cname'],
                            'ititle'        =>@$payload['title'],
                            'iyear'         =>@$payload['iyear'],
                            'userid'         =>@$payload['userid'],
                            'itheme1'       =>@$payload['poem_theme_selected'][0],
                            'itheme2'       =>@$payload['poem_theme_selected'][1],
                            'itheme3'       =>@$payload['poem_theme_selected'][2],
                            'itheme4'       =>@$payload['poem_theme_selected'][3],
                            'itheme5'       =>@$payload['poem_theme_selected'][4],
                            'imood1'        =>@$payload['category_id'][0],
                            'imood2'        =>@$payload['category_id'][1],
                            'imood3'        =>@$payload['category_id'][2],
                            'icontent_url'             =>@$payload['source_link'],
                            'curl'                     =>@$payload['source_link'],
                            'ctext'  =>@$payload['source'],
                            'item_text1'               =>@$addition_url[0]['itext'],
                            'item_text2'               =>@$addition_url[1]['itext'],
                            'item_text3'               =>@$addition_url[2]['itext'],
                            'iadd_url_1'               =>@$addition_url[0]['url'],
                            'iadd_url_2'               =>@$addition_url[1]['url'],
                            'iadd_url_3'               =>@$addition_url[2]['url'],
                            'inum_words'               =>$str_word_count,
                            'inum_words_bin'           =>$check_words_bin,
                            'inum_lines'               =>$inum_lines,
                            'inum_words_per_line'      =>(int)$inum_words_per_line,
                            'inum_words_per_line_bin'  =>$inum_words_per_line_bin,
                            'notify_by' =>  $payload['notify_via'],
                        ]); 
            PoemText::where('itemid',$id)->update([
                'ititle'        =>@$request->title,
                'creatorid'     =>$payload['creatorid']!='other' ? $checkPoet:$creator_id,
                'cname'         =>$payload['creatorid']!='other' ? $checkPoet_name['cname']:$payload['cname'],
                'iyear'         =>@$payload['iyear'],
                'icontent_url'  =>@$payload['source_link'],
                'ctext'  =>@$payload['source'],
                'itext'         =>@$payload['description']
            ]);
            // Interaction::where('itemid',$id)->update([
            //     'iyear'              =>@$payload['iyear'],
            //     'itheme_ids'        =>implode(",",$payload['poem_theme_selected']),
            //     'imood_ids'         =>implode(",",$payload['category_id']),
            //     'inum_words'          =>$str_word_count,
            //     'inum_words_bin'      =>$check_words_bin,
            //     'inum_lines'          =>$inum_lines,
            //     'inum_words_per_line'      =>(int)$inum_words_per_line,
            //     'inum_words_per_line_bin'  =>$inum_words_per_line_bin,
            //     'is_admin'                 =>1
            // ]);
            return redirect('/admin/poem/list')->with('success','Poem updated  successfully.'); 
        }
        
        $itemData = Item::with('itemAddedByUser','itemDetail')->where('itemid',$id)->first();
        // dd($itemData);

        $selected_array = [];

        if(!empty($itemData['itheme1'])){
            array_push($selected_array, $itemData['itheme1']);
        }

        if(!empty($itemData['itheme2'])){
            array_push($selected_array, $itemData['itheme2']);
        }

        if(!empty($itemData['itheme3'])){
            array_push($selected_array, $itemData['itheme3']);
        }

        if(!empty($itemData['itheme4'])){
            array_push($selected_array, $itemData['itheme4']);
        }

        if(!empty($itemData['itheme5'])){
            array_push($selected_array, $itemData['itheme5']);
        }

        $selected_mood_array = [];

        if(!empty($itemData['imood1'])){
            array_push($selected_mood_array, $itemData['imood1']);
        }

        if(!empty($itemData['imood2'])){
            array_push($selected_mood_array, $itemData['imood2']);
        }

        if(!empty($itemData['imood3'])){
            array_push($selected_mood_array, $itemData['imood3']);
        }


       
        $themeList = array (
              array('item_id'=> 1, 'item_text'=> 'Love'),
              array('item_id'=> 2, 'item_text'=> 'Loss'),
              array('item_id'=> 3, 'item_text'=> 'Relationships'),
              array('item_id'=> 4, 'item_text'=> 'Religion or Spirituality'),
              array('item_id'=> 5, 'item_text'=> 'Society')
        );

        $moodList = array (
              array('item_id'=> 2, 'item_text'=> 'Sunny'),
              array('item_id'=> 3, 'item_text'=> 'Gloomy'),
              array('item_id'=> 4, 'item_text'=> 'Reflective')
        );


        $creators   = Creator::get();
        $label = 'Edit poem';
        return view('backend.poem.editPoem',compact('label','creators','moodList','themeList','itemData', 'selected_array','selected_mood_array','id'));
    }
    
    function deletePoem(Request $request,$id){
       $data= Item::where('itemid', base64_decode($id))->first();
       if(!empty($data)){
           Item::where('itemid', base64_decode($id))->delete(); 
           PoemText::where('itemid', base64_decode($id))->delete();
           Interaction::where('itemid', base64_decode($id))->delete();
           Collection::where('item_id', base64_decode($id))->delete();
           Session::flash('success', 'Poem deleted successfully');
         return $response = array('status'=>'ok'); 
       }
    }
    
    public function validatePoetName(){

        $title = $_GET['otherPoet'];
        $nameCount = Creator::where('cname',$title)->count();
        if ($nameCount >0) {
            $resp = 'false';
        }else{
            $resp = 'true';
        }
        return $resp;
    }


    public function validateEditPoetName( Request $request){

        $data = $request->all();
        $title = @$data['otherPoet'];
        
        if ($data['id'] == null) {
            $count = Creator::where('cname',$title)->count();;
            if ($count > 0) {
                return 'false';
            } else {
                return 'true';
            }
        } else{
            $id    = $data['id'];
            $count = Creator::where('cname',$title)
                             ->where('id','!=',$id)
                             ->count();
            if ($count > 0) {
                return 'false';
            } else {
                return 'true';
            }
        }
    }

    public function changeStatusPoem(Request $request){
        $userdata = User::where('userid',$request->userid)->first();
        $poemdata = Item::where('itemid',$request->itemid)->first();
        
        Item::where('itemid',$request->itemid)
            ->update([
                'approved_by_admin'=>($request->status==1)?1:0,
            ]);
            $content  = '';
            $subject  = '';
             $project_name = 'Green Pheasants';
            $user_name  = $userdata->user_name;
            $email  = $userdata->uemail;
            if($request->status==1){
            $content  = 'We are happy to let you know that we have published the poem that you submitted.';
            $subject = 'Your poem was published by Green Pheasants';
             $others = 'Many thanks!';
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
                  CURLOPT_POSTFIELDS => "{\r\n\r\n \"notification\": {\r\n\r\n  \"title\": \"Green Pheasants - Poem confirmation\",\r\n\r\n  \"body\": \"Your poem $poemdata->ititle has been published\",\r\n  \"click_action\": \"https://www.greenpheasants.com/#/poem/$request->itemid\"\r\n\r\n },\r\n\r\n \"to\" : \"$userdata->fcm_token\"\r\n\r\n}",
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
            else{
               $content  = 'After reviewing the poem that you submitted, we are sorry to inform you that we have decided not to publish it since it did not meet our publishing criteria. Please feel welcome to submit other poems in the future.';
               $subject = 'Your poem was not published';
               $others = 'Kind regards,';
            }
           echo $subject;
            Mail::send('backend.emails.poempublish',['name'=>$user_name,'email'=>$email,'content'=>$content,'other'=>$others],function($message) use($email,$project_name,$subject){
                $message->to($email,$project_name)->subject($subject);
                $message->from('info@greenpheasants.com',"Green Pheasants");
            });
        
        return response()->json(['success'=>'Status changed successfully']);
    }


}




