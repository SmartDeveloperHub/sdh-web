@extends('layouts.base')

@section('scripts')
    @parent
    <script src="assets/js/d3/d3.min.js"></script>
    <script src="assets/js/nvd3/nv.d3.min.js"></script>
    <script src="assets/js/joinable/joinable.js"></script>
@stop

@section('css')
    @parent
    <link rel="stylesheet" href="assets/css/nv.d3.min.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/linecons/css/linecons.css">
    <link rel="stylesheet" href="assets/fonts/octicons/css/octicons.css">
@stop

@section('body')


<div class="settings-pane">

    <a href="#" data-toggle="settings-pane" data-animate="true">
        &times;
    </a>

    <div class="settings-pane-inner">

        <div class="row">

            <div class="col-md-4">

                <div class="user-info">

                    <div class="user-image">
                        <a href="extra-profile.html">
                            <img src="assets/images/user-2.png" class="img-responsive img-circle" />
                        </a>
                    </div>

                    <div class="user-details">

                        <h3>
                            <a href="extra-profile.html">John Smith</a>

                            <!-- Available statuses: is-online, is-idle, is-busy and is-offline -->
                            <span class="user-status is-online"></span>
                        </h3>

                        <p class="user-title">Web Developer</p>

                        <div class="user-links">
                            <a href="extra-profile.html" class="btn btn-primary">Edit Profile</a>
                            <a href="extra-profile.html" class="btn btn-success">Upgrade</a>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-8 link-blocks-env">

                <div class="links-block left-sep">
                    <h4>
                        <span>Notifications</span>
                    </h4>

                    <ul class="list-unstyled">
                        <li>
                            <input type="checkbox" class="cbr cbr-primary" checked="checked" id="sp-chk1" />
                            <label for="sp-chk1">Messages</label>
                        </li>
                        <li>
                            <input type="checkbox" class="cbr cbr-primary" checked="checked" id="sp-chk2" />
                            <label for="sp-chk2">Events</label>
                        </li>
                        <li>
                            <input type="checkbox" class="cbr cbr-primary" checked="checked" id="sp-chk3" />
                            <label for="sp-chk3">Updates</label>
                        </li>
                        <li>
                            <input type="checkbox" class="cbr cbr-primary" checked="checked" id="sp-chk4" />
                            <label for="sp-chk4">Server Uptime</label>
                        </li>
                    </ul>
                </div>

                <div class="links-block left-sep">
                    <h4>
                        <a href="#">
                            <span>Help Desk</span>
                        </a>
                    </h4>

                    <ul class="list-unstyled">
                        <li>
                            <a href="#">
                                <i class="fa-angle-right"></i>
                                Support Center
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa-angle-right"></i>
                                Submit a Ticket
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa-angle-right"></i>
                                Domains Protocol
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa-angle-right"></i>
                                Terms of Service
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

        </div>

    </div>

</div>

<nav class="navbar horizontal-menu navbar-fixed-top"><!-- set fixed position by adding class "navbar-fixed-top" -->

    <div class="navbar-inner">

        <!-- Navbar Brand -->
        <div class="navbar-brand">
            <a href="dashboard-1.html" class="logo">
                <img src="assets/images/logo.gif" width="80" alt=""/>
            </a>
            <a href="#" data-toggle="settings-pane" data-animate="true">
                <i class="linecons-cog"></i>
            </a>
        </div>

        <!-- Mobile Toggles Links -->
        <div class="nav navbar-mobile">

            <!-- This will toggle the mobile menu and will be visible only on mobile devices -->
            <div class="mobile-menu-toggle">
                <!-- This will open the popup with user profile settings, you can use for any purpose, just be creative -->
                <a href="#" data-toggle="settings-pane" data-animate="true">
                    <i class="linecons-cog"></i>
                </a>

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
                <a href="organization-dashboard.html">
                    <i class="fa-globe"></i>
                    <span class="title">Organization</span>
                </a>
            </li>
            <li>
                <a href="project-dashboard.html">
                    <i class="fa-cubes"></i>
                    <span class="title">Projects</span>
                </a>
            </li>
            <li>
                <a href="user-dashboard.html">
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

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

    <div class="main-content">
    @section('main-content')
        <h1>Empty template. Extend me!</h1>
    @show
    </div>


</div>

{{-- End of body section --}}
@stop