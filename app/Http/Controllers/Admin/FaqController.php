<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Requests, DB, Session, Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Faq;
use DataTables;
use Exception;
use Config;
use Crypt;
use Auth;

class FaqController extends Controller
{
    public function indexFaq(Request $request)
    {
        $faqs = Faq::orderBy('id', 'desc')->get();
        return view('backend.faq.list', compact('faqs'));
    }

     public function addFaq(Request $request){
        if ($request->isMethod('post')) {
            $payload = $request->except('_token');
            Faq::create([
                'title'       => $payload['title'],
                'description' => $payload['description']
            ]);
            return redirect('/admin/faqs')->with('success','FAQ added  successfully.'); 
        }
        return view('backend.faq.add');
    }

    public function editFaq(Request $request,$id){
        
        $faq = Faq::find($id);
        
        if ($request->isMethod('post')) {
            $payload = $request->except('_token');
            Faq::where('id',$payload['id'])
                ->update([
                    'title'       => $payload['title'],
                    'description' => $payload['description']
                ]);
            return redirect('/admin/faqs')->with('success','FAQ updated  successfully.'); 
        }
        return view('backend.faq.edit', compact('faq', 'id'));
    }

    public function validateFaqTitle(){

        $title = $_GET['title'];
        $nameCount = Faq::where('title',$title)->count();
        // dd($nameCount);
        if ($nameCount >0) {
            $resp = 'false';
        }else{
            $resp = 'true';
        }
           return $resp;
    }


    public function validateEditFaqTitle( Request $request){

        $data = $request->all();
        $title = @$data['title'];
        
        if ($data['id'] == null) {
            $count = Faq::where('title',$title)->count();;
            if ($count > 0) {
                return 'false';
            } else {
                return 'true';
            }
        } else{
            $id    = $data['id'];
            $count = Faq::where('title',$title)
                             ->where('id','!=',$id)
                             ->count();
            if ($count > 0) {
                return 'false';
            } else {
                return 'true';
            }
        }
    }
   
    public function deleteFaq($id, Request $request){
       $data= Faq::where('id', base64_decode($id))->first();
       if(!empty($data)){
           Faq::where('id', base64_decode($id))->delete();
        //   Session::flash('success', 'FAQ deleted successfully');
         return $response = array('status'=>'ok'); 
       }
    }
    
}
