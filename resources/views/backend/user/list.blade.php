@include('backend.common.header_main')
@section('title', 'Manage'.' '.$label.'s')
@section('content')


        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                          <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                       
                                         <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashbord</a></li>
                                        <li class="breadcrumb-item active">Users</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Users</h4>
                            </div>
                        </div>
                    </div> 
               
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-box">
                               <div class="row">
                                 <div class="col-md-6">
                                   <h4 class="header-title mb-3">All Users </h4>
                                 </div>
                                 <div class="col-md-6 text-right">
                                    <a href="{{url('admin/user/add')}}" class="btn btn-primary  mb-3">Add User</a>
                                 </div>
                                </div>
                                <div class="table-responsive">
                                    <table   id="example1"  class="table  table-hover table-nowrap table-centered m-0">
                                        <thead class="thead-light">
                                            <tr>
                                                 <th>Sr. No</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th class="no-sort"> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php foreach ($userList as $key => $value): ?>
                                             <tr>
                                                 <td>{{$key+1}}</td>
                                                 <td>
                                                    <h5 class="m-0 font-weight-normal">{{@$value['user_name']}}</h5>
                                                    <p class="mb-0 text-muted"><small>Member Since {{@$value['created_at']}}</small></p>
                                                 </td>
                                                 <td>{{@$value['uemail']}}</td>
                                                 <td> <a href="{{url('admin/user/view/'.$value['userid'])}}" class="btn btn-xs btn-primary"><i class="mdi mdi-eye"></i></a>
                                                    <a val="{{base64_encode($value['userid'])}}" href="javascript: void(0);"  class="btn btn-xs btn-danger del_btn"><i class="mdi mdi-trash-can"></i></a>
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
        $('#example1').dataTable( {
            "bSort": false
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
                url: "{{ url('admin/user/delete') }}" + '/' + userId,
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
