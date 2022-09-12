<?php

namespace App\Http\Controllers\Admin;

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
                        ->orderBy('userid','desc')
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

            if($request->poet!='other'){
                $checkPoet = Creator::where('cname',$request->poet)->first();         
            }else{
               $creator_id = Creator::create([
                                        'cname' =>$request->otherPoet,
                                    ])->id;
            }  
            
            $poemId =   Item::create([
                            'creatorid'         =>$request->poet!='other' ? $checkPoet['creatorid']:$creator_id,
                            'userid'             =>0,
                            'cname'         =>$request->poet!='other' ? $request->poet:$request->otherPoet,
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
                            'inum_words_per_line_bin'  =>$inum_words_per_line_bin,
                            'is_admin'                 =>1
                        ])->id;

            PoemText::create([
                'itemid'        =>$poemId,
                'ititle'        =>@$request->title,
                'creatorid'     =>$request->poet!='other' ? $checkPoet['creatorid']:$creator_id,
                'cname'         =>$request->poet!='other' ? $request->poet:$request->otherPoet,
                'iyear'         =>@$request->iyear,
                'icontent_url'  =>@$request->source,
                'itext'         =>@$request->description
            ]);

            // $userData = User::where('userid',$request->user_id)->first();

            Interaction::create([
                'userid'             =>0,
                'ucountry_id'        =>0,
                // 'visitorid'       =>$request->description,
                // 'vcountry'        =>$request->description,
                'itemid'             =>$poemId,
                // 'creatorid'       =>$request->description,
                'iyear'              =>@$request->iyear,
                'itheme_ids'        =>implode(",", array_column($request->poem_theme_selected, "id")),
                'imood_ids'         =>implode(",", array_column($request->poem_mood_selected, "id")),
                'inum_words'          =>$str_word_count,
                'inum_words_bin'      =>$check_words_bin,
                'inum_lines'          =>$inum_lines,
                'inum_words_per_line'      =>(int)$inum_words_per_line,
                'inum_words_per_line_bin'  =>$inum_words_per_line_bin,
                'is_admin'                 =>1
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
            

            return redirect('admin/poem/list')->with('success','Poem added  successfully.'); 
        }
        
        $themeList = array (
              array('item_id'=> 1, 'item_text'=> 'Love'),
              array('item_id'=> 2, 'item_text'=> 'Loss'),
              array('item_id'=> 3, 'item_text'=> 'Relationships'),
              array('item_id'=> 4, 'item_text'=> 'Religion or spirit'),
              array('item_id'=> 5, 'item_text'=> 'Society')
        );

        $moodList = array (
              array('item_id'=> 2, 'item_text'=> 'Sunny'),
              array('item_id'=> 3, 'item_text'=> 'Gloomy'),
              array('item_id'=> 4, 'item_text'=> 'Reflection')
        );

        $creators   = Creator::get();

        $label = 'Add poem';
        return view('backend.poem.addPoem',compact('label','creators','moodList','themeList'));
    }

    public function editPoem(Request $request,$id){
        $itemData = Item::with('itemAddedByUser','itemDetail')->where('itemid',$id)->first();
        dd($itemData);
        if ($request->isMethod('post')) {
            $payload = $request->except('_token');
            // Poem::where('id',$payload['id'])
            //     ->update([
            //         'title'       => $payload['title'],
            //         'description' => $payload['description']
            //     ]);
            return redirect('/admin/poem/list')->with('success','Faq updated  successfully.'); 
        }
        
        $themeList = array (
              array('item_id'=> 1, 'item_text'=> 'Love'),
              array('item_id'=> 2, 'item_text'=> 'Loss'),
              array('item_id'=> 3, 'item_text'=> 'Relationships'),
              array('item_id'=> 4, 'item_text'=> 'Religion or spirit'),
              array('item_id'=> 5, 'item_text'=> 'Society')
        );

        $moodList = array (
              array('item_id'=> 2, 'item_text'=> 'Sunny'),
              array('item_id'=> 3, 'item_text'=> 'Gloomy'),
              array('item_id'=> 4, 'item_text'=> 'Reflection')
        );

        $creators   = Creator::get();
        $label = 'Edit poem';
        return view('backend.poem.editPoem',compact('label','creators','moodList','themeList','itemData'));
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


}
