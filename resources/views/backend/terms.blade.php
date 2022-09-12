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
                                        <li class="breadcrumb-item active">Edit terms & condtion</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Edit terms & condtion</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" id="termForm" action="{{url('admin/edit-terms-condtion')}}">
                                    <div class="row">
                                            @csrf                                        
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Title(English)</label>
                                                    <input class="form-control" id="title" name="title" type="text"  value="{{@$terms_condition->title}}">    
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="">Description(English)</label>
                                                    <textarea class="form-control textar" id="description_id" name="description_id">{{@$terms_condition->description}}</textarea>
                                                    <input class="form-control" id="description_hidden_id" name="description" type="hidden" value="{{@$terms_condition->description}}">
                                                    <label class="error" for="description_hidden_id"></label>
                                                </div>
                                            </div>                                
                                    </div>

                                    <!-- end col-->
                                    <div class="row mt-2 mb-3">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-success waves-effect waves-light btn-primary-theme"><i class="fe-check-circle mr-1"></i> Submit</button>
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
    tinymce.init({
        selector: '.textar,.textar1',
        height: 300,
        menubar: true,
        forced_root_block : "", /*to remove auto p tag */
        plugins: [
            'advlist autolink link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media contextmenu paste code'
        ],
         toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
        image_advtab: true,
        /*to take automatic urls starts*/
        relative_urls: false,
        remove_script_host: false,
        /*to take automatic urls ends*/
        file_browser_callback_types: 'file image media',
       
        image_title: true, 
        // enable automatic uploads of images represented by blob or data URIs
        automatic_uploads: true,
        images_upload_url: "{{url('admin/contentManagement/termAndCondtion')}}",

        file_picker_types: 'image media file', 
        setup: function (editor) {
            editor.on('change', function (e) {
                // alert(editor.getContent());
                $('textarea[name="'+editor.targetElm.name+'"]').next('input').val($.trim(editor.getContent()));
            });
        },     
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function() {
                var file = this.files[0];

                var id = 'blobid' + (new Date()).getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var blobInfo = blobCache.create(id, file);
                blobCache.add(blobInfo);
                // alert(blobInfo.blobUri());
                cb(blobInfo.blobUri(), { title: file.name });
            };

           input.click();
        }
    });
</script>


    <script type="text/javascript">
    $('#termForm').validate({
        ignore:[],
        rules:{
            "title":{
                required:true,
                maxlength:200,
                minlength:5,
            },
            "description":{
                required:true,
                minlength:20,
            },
        },
        messages:{
            "title":{
                required:"Please enter title",
                maxlength:"Maximum 200 characters are allowed",
                minlength:"Title must contain 5 characters",
            },
            "description":{
                required:"Please enter description",
                minlength:"Description must contain 20 characters",
            },
        },
    });
</script>

