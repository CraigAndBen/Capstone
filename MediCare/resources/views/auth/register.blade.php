{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

<!DOCTYPE html>
<html lang="en">
  <!-- [Head] start -->
  <head>
    <title>MediCare | Sign Up</title>
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

    <div class="auth-main">
      <div class="auth-wrapper v3">
        <div class="auth-form">
          <div class="card mt-5">
            <div class="card-body">
              {{-- <a href="/" class="d-flex justify-content-center align-items-center" mt-3 ">
                <img src="{{asset('logo.jpg')}}" alt="" class="" style="max-width: 200px; max-height: 130px">
              </a> --}}
              <div class="row">
                <div class="d-flex justify-content-center">
                  <div class="auth-header">
                    <h2 class="text-primary mt-5"><b>Sign up</b></h2>
                    <p class="f-16 mt-2">Enter your credentials to continue</p>
                  </div>
                </div>
              </div>
              {{-- <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted" style="width: 100%">
                <img src="{{asset('admin_assets/images/authentication/google-icon.svg')}}" />Sign Up With Google
              </button>
              <div class="saprator mt-3">
                <span>or</span>
              </div>
              <h5 class="my-4 d-flex justify-content-center">Sign Up with Email address</h5> --}}

              <form method="POST" action="{{route('register')}}">
                @csrf
                {{-- <x-input-error :messages="$errors->get('name')" class="mt-2" /> --}}
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-floating mb-3">
                      <input type="text" name="name" class="form-control" id="floatingInput name" placeholder="Name" />
                      <label for="floatingInput">Name</label>
                      <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                  </div>
                </div>
                <div class="form-floating mb-3">
                  <input type="email" name="email" class="form-control" id="floatingInput email" placeholder="Email Address" />
                  <label for="floatingInput">Email Address</label>
                  <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="form-floating mb-3">
                  <input type="password" name="password" class="form-control" id="floatingInput password" placeholder="Password" />
                  <label for="floatingInput">Password</label>
                  <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="form-floating mb-3">
                  <input type="password" name="password_confirmation" class="form-control" id="floatingInput password_confirmation" placeholder="password confirmation" />
                  <label for="floatingInput">Password Confirmation</label>
                  <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
                <div class="form-check mt-3s">
                  <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="" />
                  <label class="form-check-label" for="customCheckc1">
                    <h5>Agree with <span>Terms & Condition.</span></h5>
                  </label>
                </div>
                <div class="d-grid mt-4">
                  <button type="submit" class="btn btn-primary p-2">Sign Up</button>
                </div>
                <hr />
              </form>
              <h5 class="d-flex justify-content-center"><a href="{{route('login')}}">Already have an account?</a></h5>
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

