@extends('layouts.base')

@section('scripts')
    @parent
    <script type="application/javascript">
        SDH_API_URL = "http://localhost:12345";
    </script>

@stop

@section('css')
    @parent
    <link rel="stylesheet" href="sdh-framework/lib/nvd3/nv.d3.min.css">
    <link rel="stylesheet" href="sdh-framework/style/components.css">
    <link rel="stylesheet" href="sdh-framework/fonts/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="sdh-framework/fonts/linecons/css/linecons.css">
    <link rel="stylesheet" href="sdh-framework/fonts/octicons/css/octicons.css">
    <link rel="stylesheet" href="sdh-framework/framework.widget.common.css">
    <link rel="stylesheet" href="sdh-framework/framework.widget.heatmap.css">
@stop

@section('body')

<nav class="navbar horizontal-menu navbar-fixed-top"><!-- set fixed position by adding class "navbar-fixed-top" -->

    <div class="navbar-inner">

        <!-- Navbar Brand -->
        <div class="navbar-brand">
            <a href="dashboard-1.html" class="logo">
                <img src="assets/images/logo.gif" width="80" alt=""/>
            </a>
        </div>

        <!-- Mobile Toggles Links -->
        <div class="nav navbar-mobile">

            <!-- This will toggle the mobile menu and will be visible only on mobile devices -->
            <div class="mobile-menu-toggle">

                <a href="#" data-toggle="user-info-menu-horizontal">
                    <i class="fa-bell-o"></i>
                    <span class="badge badge-success">7</span>
                </a>

                <!-- data-toggle="mobile-menu-horizontal" will show horizontal menu links only -->
                <!-- data-toggle="mobile-menu" will show sidebar menu links only -->
                <!-- data-toggle="mobile-menu-both" will show sidebar and horizontal menu links -->
                <a href="#" data-toggle="mobile-menu-horizontal">
                    <i class="fa-bars"></i>
                </a>
            </div>

        </div>

        <div class="navbar-mobile-clear"></div>



        <!-- main menu -->

        <ul class="navbar-nav">
            <li>
                <a href="organization-dashboard">
                    <i class="fa-globe"></i>
                    <span class="title">Organization</span>
                </a>
            </li>
            <li>
                <a href="project-dashboard">
                    <i class="fa-cubes"></i>
                    <span class="title">Projects</span>
                </a>
            </li>
            <li>
                <a href="user-dashboard">
                    <i class="fa-users"></i>
                    <span class="title">Users</span>
                </a>
            </li>
        </ul>


        <!-- notifications and other links -->
        <ul class="nav nav-userinfo navbar-right">

            <li class="search-form"><!-- You can add "always-visible" to show make the search input visible -->

                <form method="get" action="extra-search.html">
                    <input type="text" name="s" class="form-control search-field" placeholder="Type to search..." />

                    <button type="submit" class="btn btn-link">
                        <i class="linecons-search"></i>
                    </button>
                </form>

            </li>

            <li class="dropdown xs-left">

                <a href="#" data-toggle="dropdown" class="notification-icon">
                    <i class="fa-envelope-o"></i>
                    <span class="badge badge-green">15</span>
                </a>

                <ul class="dropdown-menu messages">
                    <li>

                        <ul class="dropdown-menu-list list-unstyled ps-scrollbar">
                        </ul>

                    </li>

                    <li class="external">
                        <a href="blank-sidebar.html">
                            <span>All Messages</span>
                            <i class="fa-link-ext"></i>
                        </a>
                    </li>
                </ul>

            </li>

            <li class="dropdown xs-left">
                <a href="#" data-toggle="dropdown" class="notification-icon notification-icon-messages">
                    <i class="fa-bell-o"></i>
                    <span class="badge badge-purple">7</span>
                </a>

                <ul class="dropdown-menu notifications">
                </ul>
            </li>

            <li class="dropdown user-profile">
                <a href="#" data-toggle="dropdown">
                    <img src="assets/images/user-1.png" alt="user-image" class="img-circle img-inline userpic-32" width="28" />
						<span>
							Arlind Nushi
							<i class="fa-angle-down"></i>
						</span>
                </a>

                <ul class="dropdown-menu user-profile-menu list-unstyled">
                    <li>
                        <a href="#edit-profile">
                            <i class="fa-edit"></i>
                            New Post
                        </a>
                    </li>
                    <li>
                        <a href="#settings">
                            <i class="fa-wrench"></i>
                            Settings
                        </a>
                    </li>
                    <li>
                        <a href="#profile">
                            <i class="fa-user"></i>
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="#help">
                            <i class="fa-info"></i>
                            Help
                        </a>
                    </li>
                    <li class="last">
                        <a href="extra-lockscreen.html">
                            <i class="fa-lock"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#" data-toggle="chat">
                    <i class="fa-comments-o"></i>
                </a>
            </li>

        </ul>

    </div>

</nav>

@yield('timeChart')

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

    <div class="main-content"></div>

</div>
<div id="template-exec" style="display: none";></div>
<div id="loading" class="hidden">
    <div class="loading-info text-center"></div>
</div>

{{-- End of body section --}}
@stop