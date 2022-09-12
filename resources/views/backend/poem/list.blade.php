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
                                        <li class="breadcrumb-item active">Poems</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Poems</h4>
                            </div>
                        </div>
                    </div> 
               
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-box">
                               <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="header-title mb-3">All Poems</h4>
                                    </div>
                                     <div class="col-md-6 text-right">
                                        <a href="{{url('admin/poem/add')}}" class="btn btn-primary  mb-3">Add Poem</a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table   id="example"  class="table  table-hover table-nowrap table-centered m-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Poem Title</th>
                                                <th>Poem Added by</th>
                                                <th>Year</th>
                                                <th>Poem Approve status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>

                                            <?php foreach ($poemList as $key => $value): ?>
                                                <tr>
                                                    <td>{{@$value['ititle']}}</td>
                                                    <td>
                                                         <h5 class="m-0 font-weight-normal">{{@$value['itemAddedByUser']['user_name']}}</h5>
                                                         <p class="mb-0 text-muted"><small>Member Since {{@$value['created_at']}}</small>
                                                         </p>
                                                    </td>
                                                <td>{{@$value['iyear']}}</td>
                                                
                                                 <td>
                                                <input data-id="{{$value['itemid']}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $value['approved_by_admin']==2 ? 'checked' : '' }}>
                                        </td> 
                                        
                                         <td> <a href="{{url('/admin/poem/edit/'.@$value['itemid'])}}" class="btn btn-xs btn-success"><i class="mdi mdi-pencil"></i></a>
                                                    <a val="{{base64_encode($value['itemid'])}}" href="javascript: void(0);"  class="btn btn-xs btn-danger del_btn"><i class="mdi mdi-trash-can"></i></a>
                                                    </td>
                                              
                                                      
                                                </tr>
                                             <?php endforeach ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                               <th>Poem Title</th>
                                               <th>Poem Added by</th>
                                               <th>Year</th>
                                               <th>Poem Approve status</th>
                                               <th>Action</th>
                                            </tr>
                                        </tfoot>
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

<script type="text/javascript">
    $(document).ready( function () {
        $('#example').DataTable();
    });
</script>
    
<script>

    $(document).on('click','.toggle-class',function(){
       
        var status = $(this).prop('checked') == true ? 2 : 1; 
        var itemid = $(this).data('id'); 
         alert('here',itemid);      
        $.ajax({
            type: "GET",
            dataType: "json",
            url: 'admin/poem/changeStatus',
            data: {'status': status, 'itemid': itemid},
            success: function(data){
              console.log(data.success)
            }
        });
    });

</script>

<script>
    $(document).on('click','.del_btn',function(){
        var confirmation =  confirm('Are you sure you want to delete this?');
        var userId = $(this).attr("val");
        var ev        = $(this);
        if(confirmation == true){
            $.ajax({
                 url: "{{ url('admin/poem/delete') }}" + '/' + userId,
                type: 'POST',
               data : {"_token":"{{ csrf_token() }}"},  //pass the CSRF_TOKEN()
             success: function (data) {
                    if (data.status == 'ok') {
                        $(ev).closest('tr').hide();
                        toastr.success('User deleted successfully');
                    }   
                }         
            });
        }else{
            return false;
        }
    });
</script>