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
                                    <li class="breadcrumb-item active">FAQ's</li>
                                </ol>
                            </div>
                            <h4 class="page-title">FAQ's List</h4>
                        </div>
                    </div>
                </div> 
           

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                           <div class="row">
                             <div class="col-md-6">
                               <h4 class="header-title mb-3">FAQ's List </h4>
                             </div>
                             <div class="col-md-6 text-right">
                                <a href="{{url('admin/add-faq')}}" class="btn btn-primary  mb-3">Add FAQ </a>
                             </div>
                            </div>
                            <div class="table-responsive">
                                <table  id="basic-datatable1" class="table  table-hover table-nowrap table-centered m-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Title</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($faqs as $key => $value)
                                            <tr>
                                                <td>
                                                    {{$key+1}}
                                                </td>
                                                <td>
                                                    <h5 class="m-0 font-weight-normal">{{@$value['title']}}</h5>
                                                </td>
                                                <td> 
                                                   <a href="{{url('/admin/edit-faq/'.@$value['id'])}}" class="btn btn-xs btn-success"><i class="mdi mdi-pencil"></i></a>
                                                    <a val="{{base64_encode($value['id'])}}" href="javascript: void(0);"  class="btn btn-xs btn-danger del_btn"><i class="mdi mdi-trash-can"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td></td>
                                                <td><p>No record found</p></td>
                                                <td></td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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

        <script>
            $(document).on('click','.del_btn',function(){
                var confirmation =  confirm('Are you sure you want to delete this?');
                var userId = $(this).attr("val");
                var ev        = $(this);
                if(confirmation == true){
                    $.ajax({
                         url: "{{ url('admin/delete-faq') }}" + '/' + userId,
                        type: 'POST',
                       data : {"_token":"{{ csrf_token() }}"},  //pass the CSRF_TOKEN()
                     success: function (data) {
                            if (data.status == 'ok') {
                                $(ev).closest('tr').hide();
                                toastr.success('FAQ deleted successfully');
                            }   
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        }         
                    });
                }else{
                    return false;
                }
            });
        </script>