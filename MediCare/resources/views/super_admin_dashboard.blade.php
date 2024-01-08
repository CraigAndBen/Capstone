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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


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
                                                        <img src="{{ asset('logo.jpg') }}"
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
                            <img src="{{ asset('logo.jpg') }}" alt="user-image"
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
                        <label>Calendar</label>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Calendar Holiday</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.calendar') }}">Calendar</a></li>
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
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.admin') }}">Admin</a>
                            </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.doctor') }}">Doctor</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.nurse') }}">Nurse</a>
                            </li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.user') }}">User</a>
                            </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.supply_officer') }}">Supply Officer</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.staff') }}">Staff</a>
                            </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.cashier') }}">Cashier</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.pharmacist') }}">Pharmacist</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Patient Analytics</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link"><span class="pc-micon"></span><span class="pc-mtext">Gender</span><span
                                class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.patient.gender') }}">All Patient</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.admitted.gender') }}">Admitted</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.outpatient.gender') }}">Outpatient</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link"><span class="pc-micon"></span><span class="pc-mtext">Age</span><span
                                class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.patient.age') }}">All Patient</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.admitted.age') }}">Admitted</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.outpatient.age') }}">Outpatient</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link"><span class="pc-micon"></span><span class="pc-mtext">Admitted</span><span
                                class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.admitted') }}">All Patient</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Outpatient</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.outpatient') }}">All Patient</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link"><span class="pc-micon"></span><span class="pc-mtext">Diagnose</span><span
                                class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.patient.diagnose') }}">All Patient</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.admitted.diagnose') }}">Admitted</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.outpatient.diagnose') }}">Outpatient</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link"><span class="pc-micon"></span><span class="pc-mtext">Diagnose
                                Trend</span><span class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.patient.diagnose_trend') }}">All Patient</a>
                            </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.admitted.diagnose_trend') }}">Admitted</a>
                            </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.outpatient.diagnose_trend') }}">Outpatient</a>
                            </li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Inventory Analytics </label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Overview Report</span><span class="pc-arrow"><i
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
                                href="{{ route('superadmin.medication.demo') }}">Medication</a>
                        </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.product.demo') }}">Item</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Item Report</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Expiry</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.product.expiration') }}">Expiring Item</a></li>

                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Report</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link"><span class="pc-micon"></span><span class="pc-mtext">Report
                                List</span><span class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.report.history') }}">Report History</a></li>
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

                <div class="col-xl-6 col-md-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            @if ($patientCount)
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <small>Total Patient This Year</small>
                                        <h3>{{ $patientCount }}</h3>
                                    </div>
                                </div>
                                <canvas id="patientChart" width="90%" height="36"></canvas>
                                <script>
                                    // PHP arrays to JavaScript variables
                                    const admittedMonths = @json($admittedPatientsByMonth->pluck('month'));
                                    const admittedCounts = @json($admittedPatientsByMonth->pluck('count'));

                                    const outpatientMonths = @json($outpatientPatientsByMonth->pluck('month'));
                                    const outpatientCounts = @json($outpatientPatientsByMonth->pluck('count'));

                                    // Create an array for all months
                                    const allMonths = [
                                        'January', 'February', 'March', 'April', 'May', 'June', 'July',
                                        'August', 'September', 'October', 'November', 'December'
                                    ];

                                    // Initialize arrays to store counts for admitted and outpatient data
                                    const admittedMonthCounts = Array.from({
                                        length: 12
                                    }, () => 0);
                                    const outpatientMonthCounts = Array.from({
                                        length: 12
                                    }, () => 0);

                                    // Fill in the counts for the corresponding months for admitted patients
                                    for (let i = 0; i < admittedMonths.length; i++) {
                                        const monthIndex = allMonths.indexOf(admittedMonths[i]);
                                        if (monthIndex !== -1) {
                                            admittedMonthCounts[monthIndex] = admittedCounts[i];
                                        }
                                    }

                                    // Fill in the counts for the corresponding months for outpatient patients
                                    for (let i = 0; i < outpatientMonths.length; i++) {
                                        const monthIndex = allMonths.indexOf(outpatientMonths[i]);
                                        if (monthIndex !== -1) {
                                            outpatientMonthCounts[monthIndex] = outpatientCounts[i];
                                        }
                                    }

                                    var ctx = document.getElementById('patientChart').getContext('2d');
                                    var myChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: allMonths,
                                            datasets: [{
                                                    label: 'Admitted Patient',
                                                    data: admittedMonthCounts,
                                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                                    borderColor: 'rgba(54, 162, 235, 1)',
                                                    borderWidth: 1
                                                },
                                                {
                                                    label: 'Outpatient',
                                                    data: outpatientMonthCounts,
                                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                    borderColor: 'rgba(255, 99, 132, 1)',
                                                    borderWidth: 1
                                                }
                                            ]
                                        },
                                        options: {
                                            title: {
                                                text: 'Patient Visits by Month'
                                            },
                                            scales: {
                                                y: {
                                                    beginAtZero: true,
                                                    ticks: {
                                                        stepSize: 1
                                                    }
                                                }
                                            },
                                            legend: {
                                                display: true
                                            }
                                        }
                                    });
                                </script>
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
                                        <h5>Patient Diagnoses This Year</h5>
                                    </div>
                                    <div class="col-auto"> </div>
                                </div>

                                <canvas id="diagnosisChart" width="100%" height="35"></canvas>
                                <div class="text-center my-3">
                                    <a href="{{ route('superadmin.analytics.patient.diagnose') }}"
                                        class="btn btn-primary">View
                                        all</a>
                                </div>
                            @else
                                <div class="text-center">
                                    <h3>No Patient Yet.</h3>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>

            <div class="row">
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
                                                borderColor: 'rgba(54, 162, 235, 1)', // Blue
                                                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Lighter blue fill
                                                borderWidth: 1,
                                                fill: true, // To fill the area under the line
                                                pointRadius: 5, // Adjust the size of data points on the line
                                                pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Blue data points
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
                                                    beginAtZero: true,
                                                    ticks: {
                                                        stepSize: 1
                                                    },
                                                }
                                            }
                                        }
                                    });
                                </script>
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
                                        <script>
                                            var ctx = document.getElementById('userRolesChart').getContext('2d');
                                            var labels = @json($usersLabels);
                                            var data = @json($usersData);

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



                <!-- [ sample-page ] end -->
            </div>

            <div class="row mt-2">
                <div class="col-xl-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($counts)
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <small>Item Movement</small>
                                        <h6>Segregates and ranked the items based on their consumption rate</h6>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6 mx-auto">
                                        <canvas id="productGraph"></canvas>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="classification">
                                                <strong>Fast Moving</strong>
                                                @if (count($fastProducts) > 0)
                                                    <ul>
                                                        @foreach ($fastProducts as $index => $product)
                                                            <li><strong> {{ $index + 1 }} -</strong>
                                                                {{ $product['name'] }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    No items in this classification.
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="classification">
                                                <strong>Slow Moving:</strong>
                                                @if (count($slowProducts) > 0)
                                                    <ul>
                                                        @foreach ($slowProducts as $index => $product)
                                                            <li><strong>{{ $index + 1 }} -
                                                                </strong>{{ $product['name'] }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    No items in this classification.
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="classification">
                                                <strong>Non-Moving:</strong>
                                                @if (count($nonMovingProducts) > 0)
                                                    <ul>
                                                        @foreach ($nonMovingProducts as $product)
                                                            <li>{{ $product['name'] }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    No items in this classification.
                                                @endif
                                            </div>
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
    var diagnosisData = @json($rankedDiagnosis);
    // Get the canvas element
    var ctx = document.getElementById('diagnosisChart').getContext('2d');

    // Extract data from PHP variable
    var months = [];
    var datasets = [];

    // Create an array with all months in the year
    for (let i = 1; i <= 12; i++) {
        months.push(moment(i, "M").format("MMMM"));
    }

    // Extract unique diagnoses from the data
    const uniqueDiagnoses = [...new Set(diagnosisData.map(item => item.diagnose))];

    const predefinedColors = ['red', 'blue', 'orange', 'green', 'violet'];
    
    let colorIndex = 0;

    // Create a dataset for each unique diagnosis
    uniqueDiagnoses.forEach(diagnose => {
        const data = [];
        for (let i = 1; i <= 12; i++) {
            const monthData = diagnosisData.find(item => item.month === i && item.diagnose === diagnose);
            if (monthData) {
                data.push(monthData.total_occurrences);
            } else {
                data.push(0);
            }
        }

        datasets.push({
            label: diagnose.charAt(0).toUpperCase() + diagnose.slice(1),
            data: data,
            fill: false,
            borderColor: predefinedColors[colorIndex],
        });

        colorIndex = (colorIndex + 1) % predefinedColors.length;
    });

    // Create the line chart
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: datasets,
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
<script>
    var categories = @json($categories);
    var counts = @json($counts);

    var ctx = document.getElementById('productGraph').getContext('2d');

    // Get the product counts as an array
    var productCounts = Object.values(counts);

    // Create an array to store the labels with counts
    var labelsWithCounts = [];
    for (var i = 0; i < categories.length; i++) {
        labelsWithCounts.push(categories[i] + ' (' + productCounts[i] + ')');
    }

    var chartData = {
        labels: labelsWithCounts, // Use labels with counts
        datasets: [{
            data: productCounts, // Use the product counts array
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 205, 86, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                // Add more colors if needed
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(255, 205, 86, 1)',
                'rgba(54, 162, 235, 1)',
                // Add more colors if needed
            ],
            borderWidth: 1, // Border width of the pie chart slices
        }],
    };

    var myChart = new Chart(ctx, {
        type: 'pie', // Use pie chart type
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Product Distribution',
                fontSize: 16,
            },
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    boxWidth: 12, // Adjust the box width of legend items
                    fontSize: 12, // Adjust the font size of legend items
                },
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var label = data.labels[tooltipItem.index] || '';
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        return label + ': ' + value;
                    },
                },
            },
            hover: {
                mode: 'nearest',
                intersect: true,
            },
        },
    });
</script>

</html>
