<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">

    <div class="slimscroll-menu" id="remove-scroll">

        <!-- Logo -->
        <div class="user-box">
            <div class="user-img">
               <a href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/sports-app-logo.png') }}" width="120" alt="user-img" title=""></a> 
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu" id="side-menu">

                <!--<li class="menu-title">Navigation</li>-->

                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fi-air-play"></i> <span> Dashboard </span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);"><i class="fas fa-running"></i> <span> Sports </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ route('sport') }}">All Sports</a></li>
                        <li><a href="{{ route('createSport') }}">Add New Sports</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('challange') }}"><i class="far fa-handshake"></i><span> All Challenges </span> </a>
                </li>
                
                <li>
                    <a href="{{ route('match') }}"><i class=" far fa-futbol"></i><span> All Matches </span></a>
                </li>
                
                <li>
                    <a href="{{ route('allTeams') }}"><i class="fas fa-users"></i><span> All Teams </span> </a>
                </li>
                
                
                <li>
                    <a href="{{ route('allVenues') }}"><i class="far fa-map"></i><span> All Venues </span></a>
                </li>

                <li>
                    <a href="javascript: void(0);"><i class="far fa-bell"></i><span> Push Notification </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ route('notification') }}">All Notifications</a></li>
                        <li><a href="{{ route('create') }}">Send New Notification</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="{{ route('feedback') }}"><i class="far fa-comment-alt"></i><span> All Feedbacks </span> </a>
                </li>

                {{-- <li>
                    <a href="#">
                        <i class="fi-command"></i> <span> Widgets </span>
                    </a>
                </li> --}}

            </ul>

        </div>
        <!-- Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->