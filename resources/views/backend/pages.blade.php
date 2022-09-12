@include('backend.common.header_main')

  <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                      <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">            
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                    <li class="breadcrumb-item active">Pages</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Pages</h4>
                        </div>
                    </div>
                </div>            

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                           <div class="row">
                             <div class="col-md-6">
                               <h4 class="header-title mb-3">All Pages </h4>
                             </div>
                            </div>
                            <div class="table-responsive">
                                <table  id="basic-datatable5" class="table  table-hover table-nowrap table-centered m-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th colspan="2">Page Name</th>    
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <h5 class="m-0 font-weight-normal">Privacy and Cookies
                                                </h5>
                                            </td>
                                            <td></td>
                                            <td>
                                                <a href="{{url('admin/edit-privacy')}}" class="btn btn-xs btn-success"><i class="mdi mdi-pencil"></i></a>  
                                            </td>
                                        </tr>
                                         <tr>
                                            <td>
                                                <h5 class="m-0 font-weight-normal"> Terms & Conditions</h5>
                                            </td>
                                            <td></td>
                                             <td>
                                                <a href="{{url('admin/edit-terms')}}" class="btn btn-xs btn-success"><i class="mdi mdi-pencil"></i></a>
                                             </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h5 class="m-0 font-weight-normal"> Home Page</h5>
                                            </td>
                                            <td></td>
                                            <td>
                                                <a href="{{url('admin/edit-home-information')}}" class="btn btn-xs btn-success"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h5 class="m-0 font-weight-normal"> Service Page</h5>
                                            </td>
                                            <td></td>
                                            <td>
                                                <a href="{{url('admin/edit-service-page')}}" class="btn btn-xs btn-success"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h5 class="m-0 font-weight-normal">Contact us</h5>
                                            </td>
                                            <td></td>
                                            <td>
                                                <a href="{{url('admin/edit-contact-page')}}" class="btn btn-xs btn-success"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h5 class="m-0 font-weight-normal">About us</h5>
                                            </td>
                                            <td></td>
                                            <td>
                                                <a href="{{url('admin/edit-about-us-page')}}" class="btn btn-xs btn-success"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h5 class="m-0 font-weight-normal">Footer Information</h5>
                                            </td>
                                            <td></td>
                                            <td>
                                                <a href="{{url('admin/edit-footer-information')}}" class="btn btn-xs btn-success"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h5 class="m-0 font-weight-normal">Social Setting</h5>
                                            </td>
                                            <td></td>
                                            <td>
                                                <a href="{{url('admin/edit-social-setting-page')}}" class="btn btn-xs btn-success"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
               
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12  text-center">
                       &copy; Copyright 2022 Green Pheasants Team. All Rights Reserved
                    </div>
                  
                </div>
            </div>
        </footer>
    @include('backend.common.footer')
    </div>
</div>
<div class="rightbar-overlay"></div>