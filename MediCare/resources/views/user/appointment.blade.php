@extends('layouts.inner_home')

@section('content')

<section class="breadcrumbs">
  <div class="container" style="margin-top: 85px">

    <div class="d-flex justify-content-between align-items-center">
      <h2><b>Doctor Appointment</b></h2>
      <ol>
        <li><a href="user/dashboard">Home</a></li>
        <li>Appointment</li>
      </ol>
    </div>

  </div>
</section><!-- End Breadcrumbs Section -->

          <section class="inner-page">
            <div class="container">
              <div class="auth-main">
                <div class="auth-wrapper v3">
                  <div class="auth-form">
                    <div class="card my-3 shadow">
                      <div class="card-body">
                        <a href="#" class="d-flex justify-content-center mt-3">
                          <img src="{{asset('logo.jpg')}}" alt="" class="" style="max-width: 200px; max-height: 130px">
                        </a>
                        <div class="row">
                          <div class="d-flex justify-content-center">
                            <div class="auth-header text-center">
                              <h2 class="text-primary mt-5"><b>Doctor Appointment Request Form</b></h2>
                              <p class="f-16 mt-2">Fill the form below and we will get back soon to you for more updates and plan your appointment.</p>
                            </div>
                          </div>
                        </div>     
                        <form method="POST" action="{{ route('login') }}">
                          @csrf
                          <div class="row mt-4">
                            <div class="col-md-6">
                              <div class="form-floating mb-3 ">
                                <input type="text" class="form-control ml-2" id="floatingInput fname" placeholder="First Name" name="fname" />
                                <label for="floatingInput">First Name</label> 
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-floating mb-3 ">
                                <input type="text" class="form-control" id="floatingInput lname" placeholder="Last Name" name="lname" />
                                <label for="floatingInput">Last Name</label> 
                              </div>
                            </div>
                          </div>
                          <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingInput email" name="email" placeholder="Email Address"  />
                            <label for="floatingInput">Email Address</label> 
                          </div>
                          <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="floatingInput phone" name="phone" placeholder="Phone"  />
                            <label for="floatingInput">Phone Number</label> 
                          </div>
                          <div class="mb-3">
                            <select class="form-control  p-3" id="doctor" name="doctor">
                              <option>Select a Doctor</option>
                              @foreach ($doctors as $doctor)
                                <option value="Dr. Jhon Smith">{{$doctor->name}}</option>
                              @endforeach

                            </select>
                          </div>
                          <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="floatingInput date" name="date" placeholder="Date" min="" />
                            <label for="floatingInput" >Appointment Date</label> 
                          </div>
                          <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput reason" name="reason" placeholder="Reason for Appointment"  />
                            <label for="floatingInput">Reason for Appointment</label> 
                          </div>
                          <div class="d-flex mt-1 justify-content-between">
                            <div class="form-check">
                              <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="" />
                              <label class="form-check-label text-muted" for="customCheckc1">I agree to the terms and conditions</label>
                            </div>
                          </div>
                          <div class="text-center mt-4 mb-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
@endsection