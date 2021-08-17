 <!-- Top Bar Start -->
<div class="topbar">

    <nav class="navbar-custom">

        <ul class="list-unstyled topbar-right-menu float-right mb-0">

            <!-- Top Search -->
            <!-- <li class="hide-phone app-search">
                <form>
                    <input type="text" placeholder="Search..." class="form-control">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </li> -->

            {{-- <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <i class="fi-bell noti-icon"></i>
                    <span class="badge badge-danger badge-pill noti-icon-badge">4</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-lg">


                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5 class="m-0"><span class="float-right"><a href="" class="text-dark"><small>Clear All</small></a> </span>Notifications</h5>
                    </div>

                    <div class="slimscroll" style="max-height: 230px;">
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <div class="notify-icon bg-success"><i class="mdi mdi-comment-account-outline"></i></div>
                            <p class="notify-details">Caleb Flakelar commented on Admin<small class="text-muted">1 min ago</small></p>
                        </a>

                    </div>

                    <!-- All-->
                    <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
                        View all <i class="fi-arrow-right"></i>
                    </a>
                </div>
            </li> --}}

            @php
                $user = Auth::guard('admin')->user();
            @endphp

            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <img @if($user->image != null) src="{{ asset('files/'.$user->image) }}" @else src="{{ asset('assets/images/users/avatar-1.jpg') }}" @endif alt="user" class="rounded-circle"> <span class="ml-1">{{ $user->first_name }} {{ $user->last_name }}  <i class="mdi mdi-chevron-down"></i> </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h6 class="text-overflow m-0">Welcome</h6>
                    </div>

                    <!-- item-->
                    <a href="{{ route('profilePage') }}" class="dropdown-item notify-item">
                        <i class="fi-head"></i> <span>My Account</span>
                    </a>
                    
                    <!-- item-->
                    <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                        <i class="fi-power"></i> <span>Logout</span>
                    </a>

                </div>
            </li>

        </ul>
        <ul class="list-inline menu-left mb-0">
            <h4 class="page-title">Dashboard</h4>
            <ol class="breadcrumb">
                <?php $segments = ''; ?>
                @foreach(Request::segments() as $segment)
                    <?php $segments .= '/'.$segment; ?>
                    <li>
                        <a href="{{ $segments }}"><span style="text-transform:capitalize ">{{$segment}}</span></a>
                    </li>
                @endforeach
            </ol>
        </ul>
    </nav>

</div>
<!-- Top Bar End -->