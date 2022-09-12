@include('backend.common.header_main')
@section('title', 'Manage'.' '.$label.'s')
@section('content')

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                          <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                       
                                         <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashbord</a></li>
                                        <li class="breadcrumb-item active">Users</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Users</h4>
                            </div>
                        </div>
                    </div> 
               
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-box">
                               <div class="row">
                                    <form id="personal_form" action="{{url('admin/user/add')}}" method="post"  enctype="multipart/form-data" >
                                        @csrf
                                        <div class="row">

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="firstname">Full Name</label>
                                                    <input type="text" name="user_name"  required="" class="form-control" placeholder="Enter Your Full Name" >
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="uemail"  class="form-control" placeholder="Enter Your Email" >
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Password</label>
                                                    <input type="password" name="password"  class="form-control" placeholder="Enter Your password">
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                  <label for="text">Address</label>
                                                    <input type="text" name="address"   class="form-control" required="" placeholder="Enter Your Address">
                                                </div>
                                                <label for="address" class="error"></label>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                   <label>Country</label>
                                                    <select class="form-control custom-select" name="ucountry_id" required="">
                                                       <option value="" selected disabled>Choose Nationality </option>
                                                        @foreach($countries as $country)
                                                            <option value="{{@$country['id']}}">{{@$country['country_name']}}</option>
                                                        @endforeach              
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-success waves-effect waves-light m-1"><i class="fe-check-circle mr-1"></i> Submit</button>
                                                <button type="submit" class="btn btn-light waves-effect waves-light m-1"><i class="fe-x mr-1"></i> Cancel</button>
                                            </div>
                                        </div>
                                    </form>
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


<script type="text/javascript">
$(document).ready(function(){
    $('#personal_form').validate({
        rules: {
            user_name: {
                required: true
            },
            uemail: {
                required: true
            },
            address:{
                required:true
            },
            ucountry_id: {
                required: true
            },
            password: {
                required: true
            },
        },
        messages: {
            user_name: {
                required: "Please enter your full name"
            },
            uemail: {
                required: "Plaese enter email"
            },
            address:{
                required:"Please enter address"
            },
            ucountry_id: {
                required: "Plaese select country"
            },
            password: {
                required: "Plaese enter password"
            },
           
        },
   
    });
});
</script>