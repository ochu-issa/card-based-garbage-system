<!-- header section start-->
<div class="header-section">
    <!--logo and logo icon start-->
    <div class="logo">
        <a href="index.html">
            <span class="logo-img">
                <img src="assets/images/logo_sm.png" alt="" height="26">
            </span>
            <!--<i class="fa fa-maxcdn"></i>-->
            <span class="brand-name">CBGCS</span>
        </a>
    </div>

    <!--toggle button start-->
    <a class="toggle-btn"><i class="ti ti-menu"></i></a>
    <!--toggle button end-->

    <!--mega menu start-->
    <div id="navbar-collapse-1" class="navbar-collapse collapse mega-menu">
        <ul class="nav navbar-nav">
            <li>
                <form class="search-content" action="index.html" method="post">
                    <input type="text" class="form-control mt-3" name="keyword" placeholder="Search...">
                    <span class="search-button"><i class="ti ti-search"></i></span>
                </form>
            </li>
        </ul>
    </div>
    <!--mega menu end-->
    <div class="notification-wrap">
        <!--right notification start-->
        <div class="right-notification">
            <ul class="notification-menu">
                <li>
                    <a href="javascript:;" data-toggle="dropdown">
                        <img src="assets/images/users/avatar-1.jpg" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-menu">
                        <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5 text-muted"></i> Profile</a>
                        <a class="dropdown-item" href="#"><span class="badge badge-success pull-right">5</span><i class="mdi mdi-settings m-r-5 text-muted"></i> Settings</a>
                        <a class="dropdown-item" href="#"><i class="mdi mdi-lock-open-outline m-r-5 text-muted"></i> Lock screen</a>
                        <a class="dropdown-item" href="{{route('auth.logout')}}"><i class="mdi mdi-logout m-r-5 text-muted"></i> Logout</a>
                    </div>

                </li>
            </ul>
        </div><!--right notification end-->
    </div>
</div>
<!-- header section end-->
