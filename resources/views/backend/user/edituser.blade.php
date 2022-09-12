@include('backend.common.header_main')
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
                                            <li class="breadcrumb-item"><a href="{{url('admin/user/list')}}">User</a></li>
                                            <li class="breadcrumb-item active">Edit User</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Edit User</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <a href="{{url('admin/user/list')}}" class="btn btn-primary  mb-3">Back To User</a>
                                            </div>
                                        </div>
                                    <form id="personal_form" action="{{url('admin/user/edit/'.$id)}}" method="post"  enctype="multipart/form-data" >
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="firstname">Full Name</label>
                                                    <input type="text" name="full_name" value="{{@$userList['full_name']}}" required="" class="form-control" placeholder="Enter Your Full Name" >
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="email" value="{{@$userList['email']}}" readonly="" class="form-control" placeholder="Enter Your Email" >
                                                </div>
                                            </div>
<!--                                                 <div class="col-lg-6">
                                                <div class="form-group">
                                                   <label >Mobile</label>
                                                    <input type="text" name="" class="form-control" placeholder="Enter Your Mobile No.">
                                                </div>
                                                <label for="address" class="error"></label>
                                            </div> -->
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                  <label for="text">Address</label>
                                                    <input type="text" name="address" value="{{@$userList['address']}}"  class="form-control" required="" placeholder="Enter Your Address">
                                                </div>
                                                <label for="address" class="error"></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                   <label>Country</label>
                                                    <select class="form-control custom-select" name="country_id" required="">
                                                       <option value="" selected disabled>Choose Nationality </option>
                                                        @foreach($countries as $country)
                                                            <option value="{{@$country['id']}}" @if($userList['country_id']==$country['id']) selected @endif>{{@$country['full_name']}}</option>
                                                        @endforeach              
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                  <label>City</label>
                                                    <input type="text" class="form-control" name="city_name" required="" value="{{@$userList['city_name']}}" placeholder="City">
                                                </div>
                                                <label for="city_name" class="error"></label>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Hourly Price</label>
                                                    <input type="text" class="form-control" name="hourly_price" required="" value="{{@$userList['hourly_price']}}" placeholder="Hourly price">
                                                </div>
                                                <label for="hourly_price" class="error"></label>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-success waves-effect waves-light m-1"><i class="fe-check-circle mr-1"></i> Submit</button>
                                                <button type="submit" class="btn btn-light waves-effect waves-light m-1"><i class="fe-x mr-1"></i> Cancel</button>
                                            </div>
                                        </div>
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                        </div>
                        <!-- end row-->
                    </div> <!-- container -->
                </div> <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12  text-center">
                               &copy; Copyright 2021 Hagel Team. All Rights Reserved
                            </div>
                          
                        </div>
                    </div>
                </footer>
               @include('backend.common.footer')
           </div>
       </div>
       <div class="rightbar-overlay"></div>


<script type="text/javascript" src="{{url('admin/js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{url('admin/js/jquery.validate.js')}}"></script>
<script type="text/javascript">
    
    
$(document).ready(function(){
    $('#personal_form').validate({
        rules: {
            full_name: {
                required: true
            },
            address:{
                required:true
            },
            continent_id: {
                required: true
            },
            country_id: {
                required: true
            },
            city_name: {
                required: true
            },
            hourly_price:{
                required: true,
                min:1,
                number:true
            },
        },
        messages: {
            full_name: {
                required: "Please enter your full name"
            },
            address:{
                required:"Please enter address"
            },
            continent_id: {
                required: "Plaese select continent name"
            },
            country_id: {
                required: "Plaese select country"
            },
            city_name: {
                required: "Plaese enter city name"
            },
            hourly_price:{
                required: "Plaese enter hourly price"
            },
        },
   
    });
});
</script>