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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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
                            <img src="{{asset($info->image_data)}}" alt="user-image"
                                class="user-avtar" />
                            <span>
                                <i class="ti ti-settings"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <h4>Good Morning, <span class="small text-muted">{{ucwords($profile->first_name)}} {{ucwords($profile->last_name)}}</span>
                                </h4>
                                <hr>
                                <div class="profile-notification-scroll position-relative"
                                    style="max-height: calc(100vh - 280px)">
                                    <a href="{{ route('doctor.profile') }}" class="dropdown-item">
                                        <i class="ti ti-settings"></i>
                                        <span>Account Settings</span>
                                    </a>
                                    <a href="{{ route('doctor.social') }}" class="dropdown-item">
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
                        <label>Account</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Profile Update</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('doctor.profile') }}">Update
                                    Profile</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('doctor.social') }}">Update Social
                                    Profile</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('doctor.profile.password') }}">Update Profile Password</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Notification</label>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Notifcations</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('doctor.notification') }}">Notification List</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Appointment</label>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Appointment List</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('doctor.appointment') }}">All
                                    Appointment</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('doctor.appointment.confirmed') }}">Confirmed List</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('doctor.appointment.done') }}">Done List</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Patient</label>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Patient List</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('doctor.patient') }}">
                                    All Patient</a></li>
                        </ul>
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
                {{-- <div class="col-xl-4 col-md-6">
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
                </div> --}}

                <div class="col-xl-6 col-md-12 mt-4">
                    <div class="card">
                        <div class="card-body">
                            @if ($patientCount)
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <small>Total Patient This Year</small>
                                        <h3>{{ $patientCount }}</h3>
                                    </div>
                                </div>
                                <canvas id="patientChart" width="100%" height="95"></canvas>
                            @else
                                <div class="text-center">
                                    <h3>No Patient Yet.</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- <div class="col-xl-6 col-md-12 mt-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3 align-items-center">
                                <div class="col">
                                    <h5>Patient Diagnosis This Year</h5>
                                </div>
                                <div class="col-auto"> </div>
                            </div>
                            @if ($rankedDiagnosis)
                                <canvas id="diagnosisChart"></canvas>

                                <ul class="list-group list-group-flush mt-3">
                                    @foreach ($rankedDiagnosis as $diagnosis)
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-start">
                                                <div class="col">
                                                    <h5 class="mb-0">{{ $diagnosis->diagnosis }}</h5>
                                                </div>
                                                <div class="col-auto">
                                                    <h5 class="mb-0">{{ $diagnosis->total_occurrences }}<span
                                                            class="ms-2 align-top avtar avtar-xxs bg-light-success"><i
                                                                class="ti ti-chevron-up text-success"></i></span></h5>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="text-center">
                                    <a href="#!" class="btn btn-primary">View all</a>
                                </div>
                            @else
                                <div class="text-center">
                                    <h3>No Patient Yet.</h3>
                                </div>
                            @endif

                        </div>
                    </div>
                </div> --}}
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
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
     // Convert the PHP array to JavaScript variables
     const months = @json($patientsByMonth->pluck('month'));
        const patientCounts = @json($patientsByMonth->pluck('count'));

        var ctx = document.getElementById('patientChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Patient',
                    data: patientCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
</script>

</html>
