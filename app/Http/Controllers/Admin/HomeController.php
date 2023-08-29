<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Terms;
use App\Models\PrivacyPolicy;
use App\Traits\ImagesTrait;
use App\Models\SupportPayment;



class HomeController extends Controller
{
    use ImagesTrait;

    protected $label;

    public function __construct(){
        $this->label = 'Home';
        $this->middleware('auth:admin');
    }

    public function term(Request $request){

        if($request->isMethod('post')){
            $input = $request->all();
            $update = Terms::first();   
            if(!empty($update)){
                $update->title         = $request->title;
                $update->description   = $request->description_id;

                 if ($update->save()){             
                      return redirect()->back()->with('success','Terms & Condtions updated sucessfully');
                 }else{
                    Session::flash('error','Something went wrong');
                    return redirect()->back();
                }
            }
        }   
        $label = 'Terms & Condtions';
        $terms_condition = Terms::orderby('created_at', 'desc')->first();
        return view('backend.terms', compact('terms_condition','label'));
    }

    public function privacyPolicy(Request $request){
        if($request->isMethod('post')){
            $input  = $request->all();
            $update = PrivacyPolicy::first();   
            if(!empty($update)){
                $update->title         = $request->title;
                $update->description   = $request->description;
                $update->showExtraPrivacyData  = $request->showExtraPrivacyData;
                 if ($update->save()){             
                      return redirect()->back()->with('success','Privacy & Policy updated sucessfully');
                 }else{
                    Session::flash('error','Something went wrong');
                    return redirect()->back();
                }
            }
        }   

        $label = 'Privacy & Policy';
        $privacyPolicy = PrivacyPolicy::orderby('created_at','desc')->first();

        return view('backend.privacyPolicy', compact('privacyPolicy','label'));
    }

    function getPaymentTransactionList(Request $request){
        $label = 'Payment Transaction';

        $supportPaymentList = SupportPayment::with('userDetail')
                        ->orderby('created_at','desc')
                        ->get();

        // dd($supportPaymentList);           

        return view('backend.paymentTransaction.list',compact('supportPaymentList','label'));
    }

    function viewPaymentTransaction(Request $request,$id){
        $label = 'View Payment Transaction';

        $supportPaymentdetail = SupportPayment::with('userDetail')
                                            ->where('id',$id)
                                            ->orderBy('id','desc')
                                            ->first();

        return view('backend.paymentTransaction.view',compact('supportPaymentdetail','label'));
    }

}











