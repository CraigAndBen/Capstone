<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title> MediCare | Supply Officer Dashboard </title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- [Favicon] icon -->
    <link href="{{ asset('logo.jpg') }}" rel="icon">
    <!-- [Google Font] Family -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
        id="main-font-link" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('admin_assets/fonts/tabler-icons.min.css') }}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('admin_assets/fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('admin_assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('admin_assets/css/style-preset.css') }}" id="preset-style-link" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <!-- [ Pre-loader ] End -->
    <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="m-header mt-3">

            <a href="{{ route('supply_officer.dashboard') }}" class="logo me-auto"><img src="{{ asset('logo.jpg') }}"
                    alt="" class="" style="max-width: 150px; max-height: 90px"></a>
            <!-- ======= Menu collapse Icon ===== -->
        </div>
        <div class="header-wrapper">
            <!-- [Mobile Media Block] start -->
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item header-mobile-collapse">
                        <a href="#" class="pc-head-link head-link-primary ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                </ul>
            </div>
            <!-- [Mobile Media Block end] -->
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <div class="mt-3 text-left">
                            <h5><i>{{$currentDate}} | {{$currentTime}}</i></h5>
                        </div>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <i class="ti ti-bell"></i>
                        </a>
                        <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                            @if ($count > 0)
                                <div class="dropdown-header">
                                    <h5>All Notification <span
                                            class="badge bg-warning rounded-pill ms-1">{{ $count }}</span></h5>
                                </div>
                                <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                                    style="max-height: calc(100vh - 215px)">
                                    <div class="list-group list-group-flush w-100">
                                        <div class="list-group-item">
                                            <select class="form-select">
                                                <option value="all">All Notification</option>
                                                <option value="new">New</option>
                                                <option value="unread">Unread</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        @foreach ($limitNotifications as $notification)
                                            <a class="list-group-item list-group-item-action"
                                                href="{{ route('supply_officer.notification') }}">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ asset('admin_assets/images/user/avatar-2.jpg') }}"
                                                            alt="user-image" class="user-avtar" />
                                                    </div>
                                                    <div class="flex-grow-1 ms-1">
                                                        <span
                                                            class="float-end text-muted">{{ $notification->time }}</span>
                                                        <h5>{{ ucwords($notification->title) }}</h5>
                                                        <p class="text-body fs-6">
                                                            {{ Str::words($notification->message, $limit = 10, $end = '...') }}
                                                        </p>
                                                        @if ($notification->is_read == 0)
                                                            <div class="badge rounded-pill bg-light-danger">Unread</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="text-center py-2">
                                    <a href="{{ route('supply_officer.notification') }}" class="btn btn-primary">Show all</a>
                                </div>
                            @else
                                <div class="dropdown-header">
                                    <h5>All Notification <span class="badge bg-warning rounded-pill ms-1">0</span></h5>
                                </div>
                                <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                                    style="max-height: calc(100vh - 215px)">
                                    <div class="list-group list-group-flush w-100">
                                        <div class="list-group-item">
                                            <select class="form-select">
                                                <option value="all">All Notification</option>
                                                <option value="new">New</option>
                                                <option value="unread">Unread</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <a class="list-group-item list-group-item-action">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('admin_assets/images/user/avatar-2.jpg') }}"
                                                        alt="user-image" class="user-avtar" />
                                                </div>
                                                <div class="flex-grow-1 ms-1">
                                                    <h5>No Notification Yet.</h5>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </li>
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <img src="{{ asset('admin_assets/images/user/avatar-2.jpg') }}" alt="user-image"
                                class="user-avtar" />
                            <span>
                                <i class="ti ti-settings"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <h4>Good Morning, <span class="small text-muted">{{ $profile->first_name }}</span>
                                </h4>
                                <div class="profile-notification-scroll position-relative"
                                    style="max-height: calc(100vh - 280px)">
                                    <a href="{{ route('supply_officer.profile') }}" class="dropdown-item">
                                        <i class="ti ti-settings"></i>
                                        <span>Account Settings</span>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <i class="ti ti-user"></i>
                                        <span>Social Profile</span>
                                    </a>
                                    <a href="{{ route('supply_officer.logout') }}" class="dropdown-item">
                                        <i class="ti ti-logout"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->
    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <div class="mt-3">
                    <a href="{{ route('supply_officer.dashboard') }}" class="logo me-auto"><img
                            src="{{ asset('logo.jpg') }}" alt="" class=""
                            style="max-width: 150px; max-height: 90px"></a>
                </div>

            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item pc-caption">
                        <label>Dashboard</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('supply_officer.dashboard') }}" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Home</span></a>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Account Settings</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Profile Update</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('supply_officer.profile') }}">Update
                                    Profile</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('supply_officer.profile.password') }}">Update Profile Password</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Notification</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Notification List</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('supply_officer.notification') }}">Notification</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Inventory</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Product</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('supply_officer.product') }}">Product List</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('supply_officer.category') }}">Category List</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Request</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('supply_officer.request') }}">Request List</a></li>
                        </ul>
                    </li>
                </ul>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ Main Content ] start -->
            <div class="row">

                <!-- [ sample-page ] start -->
                <div class="col-sm-12 mt-3">
                    <div class="card">
                        <div class="card-header py-3 ">
                            <h3>Welcome Back! {{ucwords($profile->first_name)}} {{ucwords($profile->last_name)}}</h3>
                        </div>
                        <div class="card-body">
                            <div class="container" style="height: 600px">
                                <a href="#" class="d-flex justify-content-center mt-5">
                                    <img src="{{asset('logo.jpg')}}" alt="" class="" style="max-width: 400px; max-height: 230px">
                                  </a>
                            </div>
                        </div>
                    </div>
                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            {{-- <div class="row">
                <div class="col my-1">
                    <p class="m-0">Copyright &copy; <a href="https://codedthemes.com/"
                            target="_blank">Codedthemes</a></p>
                </div>
                <div class="col-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                        <li class="list-inline-item"><a href="https://codedthemes.com/" target="_blank">Home</a></li>
                        <li class="list-inline-item"><a href="https://codedthemes.com/privacy-policy/"
                                target="_blank">Privacy Policy</a></li>
                        <li class="list-inline-item"><a href="https://codedthemes.com/contact/"
                                target="_blank">Contact us</a></li>
                    </ul>
                </div>
            </div> --}}
        </div>
    </footer>
    <!-- Required Js -->
    <script src="{{ asset('admin_assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/config.js') }}"></script>
    <script src="{{ asset('admin_assets/js/pcoded.js') }}"></script>


    <!-- [Page Specific JS] start -->
    <!-- Apex Chart -->
    {{-- <script src="{{ asset('admin_assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/pages/dashboard-default.js') }}"></script> --}}
    <!-- [Page Specific JS] end -->
</body>
<!-- [Body] end -->

</html>
