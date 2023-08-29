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
                                        <li class="breadcrumb-item active">Items</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Items</h4>
                            </div>
                        </div>
                    </div> 
               
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-box">
                               <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="header-title mb-3">All Items</h4>
                                    </div>
                                     <div class="col-md-6 text-right">
                                        <a href="{{url('admin/poem/add')}}" class="btn btn-primary  mb-3">Add Item</a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table   id="example"  class="table  table-hover table-nowrap table-centered m-0">
                                        <thead class="thead-light">
                                            <tr>
                                                 <th>Sr. No</th>
                                                <th style="display:none;">Id</th>
                                                <th>Item Title</th>
                                                <th>Item Added by</th>
                                                <th>Year</th>
                                                <th>Item Approve status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php foreach ($poemList as $key => $value): ?>
                                                <tr>
                                                     <td>{{$key+1}}</td>
                                                    <td style="display:none;">  
                                                            {{@$value['itemid']}}
                                                        
                                                    </td>
                                                    <td> 
                                                            {{@$value['ititle']}}
                                                        
                                                    </td>
                                                    <td>
                                                         <h5 class="m-0 font-weight-normal">
                                                            @if($value['is_admin']==0)
                                                                {{@$value['itemAddedByUser']['user_name']}}
                                                            @else
                                                            Admin
                                                            @endif    

                                                         </h5>
                                                         <p class="mb-0 text-muted"><small>Member Since {{@$value['created_at']}}</small>
                                                         </p>
                                                    </td>
                                                    <td>{{@$value['iyear']}}</td>
                                                
                                                    <td>
                                                        <input dat="{{$value['itemid']}}" dat-userid="{{$value['userid']}}" class="toggle-class toggleSattus" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $value['approved_by_admin']==1 ? 'checked' : '' }}>
                                                    </td> 
                                        
                                                    <td> 
                                                    <a href="{{url('/admin/poem/view/'.@$value['itemid'])}}" class="btn btn-xs btn-primary"><i class="mdi mdi-eye"></i></a>
                                                    <a href="{{url('/admin/poem/edit/'.@$value['itemid'])}}" class="btn btn-xs btn-success"><i class="mdi mdi-pencil"></i></a>
                                                    <a val="{{base64_encode($value['itemid'])}}" class="btn btn-xs btn-danger del_btn"><i class="mdi mdi-trash-can"></i></a>
                                                    </td>
                                              
                                                </tr>
                                             <?php endforeach ?>
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

<script type="text/javascript">
    $(document).ready( function () {
        $('#example').DataTable({
            order: [[0, 'desc']],
        });
</script>
    
<script>

    $(document).on('change','.toggleSattus',function(){
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var itemid = $(this).attr('dat');    
        var userid = $(this).attr('dat-userid');    
        console.log(status,itemid)   
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('admin/poem/changeStatus') }}",
            data: {'status': status, 'itemid': itemid, 'userid': userid},
            success: function(data){
              console.log(data.success)
              toastr.success(data.success);

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