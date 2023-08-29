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
                                            <li class="breadcrumb-item"><a href="{{url('admin/user/list')}}">User List</a></li>
                                            <li class="breadcrumb-item active">View User</li>
                                        </ol>
                                    </div>

                                    <h4 class="page-title">
                                        View User
                                    </h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                   <div class="row">
                                        <form>
                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="firstname">Full Name</label>
                                                        <input type="text" value="{{$userList['user_name']}}" readonly name="user_name"  required="" class="form-control" placeholder="Enter Your Full Name" >
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" value="{{$userList['uemail']}}" readonly name="uemail"  class="form-control" placeholder="Enter Your Email" >
                                                    </div>
                                                </div>
<!-- 
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                        <input type="password" readonly name="password"  class="form-control" value="{{$userList['password']}}" placeholder="Enter Your password">
                                                    </div>
                                                </div> -->

                                              <!--   <div class="col-lg-6">
                                                    <div class="form-group">
                                                      <label for="text">Address</label>
                                                        <input type="text" readonly name="address"   class="form-control" value="{{$userList['address']}}" required="" placeholder="Enter Your Address">
                                                    </div>
                                                    <label for="address" class="error"></label>
                                                </div> -->

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                       <label>Country</label>
                                                        <select class="form-control custom-select" name="ucountry_id" disabled required="">
                                                           <option value="" selected disabled>Choose Nationality </option>
                                                            @foreach($countries as $country)
                                                                <option  disabled 
                                                                  value="{{@$country['id']}}" {{$userList['ucountry_id'] == $country['id']  ? 'selected' : ''}}>{{@$country['country_name']}}</option>
                                                            @endforeach              
                                                        </select>
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
       
    <div class="rightbar-overlay"></div>
