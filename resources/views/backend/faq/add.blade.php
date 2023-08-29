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
                                            <li class="breadcrumb-item"><a href="{{url('admin/faqs')}}">FAQ's</a></li>
                                            <li class="breadcrumb-item active">"Add FAQ"</li>
                                        </ol>
                                    </div>

                                    <h4 class="page-title">
                                        Add FAQ
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
										   <h4 class="header-title mb-3">Add FAQ</h4>
										 </div>
                                            <div class="col-lg-6 text-right">
                                                <a href="{{url('admin/faqs')}}" class="btn btn-primary  mb-3">Back To FAQ's</a>
                                            </div>
                                        </div>
                                        <form action="{{url('admin/add-faq')}}" id="add_faq" method="post" enctype="multipart/form-data" >
                                            @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Title</label>
                                                    <input type="text" name="title" class="form-control" placeholder="Enter title" value="" >
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="">Description</label>
                                                    <textarea class="form-control" placeholder="Enter description"  id="description_id" name="description"></textarea>
                                                    <!--<input class="form-control" id="description_hidden_id" name="description" type="hidden" value="">-->
                                                    <!--<label class="error" for="description_hidden_id"></label>-->
                                                </div>
                                            </div>
                                            <div class="col-12 mt-2">
                                                    <button type="submit" id="btn-submit" class="btn btn-success waves-effect waves-light btn-primary-theme"><i class="fe-check-circle mr-1"></i> Submit</button>
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

    <script type="text/javascript" src="{{url('admin/js/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{url('admin/js/jquery.validate.js')}}"></script>
    <script type="text/javascript" src="{{url('admin/js/tinymce/tinymce.min.js')}}"></script>

    <script type="text/javascript">

         $(document).ready(function() {
  
        CKEDITOR.replace( 'description' );
            
         });
         
         
        $(document).ready(function () {
            // $("#add_faq").submit(function (e) {
            //     $("#btn-submit").attr("disabled", true);
            //     return true;
            // });
        });

        $('#add_faq').validate({
            ignore:[],
            rules:{
                "title":{
                    required:true,
                    minlength:5,
                    remote:"{{ url('admin/check-faq-title')}}",
                },
                // "description":{
                //     required:true,
                //     minlength:20,
                // },
            },
            messages:{
                "title":{
                    required:"Please enter title",
                    minlength:"Title must contain 5 characters",
                    remote:"*Title already registered",
                },
                // "description":{
                //     required:"Please enter description",
                //     minlength:"Description must contain 20 characters",
                // },
            },
            submitHandler: function (form) {
                console.log("Submitted!");
                $("#btn-submit").attr("disabled", true);
                form.submit();
            }
        });
    </script>

