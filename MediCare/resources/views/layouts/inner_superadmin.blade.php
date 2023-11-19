<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>MediCare | Super Admin Dashboard</title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <script  src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script  src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script  src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>



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
                    </li>
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
                                    <a href="{{ route('superadmin.notification') }}" class="btn btn-primary">Show all</a>
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
                                <h4>Good Morning, <span class="small text-muted">{{ $profile->first_name }}</span>
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
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.admin') }}">Admin</a>
                            </li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.doctor') }}">Doctor</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.nurse') }}">Nurse</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.user') }}">User</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.supply_officer') }}">Supply Officer</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.staff') }}">Staff</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.cashier') }}">Cashier</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.pharmacist') }}">Pharmacist</a></li>
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
                                    href="{{ route('superadmin.analytics.patient.diagnose_trend') }}">All Patient</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.admitted.diagnose_trend') }}">Admitted</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.analytics.outpatient.diagnose_trend') }}">Outpatient</a>
                            </li>
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
                                    href="{{ route('superadmin.product.demo') }}">Item</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"></span><span
                                class="pc-mtext">Expiry</span><span class="pc-arrow"><i
                                    class="ti ti-chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('superadmin.product.expiration') }}">Expiring Items</a></li>

                        </ul>
                    </li>
                    <li class="pc-item pc-caption">
                        <label>Report</label>
                        <i class="ti ti-apps"></i>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link"><span class="pc-micon"></span><span class="pc-mtext">Report List</span><span
                                class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
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

    @yield('content')

    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                {{-- <div class="col my-1">
                    <p class="m-0">Copyright &copy; <a>MediCare</a></p>
                </div>
                <div class="col-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                        <li class="list-inline-item">Home</li>
                        <li class="list-inline-item">Privacy Policy</li>
                        <li class="list-inline-item">Contact us</li>
                    </ul>
                </div> --}}
            </div>
        </div>
    </footer>
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="{{ asset('admin_assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/config.js') }}"></script>
    <script src="{{ asset('admin_assets/js/pcoded.js') }}"></script>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- [Body] end -->
@yield('scripts')
<script>
    new DataTable('#patientTable');
</script>
</html>
