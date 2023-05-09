<!-- sidebar left start-->
<div class="sidebar-left">
    <div class="sidebar-left-info">

        <div class="user-box">
            <div class="d-flex justify-content-center">
                <img src="assets/images/users/avatar-1.jpg" alt="" class="img-fluid rounded-circle">
            </div>
            <div class="text-center text-white mt-2">
                <h6>{{Auth::user()->f_name}} {{Auth::user()->l_name}}</h6>
                <p class="text-muted m-0">Admin</p>
            </div>
        </div>

        <!--sidebar nav start-->
        <ul class="side-navigation">
            <li>
                <h3 class="navigation-title">Menu</h3>
            </li>
            <li class="">
                <a href="{{ route('dashboard') }}"><i class="mdi mdi-gauge"></i> <span>Dashboard</span></a>
            </li>
            <li class="nav-menu"><a href="{{ route('managecard') }}">
                    <i class="fa fa-id-card-o"></i> <span>Manage Cards</span></a>
            </li>
            <li class="nav-menu"><a href="{{ route('manageuser') }}">
                    <i class="fa fa-users"></i> <span>Manage Users</span></a>
            </li>
            <li class="nav-menu"><a href="{{ route('viewdepositfund') }}">
                    <i class="fa fa-money"></i> <span>Deposit Funds</span></a>
            </li>
            <li class="nav-menu"><a href="{{ route('generatereport') }}">
                    <i class="fa fa-file"></i> <span>Generate Report</span></a>
            </li>
            <li class="nav-menu"><a href="#">
                    <i class="fa fa-paper-plane"></i> <span>View Request</span></a>
            </li>
        </ul>
        <!--sidebar nav end-->
    </div>
</div>
<!-- sidebar left end-->
