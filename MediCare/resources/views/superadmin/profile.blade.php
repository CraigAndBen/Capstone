@extends('layouts.inner_doctor')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container pb-3">
      <div class="pc-content ">
        <!-- [ breadcrumb ] start -->
        <div class="page-header mt-4">
          <div class="page-block">
            <div class="row align-items-center">
              <div class="col-md-12">
                <div class="page-header-title">
                  <h5 class="m-b-10">Super Admin Account</h5>
                </div>
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('superadmin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{route('superadmin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item" aria-current="page">Super Admin Account</li>
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
                    <form method="POST" action="{{ route('doctor.password.update') }}">
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
@endsection