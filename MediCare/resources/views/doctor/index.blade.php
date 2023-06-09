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
    <link rel="icon" href="{{asset('admin_assets/images/favicon.svg')}}" type="image/x-icon" />
    <!-- [Google Font] Family -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" id="main-font-link" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{asset('admin_assets/fonts/tabler-icons.min.css')}}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{asset('admin_assets/fonts/material.css')}}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{asset('admin_assets/css/style.css')}}" id="main-style-link" />
    <link rel="stylesheet" href="{{asset('admin_assets/css/style-preset.css')}}" id="preset-style-link" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

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

            <a href="/admin/dashboard" class="logo me-auto"><img src="{{asset('logo.jpg')}}" alt="" class="" style="max-width: 150px; max-height: 90px"></a>
            <!-- ======= Menu collapse Icon ===== -->
        </div>
        <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
            <li class="pc-h-item header-mobile-collapse">
            <a href="#" class="pc-head-link head-link-secondary ms-0" id="mobile-collapse">
                <i class="ti ti-menu-2"></i>
            </a>
            </li>
            <li class="dropdown pc-h-item d-inline-flex d-md-none">
            <a class="pc-head-link head-link-secondary dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#"
                role="button" aria-haspopup="false" aria-expanded="false">
                <i class="ti ti-search"></i>
            </a>
            <div class="dropdown-menu pc-h-dropdown drp-search">
                <form class="px-3">
                <div class="form-group mb-0 d-flex align-items-center">
                    <i class="ti ti-search"></i>
                    <input type="search" class="form-control border-0 shadow-none" placeholder="Search here..." />
                </div>
                </form>
            </div>
            </li>
            <li class="pc-h-item d-none d-md-inline-flex">
            <form class="header-search">
                <i class="ti ti-search icon-search"></i>
                <input type="search" class="form-control" placeholder="Search here..." />
                <button class="btn btn-light-secondary btn-search"><i class="ti ti-adjustments-horizontal"></i></button>
            </form>
            </li>
        </ul>
        </div>
        <!-- [Mobile Media Block end] -->
        <div class="ms-auto">
        <ul class="list-unstyled">
            <li class="dropdown pc-h-item">
            <a class="pc-head-link head-link-secondary dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                role="button" aria-haspopup="false" aria-expanded="false">
                <i class="ti ti-bell"></i>
            </a>
            <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                <div class="dropdown-header">
                <a href="#!" class="link-primary float-end text-decoration-underline">Mark as all read</a>
                <h5>All Notification <span class="badge bg-warning rounded-pill ms-1">01</span></h5>
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
                        <img src="{{asset('admin_assets/images/user/avatar-2.jpg')}}" alt="user-image" class="user-avtar" />
                        </div>
                        <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">2 min ago</span>
                        <h5>John Doe</h5>
                        <p class="text-body fs-6">It is a long established fact that a reader will be distracted </p>
                        <div class="badge rounded-pill bg-light-danger">Unread</div>
                        <div class="badge rounded-pill bg-light-warning">New</div>
                        </div>
                    </div>
                    </a>
                    <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                        <div class="user-avtar bg-light-success"><i class="ti ti-building-store"></i></div>
                        </div>
                        <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">3 min ago</span>
                        <h5>Store Verification Done</h5>
                        <p class="text-body fs-6">We have successfully received your request.</p>
                        <div class="badge rounded-pill bg-light-danger">Unread</div>
                        </div>
                    </div>
                    </a>
                    <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                        <div class="user-avtar bg-light-primary"><i class="ti ti-mailbox"></i></div>
                        </div>
                        <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">5 min ago</span>
                        <h5>Check Your Mail.</h5>
                        <p class="text-body fs-6">All done! Now check your inbox as you're in for a sweet treat! </p>
                        <button class="btn btn-sm btn-primary">Mail <i class="ti ti-brand-telegram"></i></button>
                        </div>
                    </div>
                    </a>
                    <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                        <img src="{{asset('admin_assets/images/user/avatar-1.jpg')}}" alt="user-image" class="user-avtar" />
                        </div>
                        <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">8 min ago</span>
                        <h5>John Doe</h5>
                        <p class="text-body fs-6">Uploaded two file on &nbsp;<strong>21 Jan 2020</strong></p>
                        <div class="notification-file d-flex p-3 bg-light-secondary rounded">
                            <i class="ti ti-arrow-bar-to-down"></i>
                            <h5 class="m-0">demo.jpg</h5>
                        </div>
                        </div>
                    </div>
                    </a>
                    <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                        <img src="{{asset('admin_assets/images/user/avatar-3.jpg')}}" alt="user-image" class="user-avtar" />
                        </div>
                        <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">10 min ago</span>
                        <h5>Joseph William</h5>
                        <p class="text-body fs-6">It is a long established fact that a reader will be distracted </p>
                        <div class="badge rounded-pill bg-light-success">Confirmation of Account</div>
                        </div>
                    </div>
                    </a>
                </div>
                </div>
                <div class="dropdown-divider"></div>
                <div class="text-center py-2">
                <a href="#!" class="link-primary">Mark as all read</a>
                </div>
            </div>
            </li>
            <li class="dropdown pc-h-item header-user-profile">
            <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                role="button" aria-haspopup="false" aria-expanded="false">
                <img src="{{asset('admin_assets/images/user/avatar-2.jpg')}}" alt="user-image" class="user-avtar" />
                <span>
                <i class="ti ti-settings"></i>
                </span>
            </a>
            <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                <div class="dropdown-header">
                <h4>Good Morning, <span class="small text-muted"> John Doe</span></h4>
                <p class="text-muted">Project Admin</p>
                <form class="header-search">
                    <i class="ti ti-search icon-search"></i>
                    <input type="search" class="form-control" placeholder="Search profile options" />
                </form>
                <hr />
                <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 280px)">
                    <div class="upgradeplan-block bg-light-warning rounded">
                    <h4>Explore full code</h4>
                    <p class="text-muted">Buy now to get full access of code files</p>
                    <a href="https://codedthemes.com/item/berry-bootstrap-5-admin-template/" target="_blank"
                        class="btn btn-warning">Buy Now</a>
                    </div>
                    <hr />
                    <div class="settings-block bg-light-primary rounded">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" />
                        <label class="form-check-label" for="flexSwitchCheckDefault">Start DND Mode</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked />
                        <label class="form-check-label" for="flexSwitchCheckChecked">Allow Notifications</label>
                    </div>
                    </div>
                    <hr />
                    <a href="#" class="dropdown-item">
                    <i class="ti ti-settings"></i>
                    <span>Account Settings</span>
                    </a>
                    <a href="#" class="dropdown-item">
                    <i class="ti ti-user"></i>
                    <span>Social Profile</span>
                    </a>
                    <a href="{{route('user.logout')}}" class="dropdown-item">
                    <i class="ti ti-logout"></i>
                    <span>Logout</span>
                    </a>
                </div>
                </div>
            </div>
            </li>
        </ul>
        </div> </div>
        </header>
<!-- [ Header ] end -->
 <!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <div class="mt-3">
        <a href="/admin/dashboard" class="logo me-auto"><img src="{{asset('logo.jpg')}}" alt="" class="" style="max-width: 150px; max-height: 90px"></a>
      </div>

    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item pc-caption">
          <label>Dashboard</label>
          <i class="ti ti-dashboard"></i>
        </li>
        <li class="pc-item">
          <a href="/admin/dashboard" class="pc-link"><span class="pc-micon"><i class="ti ti-dashboard"></i></span><span
              class="pc-mtext">Dashboard</span></a>
        </li>
        <li class="pc-item pc-caption">
          <label>Pages</label>
          <i class="ti ti-news"></i>
        </li>
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-key"></i></span><span
              class="pc-mtext">Authentication</span><span class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" target="_blank" href="../pages/login-v3.html">Login</a></li>
            <li class="pc-item"><a class="pc-link" target="_blank" href="../pages/register-v3.html">register</a></li>
          </ul>
        </li>

        <li class="pc-item pc-caption">
          <label>Elements</label>
          <i class="ti ti-apps"></i>
        </li>
        <li class="pc-item">
          <a href="../elements/bc_typography.html" class="pc-link"><span class="pc-micon"><i
                class="ti ti-typography"></i></span><span class="pc-mtext">Typography</span></a>
        </li>
        <li class="pc-item">
          <a href="../elements/bc_color.html" class="pc-link"><span class="pc-micon"><i class="ti ti-brush"></i></span><span
              class="pc-mtext">Color</span></a>
        </li>
        <li class="pc-item">
          <a href="https://tablericons.com" class="pc-link" target="_blank"><span class="pc-micon"><i
                class="ti ti-plant-2"></i></span><span class="pc-mtext">Tabler</span><span class="pc-arrow"></a>
        </li>

        <li class="pc-item pc-caption">
          <label>Other</label>
          <i class="ti ti-brand-chrome"></i>
        </li>
        <li class="pc-item"><a href="../other/sample-page.html" class="pc-link"><span class="pc-micon"><i
                class="ti ti-brand-chrome"></i></span><span class="pc-mtext">Sample page</span></a></li>
        <li class="pc-item"><a href="https://codedthemes.gitbook.io/berry-bootstrap/" target="_blank" class="pc-link"><span
              class="pc-micon"><i class="ti ti-vocabulary"></i></span><span class="pc-mtext">Document</span></a></li>
      </ul>
      <div class="pc-navbar-card bg-primary rounded">
        <h4 class="text-white">Berry Pro</h4>
        <p class="text-white opacity-75">Checkout Berry pro features</p>
        <a href="https://codedthemes.com/item/berry-bootstrap-5-admin-template/" target="_blank" class="btn btn-light text-primary">Pro</a>
      </div>
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
                      <a
                        type="button"
                        class="avtar bg-secondary dropdown-toggle arrow-none"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                      >
                        <i class="ti ti-dots"></i>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li><button class="dropdown-item" type="button">Import Card</button></li>
                        <li><button class="dropdown-item" type="button">Export</button></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <span class="text-white d-block f-34 f-w-500 my-2">1350 <i class="ti ti-arrow-up-right-circle opacity-50"></i></span>
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
                    <ul class="nav nav-pills justify-content-end mb-0" id="chart-tab-tab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button
                          class="nav-link text-white active"
                          id="chart-tab-home-tab"
                          data-bs-toggle="pill"
                          data-bs-target="#chart-tab-home"
                          type="button"
                          role="tab"
                          aria-controls="chart-tab-home"
                          aria-selected="true"
                          >Month</button
                        >
                      </li>
                      <li class="nav-item" role="presentation">
                        <button
                          class="nav-link text-white"
                          id="chart-tab-profile-tab"
                          data-bs-toggle="pill"
                          data-bs-target="#chart-tab-profile"
                          type="button"
                          role="tab"
                          aria-controls="chart-tab-profile"
                          aria-selected="false"
                          >Year</button
                        >
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="tab-content" id="chart-tab-tabContent">
                  <div class="tab-pane show active" id="chart-tab-home" role="tabpanel" aria-labelledby="chart-tab-home-tab" tabindex="0">
                    <div class="row">
                      <div class="col-6">
                        <span class="text-white d-block f-34 f-w-500 my-2">$130<i class="ti ti-arrow-up-right-circle opacity-50"></i></span>
                        <p class="mb-0 opacity-50">Total Earning</p>
                      </div>
                      <div class="col-6">
                        <div id="tab-chart-1"></div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="chart-tab-profile" role="tabpanel" aria-labelledby="chart-tab-profile-tab" tabindex="0">
                    <div class="row">
                      <div class="col-6">
                        <span class="text-white d-block f-34 f-w-500 my-2">$29961 <i class="ti ti-arrow-down-right-circle opacity-50"></i></span>
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
                    <h4 class="text-white mb-1">$203k <i class="ti ti-arrow-up-right-circle opacity-50"></i></h4>
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
                    <h4 class="mb-1">$550K <i class="ti ti-arrow-up-right-circle opacity-50"></i></h4>
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
                        <h4 class="mb-0"
                          >$1839.00<span class="ms-2 align-top avtar avtar-xxs bg-light-success"
                            ><i class="ti ti-chevron-up text-success"></i></span
                        ></h4>
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
                        <h4 class="mb-0"
                          >$100.00<span class="ms-2 align-top avtar avtar-xxs bg-light-danger"
                            ><i class="ti ti-chevron-down text-danger"></i></span
                        ></h4>
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
                        <h4 class="mb-0"
                          >$200.00<span class="ms-2 align-top avtar avtar-xxs bg-light-success"
                            ><i class="ti ti-chevron-up text-success"></i></span
                        ></h4>
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
                        <h4 class="mb-0"
                          >$189.00<span class="ms-2 align-top avtar avtar-xxs bg-light-danger"
                            ><i class="ti ti-chevron-down text-danger"></i></span
                        ></h4>
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
                        <h4 class="mb-0"
                          >$189.00<span class="ms-2 align-top avtar avtar-xxs bg-light-danger"
                            ><i class="ti ti-chevron-down text-danger"></i></span
                        ></h4>
                      </div>
                    </div>
                  </li>
                </ul>
                <div class="text-center">
                  <a href="#!" class="b-b-primary text-primary">View all <i class="ti ti-chevron-right"></i></a>
                </div>
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
            <p class="m-0">Copyright &copy; <a href="https://codedthemes.com/" target="_blank">Codedthemes</a></p>
          </div>
          <div class="col-auto my-1">
            <ul class="list-inline footer-link mb-0">
              <li class="list-inline-item"><a href="https://codedthemes.com/" target="_blank">Home</a></li>
              <li class="list-inline-item"><a href="https://codedthemes.com/privacy-policy/" target="_blank">Privacy Policy</a></li>
              <li class="list-inline-item"><a href="https://codedthemes.com/contact/" target="_blank">Contact us</a></li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
 <!-- Required Js -->
<script src="{{asset('admin_assets/js/plugins/popper.min.js')}}"></script>
<script src="{{asset('admin_assets/js/plugins/simplebar.min.js')}}"></script>
<script src="{{asset('admin_assets/js/plugins/bootstrap.min.js')}}"></script>
<script src="{{asset('admin_assets/js/config.js')}}"></script>
<script src="{{asset('admin_assets/js/pcoded.js')}}"></script>


    <!-- [Page Specific JS] start -->
    <!-- Apex Chart -->
    <script src="{{asset('admin_assets/js/plugins/apexcharts.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/pages/dashboard-default.js')}}"></script>
    <!-- [Page Specific JS] end -->
  </body>
  <!-- [Body] end -->
</html> 

