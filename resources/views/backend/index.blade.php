@include('backend.common.header_main')

<style type="text/css">
    .centred {
        text-align: center;
    }
</style>
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                  <div class="page-title-box">
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 col-xl-3">
                    <div class="widget-rounded-circle card-box dashboard-card-box">
					<a href="{{url('admin/user/list')}}">
                        <div class="row">
                            <div class="col-4">
                                <div class="avatar-lg rounded-circle bg-primary border-primary border">
                                    <i class="fe-users font-22 avatar-title text-white"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-right">
                                    <h3 class="mt-1"><span data-plugin="counterup">{{$userCount}}</span></h3>
                                    <p class="text-muted mb-1 text-truncate">
                                       Users</p>
                                </div>
                            </div>
                        </div>
						</a>
                    </div>
                </div>
                
                <div class="col-md-3 col-xl-3">
                    <div class="widget-rounded-circle card-box dashboard-card-box">
						<a href="{{url('admin/poem/list')}}">
                        <div class="row">
                            
                            <div class="col-4">
                                <div class="avatar-lg rounded-circle bg-success border-success border">
                                    <i class="mdi-office-building mdi font-22 avatar-title text-white"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-right">
                                    <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$poemCount}}</span></h3>
                                    <p class="text-muted mb-1 text-truncate">
                                        Items</p>
                                </div>
                            </div>
                        </div> 
						</a>
                    </div> 
                </div>
            
            </div>     
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <h4 class="header-title mb-3">Recent Users </h4>
                            <div class="table-responsive">
                                <table id="example" class="table table-borderless table-hover table-nowrap table-centered m-0 ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Country</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($userList as $key => $value): ?>
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>
                                                <h5 class="m-0 font-weight-normal">{{ucfirst(@$value['user_name'])}}</h5>
                                                <p class="mb-0 text-muted"><small>Member Since {{@$value['created_at']}}</small>
                                                </p>
                                            </td>
                                            
                                            <td><?php  echo($value['uemail']); ?></td>
                                            <td><?php  echo($value['countries']['country_name']); ?></td>

                                                 <td> <a href="{{url('admin/user/view/'.$value['userid'])}}" class="btn btn-xs btn-primary"><i class="mdi mdi-eye"></i></a>
                                                    <a val="{{base64_encode($value['userid'])}}" href="javascript: void(0);"  class="btn btn-xs btn-danger del_btn"><i class="mdi mdi-trash-can"></i></a>
                                                 </td>

                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                   
                                </table>
                                <div class="left">
                                    <a href="{{url('admin/user/list')}}">View all users</a>
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






















