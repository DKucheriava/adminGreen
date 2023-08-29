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
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Profile</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                           <div class="row">
                                <div class="col-md-12">
								     <h4 class="header-title mb-3">Profile Settings</h4>
								</div>   
						   </div>
                           
                                <form id="admin_personal_form" action="{{url('admin/profile')}}" method="post"  enctype="multipart/form-data" >
                                    @csrf
                                     <div class="row">
                                <div class="col-lg-4">
                                    <div class=" profile_user">
										<div class="card">
											<div class="card-body text-center">
												<div class="user-image ">
    												<img class="rounded-circle img-thumbnail old_image" src="{{@$userList['image']?asset('admin/images/profile/'.$userList['image']):asset('admin/images/dummy.png')}}">
    												<label for="user-img">Upload Image</label>
    												<input id="user-img" class="img_upload" name="image" style="display:none" type="file">
												</div>
											</div>
										</div>
									</div>
                                </div>

								<div class="col-lg-8">
									<h4 class="headsub">Basic Info</h4>
                                    <div class="row">    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fullname">Full Name</label>
                                                <input type="text" id="fullname" name="name" value="{{@$userList['name']}}" class="form-control" placeholder="Enter Your Full Name">
                                            </div>
                                            <label for="name" class="error"></label>
                                        </div>

										<div class="col-md-6">
                                            <div class="form-group">
                                                <label for="emailaddress">Email Address</label>
                                                <input type="email" readonly="" id="emailaddress" name="email" value="{{@$userList['email']}}" class="form-control" placeholder="Enter Your Email Address">
                                            </div>
                                            <label for="email" class="error"></label>
                                        </div>
										
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gmail">Address</label>
                                                <input type="text" id="address" class="form-control" placeholder="Enter Your Address" name="address" value="{{@$userList['address']}}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <div class="input-group">
                                                    <select class="form-control custom-select" name="country_id">
                                                       <option value="" selected disabled>Choose Nationality </option>
                                                        <?php foreach ($countries as $key => $country): ?>
                                                            <option value="{{@$country['id']}}" @if(@$userList['country_id']==@$country['id']) selected @endif>
                                                                {{@$country['country_name']}}
                                                            </option>
                                                        <?php endforeach ?>       
                                                    </select>
                                                </div>
                                            </div>
                                            <label for="country_id" class="error"></label>
                                        </div>
                                    </div>
										<div class=" text-right">
										    <button class="btn btn-success btn-primary-theme">Submit</button>
                                            </div>
                                </div>
                                      
                                        </div>
                                    </form>
                                
					        </div>
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
    
    
    $(document).on('submit', 'form', function() {
        $('button').attr('disabled', 'disabled');
    });
    $(document).ready(function(){
        function readURL(input)
        {
            if(input.files && input.files[0])
            {
                var reader = new FileReader();
                reader.onload = function(e)
                {
                    $('.old_image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('.img_upload').change(function(){
            var img_name = $(this).val();
            if(img_name != '' && img_name != null)
            {
                var img_arr = img_name.split('.');
                var ext = img_arr.pop();
                ext = ext.toLowerCase();
                // alert(ext); return false;
                if(ext == 'jpeg' || ext == 'jpg' || ext == 'png')
                {
                    input = document.getElementById('img_upload');
                    readURL(this);
                }
            } else{
                $(this).val('');
                alert('Please select an image of .jpeg, .jpg, .png file format.');
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#admin_personal_form').validate({
            rules: {
                full_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    // remote:'http://127.0.0.1:8000/validate-email'
                },
                address:{
                    required:true
                },
                country_id: {
                    required: true
                },
            },
            messages: {
                full_name: {
                    required: "Please enter your full name"
                },
                email: {
                    required: "Please enter your email",
                    email : "Please enter a valid email",
                    // remote: "Email already registered"
                },
                address:{
                    required:"Please enter address"
                },
                country_id: {
                    required: "Plaese select country"
                },
            },
       
        });
    });

</script>