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
                                            <li class="breadcrumb-item active">Edit FAQ</li>
                                        </ol>
                                    </div>

                                    <h4 class="page-title">
                                        Edit FAQ
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
										   <h4 class="header-title mb-3">Edit FAQ</h4>
										 </div>
                                            <div class="col-lg-6 text-right">
                                                <a href="{{url('admin/faqs')}}" class="btn btn-primary  mb-3">Back To FAQ's</a>
                                            </div>
                                        </div>
                                       
                                        <form action="{{url('admin/edit-faq/'.@$id)}}" id="add_faq" method="post" enctype="multipart/form-data" >
                                       
                                        
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Title</label>
                                                    <input type="text" name="title"  class="form-control" placeholder="Enter title " value="{{$faq['title']}}" >
                                                </div>
                                            </div>
                                        
                                         <input type="hidden" class="countryIdClass" value="{{$id}}" id="coty_id" name="id">
                                         
                                            
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="">Description</label>
                                                    <textarea class="form-control "  id="description_id" name="description">{{@$faq->description}}</textarea>
                                                    <!--<input class="form-control" id="description_hidden_id" name="description" type="hidden" value="{{@$faq->description}}">-->
                                                    <!--<label class="error" for="description_hidden_id"></label>-->
                                                </div>
                                            </div>
											 <div class="col-12 mt-2">
                                                    <button type="submit" class="btn btn-success waves-effect waves-light btn-primary-theme"><i class="fe-check-circle mr-1"></i> Submit</button>
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

<!--    <script type="text/javascript">-->
<!--        tinymce.init({-->
<!--            selector: '.textar,.textar1',-->
<!--            height: 300,-->
<!--            menubar: true,-->
            forced_root_block : "", /*to remove auto p tag */
<!--            plugins: [-->
<!--                'advlist autolink link image charmap print preview anchor',-->
<!--                'searchreplace visualblocks code fullscreen',-->
<!--                'insertdatetime media contextmenu paste code'-->
<!--            ],-->
<!--             toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',-->
<!--            image_advtab: true,-->
            /*to take automatic urls starts*/
<!--            relative_urls: false,-->
<!--            remove_script_host: false,-->
            /*to take automatic urls ends*/
<!--            file_browser_callback_types: 'file image media',-->
           
<!--            image_title: true, -->
            // enable automatic uploads of images represented by blob or data URIs
<!--            automatic_uploads: true,-->
<!--            images_upload_url: "{{url('admin/contentManagement/termAndCondtion')}}",-->

<!--            file_picker_types: 'image media file', -->
<!--            setup: function (editor) {-->
<!--                editor.on('change', function (e) {-->
                    // alert(editor.getContent());
<!--                    $('textarea[name="'+editor.targetElm.name+'"]').next('input').val($.trim(editor.getContent()));-->
<!--                });-->
<!--            },     -->
<!--            file_picker_callback: function(cb, value, meta) {-->
<!--                var input = document.createElement('input');-->
<!--                input.setAttribute('type', 'file');-->
<!--                input.setAttribute('accept', 'image/*');-->
<!--                input.onchange = function() {-->
<!--                    var file = this.files[0];-->

<!--                    var id = 'blobid' + (new Date()).getTime();-->
<!--                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;-->
<!--                    var blobInfo = blobCache.create(id, file);-->
<!--                    blobCache.add(blobInfo);-->
                    // alert(blobInfo.blobUri());
<!--                    cb(blobInfo.blobUri(), { title: file.name });-->
<!--                };-->

<!--               input.click();-->
<!--            }-->
<!--        });-->
<!--</script>-->



<script type="text/javascript">
    $('#add_faq').validate({
        ignore:[],
        rules:{
            "title":{
                required:true,
                minlength:5,
                remote:{
                    url:"{{ url('admin/edit-check-faq-title')  }}",
                    data:{
                        id:function(){
                            return $('.countryIdClass').val();
                        }
                    }
                } 
            },
            "description":{
                required:true,
                minlength:20,
            },
        },
        messages:{
            "title":{
                required:"Please enter title",
                minlength:"Title must contain 5 characters",
                remote:"*Title already registered",
            },
            "description":{
                required:"Please enter description",
                minlength:"Description must contain 20 characters",
            },
        },
    });
</script>
