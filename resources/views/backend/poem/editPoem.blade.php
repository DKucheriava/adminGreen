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
                                            <li class="breadcrumb-item"><a href="{{url('admin/poem/list')}}">Item</a></li>
                                            <li class="breadcrumb-item active">Edit Item</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">
                                        Edit Item
                                    </h4>
                                </div>
                            </div>
                        </div>     
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <a href="{{url('admin/poem/list')}}" class="btn btn-primary  mb-3">Back To Item</a>
                                            </div>
                                        </div>
                                        <form action="{{url('admin/poem/edit')}}/{{$id}}" id="add_faq" method="post" enctype="multipart/form-data" >
                                        
                                        @csrf

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Title</label>
                                                    <input type="text" name="title" class="form-control" placeholder="Enter title" value="{{ @$itemData['ititle']}}" >
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="build_label">Select Poet</label>
                                                    <select name="creatorid" class="form-control selectPoet">
                                                        <option value="" disabled selected>Select Poet
                                                        </option>
                                                      @foreach($creators as $creator)
                                                          <option value="{{$creator->creatorid}}"  @if($creator['creatorid']==$itemData['creatorid']) selected="" @endif >{{@$creator->cname}}</option>
                                                      @endforeach
                                                      <option value="other">Other</option>
                                                    </select>

                                                    <label id="category_id-error" class="error" for="poet_id"></label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 newCreatorAdded">
                                                <div class="form-group">
                                                    <label>New poet</label>
                                                    <input type="text" name="cname" class="form-control" placeholder="Enter poet name" value="" >
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Year</label>
                                                    <input type="text" maxlength="4" name="iyear" class="form-control" placeholder="Enter Year" value="{{$itemData['iyear']}}" >
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="">The Item Description</label>
                                                    <textarea class="form-control" id="description_id" name="description">{{ @$itemData['itemDetail']['itext']}}</textarea>
                                                 <!--    <input class="form-control" id="description_hidden_id" name="description" type="hidden" value="{{$itemData['ititle']}}"> -->
                                                    <label class="error description_hidden_clss" for="description_hidden_id" style="display: none;">Please enter description</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Source</label>
                                                    <input type="text" name="source" class="form-control" placeholder="Enter Source Text" required="" value="{{$itemData['ctext']}}" >
                                                </div>
                                            </div>

                                             <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Source Link</label>
                                                    <input type="text" name="source_link" class="form-control" placeholder="Enter Source Link" required="" value="{{$itemData['curl']}}" >
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="col-lg-6">
                                                <div class="form-group mul-cat-main">
                                                    <label class="build_label">Select theme</label>
                                                    <select class="form-control category_id_class mul_category" required="" name="poem_theme_selected[]" multiple="multiple">

                                                        @foreach($themeList as $theme)
                                                            <option value="{{$theme['item_text']}}" 
                            @if (in_array($theme['item_text'], $selected_array)) selected @endif
                                                            >{{@$theme['item_text']}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label id="poem_theme_selected[]-error" class="error" for="poem_theme_selected[]"></label>
                                                </div>
                                            </div>
                                                
                                            <div class="col-lg-6">
                                                <div class="form-group mul-cat-main">
                                                    <label class="build_label">Select mood</label>
                                                    <select required="" class="form-control category_id_class mul_mood" name="category_id[]" multiple="multiple">
                                                        @foreach($moodList as $mood)
                                                            <option value="{{$mood['item_text']}}"   @if (in_array($mood['item_text'], $selected_mood_array)) selected @endif>{{@$mood['item_text']}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label id="category_id[]-error" class="error" for="category_id[]"></label>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="row itemBox">
                                                    <div class="col-md-12">
                                                        <!-- ////////Size/////// -->
                                                        <div class="items_size items_prices mb-3">
                                                            <div class="itm_heading mb-3 d-flex align-items-center justify-content-between">
                                                               <h4 class="build_label">Additional URL</h4>
                                                               <a href="javascript:;" class="add_more">
                                                                    <i class="fa fa-plus"></i> Add Additional URL
                                                                </a>
                                                            </div>
                                                           
                                                            <div class="size_chart">
                                                                <div class="apnnd_div">
                                                                    @if($itemData['item_text1'] != "")
                                                                    <div class="price_wrap main_div mb-2"> 
                                                                        <div class="row" part="1"> 
                                                                            <div class="col-lg-3"> 
                                                                                <label class="chart_head mb-2">Content</label> 
                                                                                <div class="form-group"> 
                                                                                    <input type="text" class="form-control valid" id="size_0" required="" name="price_firsrt_append_div[0][itext]" value="{{$itemData['item_text1']}}" placeholder="Content" aria-required="true" aria-invalid="false">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3"> 
                                                                                <label class="chart_head mb-2">URL</label> 
                                                                                <div class="form-group"> 
                                                                                    <input type="text" class="form-control valid" id="url_0" required="" name="price_firsrt_append_div[0][url]" value="{{$itemData['iadd_url_1']}}" placeholder="url" aria-required="true" aria-invalid="false">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <p class="text-right mb-0"> <a href="javascript:;" class="remove_apnd"> <i class="fa fa-times"></i> Remove</a></p>
                                                                    </div>
                                                                    @endif
                                                                    @if($itemData['item_text2'] != "")
                                                                    <div class="price_wrap main_div mb-2"> 
                                                                        <div class="row" part="2"> 
                                                                            <div class="col-lg-3"> 
                                                                                <label class="chart_head mb-2">Content</label> 
                                                                                <div class="form-group"> 
                                                                                    <input type="text" class="form-control valid" id="size_1" required="" name="price_firsrt_append_div[1][itext]" value="{{$itemData['item_text2']}}" placeholder="Content" aria-required="true" aria-invalid="false">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3"> 
                                                                                <label class="chart_head mb-2">URL</label> 
                                                                                <div class="form-group"> 
                                                                                    <input type="text" class="form-control valid" id="url_1" required="" name="price_firsrt_append_div[1][url]" value="{{$itemData['iadd_url_2']}}" placeholder="url" aria-required="true" aria-invalid="false">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <p class="text-right mb-0"> <a href="javascript:;" class="remove_apnd"> <i class="fa fa-times"></i> Remove</a></p>
                                                                    </div>
                                                                    @endif
                                                                    @if($itemData['item_text3'] != "")
                                                                    <div class="price_wrap main_div mb-2"> 
                                                                        <div class="row" part="3"> 
                                                                            <div class="col-lg-3"> 
                                                                                <label class="chart_head mb-2">Item Text</label> 
                                                                                <div class="form-group"> 
                                                                                    <input type="text" class="form-control valid" id="size_2" name="price_firsrt_append_div[2][itext]" required="" value="{{$itemData['item_text3']}}" placeholder="Poem Text" aria-required="true" aria-invalid="false">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3"> 
                                                                                <label class="chart_head mb-2">URL</label> 
                                                                                <div class="form-group"> 
                                                                                    <input type="text" class="form-control valid" id="url_2" name="price_firsrt_append_div[2][url]" required="" value="{{$itemData['iadd_url_2']}}" placeholder="url" aria-required="true" aria-invalid="false">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <p class="text-right mb-0"> <a href="javascript:;" class="remove_apnd"> <i class="fa fa-times"></i> Remove</a></p>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <br>    

                                            <div class="col-md-12">
                                                <div class="row itemBox">
                                                    <div class="col-md-6">
                                                        <fieldset>
                                                            <h4 class="build_label">Notify me when 10 users add this poem to their collection</h4>
                                                              <div>
                                                                <input type="radio" id="coding" name="notify_via" value="1" {{($itemData['notify_by'] == 1) ? 'checked' : ''}}>
                                                                <label for="coding">By email</label>
                                                              </div>
                                                               @if($itemData['userid'] != 0)
                                                              <div>
                                                                <input type="radio" id="music" name="notify_via" value="2" {{($itemData['notify_by'] == 2) ? 'checked' : ''}}>
                                                                <label for="music">On my mobile phone</label>
                                                              </div>

                                                              <div>
                                                                <input type="radio" id="notify_both" name="notify_via" value="3" {{($itemData['notify_by'] == 3) ? 'checked' : ''}}>
                                                                <label for="notify_both">Both email and mobile phone</label>
                                                              </div>
                                                              @endif
                                                              <div>
                                                                <input type="radio" id="notify_noo" name="notify_via" value="0" {{($itemData['notify_by'] == 0) ? 'checked' : ''}}>
                                                                <label for="notify_noo">No, thanks</label>
                                                              </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group text-left">
                                                      <input type="checkbox" checked="" id="customCheck_nw" name="poem_in_public_domain" value="Bike">
                                                         <label for="customCheck_nw"> This poem is in the <a href="{{url('termsCondtion')}}" class="ter_links">public domain*</a></label>
                                                         <label for="poem_in_public_domain" class="error col-md-12"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row mt-3">
                                                    <div class="col-12 text-center">
                                                        <button type="submit" class="btn btn-success waves-effect waves-light m-1 edtbtnclss"><i class="fe-check-circle mr-1"></i> Submit</button>
                                                    </div>
                                                </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>


    <script type="text/javascript">

        $(document).ready(function() {
  
        CKEDITOR.replace( 'description' );
            
         });

          $(document).on('click','.edtbtnclss',function(){
          
           var descdata = CKEDITOR.instances['description_id'].getData();
             console.log('descrrrrrr',descdata);
             if (descdata == '') {
                    console.log('Please provide the contents.');
                $('.description_hidden_clss').show();
                return false;
            }else{
                $('.description_hidden_clss').hide();
                return true;

            }
        });

        $('.newCreatorAdded').hide();
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
        $(document).on('change','.selectPoet',function(){
            
            if($(this).val()=='other'){
                // alert('here');
                $('.newCreatorAdded').show();
                
            }else{
                $('.newCreatorAdded').hide();
               
            }
        });
    </script>

    <script type="text/javascript">
        var i = 3;
        $(document).on('click', '.add_more', function(){
            var len = $('.main_div').length;
             ++i;
            if(len == 3) { jQuery(".add_more").hide(); return; } else {
                jQuery(".add_more").show();
            }
                $('.apnnd_div').append('<div class="price_wrap main_div mb-2"> <div class="row" part="'+i+'"> <div class="col-lg-6"> <label class="chart_head mb-2">Content</label> <div class="form-group"> <input type="text" class="form-control" id="size_'+i+'" name="price_firsrt_append_div['+i+'][itext]" value="" placeholder="Content"></div></div><div class="col-lg-6"> <label class="chart_head mb-2">URL</label> <div class="form-group"> <input type="text" class="form-control" id="url_'+i+'" name="price_firsrt_append_div['+i+'][url]" value="" placeholder="URL"></div></div></div><p class="text-right mb-0"> <a href="javascript:;" class="remove_apnd"> <i class="fa fa-times"></i> Remove</a></p></div>');

                if (len==0) {
                    $('.remove_apnd').hide();
                }

            $("input[id^=size_").each(function(){
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter poem text",
                    }
                });   
            });

            $("input[id^=url").each(function(){
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter url",
                    }
                });   
            });

        }); 

        $("body").on('click', '.remove_apnd', function(){
            $(this).parent().parent().remove();
            var len = $('.main_div').length;
            if(len == 3) { jQuery(".add_more").hide(); } else {
                jQuery(".add_more").show();
            }
            var lengt = $('.main_div').length;
            if (lengt>1) {
                $('.main_div').last().find('.remove_apnd').show();
            }
        });
    </script>

    <script type="text/javascript">

    	$(document).ready(function() {
    	    $(".mul_category").select2({
                        placeholder: "Select Theme"
                    });

            $(".mul_mood").select2({
                placeholder: "Select Mood"
            });

    	});

        $('#add_faq').validate({
    //          for (instance in CKEDITOR.instances) {
    //     CKEDITOR.instances[instance].updateElement();
    // }
            ignore:[],
            rules:{
                "title":{
                    required:true,
                    // minlength:5,
                    remote:"{{ url('admin/check-faq-title')}}",
                },
                "cname":{
                    required: {
                        depends: function(element){
                            if($('.selectPoet').val()=='other'){
                                    return true;
                            } else {
                                    return false;
                            }
                        }
                    },
                },
                "iyear":{
                    required:true,
                    number:true,
                    maxlength: 4,
                    digits: true,
                },
                // "description":{
                //     required:true,
                //     minlength:20,
                // },
                "poem_in_public_domain":{
                    required:true,
                },
            },
            messages:{
                "title":{
                    required:"Please enter title",
                    minlength:"Title must contain 5 characters",
                    remote:"*Title already registered",
                },
                'cname':{
                    required:"Please enter poet name",
                },  
                "iyear":{
                    required:"Please enter year",
                    minlength:"Year must contain 20 characters",
                },
                // "description":{
                //     required:"Please enter description",
                //     minlength:"Description must contain 20 characters",
                // },
                "poem_in_public_domain":{
                    required:"Please select public domain"
                },
            },
        });
    </script>







