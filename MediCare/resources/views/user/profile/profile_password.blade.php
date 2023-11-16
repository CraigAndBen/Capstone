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
                <div class="card my- shadow">
                  <div class="card-body">  
                    <div class="row">
                      <div class="d-flex justify-content-center">
                        <div class="auth-header text-center">
                          <h2 class="text-primary mt-5"><b>Update Password</b></h2>
                          <p class="f-16 mt-2">Ensure your account is using a long, random password to stay secure.</p>
                        </div>
                      </div>
                    <form method="POST" action="{{ route('user.password.update') }}">
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

                      @if (session('success'))
                      <div class="alert alert-success">
                          <span class="fa fa-check-circle"></span> {{ session('success') }}
                      </div>
                    @endif

                      @if (session('info'))
                          <div class="alert alert-info">
                            {{ session('info') }}
                          </div>
                      @endif

                      <div class="row mt-2">
                        <div class="col-md-6 offset-md-3">
                          <div class="form-floating mb-3 ">
                            <input type="password" class="form-control ml-2" id="floatingInput current_password"  name="current_password" placeholder="Current Password"/>
                            <label for="floatingInput">Current Password</label> 
                          </div>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-md-6 offset-md-3">
                          <div class="form-floating mb-3 ">
                            <input type="password" class="form-control ml-2" id="floatingInput password" placeholder="New Password" name="password"/>
                            <label for="floatingInput">New Password</label> 
                          </div>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-md-6 offset-md-3">
                          <div class="form-floating mb-3 ">
                            <input type="password" class="form-control ml-2" id="floatingInput password_confirmation" placeholder="New Password" name="password_confirmation"/>
                            <label for="floatingInput">Confirm Password</label> 
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