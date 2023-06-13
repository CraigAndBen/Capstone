<!DOCTYPE html>
<html lang="en">
  <!-- [Head] start -->
  <head>
    <title>MediCare | Doctor Dashboard</title>
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
      <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
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
                  <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar" />
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
                  <img src="../assets/images/user/avatar-1.jpg" alt="user-image" class="user-avtar" />
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
                  <img src="../assets/images/user/avatar-3.jpg" alt="user-image" class="user-avtar" />
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
          <h4>Good Morning, <span class="small text-muted">{{$user->name}}</span></h4>
          <p class="text-muted">{{$user->role}}</p>
          <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 280px)">
            <a href="{{route('doctor.profile.edit')}}" class="dropdown-item">
              <i class="ti ti-settings"></i>
              <span>Account Settings</span>
            </a>
            <a href="#" class="dropdown-item">
              <i class="ti ti-user"></i>
              <span>Social Profile</span>
            </a>
            <a href="{{route('doctor.logout')}}" class="dropdown-item">
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
        <a href="/doctor/dashboard" class="logo me-auto"><img src="{{asset('logo.jpg')}}" alt="" class="" style="max-width: 150px; max-height: 90px"></a>
      </div>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item pc-caption">
          <label>Dashboard</label>
          <i class="ti ti-dashboard"></i>
        </li>
        <li class="pc-item">
          <a href="../dashboard/index.html" class="pc-link"><span class="pc-micon"><i class="ti ti-dashboard"></i></span><span
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
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->



    <!-- [ Main Content ] start -->
    <div class="pc-container pb-3">
      <div class="pc-content ">
        <!-- [ breadcrumb ] start -->
        <div class="page-header mt-4">
          <div class="page-block">
            <div class="row align-items-center">
              <div class="col-md-12">
                <div class="page-header-title">
                  <h5 class="m-b-10">Doctor Profile</h5>
                </div>
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/doctor/dashboard">Home</a></li>
                  <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
                  <li class="breadcrumb-item" aria-current="page">Doctor Profile</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- [ breadcrumb ] end -->


        <!-- [ Main Content ] start -->
        {{-- <div class="row">
          <!-- [ sample-page ] start -->
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <h5>Hello card</h5>
              </div>
              <div class="card-body">
                <p
                  >"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                  aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis
                  aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                  cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
                </p>
              </div>
            </div> --}}
              {{-- <div class="card my-3 shadow">
                  <div class="card-body">  
                    <div class="row">
                      <div class="d-flex justify-content-center">
                        <div class="auth-header text-center">
                          <h2 class="text-primary mt-5"><b>Profile Information</b></h2>
                          <p class="f-16 mt-2">Update your account's profile information and email address.</p>
                        </div>
                      </div>
                    <form method="POST" action="{{ route('profile.update') }}">
                      @csrf
                      @method('patch')

                          @if (session('status'))
                          <div class="row mt-4">
                            <div class="col-md-6 bg-primary text-light text-center offset-md-3 p-2 rounded-pill">
                              <h3>{{session('status')}}</h3>
                            </div>
                          </div>
                          @endif

                      <div class="row mt-2">
                        <div class="col-md-6 offset-md-3">
                          <div class="form-floating mb-3 ">
                            <input type="text" class="form-control ml-2" id="floatingInput name" placeholder="{{$user->name}}" name="name" value="{{$user->name}}"/>
                            <label for="floatingInput">Name</label> 
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                          </div>
                        </div>
                      </div>
                      <div class="row mt-1">
                        <div class="col-md-6 offset-md-3">
                          <div class="form-floating mb-3 ">
                            <input type="email" class="form-control" id="floatingInput lname" placeholder="{{$user->email}}" value="{{$user->email}}" name="email"/>
                            <label for="floatingInput">Email</label> 
                          </div>
                        </div>
                      </div>
                      <div class="text-center mt-4 mb-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                      </div>
                    </form>
                  </div>
                </div>
          </div> --}}

        <section class="inner-page">

            {{-- profile update --}}
            <div class="container">
              <div class="auth-form">
                <div class="card my-3 shadow">
                  <div class="card-body">  
                    <div class="row">
                      <div class="d-flex justify-content-center">
                        <div class="auth-header text-center">
                          <h2 class="text-primary mt-5"><b>Profile Information</b></h2>
                          <p class="f-16 mt-2">Update your account's profile information and email address.</p>
                        </div>
                      </div>
                    <form method="POST" action="{{ route('profile.update') }}">
                      @csrf
                      @method('patch')

                          @if (session('status'))
                          <div class="row mt-4">
                            <div class="col-md-6 bg-primary text-light text-center offset-md-3 p-2 rounded-pill">
                              <h3>{{session('status')}}</h3>
                            </div>
                          </div>
                          @endif

                      <div class="row mt-2">
                        <div class="col-md-6 offset-md-3">
                          <div class="form-floating mb-3 ">
                            <input type="text" class="form-control ml-2" id="floatingInput name" placeholder="{{$user->name}}" name="name" value="{{$user->name}}"/>
                            <label for="floatingInput">Name</label> 
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                          </div>
                        </div>
                      </div>
                      <div class="row mt-1">
                        <div class="col-md-6 offset-md-3">
                          <div class="form-floating mb-3 ">
                            <input type="email" class="form-control" id="floatingInput lname" placeholder="{{$user->email}}" value="{{$user->email}}" name="email"/>
                            <label for="floatingInput">Email</label> 
                          </div>
                        </div>
                      </div>
                      <div class="text-center mt-4 mb-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

          </section>

          <section class="inner-page ">

            {{-- profile update --}}
            <div class="container">
              <div class="auth-form">
                <div class="card my- shadow">
                  <div class="card-body">  
                    <div class="row">
                      <div class="d-flex justify-content-center">
                        <div class="auth-header text-center">
                          <h2 class="text-primary mt-5"><b>Update Password</b></h2>
                          <p class="f-16 mt-2">Ensure your account is using a long, random password to stay secure.</p>
                        </div>
                      </div>
                    <form method="POST" action="{{ route('password.update') }}">
                      @csrf
                      @method('put')

                          @if (session('status'))
                          <div class="row mt-4">
                            <div class="col-md-6 bg-primary text-light text-center offset-md-3 p-2 rounded-pill">
                              <h3>{{session('status')}}</h3>
                            </div>
                          </div>
                          @endif

                      <div class="row mt-2">
                        <div class="col-md-6 offset-md-3">
                          <div class="form-floating mb-3 ">
                            <input type="password" class="form-control ml-2" id="floatingInput current_password"  name="current_password" placeholder="Current Password"/>
                            <label for="floatingInput">Current Password</label> 
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-danger" />
                          </div>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-md-6 offset-md-3">
                          <div class="form-floating mb-3 ">
                            <input type="password" class="form-control ml-2" id="floatingInput password" placeholder="New Password" name="password"/>
                            <label for="floatingInput">New Password</label> 
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-danger" />
                          </div>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-md-6 offset-md-3">
                          <div class="form-floating mb-3 ">
                            <input type="password" class="form-control ml-2" id="floatingInput password_confirmation" placeholder="New Password" name="password_confirmation"/>
                            <label for="floatingInput">Confirm Password</label> 
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                          </div>
                        </div>
                      </div>
                      <div class="text-center mt-4 mb-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

          </section>
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

  </body>
  <!-- [Body] end -->
</html>
