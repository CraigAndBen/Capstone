<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>MediCare | Login</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('logo.jpg') }}" type="image/x-icon" />
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


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

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <a href="/" class="d-flex justify-content-center mt-3">
                            <img src="{{ asset('logo.jpg') }}" alt="" class=""
                                style="max-width: 200px; max-height: 130px">
                        </a>
                        <div class="row">
                            <div class="d-flex justify-content-center">
                                <div class="auth-header">
                                    <h2 class="text-primary mt-5"><b>Hi, Welcome to MediCare</b></h2>
                                    <p class="f-16 mt-2">Enter your credentials to continue</p>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input. Please fix the
                                    following errors: <br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingInput email"
                                    placeholder="Email address" name="email" />
                                <label for="floatingInput">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingInput password" name="password"
                                    placeholder="Password" />
                                <label for="floatingInput">Password</label>
                            </div>
                            <div class="d-flex mt-1 justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="customCheckc1"
                                        checked="" />
                                    <label class="form-check-label text-muted" for="customCheckc1">Remember me</label>
                                </div>
                          
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" id="submitBtn" class="btn btn-primary">Sign In</button>
                            </div>

                        </form>
                        <hr />
                        <a class="d-flex justify-content-center" href="{{ route('register') }}">Don't have an
                            account?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="{{ asset('admin_assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/config.js') }}"></script>
    <script src="{{ asset('admin_assets/js/pcoded.js') }}"></script>
</body>
<!-- [Body] end -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const submitButton = document.getElementById('submitBtn');

        form.addEventListener('submit', function() {
            // Disable the form and submit button
            form.classList.add('disabled');
            submitButton.disabled = true;
        });
    });
</script>

</html>
