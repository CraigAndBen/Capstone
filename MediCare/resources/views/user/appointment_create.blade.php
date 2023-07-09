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
                        <div class="row mb-5">
                          <div class="d-flex justify-content-center">
                            <div class="auth-header text-center">
                              <h2 class="text-primary mt-5"><b>Doctor Appointment Request Form</b></h2>
                              <p class="f-16 mt-2">Fill the form below and we will get back soon to you for more updates and plan your appointment.</p>
                            </div>
                          </div>
                        </div>   
                        
                        
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
                                <span class="fa fa-check-circle"></span> {{ session('info') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('user.create.appointment') }}">
                          @csrf
                          <div class="row mt-4 text-start">
                            <div class="col-md-4">
                              <div class="form-floating mb-3 ">
                                <input type="text" class="form-control ml-2" id="floatingInput first_name" placeholder="First Name" name="first_name" />
                                <label for="floatingInput">First Name</label> 
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-floating mb-3 ">
                                <input type="text" class="form-control" id="floatingInput middle_name" placeholder="Middle Name" name="middle_name" />
                                <label for="floatingInput">Middle Name</label> 
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-floating mb-3 ">
                                <input type="text" class="form-control" id="floatingInput last_name" placeholder="Last Name" name="last_name" />
                                <label for="floatingInput">Last Name</label> 
                              </div>
                            </div>
                          </div>
                          <hr>
                          <div class="row">
                            <div class="col-md-6">
                              <div class=" form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput street" name="street" placeholder="Street"  />
                                <label for="floatingInput">Street</label> 
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class=" form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput brgy" name="brgy" placeholder="Brgy"  />
                                <label for="floatingInput">State/Barangay</label> 
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class=" form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput city" name="city" placeholder="City"  />
                                <label for="floatingInput">City</label> 
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class=" form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput province" name="province" placeholder="Province"  />
                                <label for="floatingInput">Province</label> 
                              </div>
                            </div>
                          </div>
                          <hr>
                          <div class="row" form-floating mb-3>
                            <div class="col-md-6">
                              <div class=" form-floating mb-3">
                                <input type="date" class="form-control" id="floatingInput birthdate" name="birthdate" placeholder="Date of Birth"  />
                                <label for="floatingInput">Date of Birth</label> 
                              </div>
                            </div>
                            <div class="mb-3 col-md-6">
                              <select class="form-control  p-3" id="gender" name="gender">
                                <option>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="diagnostic appointment">Others</option>
                              </select>
                            </div>
                          </div>
                          <hr>
                          <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="floatingInput phone" name="phone" placeholder="Phone"  />
                            <label for="floatingInput">Phone</label> 
                          </div>
                          <hr>
                          <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingInput email" name="email" placeholder="Email Address"  />
                            <label for="floatingInput">Email Address</label> 
                          </div>
                          <hr>
                          <div class="row mt-4">
                            <h5>Which specialist do you want to appoint of?</h5>
                            <div class="form-floating mb-3">
                              <select class="form-control p-3" id="specialties" name="specialties">
                                <option>Select Specialist</option>
                                @foreach ($infos as $info)
                                  <option value="{{$info->specialties}}">{{$info->specialties}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="row mt-4">
                            <div class="form-floating mb-3">
                              <h5>Which procedure do you want to make an appointment for?</h5>
                              <select class="form-control  p-3" id="appointment_type" name="appointment_type">
                                <option>Select a Type of Appointment</option>
                                <option value="regular check-up">Regular Check-up</option>
                                <option value="Follow-up appointment">Follow-up Appointment</option>
                                <option value="diagnostic appointment">Diagnostic Appointment</option>
                                <option value="specialist consultation">Specialist Consultation</option>
                              </select>
                            </div>
                          </div>
                          <hr>
                          <div class="row mt-4">
                            <h5>Preffered Appointment Date and Time <i>(Monday - Friday)</i></h5>
                            <div class="col-md-6">
                              <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="appointment_date" name="appointment_date" placeholder="Date" min="<?= date('Y-m-d') ?>" />
                                <label for="floatingInput">Appointment Date</label> 
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-floating mb-3">
                                <select class="form-control  p-3" id="appointment_time" name="appointment_time">
                                  <option>Select Time of Appointment</option>
                                  @foreach ($updatedTime as $time)
                                    <option value="{{$time}}">{{$time}} </option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                          <hr class="mb-3">
                          <h5>Reason for appointment</h5>
                          <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput reason" name="reason" placeholder="Reason For Appointment"  />
                            <label for="floatingInput">Reason for Appointment</label> 
                          </div>
                          <div class="d-flex mt-1 justify-content-between">
                            <div class="form-check">
                              <input class="form-check-input input-primary" type="checkbox" id="check" name="check" />
                              <label class="form-check-label text-muted" for="customCheckc1">I agree to the terms and conditions</label>
                            </div>
                          </div>
                          <hr>
                          <div class="text-end mt-4 mb-3">
                            <button type="submit" class="btn btn-danger">Cancel</button>
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

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

@section('scripts')
<script>
  $(document).ready(function() {

      // Get the date input element
      var dateInput = document.getElementById('date');

      // Set the min and max dates for weekdays (Monday to Friday)
      var today = moment();
      var minDate = moment(today);
      var maxDate = moment(today);

      // Set the min date to the nearest upcoming Monday
      minDate = minDate.add((1 + 7 - minDate.day()) % 7, 'days');

      // Set the max date to the nearest upcoming Friday
      maxDate = maxDate.add((5 + 7 - maxDate.day()) % 7, 'days');

      // Format the min and max dates as strings in the 'yyyy-mm-dd' format
      var minDateString = minDate.format('YYYY-MM-DD');
      var maxDateString = maxDate.format('YYYY-MM-DD');

      // Set the min and max attributes of the date input
      dateInput.setAttribute('min', minDateString);
      dateInput.setAttribute('max', maxDateString);
  });
</script>


@endsection