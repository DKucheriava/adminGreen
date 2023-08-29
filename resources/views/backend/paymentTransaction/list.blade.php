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
                                        <li class="breadcrumb-item active">Payment Transactions</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Payment Transactions</h4>
                            </div>
                        </div>
                    </div> 
               
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-box">
                               <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="header-title mb-3">All Payment Transactions</h4>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table   id="example"  class="table  table-hover table-nowrap table-centered m-0">
                                        <thead class="thead-light">
                                            <tr>
                                                 <th>Sr. No</th>
                                                <th>Paid By</th>
                                                <th>Payment Type</th>
                                                <th>Payment By</th>
                                                <th>Amount</th>
                                                <th>Transaction Time</th>
                                                <th>Payment status</th>
                                                <th  class="no-sort"> Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php foreach ($supportPaymentList as $key => $value): ?>
                                                <tr>    
                                                     <td>{{$key+1}}</td>
                                                    <td><?php 
                                                            if($value['userid']==null) {
                                                                echo  "Guest User";
                                                            }else{
                                                                echo $value['userDetail']['user_name'];
                                                            } 
                                                         ?>
                                                    </td>
                                                    <td><?php 
                                                            if($value['payment_type'] ==0){
                                                                echo "One time payment";
                                                            }else{
                                                                echo "Monthly Payment";
                                                            }
                                                            $new_date  = date('d F Y,h:i:s A', strtotime($value['created_at']));

                                                            if($value['payment_by']=='card'){
                                                                $payment_by ='Stripe';
                                                            }else{
                                                                $payment_by ='Paypal';
                                                            }

                                                        ?>
                                                    </td>
                                                    <td>{{ucfirst(@$payment_by)}}</td>
                                                    <td>{{@$value['amount']}} {{@$value['currency']}}</td>
                                                    <td>{{@$new_date}}</td>
                                                    <td>{{@$value['status']}}</td>
                                                    <td> <a href="{{url('/admin/viewPaymentTransactionList/'.@$value['id'])}}" class="btn btn-xs btn-success"><i class="mdi mdi-eye"></i></a>
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
        
        $('#example').dataTable( {
           "order": [],
           "columnDefs": [ {
                "targets"  : 'no-sort',
                "orderable": false,
            }]
        });

    });
</script>
    
<script>

    $(document).on('change','.toggleSattus',function(){
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var itemid = $(this).attr('dat');    
        console.log(status,itemid)   
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('admin/poem/changeStatus') }}",
            data: {'status': status, 'itemid': itemid},
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