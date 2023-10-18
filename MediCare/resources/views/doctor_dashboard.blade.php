<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title> MediCare | Doctor Dashboard </title>
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
                            <div class="dropdown-header">
                                <h5>All Notification <span
                                        class="badge bg-warning rounded-pill ms-1">{{ $count }}</span></h5>
                            </div>
                            <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                                style="max-height: calc(100vh - 215px)">
                                <div class="list-group list-group-flush w-100">
                                    @foreach ($limitNotifications as $notification)
                                        <a class="list-group-item list-group-item-action"
                                            href="{{ route('doctor.notification') }}">
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
                                <a href="{{ route('doctor.notification') }}" class="btn btn-primary">Show all</a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <img src="{{ asset($info->image_data) }}" alt="user-image" class="user-avtar" />
                            <span>
                                <i class="ti ti-settings"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">

                                <?php
                                // Assuming $currentTime is in the format "H:i" (24-hour format)
                                $currentHour = date('H', strtotime($currentTime));
                                
                                if ($currentHour >= 6 && $currentHour < 12) {
                                    // It's morning (between 6 AM and 12 PM)
                                    $greeting = 'Good Morning';
                                } elseif ($currentHour >= 12 && $currentHour < 17) {
                                    // It's afternoon (between 12 PM and 5 PM)
                                    $greeting = 'Good Afternoon';
                                } else {
                                    // It's evening or night
                                    $greeting = 'Good Evening';
                                }
                                ?>

                                <h4>{{ $greeting }}, <span class="small text-muted">Dr.
                                        {{ ucwords($profile->first_name) }}
                                        {{ ucwords($profile->last_name) }}</span>
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
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Appointment Calendar</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('doctor.appointment.calendar') }}">Calendar</a></li>
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
                            <li class="pc-item"><a class="pc-link" href="{{ route('doctor.patient') }}">All
                                    Patient</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('doctor.admitted') }}">Admitted
                                    Patient</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('doctor.outpatient') }}">OutPatient List</a></li>
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
                        @if ($patientCount)
                            <div class="card-body">
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <small>Total Admitted Patient This Year</small>
                                        <h3>{{ $patientCount }}</h3>
                                    </div>
                                </div>
                                <canvas id="patientChart" width="100%" height="87"></canvas>
                                <script>
                                    // PHP array to JavaScript variables
                                    const patientMonths = @json($patientsByMonth->pluck('month'));
                                    const patientCounts = @json($patientsByMonth->pluck('count'));

                                    // Create an array for all months
                                    const allMonths = [
                                        'January', 'February', 'March', 'April', 'May', 'June', 'July',
                                        'August', 'September', 'October', 'November', 'December'
                                    ];

                                    // Initialize an array to store counts for each month
                                    const monthCounts = Array.from({
                                        length: 12
                                    }, () => 0);

                                    // Fill in the counts for the corresponding months
                                    for (let i = 0; i < patientMonths.length; i++) {
                                        const monthIndex = allMonths.indexOf(patientMonths[i]);
                                        if (monthIndex !== -1) {
                                            monthCounts[monthIndex] = patientCounts[i];
                                        }
                                    }

                                    var ctx = document.getElementById('patientChart').getContext('2d');
                                    var myChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: allMonths,
                                            datasets: [{
                                                label: 'Admitted Patient',
                                                data: monthCounts,
                                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                                borderColor: 'rgba(54, 162, 235, 1)',
                                                borderWidth: 1
                                            }]
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
                            </div>
                        @else
                            <div class="card-body">
                                <div class="text-center">
                                    <h3>No Patient Yet.</h3>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="col-xl-6 col-md-12 mt-4">
                    <div class="card">
                        @if ($appointmentCount)
                            <div class="card-body">
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <h5>Appointment This Year</h5>
                                    </div>
                                    <div class="col-auto"> </div>
                                </div>
                                <canvas id="appointmentChart"></canvas>
                                <hr>
                                <div class="row mb-3 align-items-center">
                                    <div class="col">
                                        <h5>Current Appointments</h5>
                                    </div>
                                    <div class="col-auto"> </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($limitCurrentMonthAppointments as $appointment)
                                                <tr>
                                                    <td>{{ ucwords($appointment->first_name) }}
                                                        {{ ucwords($appointment->last_name) }}</td>
                                                    <td>{{ ucwords($appointment->appointment_type) }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                                    </td>
                                                    <td>{{ ucwords($appointment->appointment_time) }}</td>
                                                    <td>{{ ucwords($appointment->status) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('doctor.appointment') }}" class="btn btn-primary">View all</a>
                                </div>
                            </div>
                        @else
                            <div class="card-body">
                                <div class="text-center">
                                    <h3>No Appointment Yet.</h3>
                                </div>
                            </div>
                        @endif


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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<!-- [Body] end -->
<script>
    // Convert the PHP array to JavaScript variables
    const appointmentMonths = @json($months);
    const appointmentCounts = @json($appointmentCounts);

    var ctx = document.getElementById('appointmentChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: appointmentMonths,
            datasets: [{
                label: 'Monthly Appointments',
                data: appointmentCounts,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: false
            }]
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

</html>
