@include('backend.common.header_main')

<style>

    .orderTransactionH4 {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #eee;
        padding: 4px 0px;
    }
    
    .orderTransactionH4 h4 {
        font-size: 15px;
        font-weight: 600;
    }
    
    .orderTransactionH4 label {
        margin: 0px;
    }
    
    .titleTranaction {
        padding-left: 0px;
        padding-top: 20px;
    }

</style>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashbord</a></li>
                                            <li class="breadcrumb-item"><a href="{{url('admin/faqs')}}">Payment Transactions List</a></li>
                                            <li class="breadcrumb-item active">View Payment Transaction</li>
                                        </ol>
                                    </div>

                                    <h4 class="page-title">
                                        View Payment Transaction
                                    </h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
										<div class="col-lg-6">
										   <h4 class="header-title mb-3">View Payment Transaction</h4>
										 </div>
                                            <div class="col-lg-6 text-right">
                                                <a href="{{url('admin/paymentTransaction/list')}}" class="btn btn-primary  mb-3">Back To Payment Transactions List</a>
                                            </div>
                                        </div>
                                       
                                        <form  method="post" enctype="multipart/form-data" >
                                       
                                        
                                        @csrf
                                        <div class="row">
                                            
                                            <div class="col-lg-12">
                                                <div class="form-group orderTransactionH4">
                                                        <label>Paid by</label>
                                                    {{@$supportPaymentdetail['userDetail']['user_name']}} 
                                                </div>
                                            </div>    

                                            @if($supportPaymentdetail['email']!=null)
                                                <div class="col-lg-12">
                                                    <div class="form-group orderTransactionH4">
                                                            <label>User Email</label>
                                                         {{@$supportPaymentdetail['email']}}
                                                    </div>
                                                </div>    
                                            @else
                                                <div class="col-lg-12">
                                                    <div class="form-group orderTransactionH4">
                                                            <label>User Email</label>
                                                         {{@$supportPaymentdetail['userDetail']['uemail']}}
                                                    </div>
                                                </div>
                                            @endif

                                            <?php 
                                                // dd($supportPaymentdetail);
                                                if($supportPaymentdetail['payment_type'] ==0){
                                                    $text = "One time payment";
                                                }else{
                                                    $text = "Monthly Payment";
                                                }
                                            ?>

                                            <div class="col-lg-12">
                                                <div class="form-group orderTransactionH4">
                                                        <label>Payment Type</label>
                                                    {{@$text}}
                                                </div>
                                            </div>     
                                            
                                            <div class="col-lg-12">
                                                <div class="form-group orderTransactionH4">
                                                    <label>Payment By</label>
                                                    
                                                    <?php 
                                                        if(@$supportPaymentdetail['payment_by']=='card'){
                                                            $payment_by ='Stripe';
                                                        }else{
                                                            $payment_by ='Paypal';
                                                        }
                                                    ?>
                                                    {{ucfirst(@$payment_by)}}
                                                </div>
                                            </div>     

                                            @if($supportPaymentdetail['charge_id']!=null)
                                                @if($supportPaymentdetail['card']!=null)
                                                    <div class="col-lg-12">
                                                        <div class="form-group orderTransactionH4">
                                                                <label>Card Number </label>
                                                             xxxx xxxx xxxx {{@$supportPaymentdetail['card']}}
                                                        </div>
                                                    </div>    
                                                @endif


                                                <div class="col-lg-12">
                                                    <div class="form-group orderTransactionH4">
                                                            <label>Charge Id</label>
                                                        {{@$supportPaymentdetail['charge_id']}}
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group orderTransactionH4">
                                                            <label>Customer Id</label>
                                                        {{@$supportPaymentdetail['customer_id']}}
                                                    </div>
                                                </div>
                                            @endif

                                            @if($supportPaymentdetail['payment_id']!=null)
                                                <div class="col-lg-12">
                                                    <div class="form-group orderTransactionH4">
                                                            <label>Payment Id</label>
                                                        {{@$supportPaymentdetail['payment_id']}}
                                                    </div>
                                                </div>     

                                                <div class="col-lg-12">
                                                    <div class="form-group orderTransactionH4">
                                                            <label>Payer Id</label>
                                                        {{@$supportPaymentdetail['payer_id']}}
                                                    </div>
                                                </div>     

                                                <div class="col-lg-12">
                                                    <div class="form-group orderTransactionH4">
                                                            <label>Payer Email</label>
                                                        {{@$supportPaymentdetail['payer_email']}}
                                                    </div>
                                                </div>     
                                            @endif

                                            <div class="col-lg-12">
                                                <div class="form-group orderTransactionH4">
                                                    <label>Amount</label>
                                                    {{@$supportPaymentdetail['amount']}} {{@$supportPaymentdetail['currency']}}
                                                </div>
                                            </div>        

                                            <div class="col-lg-12">
                                                <div class="form-group orderTransactionH4">
                                                    <label>Transaction Time</label>
                                                    {{date('d F Y,h:i:s A', strtotime($supportPaymentdetail['created_at']))}}

                                                </div>
                                            </div>                                   

                                            <div class="col-sm-12 mb-3">
                                                <div class="orderTransactionH4" >
                                                    <label>Payment status</label>
                                                    <h4>{{@$supportPaymentdetail['status']}}</h4>
                                                 </div>
                                            </div>

                                            
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div> 
                    </div>
               @include('backend.common.footer')
           </div>
       </div>
       <div class="rightbar-overlay"></div>
