<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Green Pheasants Team</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="{{ url('admin/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('admin/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('admin/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('admin/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
        <link href="{{ url('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
        <link href="{{ url('admin/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
        <link href="{{ url('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="{{ url('admin/css/chosen.css') }}">
		<link href="{{ url('admin/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('admin/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('admin/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('admin/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('admin/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('admin/css/sweetalert.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('admin/css/toastr.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    
    
        <style type="text/css">
            #side-menu a:hover{
                background-color:  #212529 !important;
            }
            #side-menu a:visited{
                background-color: #212529 !important;
            }
            .btn-primary{
                background-color: rgb(71,161,69) !important;
                border: rgb(52,118,50) !important;
            }
            .btn-primary:hover{
                background-color: rgb(52,118,50) !important;
                border: rgb(52,118,50) !important;
            }

            .error{
                color: red;
            }
        </style>
    </head>
    <body class="loading">
        <div id="wrapper">
            @include('backend.common.header')
            @include('backend.common.sidebar')
        </div>
        <script type="text/javascript" src="{{url('admin/js/jquery-3.2.1.min.js')}}"></script>
        <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
        <script src="{{ url('admin/js/additional-methods.min.js')}}" type="text/javascript"></script>
            
        <script src="{{ url('admin/libs/flatpickr/flatpickr.min.js') }}"></script>
      
        <script src="{{ url('admin/libs/selectize/js/standalone/selectize.min.js') }}"></script>
        <script src="{{ url('admin/js/pages/dashboard-1.init.js') }}"></script>
        
        <script type="text/javascript" src="{{ url('admin/js/toastr.min.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.6.1/gsap.min.js" integrity="sha512-cdV6j5t5o24hkSciVrb8Ki6FveC2SgwGfLE31+ZQRHAeSRxYhAQskLkq3dLm8ZcWe1N3vBOEYmmbhzf7NTtFFQ==" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script type="text/javascript" src="{{ url('admin/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script type="text/javascript" src="{{ url('admin/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script type="text/javascript" src="{{ url('admin/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script type="text/javascript" src="{{ url('admin/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
        <script type="text/javascript" src="{{ url('admin/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script type="text/javascript" src="{{ url('admin/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
        <script type="text/javascript" src="{{ url('admin/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
        <script type="text/javascript" src="{{ url('admin/libs/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
        <!---script type="text/javascript" src="{{ url('admin/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script--->
        <script type="text/javascript" src="{{ url('admin/libs/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
        <script type="text/javascript" src="{{ url('admin/libs/datatables.net-select/js/dataTables.select.min.js')}}"></script>
        <script type="text/javascript" src="{{ url('admin/libs/pdfmake/build/pdfmake.min.js')}}"></script>
        <script type="text/javascript" src="{{ url('admin/libs/pdfmake/build/vfs_fonts.js')}}"></script>
        <script src="{{ url('admin/js/select2.min.js')}}"></script>
        <script src="{{ url('admin/js/tinymce/tinymce.min.js')}}"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" ></script>
		<script src="{{ url('admin/js/app.min.js') }}"></script> 
        <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
        
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <script>
            feather.replace()
            @if(Session::has('success'))
                $(function () {
                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "10000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };
                    toastr.success("{{ Session::get('success') }}");
                });
            @endif    
            @if(Session::has('error'))
                $(function () {
                    toastr.options = {
                      "closeButton": true,
                      "debug": false,
                      "newestOnTop": false,
                      "progressBar": true,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "300",
                      "hideDuration": "1000",
                      "timeOut": "10000",
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut"
                    };
                    toastr.error("{{ Session::get('error') }}");
                });
            @endif  
        </script>
    </body>
</html>


