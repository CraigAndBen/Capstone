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
                    <form method="POST" action="{{ route('user.profile.update') }}">
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
                                <input type="text" class="form-control ml-2" id="floatingInput first_name" placeholder="{{$user->first_name}}" name="first_name" value="{{$user->first_name}}" required/>
                                <label for="floatingInput">First Name</label> 
                                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                              </div>
                            </div>
                          </div>
                          <div class="row mt-1">
                            <div class="col-md-6 offset-md-3">
                              <div class="form-floating mb-3 ">
                                <input type="text" class="form-control" id="floatingInput last_name" placeholder="{{$user->last_name}}" value="{{$user->last_name}}" name="last_name" required/>
                                <label for="floatingInput">Last Name</label> 
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
@endsection