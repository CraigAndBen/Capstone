@extends('layouts.app')

@section('content')
<div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
</div>
<div class="auth-main">
    <div class="auth-wrapper v3">
      <div class="auth-form">
        <div class="card my-3">
          <div class="card-body">
            <a href="#" class="d-flex justify-content-center">
              <h1 class="text-primary">Medi<span class="text-success">Care</span> </h1>
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
                <img src="{{asset('admin-assets/images/authentication/google-icon.svg')}}" />Sign In With Google
              </button>
            </div>
            <div class="saprator mt-2">
              <span>or</span>
            </div>
              <h5 class="my-4 d-flex justify-content-center">Sign in with Email address</h5>

              <form method="POST" action="{{route('login')}}">
                @csrf
                <div class="form-floating mb-3">
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" id="floatingInput" placeholder="Email address" value="{{ old('email') }}" required autocomplete="email" autofocus/>
                   @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                  <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating mb-3">
                  <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" id="floatingInput" placeholder="Password"  />
                  <label for="floatingInput">Password</label>
                  
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                </div>
                <div class="d-flex mt-1 justify-content-between">
                  <div class="form-check">
                    <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                    <label class="form-check-label text-muted" for="customCheckc1">Remember me</label>
                  </div>
                  @if (Route::has('password.request'))
                  <a class="btn btn-link text-primary" href="{{ route('password.request') }}">
                      {{ __('Forgot Your Password?') }}
                  </a>
                  @endif
                </div>
                <div class="d-grid mt-4">
                  <button type="submit" class="btn btn-primary">Sign In</button>
                </div>
                <hr />
                <a class="d-flex justify-content-center" href="{{route('register')}}">Don't have an account?</a>
                {{-- <h5 class="d-flex justify-content-center">D</h5> --}}
             </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
