@extends('layouts.app')

@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="auth-main">
    <div class="auth-wrapper v3">
      <div class="auth-form">
        <div class="card mt-5">
          <div class="card-body">
            <a href="#" class="d-flex justify-content-center mt-3">
                <h1 class="text-primary">Medi<span class="text-success">Care</span> </h1>
            </a>
            <div class="row">
              <div class="d-flex justify-content-center">
                <div class="auth-header mb-3">
                  <h2 class="text-primary mt-5"><b>Sign up</b></h2>
                  <p class="f-16 mt-2">Enter your credentials to continue</p>
                </div>
              </div>
            </div>
            <form method="POST" action="{{route('register')}}">
                @csrf
                <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="floatingInput name" 
                    name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="First Name" />
                    <label for="floatingInput">Name</label>

                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror

                    </div>
                </div>
                </div>
                <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput email" placeholder="Email Address" value="{{ old('email') }}" required autocomplete="email" />
                <label for="floatingInput">Email Address</label>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-floating mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="floatingInput password" placeholder="Password" required autocomplete="new-password" />
                <label for="floatingInput">Password</label>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingInput password-confirm" placeholder="Password" name="password_confirmation" required autocomplete="new-password"/>
                    <label for="floatingInput">Confirm Password</label>
                </div>
                <div class="form-check mt-3s">
                <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="" />
                <label class="form-check-label" for="customCheckc1">
                    <h5>Agree with <span>Terms & Condition.</span></h5>
                </label>
                </div>
                <div class="d-grid mt-4">
                <button type="submit" class="btn btn-secondary p-2">Sign Up</button>
                </div>
            </form>
            <hr />
            <a class="d-flex justify-content-center" href="{{route('login')}}">Already have an account?</a>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
