<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title> MediCare | Doctor Dashboard </title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('admin_assets/images/favicon.svg') }}" type="image/x-icon" />
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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

            <a href="{{ route('doctor.dashboard') }}" class="logo me-auto"><img src="{{ asset('logo.jpg') }}"
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
                        <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <i class="ti ti-bell"></i>
                        </a>
                        <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <h5>All Notification <span class="badge bg-warning rounded-pill ms-1">{{$count}}</span></h5>
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
                                            href="{{ route('doctor.appointment') }}">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('admin_assets/images/user/avatar-2.jpg') }}"
                                                        alt="user-image" class="user-avtar" />
                                                </div>
                                                <div class="flex-grow-1 ms-1">
                                                    <span class="float-end text-muted">{{ $notification->time }}</span>
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
                                <a href="{{ route('doctor.appointment') }}" class="btn btn-primary">Show all</a>
                            </div>
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
                                <p class="text-muted">{{ $profile->role }}</p>
                                <div class="profile-notification-scroll position-relative"
                                    style="max-height: calc(100vh - 280px)">
                                    <a href="{{ route('doctor.profile') }}" class="dropdown-item">
                                        <i class="ti ti-settings"></i>
                                        <span>Account Settings</span>
                                    </a>
                                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateModal">
                                        <i class="ti ti-user"></i>
                                        <span>Social Profile</span>
                                    </a>
                                    <a href="{{ route('doctor.logout') }}" class="dropdown-item">
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
                    <a href="{{ route('doctor.dashboard') }}" class="logo me-auto"><img
                            src="{{ asset('logo.jpg') }}" alt="" class=""
                            style="max-width: 150px; max-height: 90px"></a>
                </div>

            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item pc-caption">
                        <label>Dashboard</label>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('doctor.dashboard') }}" class="pc-link"><span class="pc-micon"><i
                                    class="ti ti-dashboard"></i></span><span class="pc-mtext">Home</span></a>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Account Settings</label>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('doctor.profile') }}" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Update Profile</span></a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('doctor.profile.password') }}" class="pc-link"><span
                                class="pc-micon"></span><span class="pc-mtext">Update Profile Password</span></a>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Notification</label>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('doctor.notification') }}" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Notification List</span></a>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Appointment</label>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('doctor.appointment') }}" class="pc-link"><span
                                class="pc-micon"></span><span class="pc-mtext">Appointment List</span></a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('doctor.appointment.confirmed') }}" class="pc-link"><span
                                class="pc-micon"></span><span class="pc-mtext">Confirmed List</span></a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('doctor.appointment.done') }}" class="pc-link"><span
                                class="pc-micon"></span><span class="pc-mtext">Done List</span></a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Patient</label>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('doctor.patient') }}" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Patient List</span></a>
                    </li>
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
                <div class="col-xl-4 col-md-6">
                    <div class="card bg-secondary-dark dashnum-card text-white overflow-hidden">
                        <span class="round small"></span>
                        <span class="round big"></span>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="avtar avtar-lg">
                                        <i class="text-white ti ti-credit-card"></i>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="btn-group">
                                        <a type="button" class="avtar bg-secondary dropdown-toggle arrow-none"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><button class="dropdown-item" type="button">Import Card</button></li>
                                            <li><button class="dropdown-item" type="button">Export</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <span class="text-white d-block f-34 f-w-500 my-2">1350 <i
                                    class="ti ti-arrow-up-right-circle opacity-50"></i></span>
                            <p class="mb-0 opacity-50">Total Pending Orders</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card bg-primary-dark dashnum-card text-white overflow-hidden">
                        <span class="round small"></span>
                        <span class="round big"></span>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="avtar avtar-lg">
                                        <i class="text-white ti ti-credit-card"></i>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <ul class="nav nav-pills justify-content-end mb-0" id="chart-tab-tab"
                                        role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link text-white active" id="chart-tab-home-tab"
                                                data-bs-toggle="pill" data-bs-target="#chart-tab-home" type="button"
                                                role="tab" aria-controls="chart-tab-home"
                                                aria-selected="true">Month</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link text-white" id="chart-tab-profile-tab"
                                                data-bs-toggle="pill" data-bs-target="#chart-tab-profile"
                                                type="button" role="tab" aria-controls="chart-tab-profile"
                                                aria-selected="false">Year</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-content" id="chart-tab-tabContent">
                                <div class="tab-pane show active" id="chart-tab-home" role="tabpanel"
                                    aria-labelledby="chart-tab-home-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="text-white d-block f-34 f-w-500 my-2">$130<i
                                                    class="ti ti-arrow-up-right-circle opacity-50"></i></span>
                                            <p class="mb-0 opacity-50">Total Earning</p>
                                        </div>
                                        <div class="col-6">
                                            <div id="tab-chart-1"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="chart-tab-profile" role="tabpanel"
                                    aria-labelledby="chart-tab-profile-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="text-white d-block f-34 f-w-500 my-2">$29961 <i
                                                    class="ti ti-arrow-down-right-circle opacity-50"></i></span>
                                            <p class="mb-0 opacity-50">C/W Last Year</p>
                                        </div>
                                        <div class="col-6">
                                            <div id="tab-chart-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-12">
                    <div class="card bg-primary-dark dashnum-card dashnum-card-small text-white overflow-hidden">
                        <span class="round bg-primary small"></span>
                        <span class="round bg-primary big"></span>
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="avtar avtar-lg">
                                    <i class="text-white ti ti-credit-card"></i>
                                </div>
                                <div class="ms-2">
                                    <h4 class="text-white mb-1">$203k <i
                                            class="ti ti-arrow-up-right-circle opacity-50"></i></h4>
                                    <p class="mb-0 opacity-50 text-sm">Net Profit</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card dashnum-card dashnum-card-small overflow-hidden">
                        <span class="round bg-warning small"></span>
                        <span class="round bg-warning big"></span>
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="avtar avtar-lg bg-light-warning">
                                    <i class="text-warning ti ti-credit-card"></i>
                                </div>
                                <div class="ms-2">
                                    <h4 class="mb-1">$550K <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                                    </h4>
                                    <p class="mb-0 opacity-50 text-sm">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3 align-items-center">
                                <div class="col">
                                    <small>Total Growth</small>
                                    <h3>$2,324.00</h3>
                                </div>
                                <div class="col-auto">
                                    <select class="form-select p-r-35">
                                        <option>Today</option>
                                        <option selected>This Month</option>
                                        <option>This Year</option>
                                    </select>
                                </div>
                            </div>
                            <div id="growthchart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3 align-items-center">
                                <div class="col">
                                    <h4>Popular Stocks</h4>
                                </div>
                                <div class="col-auto"> </div>
                            </div>
                            <div class="rounded bg-light-secondary overflow-hidden mb-3">
                                <div class="px-3 pt-3">
                                    <div class="row mb-1 align-items-start">
                                        <div class="col">
                                            <h5 class="text-secondary mb-0">Bajaj Finery</h5>
                                            <small class="text-muted">10% Profit</small>
                                        </div>
                                        <div class="col-auto">
                                            <h4 class="mb-0">$1839.00</h4>
                                        </div>
                                    </div>
                                </div>
                                <div id="bajajchart"></div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <div class="row align-items-start">
                                        <div class="col">
                                            <h5 class="mb-0">Bajaj Finery</h5>
                                            <small class="text-success">10% Profit</small>
                                        </div>
                                        <div class="col-auto">
                                            <h4 class="mb-0">$1839.00<span
                                                    class="ms-2 align-top avtar avtar-xxs bg-light-success"><i
                                                        class="ti ti-chevron-up text-success"></i></span></h4>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="row align-items-start">
                                        <div class="col">
                                            <h5 class="mb-0">TTML</h5>
                                            <small class="text-danger">10% Profit</small>
                                        </div>
                                        <div class="col-auto">
                                            <h4 class="mb-0">$100.00<span
                                                    class="ms-2 align-top avtar avtar-xxs bg-light-danger"><i
                                                        class="ti ti-chevron-down text-danger"></i></span></h4>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="row align-items-start">
                                        <div class="col">
                                            <h5 class="mb-0">Reliance</h5>
                                            <small class="text-success">10% Profit</small>
                                        </div>
                                        <div class="col-auto">
                                            <h4 class="mb-0">$200.00<span
                                                    class="ms-2 align-top avtar avtar-xxs bg-light-success"><i
                                                        class="ti ti-chevron-up text-success"></i></span></h4>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="row align-items-start">
                                        <div class="col">
                                            <h5 class="mb-0">TTML</h5>
                                            <small class="text-danger">10% Profit</small>
                                        </div>
                                        <div class="col-auto">
                                            <h4 class="mb-0">$189.00<span
                                                    class="ms-2 align-top avtar avtar-xxs bg-light-danger"><i
                                                        class="ti ti-chevron-down text-danger"></i></span></h4>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="row align-items-start">
                                        <div class="col">
                                            <h5 class="mb-0">Stolon</h5>
                                            <small class="text-danger">10% Profit</small>
                                        </div>
                                        <div class="col-auto">
                                            <h4 class="mb-0">$189.00<span
                                                    class="ms-2 align-top avtar avtar-xxs bg-light-danger"><i
                                                        class="ti ti-chevron-down text-danger"></i></span></h4>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="#!" class="b-b-primary text-primary">View all <i
                                        class="ti ti-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h2 class="modal-title text-light" id="myModalLabel">Update Account</h2>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('doctor.profile.update') }}">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" name="facebook" class="form-control"
                                id="facebook" placeholder="Facebook Link"  />
                            <label for="floatingInput">Facebook Link</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="twitter" class="form-control"
                                id="twitter" placeholder="Twitter Link"  />
                            <label for="floatingInput">Twitter Link</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="instagram" class="form-control"
                                id="instagram" placeholder="Instagram Link"  />
                            <label for="floatingInput">Instagram Link</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="linkedin" class="form-control"
                                id="linkedin" placeholder="Linkedin Link"  />
                            <label for="floatingInput">Linkedin Link</label> 
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
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
            </div>
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
    <script src="{{ asset('admin_assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/pages/dashboard-default.js') }}"></script>
    <!-- [Page Specific JS] end -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<!-- [Body] end -->
<script>

</script>

</html>
