<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ __('App Name') }}</title>
    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    @yield('header')

    <link rel="stylesheet" href="{{ asset('asset/css/app.min.css') }}">

    <link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/custom.css') }}">

    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('asset/img/favicon.ico') }}'
        style="width: 2px !important;" />

    <link rel="stylesheet" href="{{ asset('asset/bundles/codemirror/lib/codemirror.css') }}">
    <link rel="stylesheet" href=" {{ asset('asset/bundles/codemirror/theme/duotone-dark.css') }} ">
    <link rel="stylesheet" href=" {{ asset('asset/bundles/jquery-selectric/selectric.css') }}">
    <script src="{{ asset('asset/cdnjs/iziToast.min.js') }}"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/cdncss/iziToast.css') }}" />
    <script src="{{ asset('asset/cdnjs/sweetalert.min.js') }}"></script>
    <script src="{{ asset('asset/script/env.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/app.css') }}">

</head>

<body>
    {{-- <div class="loader"></div> --}}

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn">
                                <i data-feather="align-justify"></i></a></li>
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">

                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <span
                                class="d-sm-none d-lg-inline-block btn btn-light"> {{ __('Log Out') }} </span></a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">

                            <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger"> <i
                                    class="fas fa-sign-out-alt"></i>
                                {{ __('Log Out') }}
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="{{ route('index') }}"> <span class="logo-name"> {{ __('App Name') }} </span>
                        </a>
                    </div>
                    <ul class="sidebar-menu">

                        <li class="menu-header">{{ __('Main') }}</li>

                        <li class="sideBarli  indexSideA">
                            <a href="{{ route('index') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>
                                    {{ __('Dashboard') }} </span></a>
                        </li>

                        <li class="sideBarli  usersSideA">
                            <a href="{{ route('users') }}" class="nav-link"><i class="fa fa-users"></i><span>
                                    {{ __('Users') }} </span></a>
                        </li>

                        <li class="sideBarli  doctorsSideA">
                            <a href="{{ route('doctors') }}" class="nav-link"><i class="fas fa-plus-square"></i><span>
                                    {{ __('Doctors') }} </span></a>
                        </li>
                        <li class="sideBarli  appointmentsSideA">
                            <a href="{{ route('appointments') }}" class="nav-link"><i
                                    class="fas fa-calendar-check"></i><span>
                                    {{ __('Appointments') }} </span></a>
                        </li>

                        <li class="sideBarli  reviewsSideA">
                            <a href="{{ route('reviews') }}" class="nav-link"><i class="fas fa-star"></i><span>
                                    {{ __('Reviews') }} </span></a>
                        </li>
                        <li class="sideBarli  couponsSideA">
                            <a href="{{ route('coupons') }}" class="nav-link"><i class="fas fa-tag"></i><span>
                                    {{ __('Coupons') }} </span></a>
                        </li>
                        <li class="sideBarli  reelsSideA">
                            <a href="{{ route('reels') }}" class="nav-link"><i class="fas fa-play"></i><span>
                                    {{ __('Reels') }} </span></a>
                        </li>

                        <li class="sideBarli  reportsSideA">
                            <a href="{{ route('reports') }}" class="nav-link"><i class="fas fa-info-circle"></i><span>
                                    {{ __('Reel Reports') }} </span></a>
                        </li>

                        <li class="sideBarli  faqsSideA">
                            <a href="{{ route('faqs') }}" class="nav-link"><i class="fas fa-question-circle"></i><span>
                                    {{ __('FAQs') }} </span></a>
                        </li>
                        <li class="sideBarli  notificationsSideA">
                            <a href="{{ route('notifications') }}" class="nav-link"><i class="fa fa-bell"></i><span>
                                    {{ __('Notifications') }} </span></a>
                        </li>
                        <li class="menu-header">{{ __('Business') }}</li>

                        <li class="sideBarli  userWithdrawsSideA">
                            <a href="{{ route('userWithdraws') }}" class="nav-link"><i
                                    class="fas fa-money-bill"></i><span>
                                    {{ __('User Withdraws') }} </span></a>
                        </li>

                        <li class="sideBarli  doctorWithdrawsSideA">
                            <a href="{{ route('doctorWithdraws') }}" class="nav-link"><i
                                    class="fas fa-money-bill"></i><span>
                                    {{ __('Doctor Withdraws') }} </span></a>
                        </li>


                        <li class="sideBarli  platformEarningsSideA">
                            <a href="{{ route('platformEarnings') }}" class="nav-link"><i
                                    class="fas fa-percentage"></i><span>
                                    {{ __('Platform Earnings') }} </span></a>
                        </li>

                        <li class="sideBarli  userWalletRechargeSideA">
                            <a href="{{ route('userWalletRecharge') }}" class="nav-link"><i
                                    class="fas fa-wallet"></i><span>
                                    {{ __('Recharge Logs (User)') }} </span></a>
                        </li>


                        <li class="menu-header">{{ __('Other Data') }}</li>
                        <li class="sideBarli  doctorCategoriesSideA">
                            <a href="{{ route('doctorCategories') }}" class="nav-link"><i
                                    class="fas fa-grip-horizontal"></i><span>
                                    {{ __('Doctor Categories') }} </span></a>
                        </li>

                        <li class="sideBarli  settingsSideA">
                            <a href="{{ route('settings') }}" class="nav-link"><i class="fas fa-cog"></i><span>
                                    {{ __('Settings') }} </span></a>
                        </li>


                        <li class="menu-header">{{ __('Pages') }}</li>

                        <li class="sideBarli  privacySideA">
                            <a href="{{ route('viewPrivacy') }}" class="nav-link"><i
                                    class="fas fa-info"></i><span>{{ __('Privacy Policy') }}</span></a>
                        </li>

                        <li class="sideBarli  termsSideA">
                            <a href="{{ route('viewTerms') }}" class="nav-link"><i
                                    class="fas fa-info"></i><span>{{ __('Terms Of Use') }}</span></a>
                        </li>

                    </ul>
                </aside>
            </div>


            <!-- Main Content -->
            <div class="main-content">

                @yield('content')
                <form action="">
                    <input type="hidden" id="user_type" value="{{ session('user_type') }}">
                </form>

            </div>

        </div>
    </div>



    <script src="{{ asset('asset/js/app.min.js ') }}"></script>


    <script src="{{ asset('asset/bundles/datatables/datatables.min.js ') }}"></script>
    {{-- <script src=" {{ asset('asset/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script> --}}
    <script src="{{ asset('asset/bundles/jquery-ui/jquery-ui.min.js ') }}"></script>

    <script src=" {{ asset('asset/js/page/datatables.js') }}"></script>

    <script src="{{ asset('asset/js/scripts.js') }}"></script>
    <script src="{{ asset('asset/script/app.js') }}"></script>

    <!-- Custom JS File -->
    <script src="{{ asset('asset/bundles/summernote/summernote-bs4.js ') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.js"
        integrity="sha512-Fd3EQng6gZYBGzHbKd52pV76dXZZravPY7lxfg01nPx5mdekqS8kX4o1NfTtWiHqQyKhEGaReSf4BrtfKc+D5w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>



</body>


</html>
