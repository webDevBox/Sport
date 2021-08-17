<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
    <title>{{ config('app.name') }}</title>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- App css -->
    <link href={{ asset('theme/assets/css/bootstrap.min.css') }} rel="stylesheet" type="text/css" />
    <link href={{ asset('theme/assets/css/icons.css') }} rel="stylesheet" type="text/css" />
    <link href={{ asset('theme/assets/css/metismenu.min.css') }} rel="stylesheet" type="text/css" />
    <link href={{ asset('theme/assets/css/style.css') }} rel="stylesheet" type="text/css" />

    </head>
    <body class="account-pages">

        <!-- Begin page -->
        <!-- <div class="accountbg"></div> -->
        <div class="accountbg" style="background: url('assets/images/login-bg.jpg');background-size: cover;background-position: center;"></div>
        
        <div class="wrapper-page account-page-full">
            <div class="card mt-5">
                <div class="card-block">
                    <div class="account-box">
                        <div class="card-box p-5">
                            <h2 class="text-uppercase text-center pb-4">
<!--                                 <a href="#" class="text-success">
                                    <span>Survey Form Admin App</span>
                                </a> -->
                                <img src="{{ asset('assets/images/sports-app-logo.png') }}" width="120" alt="user-img" title="">
                            </h2>
                            @if (Session::has('error'))
                                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                            @endif
                            <form class="" action="{{ route('AdminLogin') }}" method="POST">
                                @csrf
                                <div class="form-group m-b-20 row">
                                    <div class="col-12">
                                        <label for="emailaddress">Email address</label>
                                        <input class="form-control" type="email" name="email" id="emailaddress" required="" placeholder="Enter your email">
                                    </div>
                                </div>

                                <div class="form-group row m-b-20">
                                    <div class="col-12">
                                        <label for="password">Password</label>
                                        <input class="form-control" type="password" name="password" required="" id="password" placeholder="Enter your password">
                                    </div>
                                </div>

                                <div class="form-group row text-center m-t-10">
                                    <div class="col-12">
                                        <button class="btn btn-block btn-custom waves-effect waves-light" type="submit">Sign In</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="m-t-40 text-center">
                <p class="account-copyright text-muted">2021 © Sports App - Powered by: <a href="http://www.cybexo.com" target="_blank" class="text-danger">Cybexo Inc.</a></p>
            </div>

        </div>

    <script src={{ asset('theme/assets/js/jquery.min.js') }}></script>
    <script src={{ asset('theme/assets/js/bootstrap.bundle.min.js') }}></script>
    <script src={{ asset('theme/assets/js/metisMenu.min.js') }}></script>
    <script src={{ asset('theme/assets/js/waves.js') }}></script>
    <script src={{ asset('theme/assets/js/jquery.slimscroll.js') }}></script>

    <!-- App js -->
    <script src={{ asset('theme/assets/js/jquery.core.js') }}></script>
    <script src={{ asset('theme/assets/js/jquery.app.js') }}></script>

    <script src={{ asset('theme/assets/js/modernizr.min.js') }}></script>

    </body>
</html>