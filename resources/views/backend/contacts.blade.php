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
                                        <li class="breadcrumb-item active">Queries</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Queries</h4>
                            </div>
                        </div>
                    </div> 
               
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-box">
                               <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="header-title mb-3">All Queries </h4>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table   id="example"  class="table  table-hover table-centered m-0 quries_contact_table">
                                        <thead class="thead-light">
                                            <tr>
                                               <th>Name</th>
                                               <th>Email</th>
                                               <th>Query</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php foreach ($contactUs as $key => $value): ?>
                                                <tr>
                                                    <td>{{@$value['name']}}</td>
                                                    <td>{{@$value['email']}}</td>
                                                    <td>{{@$value['message']}}</td>
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
        $('#example').DataTable();
    });
</script>

