<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title> MediCare | Super Admin Dashboard </title>
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

            <a href="{{ route('superadmin.dashboard') }}" class="logo me-auto"><img src="{{ asset('logo.jpg') }}"
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
                            <h5><i>{{ $currentDate }} | {{ $currentTime }}</i></h5>
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
                                                href="{{ route('superadmin.notification') }}">
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
                                    <a href="{{ route('superadmin.notification') }}" class="btn btn-primary">Show
                                        all</a>
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
                                <h4>Hi, Good Day! <span
                                        class="small text-muted">{{ ucwords($profile->first_name) }}</span>
                                </h4>
                                <div class="profile-notification-scroll position-relative"
                                    style="max-height: calc(100vh - 280px)">
                                    <a href="{{ route('superadmin.profile') }}" class="dropdown-item">
                                        <i class="ti ti-settings"></i>
                                        <span>Account Settings</span>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <i class="ti ti-user"></i>
                                        <span>Social Profile</span>
                                    </a>
                                    <a href="{{ route('superadmin.logout') }}" class="dropdown-item">
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
                    <a href="{{ route('superadmin.dashboard') }}" class="logo me-auto"><img
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
                        <a href="{{ route('superadmin.dashboard') }}" class="pc-link"><span
                                class="pc-micon"></span><span class="pc-mtext">Home</span></a>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Account Settings</label>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Update Account</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.profile') }}">Update
                                    Profile</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.profile.password') }}">Update Password</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Notification</label>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Notification List</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.notification') }}">Notification</a></li>
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
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.appointment') }}">Appointment</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>System Account</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Accounts</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.admin') }}">Admin
                                    Account</a>
                            </li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.doctor') }}">Doctor
                                    Account</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.nurse') }}">Nurse
                                    Account</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.user') }}">User
                                    Account</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.supply_officer') }}">Supply Officer
                                    Account</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.staff') }}">Staff
                                    Account</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.cashier') }}">Cashier
                                    Account</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.pharmacist') }}">Pharmacist
                                    Account</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Patient</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Patient List</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.patient') }}">Patient</a>
                            </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.patient.admitted') }}">Patient Admitted</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.patient.outpatient') }}">Outpatient</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Demographics</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Patient Demographics</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.demographics.gender') }}">Gender Demographics</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.demographics.age') }}">Age
                                    Demographics</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.demographics.admitted') }}">Admitted Demographics</a>
                            </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.demographics.outpatient') }}">Outpatient
                                    Demographics</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.demographics.appointment') }}">Appointment
                                    Demographics</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.demographics.diagnose') }}">Diagnose Demographics</a>
                            </li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Trend</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Diagnose Trend</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.trend.diagnose') }}">Diagnose Rising Trend</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Analytics Report</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Analytics</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.inventory.demo') }}">Inventory</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.sale.demo') }}">Sales</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.request.demo') }}">Request</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.medicine.demo') }}">Medicine</a>
                            </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.product.demo') }}">Product</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Expiry</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.product.expiration') }}">Expiring Product</a></li>

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
            <div class="row mt-2">
                <!-- [ sample-page ] start -->

                {{-- <div class="col-xl-6 col-md-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            @if ($patientCount)
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <small>Total Patient This Year</small>
                                        <h3>{{ $patientCount }}</h3>
                                    </div>
                                </div>
                                <canvas id="patientChart" width="100%" height="40"></canvas>
                            @else
                                <div class="text-center">
                                    <h3>No Patient Yet.</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            @if ($diagnosisCount)
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <h5>Patient Diagnosis This Year</h5>
                                    </div>
                                    <div class="col-auto"> </div>
                                </div>

                                <canvas id="diagnosisChart" width="100%" height="23"></canvas>
                                <ul class="list-group list-group-flush mt-3">
                                    @foreach ($diagnosesWithOccurrences as $diagnosis)
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <!-- Use align-items-center for vertical alignment -->
                                                <div class="col">
                                                    <h6 class="mb-0"> <!-- Use h6 for smaller text -->
                                                        {{ ucwords($diagnosis->diagnosis) }}
                                                    </h6>
                                                </div>
                                                <div class="col-auto">
                                                    <h6 class="mb-0"> <!-- Use h6 for smaller text -->
                                                        {{ $diagnosis->total_occurrences }}
                                                        <span class="ms-1 avtar avtar-xxs bg-light-success">
                                                            <i class="ti ti-chevron-up text-success"></i>
                                                        </span>
                                                    </h6>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="text-center">
                                    <a href="{{ route('superadmin.demographics.diagnose') }}"
                                        class="btn btn-primary">View all</a>
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

            <div class="row mt-2">
                <!-- [ sample-page ] start -->

                <div class="col-xl-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($appointmentCount)
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <small>Total Appointment This Year</small>
                                        <h3>{{ $appointmentCount }}</h3>
                                    </div>
                                </div>
                                <canvas id="appointmentChart" width="100%" height="42"></canvas>
                            @else
                                <div class="text-center">
                                    <h3>No Appointment Yet.</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($rolesCount)
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <small>Total Users</small>
                                        <h3>{{ $rolesCount }}</h3>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6 mx-auto">
                                        <canvas id="userRolesChart"></canvas>
                                    </div>
                                </div>
                            @else
                                <div class="text-center">
                                    <h3>No Users Yet.</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($totalCount)
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <small>Medicine Value</small>
                                        <h6>Based on medicine product price</h6>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6 mx-auto">
                                        <canvas id="medicineGraph"></canvas>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong style="font-size: 14px;">Most Valued:</strong>
                                            @if (count($mostValuedProducts) > 0)
                                                <ul style="font-size: 12px;">
                                                    @foreach ($mostValuedProducts as $product)
                                                        <li>{{ $product }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                No products in this classification.
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <strong style="font-size: 14px;">Medium Valued:</strong>
                                            @if (count($mediumValuedProducts) > 0)
                                                <ul style="font-size: 12px;">
                                                    @foreach ($mediumValuedProducts as $product)
                                                        <li>{{ $product }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                No products in this classification.
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <strong style="font-size: 14px;">Low Valued:</strong>
                                            @if (count($lowValuedProducts) > 0)
                                                <ul style="font-size: 12px;">
                                                    @foreach ($lowValuedProducts as $product)
                                                        <li>{{ $product }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                No products in this classification.
                                            @endif
                                        </div>
                                    </div>
                                    
                                </div>
                            @else
                                <div class="text-center">
                                    <h3>No Medicine Yet.</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
               

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
                    <p class="m-0">Copyright &copy; <a>MediCare</a></p>
                </div>
                <div class="col-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                        <li class="list-inline-item">Home</li>
                        <li class="list-inline-item">Privacy Policy</li>
                        <li class="list-inline-item">Contact us</li>
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
    {{-- <script src="{{ asset('admin_assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/pages/dashboard-default.js') }}"></script> --}}
    <!-- [Page Specific JS] end -->
</body>
<!-- [Body] end -->
<script>

    var ctx = document.getElementById('appointmentChart').getContext('2d');
    var labels = @json($appointmentLabels);
    var data = @json($appointmentData);


    // Initialize an array to hold the data for all months, initially filled with zeros
    var allMonthsData = Array.from({
        length: 12
    }, () => 0);

    // Fill in the data for the corresponding months
    for (var i = 0; i < labels.length; i++) {
        var date = new Date(labels[i]);
        var monthIndex = date.getMonth();
        allMonthsData[monthIndex] = data[i];
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: allMonths, // Use allMonths as labels
            datasets: [{
                label: 'Appointment Count',
                data: allMonthsData, // Use allMonthsData as data
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'category',
                    beginAtZero: true,
                    min: 'January', // Specify the minimum label
                    max: 'December', // Specify the maximum label
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script>
    var ctx = document.getElementById('userRolesChart').getContext('2d');
    var labels = @json($usersLabels);
    var data = @json($usersData);

    console.log('yow');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false // Allow the chart to resize based on the container
        }
    });
</script>
<script>
    var ctx = document.getElementById('medicineGraph').getContext('2d');
    var medicineGraph = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [
                'Most Valued ' + {{ $mostValuedPercentage }} + '%',
                'Medium Valued ' + {{ $mediumValuedPercentage }} + '%',
                'Low Valued ' + {{ $lowValuedPercentage }} + '%'
            ],
            datasets: [{
                data: [
                    {{ $mostValuedPercentage }},
                    {{ $mediumValuedPercentage }},
                    {{ $lowValuedPercentage }}
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)', // Green for Most Valued
                    'rgba(54, 162, 235, 0.7)', // Blue for Medium Valued
                    'rgba(255, 99, 132, 0.7)'  // Red for Low Valued
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script>
</html>
