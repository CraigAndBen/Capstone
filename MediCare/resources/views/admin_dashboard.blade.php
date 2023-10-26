<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title> MediCare | Admin Dashboard </title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

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

            <a href="{{ route('admin.dashboard') }}" class="logo me-auto"><img src="{{ asset('logo.jpg') }}"
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
                            <h5><i>{{ date('M j, Y', strtotime($currentDate)) }} | {{ $currentTime }}</i></h5>
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
                                        @foreach ($limitNotifications as $notification)
                                            <a class="list-group-item list-group-item-action"
                                                href="{{ route('admin.notification') }}">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <br>
                                                        <img src="{{ asset('admin_assets/images/user/avatar-2.jpg') }}"
                                                            alt="user-image" class="user-avtar" />
                                                    </div>
                                                    <div class="flex-grow-1 ms-1">
                                                        <span class="float-end text-muted">
                                                            {{ date('M j, Y', strtotime($notification->date)) }}
                                                            {{ date('h:i A', strtotime($notification->time)) }}
                                                        </span>
                                                        <br>
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
                                    <a href="{{ route('admin.notification') }}" class="btn btn-primary">Show all</a>
                                </div>
                            @else
                                <div class="dropdown-header">
                                    <h5>All Notification <span class="badge bg-warning rounded-pill ms-1">0</span></h5>
                                </div>
                                <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                                    style="max-height: calc(100vh - 215px)">
                                    <div class="list-group list-group-flush w-100">
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
                                    <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                        <i class="ti ti-settings"></i>
                                        <span>Account Settings</span>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <i class="ti ti-user"></i>
                                        <span>Social Profile</span>
                                    </a>
                                    <a href="{{ route('admin.logout') }}" class="dropdown-item">
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
                    <a href="{{ route('admin.dashboard') }}" class="logo me-auto"><img
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
                        <a href="{{ route('admin.dashboard') }}" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Home</span></a>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Account Settings</label>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Update Account</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('admin.profile') }}">Update
                                    Profile</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.profile.password') }}">Update Password</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Notification</label>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link"><span class="pc-micon"></span><span class="pc-mtext">Notification
                                List</span><span class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.notification') }}">Notification List</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Analytics</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Patient</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.gender') }}">Gender Demographics</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('admin.demographics.age') }}">Age
                                    Demographics</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.admitted') }}">Admit Demographics</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.outpatient') }}">Outpatient Demographics</a>
                            </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.diagnose') }}">Diagnose Demographics</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Admitted Patient</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.gender') }}">Gender Demographics</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('admin.demographics.age') }}">Age
                                    Demographics</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.admitted') }}">Admit Demographics</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.outpatient') }}">Outpatient Demographics</a>
                            </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.diagnose') }}">Diagnose Demographics</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Outpatient</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.gender') }}">Gender Demographics</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('admin.demographics.age') }}">Age
                                    Demographics</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.admitted') }}">Admit Demographics</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.outpatient') }}">Outpatient Demographics</a>
                            </li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('admin.demographics.diagnose') }}">Diagnose Demographics</a></li>
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
                                    href="{{ route('admin.trend.diagnose') }}">Diagnose Rising Trend</a></li>
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

                <div class="col-xl-6 col-md-12 mt-4">
                    <div class="card">
                        <div class="card-body">
                            @if ($patientCount)
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <small>Total Admitted Patient This Year</small>
                                        <h3>{{ $patientCount }}</h3>
                                    </div>
                                </div>
                                <canvas id="patientChart" width="100%" height="85"></canvas>
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
                                                        stepSize: 2
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

                <div class="col-xl-6 col-md-12 mt-4">
                    <div class="card">
                        <div class="card-body">
                            @if ($diagnosisCount)
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <h5>Patient Diagnoses This Year</h5>
                                    </div>
                                    <div class="col-auto"> </div>
                                </div>
                                <canvas id="diagnosisChart"></canvas>

                                <ul class="list-group list-group-flush mt-3">
                                    @foreach ($diagnosesWithOccurrences as $diagnosis)
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-start">
                                                <div class="col">
                                                    <h5 class="mb-0">{{ ucwords($diagnosis->diagnose) }}</h5>
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
                                <div class="text-center my-3">
                                    <a href="{{ route('admin.demographics.diagnose') }}" class="btn btn-primary">View
                                        all</a>
                                </div>
                            @else
                                <div class="text-center my-3">
                                    <h3>No Diagnosis Yet.</h3>
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
    {{-- <script src="{{ asset('admin_assets/js/plugins/apexcharts.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('admin_assets/js/pages/dashboard-default.js') }}"></script> --}}
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
            label: diagnose,
            data: data,
            fill: false,
            borderColor: getRandomColor(), // You can define a function to get different colors
        });
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
                    beginAtZero: true
                }
            }
        }
    });

    // Function to generate random colors for each diagnosis
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
</script>

</html>
