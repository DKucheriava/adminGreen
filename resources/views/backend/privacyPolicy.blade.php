@include('backend.common.header_main')
@section('title', 'Manage'.' '.$label.'s')
@section('content')
          
        <div class="content-page">
            <div class="content">
                <!-- Start Content-->
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item">
                                           <a href="{{url('admin/dashboard')}}">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item active">Edit Privacy Policy Page</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Edit Privacy Policy Page</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class = "row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">                        
                                   <form method="post" id="termForm" action="{{url('admin/edit-privacy-policy')}}">
                                    <div class="row">
                                        @csrf                                        
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Title(English)</label>
                                                <input class="form-control" id="title" name="title" type="text"  value="{{@$privacyPolicy->title}}">    
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                               <label>Extra Description (English)</label>
                                                 <div class="form-group">
                                <label><strong>Description :</strong></label>
                                <textarea required id="showExtraPrivacyData"  class="ckeditor form-control" name="showExtraPrivacyData">{{@$privacyPolicy->showExtraPrivacyData}}</textarea>
                                                </div>
                                            </div>
                                        </div> 
                                        <label for="showExtraPrivacyData" generated="true" class="error"></label>
                                        
                                         <div class="col-lg-12">
                                            <div class="form-group">
                                               <label class="">Description (English)</label>
                                                 <div class="form-group">
                                <label><strong>Description :</strong></label>
                                <textarea required id="description"  class="ckeditor form-control" name="description">{{@$privacyPolicy->description}}</textarea>
                                                </div>
                                            </div>
                                            
                                        </div>
                                         <label for="description" generated="true" class="error"></label>
                                    </div>

                                    <!-- end col-->
                                    <div class="row mt-3 mb-3">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-success waves-effect waves-light submitPrivacyPolicyPage btn-primary-theme"><i class="fe-check-circle mr-1"></i> Submit</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                                <!-- end card-body -->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->
                    </div>
                    <!-- end row-->
                </div>
                <!-- container -->
            </div>
            @include('backend.common.footer')
          
      </div>
  </div>
  <div class="rightbar-overlay"></div>
    
    <script type="text/javascript">
        
        $(document).ready(function(){
            $('.ckeditor').ckeditor();
        });
        
        $("#termForm").validate({
              ignore: [],
          debug: false,
            rules: { 
                 title:{
                    required:true,
                    maxlength:200,
                    minlength:5,
                },
                showExtraPrivacyData:{
                     required: function() 
                    {
                     CKEDITOR.instances.showExtraPrivacyData.updateElement();
                    },

                     minlength:10
                },
                description:{
                     required: function() 
                    {
                     CKEDITOR.instances.description.updateElement();
                    },

                     minlength:10
                }
            },
            messages:
                {
                 title:{
                    required:"Please enter title",
                    maxlength:"Maximum 200 characters are allowed",
                    minlength:"Title must contain 5 characters",
                },
                showExtraPrivacyData:{
                    required:"Please enter extra description",
                    minlength:"Please enter 10 characters"
                },
                description:{
                    required:"Please enter  description",
                    minlength:"Please enter 10 characters"
                }
            }
        });
            
    </script>


