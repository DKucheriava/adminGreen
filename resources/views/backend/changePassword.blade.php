<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title>Log In </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link href="{{ url('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
        <link href="{{ url('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
        <link href="{{ url('admin/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
        <link href="{{ url('admin/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />
        <link href="{{ url('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <style type="text/css">
        body{
            background-color: rgb(71,161,69) !important;
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
                                                <img src="{{asset('admin/images/logo.png')}}" alt="" height="22">
                                            </span>
                                        </a>
                                    </div>
                                    <p class="text-muted mb-4 mt-3">Enter your email address and password to access admin panel.</p>
                                </div>
                                
                                <form action="{{url('change-password')}}" id="admin-forget-password" method="post">
                                    @csrf
                                        <div class="form-group floating-label col-md-12">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ $email }}" placeholder="Email" disabled>
                                        </div>
                                        <div class="form-group floating-label col-md-12">
                                            <label>Password</label>
                                            <input type="password" name="new_pw" id="new_pw" class="form-control" value="" placeholder="Password">
                                        </div>
                                        <div class="form-group floating-label col-md-12">
                                            <label>Confirm Password</label>
                                            <input type="password" name="confirm_pw" class="form-control" value="" placeholder="Confirm Password">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block btn-lg btn-primary-theme">submit</button>
                                    </div>

                                </form>
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
    console.log('htrtrtr');
    $("#admin-forget-password").validate({
        rules:{
            new_pw:{
                required: true,
                minlength:8,
            },
            confirm_pw:{
                required  : true,
                minlength :8,
                equalTo   : "#new_pw",
            },
        },
        messages:{
            confirm_pw:{
                equalTo:"Confirm password is not same. Please enter again"
            }
        },
    });
});


</script>