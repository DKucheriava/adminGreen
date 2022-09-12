<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-right mb-0">
            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{Auth::guard('admin')->user()->image?asset('admin/images/profile/'.Auth::guard('admin')->user()->image):asset('admin/images/dummy.png')}}" alt="user-image" class="rounded-circle">
                    <span class="pro-user-name ml-1">
                        {{Auth::guard('admin')->user()->full_name}} <i class="mdi mdi-chevron-down"></i> 
                    </span>
                </a>
                
				<div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <div class="dropdown-divider"></div>
                    <a href="{{ url('admin/profile')}}" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>Profile</span>
                    </a>
                    <a href="{{ url('admin/logout')}}" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a>
                </div>
				
            </li>   
        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <!---a href="{{ url('admin/home') }}" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <img src="{{ url('admin/images/logo.png') }}" alt="" height="40">
                  
                </span>
                <span class="logo-lg">
                    <img src="{{ url('admin/images/logo.png') }}" alt="" height="40">
                 
                </span>
            </a--->
            <a href="{{ url('admin/home') }}" class="logo logo-light text-center">
                <span class="logo-sm">
                    <img src="{{ url('admin/images/logo.png') }}" alt="" height="70">
                </span> 
                <span class="logo-lg">
                    <img src="{{ url('admin/images/logo.png') }}" alt="" height="70">
                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>
            <li>
                <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
            </li>   
       </ul>
        <div class="clearfix"></div>
    </div>
</div>
