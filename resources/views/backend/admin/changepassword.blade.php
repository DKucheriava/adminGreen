@include('backend.common.header_main')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                               
                                    <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashbord</a></li>
                                <li class="breadcrumb-item active">Change password</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Change password</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
					<div class="card-box">
						<h4 class="header-title mb-3">Change password</h4>
                                <form id="admin_change_password" action="{{url('admin/changePassword')}}" method="post"  enctype="multipart/form-data">
                                    @csrf    
                                    <div class="row">
                                            <div class="col-lg-6">
                                            <!--<div class="col-lg-12">-->
                                            <div class="form-group mb-1">
                                                <label>New Password</label>
                                                <!--<div class="form">-->
                                                    <input type="password" name="password" id="password" required="" class="form-control new_password" value="" placeholder="Enter Password">
                                                </div>
                                            <label for="password" class="error"></label>
                                            </div>
                                        <!--</div>-->

                                        <div class="col-lg-6">
                                            <div class="form-group mb-1">
                                                <label>Confirm Password</label>
                                                <div class="input-group">
                                                    <input type="password" name="confirm_password" value="" required="" class="form-control" placeholder="Enter Password">
                                                </div>
                                            </div>
                                            <label for="confirm_password" class="error"></label>
                                        </div>
                                    </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-success waves-effect waves-light btn-primary-theme"><i class="fe-check-circle mr-1"></i> Submit</button>
                                               
                                            </div>
                                        </div>
                                    </form>
                                    
					       
					</div>
                    
                    </div>
                </div>
            </div> 
            @include('backend.common.footer')
        </div>
    </div>
</div>
<div class="rightbar-overlay"></div>
<script type="text/javascript" src="{{url('admin/js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{url('admin/js/jquery.validate.js')}}"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#admin_change_password').validate({
        rules:{
            password:{
                required:true,
                minlength:6,
                maxlength:50
            },
            confirm_password:{
                required:true,
                equalTo:"#password"
            },
        },
        messages:{
            password:{
                required:"Please enter password",
                maxlength:"Maximum 50 characters are allowed",
                minlength:"Password must contain atleast 6 characters",
            },
            confirm_password:{
                required: "Please re-enter password",
                equalTo: "Confirm password did not match with password"
            },
        }
    });
});

</script>