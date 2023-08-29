<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title>Forgot Password </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" src="{{asset('admin/images/logo-sm.png')}}">
        
        <link href="{{ url('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
        <link href="{{ url('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
        <link href="{{ url('admin/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
        <link href="{{ url('admin/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />
        <link href="{{ url('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

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
                                        <a href="{{url('/admin/login')}}" class="logo logo-dark text-center">
                                            <span class="logo-lg">
                                                <img src="{{asset('admin/images/logo.png')}}" alt="" height="45">
                                            </span>
                                        </a>
                    
                                        <a href="{{url('/admin/login')}}" class="logo logo-light text-center">
                                            <span class="logo-lg">
                                                <img src="{{asset('admin/images/logo-light.png')}}" alt="" height="22">
                                            </span>
                                        </a>
                                    </div>
                                    <h3 class="text-muted mb-4 mt-3">Forgot Password</h3>
                                </div>

                                <form action="{{url('/forgot-password')}}" id="admin-forget-password" method="post">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="emailaddress">Email address</label>
                                        <input class="form-control" type="email" name="email" value="" id="emailaddress"  placeholder="Enter your email">
                                    </div>
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary btn-block btn-primary-theme" type="submit" >Reset Password</button>
                                    </div>
                                </form>
                                <br>
                                <div class="custom-control text-center">
                                   Back to <a href ="{{url('/login')}}" class="ml-auto forgt-pswd">Login</a>
                                </div>
                            </div> <!-- end card-body -->
                        </div>
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
                               &copy; Copyright 2022 Green Pheasants Team. All Rights Reserved
                            </div>
                          
                        </div>
                    </div>
                </footer>

        <script type="text/javascript" src="{{url('admin/js/vendor.min.js')}}"></script>
        <script type="text/javascript" src="{{url('admin/js/app.min.js')}}"></script>
        <script type="text/javascript" src="{{url('admin/js/jquery-3.2.1.min.js')}}"></script>
        <script type="text/javascript" src="{{url('admin/js/jquery.validate.js')}}"></script>

    </body>

    <div class="rightbar-overlay"></div>


<script type="text/javascript">
$(document).ready(function(){
   $('#admin-forget-password').validate({
        ignore:[],
        rules:{
            "email":{
                required:true,
                // remote:'http://127.0.0.1:8000/validate-email',
            },
        },
        messages:{
            "email":{
                required:"Please enter email",
                // remote: "Email already registered"
            },
        },
    });
});
</script>