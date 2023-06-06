{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


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
    <link rel="icon" href="{{asset('admin_assets/images/favicon.svg')}}" type="image/x-icon" />
 <!-- [Google Font] Family -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" id="main-font-link" />
<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="{{asset('admin_assets/fonts/tabler-icons.min.css')}}" />
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="{{asset('admin_assets/fonts/material.css')}}" />
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="{{asset('admin_assets/css/style.css')}}" id="main-style-link" />
<link rel="stylesheet" href=".{{asset('admin_ssets/css/style-preset.css')}}" id="preset-style-link" />

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
              <a href="#" class="d-flex justify-content-center">
                <h1>Medi<span class="text-light rounded-pill bg-success p-2">Care</span></h1>
              </a>
              <div class="row">
                <div class="d-flex justify-content-center">
                  <div class="auth-header">
                    <h2 class="text-primary mt-5"><b>Hi, Welcome Back</b></h2>
                    <p class="f-16 mt-2">Enter your credentials to continue</p>
                  </div>
                </div>
              </div>
              <div class="d-grid">
                <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                  <img src="{{asset('admin_assets/images/authentication/google-icon.svg')}}" />Sign In With Google
                </button>
              </div>
              <div class="saprator mt-3">
                <span>or</span>
              </div>
              <h5 class="my-4 d-flex justify-content-center">Sign in with Email address</h5>

              <form method="POST" action="{{ route('login') }}">
                @csrf
            
                <div class="form-floating mb-3">
                  <input type="email" class="form-control" id="floatingInput email" placeholder="Email address" name="email" />
                  <label for="floatingInput">Email address</label>
                  <x-input-error :messages="$errors->get('email')" class="mt-2" />    
                </div>
                <div class="form-floating mb-3">
                  <input type="password" class="form-control" id="floatingInput password" name="password" placeholder="Password"  />
                  <label for="floatingInput">Password</label>
                  <x-input-error :messages="$errors->get('password')" class="mt-2" />    
                </div>
                <div class="d-flex mt-1 justify-content-between">
                  <div class="form-check">
                    <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="" />
                    <label class="form-check-label text-muted" for="customCheckc1">Remember me</label>
                  </div>
                  <h5 class="text-secondary">Forgot Password?</h5>
                </div>
                <div class="d-grid mt-4">
                  <button type="submit" class="btn btn-primary">Sign In</button>
                </div>

              </form>
              <hr />
              <a class="d-flex justify-content-center" href="{{route('register')}}">Don't have an account?</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="{{asset('admin_assets/js/plugins/popper.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/plugins/simplebar.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/plugins/bootstrap.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/config.js')}}"></script>
    <script src="{{asset('admin_assets/js/pcoded.js')}}"></script>

  </body>
  <!-- [Body] end -->
</html>
