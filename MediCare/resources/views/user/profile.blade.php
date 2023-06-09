@extends('layouts.inner_home')

@section('content')

<section class="breadcrumbs">
  <div class="container" style="margin-top: 88px">

    <div class="d-flex justify-content-between align-items-center">
      <h2><b>Profile</b></h2>
      <ol>
        <li><a href="user/dashboard">Home</a></li>
        <li>Profile</li>
      </ol>
    </div>

  </div>
</section><!-- End Breadcrumbs Section -->

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

          <section class="inner-page">

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
@endsection