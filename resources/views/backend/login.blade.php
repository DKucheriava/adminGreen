<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title>Log In </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" src="{{asset('admin/images/logo.png')}}">
        
        <link href="{{ url('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
        <link href="{{ url('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
        <link href="{{ url('admin/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
        <link href="{{ url('admin/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />
        <link href="{{ url('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        
        <link href="{{ url('admin/css/toastr.css') }}" rel="stylesheet" type="text/css" />


    <style type="text/css">
		  body.authentication-bg{
			background: url(https://dev.indiit.solutions/greenPheasantBackend/admin/images/theme-bg.jpg);
			background-size: cover;
			background-position: 100% 50%;
		}
        .error{
            color: red;
        }
    </style>

</head>

    <body class="loading authentication-bg authentication-bg-pattern">
           <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <div class="auth-logo">
                                        <a href="{{url('/login')}}" class="logo logo-dark text-center">
                                            <span class="logo-lg">
                                                <img src="{{asset('admin/images/logo.png')}}" alt="" height="45">
                                            </span>
                                        </a>
                                        <a href="{{url('/login')}}" class="logo logo-light text-center">
                                            <span class="logo-lg">
                                                <img src="{{asset('admin/images/logo-light.png')}}" alt="" height="22">
                                            </span>
                                        </a>
                                    </div>
                                    <p class="text-muted mb-4 mt-3">Enter your email address and password to access admin panel.</p>
                                </div>

                                <form action="{{url('/login')}}" method="post" id="admin-login">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="emailaddress">Email address</label>
                                        <input class="form-control" name="email" required="" type="email" value="{{Session::get('greenadmin_email') ? Session::get('greenadmin_email') :''}}" id="emailaddress"  placeholder="Enter your email">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" name="password" value="{{Session::get('greenadmin_password') ? Session::get('greenadmin_password') :''}}" required="" id="password" class="form-control" placeholder="Enter your password">
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="password" class="error"></label>
                                    </div>

                                    <div class="form-group mb-3 d-flex">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="remember" id="checkbox-signin" value="{{Session::get('greenadmin_email') ? '1' :'0'}}" {{Session::get('greenadmin_email') ? 'checked' : ''}} >
                                            <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                        </div>
										<a href ="{{url('/forgot-password')}}" class="ml-auto forgt-pswd">Forgot password?</a>
                                    </div>

                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary btn-block btn-primary-theme" type="submit"> Log In </button>
                                    </div>
                                </form>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->
                        <!---div class="row mt-3">
                            <div class="col-12 text-center">
                                <a href ="{{url('/forgot-password')}}" class="text-white-50 ml-1" style="color:white !important;">Forgot your password?</a>
                                
                            </div> 
                        </div---->
                        <!-- end row -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->
            <footer class="footer footer-alt">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12  text-center" style="color: white !important">
                               &copy; Copyright {{ date('Y')}} Green Pheasants Team. All Rights Reserved
                            </div>
                          
                        </div>
                    </div>
                </footer>

        <script type="text/javascript" src="{{url('admin/js/vendor.min.js')}}"></script>
        <script type="text/javascript" src="{{url('admin/js/app.min.js')}}"></script>
        <script type="text/javascript" src="{{url('admin/js/jquery-3.2.1.min.js')}}"></script>
        <script type="text/javascript" src="{{url('admin/js/jquery.validate.js')}}"></script>
        <script type="text/javascript" src="{{ url('admin/js/toastr.min.js')}}"></script>
        <script>
            @if(Session::has('success'))
                $(function () {
                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "10000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };
                    toastr.success("{{ Session::get('success') }}");
                });
            @endif    
            @if(Session::has('error'))
                $(function () {
                    toastr.options = {
                      "closeButton": true,
                      "debug": false,
                      "newestOnTop": false,
                      "progressBar": true,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "300",
                      "hideDuration": "1000",
                      "timeOut": "10000",
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut"
                    };
                    toastr.error("{{ Session::get('error') }}");
                });
            @endif  
        </script>
    </body>

    <div class="rightbar-overlay"></div>


<script type="text/javascript">
$(document).ready(function(){

   // alert($("input[type='checkbox']").val()); 
    
    $('#checkbox-signin').change(function() {
        if($(this).is(':checked')){
           $("input[type='checkbox']").val('1')
        }
        else{
          $("input[type='checkbox']").val('0')  
        }
        // alert($("input[type='checkbox']").val()); 
    });


    // $("#checkbox-signin").on("change", function() {
    //   alert($(this).is(':checked')); 
    //   });


    $('#admin-login').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password:{
                required:true,
                maxlength:50,
                minlength:6
            },
        },
        messages: {
            email: {
                required: "Please enter your email",
                email : "Please enter a valid email"
            },
            password:{
                required:"Please enter password",
            },
        },
   
    });
});

</script>