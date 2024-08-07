@extends('layouts.inner_home')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.css" rel="stylesheet">
    <!-- Moment.js for date handling -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <!-- FullCalendar JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.js"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

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
                                <div class="row mb-5">
                                    <div class="d-flex justify-content-center">
                                        <div class="auth-header text-center">
                                            <h2 class="text-primary mt-5"><b>Doctor Appointment Request Calendar</b></h2>
                                            <p class="f-16 mt-2">Select your preferred date, fill out the form below, and we
                                                will get back to you soon with more updates to plan your appointment.</p>
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
                                        {{ session('info') }}
                                    </div>
                                @endif

                                @if (session('date'))
                                    <div class="alert alert-info">
                                        {{ session('date') }}
                                    </div>
                                @endif
                                <div class="m-3 p-3">
                                    <div id="calendar"></div>
                                </div>

                                <div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false"
                                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-light">
                                                <h3 class="modal-title" id="staticBackdropLabel">Create Doctor
                                                    Appointment
                                                </h3>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('user.create.appointment') }}">
                                                    @csrf
                                                    <input type="hidden" id="appointment_date" name="appointment_date">
                                                    <div class="row mt-4 text-start">
                                                        <div class="col-md-4">
                                                            <div class="form-floating mb-3 ">
                                                                <input type="text" class="form-control ml-2"
                                                                    id="floatingInput first_name" placeholder="First Name"
                                                                    name="first_name" />
                                                                <label for="floatingInput">First Name <span
                                                                        style="color: red;">*</span></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-floating mb-3 ">
                                                                <input type="text" class="form-control"
                                                                    id="floatingInput middle_name" placeholder="Middle Name"
                                                                    name="middle_name" />
                                                                <label for="floatingInput">Middle Name <span
                                                                        style="color: red;">*</span></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-floating mb-3 ">
                                                                <input type="text" class="form-control"
                                                                    id="floatingInput last_name" placeholder="Last Name"
                                                                    name="last_name" />
                                                                <label for="floatingInput">Last Name <span
                                                                        style="color: red;">*</span></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class=" form-floating mb-3">
                                                                <select class="form-control  p-3" id="province"
                                                                name="province"  onchange="loadCities()">
                                                                {{-- <option value="">Select Province *</option>
                                                                <option value="Albay">Albay</option>
                                                                <option value="Camarines Norte">Camarines Norte</option>
                                                                <option value="Camarines Sur">Camarines Sur</option>
                                                                <option value="Catanduanes">Catanduanes</option>
                                                                <option value="Masbate">Masbate</option>
                                                                <option value="Sorsogon">Sorsogon</option> --}}
                                                            </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class=" form-floating mb-3">
                                                                <input type="text" class="form-control"
                                                                    id="floatingInput brgy" name="brgy"
                                                                    placeholder="Brgy" />
                                                                <label for="floatingInput">State/Barangay <span
                                                                        style="color: red;">*</span></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class=" form-floating mb-3">
                                                                <input type="text" class="form-control"
                                                                    id="floatingInput city" name="city"
                                                                    placeholder="City" />
                                                                <label for="floatingInput">City <span
                                                                        style="color: red;">*</span></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <select class="form-control  p-3" id="gender"
                                                                name="gender">
                                                                <option value="">Select Province *</option>
                                                                <option value="Albay">Albay</option>
                                                                <option value="Camarines Norte">Camarines Norte</option>
                                                                <option value="Camarines Sur">Camarines Sur</option>
                                                                <option value="Catanduanes">Catanduanes</option>
                                                                <option value="Masbate">Masbate</option>
                                                                <option value="Sorsogon">Sorsogon</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row" form-floating mb-3>
                                                        <div class="col-md-6">
                                                            <div class=" form-floating mb-3">
                                                                <input type="date" class="form-control"
                                                                    id="floatingInput birthdate" name="birthdate"
                                                                    placeholder="Date of Birth" />
                                                                <label for="floatingInput">Date of Birth <span
                                                                        style="color: red;">*</span></label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 col-md-6">
                                                            <select class="form-control  p-3" id="gender"
                                                                name="gender">
                                                                <option value="">Select Gender *</option>
                                                                <option value="male">Male</option>
                                                                <option value="female">Female</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="phoneInput"
                                                            name="phone" placeholder="Phone"
                                                            oninput="formatPhoneNumber(this);" />
                                                        <label for="phoneInput">Phone <span
                                                                style="color: red;">*</span></label>
                                                    </div>
                                                    <hr>
                                                    <div class="form-floating mb-3">
                                                        <input type="email" class="form-control"
                                                            id="floatingInput email" name="email"
                                                            placeholder="Email Address" />
                                                        <label for="floatingInput">Email Address <span
                                                                style="color: red;">*</span></label>
                                                    </div>
                                                    <hr>
                                                    <div class="row mt-4">
                                                        <h5>Which specialist do you want to appoint of? <span
                                                                style="color: red;">*</span></h5>
                                                        <div class="form-floating mb-3">
                                                            <select class="form-control p-3" id="specialties"
                                                                name="specialties">
                                                                <option value="">Select Specialist</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row mt-4">
                                                        <div class="form-floating mb-3">
                                                            <h5>Which procedure do you want to make an appointment for?
                                                                <span style="color: red;">*</span>
                                                            </h5>
                                                            <select class="form-control  p-3" id="appointment_type"
                                                                name="appointment_type">
                                                                <option value="">Select a Type of Appointment
                                                                </option>
                                                                <option value="regular check-up">Regular Check-up
                                                                </option>
                                                                <option value="Follow-up appointment">Follow-up
                                                                    Appointment
                                                                </option>
                                                                <option value="diagnostic appointment">Diagnostic
                                                                    Appointment
                                                                </option>
                                                                <option value="specialist consultation">Specialist
                                                                    Consultation
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row mt-4">
                                                        <div class="form-floating mb-3">
                                                            <select class="form-control  p-3" id="appointment_time"
                                                                name="appointment_time">
                                                                <option>Select Specialist First *</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <hr class="mb-3">
                                                    <h5>Reason for appointment</h5>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control"
                                                            id="floatingInput reason" name="reason"
                                                            placeholder="Reason For Appointment" />
                                                        <label for="floatingInput">Reason for Appointment <span
                                                                style="color: red;">*</span></label>
                                                    </div>
                                                    <div class="d-flex mt-1 justify-content-between">
                                                        <div class="form-check">
                                                            <input class="form-check-input input-primary" type="checkbox"
                                                                id="check" name="check" checked />
                                                            <label class="form-check-label text-muted"
                                                                for="customCheckc1"><a href="" data-toggle="modal"
                                                                    data-target="#terms">I agree to the terms and
                                                                    conditions</a></label>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="infoModal" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                                        <div class="modal-content">
                                            <div id="infoBg" class="modal-header d-flex justify-content-center">
                                                <h3 class="modal-title text-white" id="staticBackdropLabel">Doctor
                                                    Appointment
                                                </h3>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Appointment Type:</strong> <span id="eventName"></span>
                                                </p>
                                                <p><strong>Appointment Start:</strong> <span id="eventStartDate"></span>
                                                </p>
                                                <p><strong>Appointment End:</strong> <span id="eventEndDate"></span>
                                                <p><strong>Appointment Status:</strong> <span id="status"></span>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="cancelModal" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex justify-content-center"
                                                style="background: orange">
                                                <h3 class="modal-title text-white" id="staticBackdropLabel">Doctor
                                                    Appointment
                                                </h3>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Appointment Type:</strong> <span id="cancelEventName"></span>
                                                <p><strong>Appointment Start:</strong> <span
                                                        id="cancelEventStartDate"></span>
                                                </p>
                                                <p><strong>Appointment End:</strong> <span id="cancelEventEndDate"></span>
                                                <p><strong>Appointment Status:</strong> <span id="cancelStatus"></span>
                                                </p>
                                                <form action="{{ route('user.appointment.calendar.cancel') }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" id="cancelId" name="appointment_id">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-primary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="holidayModal" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex justify-content-center"
                                                style="background: red">
                                                <h3 class="modal-title text-white" id="staticBackdropLabel">Holiday
                                                </h3>
                                            </div>
                                            <div class="modal-body text-center">
                                                <h4 id="holidayName" class="pb-3"></h4>
                                                <p><strong>Date:</strong> <span id="holidayDate"></span>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="terms" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-light">
                                                <h3 class="modal-title" id="staticBackdropLabel">Terms and Conditions for
                                                    Doctor Appointment</h3>
                                            </div>
                                            <div class="modal-body">
                                                <p> &nbsp;&nbsp;The doctor appointment booking service is designed to
                                                    facilitate the
                                                    process of scheduling appointments with healthcare providers and
                                                    ensuring a seamless experience for both patients and medical
                                                    professionals. By using this service, you are agreeing to abide by the
                                                    following terms and conditions. It is essential to understand and
                                                    acknowledge these terms before proceeding with appointment bookings.</p>


                                                <p>1. <i>Use of the Service</i></p>

                                                <p>&nbsp;&nbsp;Users of this service must be at least 18 years of age or
                                                    older.
                                                    Furthermore, users are expected to provide accurate and up-to-date
                                                    personal information when booking appointments. This requirement ensures
                                                    that healthcare providers have access to essential patient details for
                                                    the purpose of delivering effective medical care.</p>

                                                <p>2. <i>Appointment Booking</i></p>

                                                <p>&nbsp;&nbsp;Our service operates as a platform for scheduling
                                                    appointments with
                                                    healthcare professionals. While we endeavor to provide a user-friendly
                                                    and efficient service, we do not guarantee the availability of specific
                                                    doctors or appointment times. Availability is subject to the discretion
                                                    of the healthcare provider. Appointments may be rescheduled or canceled
                                                    based on the healthcare provider's availability and professional
                                                    judgment.</p>

                                                <p>3. <i>Cancellation and Rescheduling</i></p>

                                                <p>&nbsp;&nbsp;In the event that an appointment needs to be canceled or
                                                    rescheduled, we
                                                    request that users do so with reasonable notice. Late cancellations or
                                                    no-shows can disrupt the healthcare provider's schedule and may result
                                                    in additional charges or fees, as determined by the individual
                                                    healthcare provider's policies.</p>

                                                <p>4. <i>Fees and Payments</i></p>

                                                <p>The terms of payment and applicable fees for appointments will be
                                                    explicitly communicated at the time of booking. Users should be aware
                                                    that payment for healthcare services rendered may be required, depending
                                                    on the policies of the respective healthcare provider.</p>

                                                <p>5. <i>Privacy and Data Security</i></p>

                                                <p>&nbsp;&nbsp;• Data Collection:
                                                    During the appointment booking process, our service collects and stores
                                                    the following personal data:
                                                    <br>&nbsp;&nbsp;- Full Name
                                                    <br>&nbsp;&nbsp;- Contact Information (email address, phone number,
                                                    etc.)
                                                    <br>&nbsp;&nbsp;- Appointment Preferences
                                                    <br>&nbsp;&nbsp;- Other Information voluntarily provided by the user
                                                    <br>
                                                    <br>
                                                    &nbsp;&nbsp;• Use of Gathered Data:
                                                    The collected data is used solely for the purpose of facilitating and
                                                    managing appointment bookings. This includes, but is not limited to,
                                                    sending confirmation details, reminders, and updates related to your
                                                    appointments.
                                                    <br>
                                                    <br>
                                                    &nbsp;&nbsp;• Data Protection:
                                                    We are committed to safeguarding your data and have implemented security
                                                    measures to prevent unauthorized access, disclosure, alteration, and
                                                    destruction of your personal information.
                                                    <br>
                                                    <br>
                                                    &nbsp;&nbsp;• Third-Party Sharing:
                                                    We do not sell, trade, or otherwise transfer your personal information
                                                    to third parties. Your data is used exclusively for the stated purposes
                                                    within our service.
                                                    <br>
                                                    <br>
                                                    &nbsp;&nbsp;• Your Consent:
                                                    By using our service and providing your personal information, you
                                                    consent to the collection and use of this information as outlined in our
                                                    Privacy Policy.
                                                    <br>
                                                    <br>
                                                    • Privacy Policy:
                                                    For more details on how your personal information is handled, please
                                                    refer to our Privacy Policy, which provides a comprehensive overview of
                                                    our data protection practices.

                                                    Please take the time to read our Privacy Policy carefully. If you have
                                                    any questions or concerns regarding the handling of your data, feel free
                                                    to contact us at medicare@gmail.com.
                                                </p>

                                                <p>6. <i>Liability</i></p>

                                                <p>&nbsp;&nbsp;It is crucial to recognize that our service acts solely as a
                                                    booking
                                                    platform and does not provide medical services. We are not responsible
                                                    for the quality or outcomes of medical care provided by healthcare
                                                    professionals. Users of the service should acknowledge that we are not
                                                    liable for any damages, losses, or injuries that may occur as a result
                                                    of using the service or the medical care provided by healthcare
                                                    professionals.</p>

                                                <p>7. <i>Changes to Terms and Conditions</i></p>

                                                <p>&nbsp;&nbsp;Our terms and conditions may be updated from time to time.
                                                    Any changes
                                                    will be posted on our website, and users are encouraged to review these
                                                    terms periodically to remain informed about the latest updates.</p>

                                                <p>8. <i>Termination</i></p>

                                                <p>&nbsp;&nbsp;We retain the right to terminate a user's access to the
                                                    service at our
                                                    discretion, especially in cases of repeated violations of the terms and
                                                    conditions or any misuse of the service.</p>

                                                <p>9. <i>Governing Law</i></p>

                                                <p>&nbsp;&nbsp; These terms and conditions are governed by the laws of the
                                                    Republic of the Philippines. Any disputes or legal matters arising from
                                                    the use of our service, including matters related to data privacy, shall
                                                    be subject to the jurisdiction of the appropriate courts in the
                                                    Philippines.
                                                    <br><br>

                                                    &nbsp;&nbsp;In particular, the processing of personal data collected
                                                    through our service is subject to the provisions of the Data Privacy Act
                                                    of the Philippines (Republic Act No. 10173). We are committed to
                                                    complying with the principles and requirements of this act, ensuring the
                                                    protection and privacy of your personal information.
                                                    <br><br>
                                                    &nbsp;&nbsp;If you have any concerns or questions regarding the
                                                    processing of your personal data, please refer to our Privacy Policy or
                                                    contact us at medicare@gmail.com. We are dedicated to addressing your
                                                    inquiries and maintaining transparency in our data processing
                                                    practices..
                                                </p>

                                                <p>10. <i>Contact Information</i></p>

                                                <p>&nbsp;&nbsp;If you have any questions or concerns regarding these terms
                                                    and
                                                    conditions, please do not hesitate to contact us using the provided
                                                    contact information. We are committed to providing a transparent and
                                                    satisfactory experience for our users and are readily available to
                                                    address any inquiries.</p>
                                                </p>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-success" data-dismiss="modal">I
                                                        Agree</button>
                                                    <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">Back</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
            loadProvinces();
            var availabilityDates = [];
            var holidayDates = [];

            $.ajax({
                url: '/user/appointment/holiday',
                method: 'GET',
                success: function(data) {
                    holidayDates = data.map(function(event) {
                        return event.start;
                    });
                },
                error: function() {
                    console.log('Failed to fetch holiday data from the server.');
                }
            });

            $('#calendar').fullCalendar({
                selectable: true,
                selectHelper: true,

                select: function(start, end, allDay) {

                    clickedDateString = start.format('YYYY-MM-DD');

                    if (holidayDates.includes(clickedDateString)) {
                        return; // Do nothing if it's a holiday
                    }

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: '/user/appointment/doctor/specialties', // Route to the Laravel controller method
                        data: {
                            date: clickedDateString
                        },
                        success: function(data) {
                            // Update the dropdown with available doctors' specialties
                            var dropdown = $('#specialties');
                            dropdown.empty();

                            // Add a default option as the first option in the select
                            dropdown.append($('<option></option>').attr('value', '').text(
                                'Select Specialist'));

                            if (data.length > 0) {
                                $.each(data, function(index, doctor) {
                                    dropdown.append($('<option></option>').attr(
                                        'value', doctor.id).text('Dr. ' +
                                        doctor.firstName + ' ' + doctor
                                        .lastName + ' - ' + doctor.specialty
                                        ));
                                });
                            } else {
                                // Handle case where no doctors are available for the selected date
                                dropdown.append($('<option></option>').attr('value', '')
                                    .text('No available doctors'));
                            }


                        },
                        error: function() {
                            console.log('Failed to fetch available doctors data.');
                        }
                    });

                    openEventModal(start.format('YYYY-MM-DD'));
                },

                header: {
                    left: 'month, agendaWeek, agendaDay, list',
                    center: 'title',
                    right: 'prev, today, next'
                },
                buttonText: {
                    today: 'Today',
                    month: 'Month',
                    agendaWeek: 'Week',
                    agendaDay: 'Day',
                    list: 'List',
                },
                eventSources: [{
                        url: '/user/appointment/event',
                        method: 'GET',
                        textColor: 'white',
                    },
                    {
                        url: '/user/appointment/holiday',
                        method: 'GET',
                        textColor: 'white',
                    },
                ],

                dayRender: function(date, cell) {
                    var currentDate = moment(); // Get the current date
                    var cellDate = moment(date);

                    // Compare the cell date with the current date
                    if (cellDate.isBefore(currentDate, 'day')) {
                        // Past days: Set a different background color
                        cell.css("background", "lightgray");
                    }
                    if (cellDate.isSame(currentDate, 'day')) {
                        // Current day: Set a different background color
                        cell.css("background", "yellow");
                    } else if (cellDate.day() === 0 || cellDate.day() === 6) {
                        // Weekend days: Set a different background color
                        cell.css("background", "lightpink");
                    }
                },

                eventRender: function(event, element) {
                    var eventColor;

                    // Check the event type and set the color accordingly
                    switch (event.type) {
                        case 'holiday':
                            eventColor = 'red';
                            break;
                        case 'availability':
                            // Use the default color for availability events
                            break;
                    }

                    switch (event.status) {
                        case 'Pending':
                            eventColor = 'orange';
                            break;
                        case 'Confirmed':
                            eventColor = 'green';
                            break;
                        case 'Done':
                            eventColor = 'darkblue';
                            break;
                    }

                    // Set the background color for the event
                    if (eventColor) {
                        element.css('background-color', eventColor);
                    }
                },

                selectAllow: function(selectInfo) {
                    var selectedStartDate = moment(selectInfo.start);

                    console.log(selectedStartDate);

                    return selectedStartDate.isSameOrAfter(moment(), 'day') && selectedStartDate
                        .day() !== 0 && selectedStartDate.day() !== 6;
                },
                eventClick: function(info) {

                    if (info.type === 'holiday') {
                        displayHolidayDetails(info);
                    }

                    if (info.status === 'Pending') {
                        cancelEventDetails(info);
                    } else if (info.status === 'Confimed' || info.status === 'Done') {
                        displayEventDetails(info);
                    }
                },
            });

            function openEventModal(date) {
                $('#createModal').modal('show');
                $('#appointment_date').val(date);
            }

            function displayEventDetails(event) {
                $('#infoModal').modal('show');
                displayEventInfo(event);
            }

            function cancelEventDetails(event) {
                $('#cancelModal').modal('show');
                displayCancelEventInfo(event);
            }

            function displayHolidayDetails(event) {
                $('#holidayModal').modal('show');
                displayHolidayInfo(event);
            }

            function displayEventInfo(event) {
                $('#eventName').text(event.title);
                $('#eventStartDate').text(moment(event.start).format('LLLL'));
                $('#eventEndDate').text(moment(event.end).format('LLLL'));

                var statusText;
                var backgroundColor;

                switch (event.status) {
                    case 'Pending':
                        statusText = 'Waiting for confirmation.';
                        backgroundColor = 'orange';
                        break;
                    case 'Confirmed':
                        statusText = 'Confirmed.';
                        backgroundColor = 'green';
                        break;
                    case 'Done':
                        statusText = 'Done.';
                        backgroundColor = 'darkblue';
                        break;
                }

                $('#status').text(statusText);
                $('#infoBg').css('background-color', backgroundColor);
            }

            function displayCancelEventInfo(event) {
                $('#cancelId').val(event.appointment_id);
                $('#cancelEventName').text(event.title);
                $('#cancelEventStartDate').text(moment(event.start).format('LLLL'));
                $('#cancelEventEndDate').text(moment(event.end).format('LLLL'));

                if (event.status == 'Pending') {
                    $('#cancelStatus').text('Waiting for confimation.');
                } else {
                    $('#cancelStatus').text(event.status);
                }
            }

            function displayHolidayInfo(event) {
                $('#holidayName').text(event.title);
                $('#holidayDate').text(moment(event.start).format('LLLL'));
            }
        });

        function formatPhoneNumber(input) {
            // Remove any non-numeric characters
            input.value = input.value.replace(/[^0-9+]/g, '');

            // Check if the input starts with "09" and change it to "+639"
            if (input.value.startsWith('09')) {
                input.value = '+639' + input.value.substring(2);
            }
        }

        function loadProvinces() {
            $.ajax({
                type: "POST",
                url: '/user/appointment/province',
                data: {
                    selectedSpecialty: selectedSpecialty,
                },
                success: function(data) {
                    var dropdown = $('#province');
                    dropdown.empty();

                    // Add a default option as the first option in the select
                    dropdown.append($('<option></option>').attr('value', '').text(
                        'Select Available Time'));

                    $.each(data, function(index, time) {
                        dropdown.append($('<option></option>').attr('value', time).text(time));
                    });
                },
                error: function() {
                    console.log('Failed to fetch available time data.');
                }
            });
        }


        $('#specialties, #appointment_date').change(function() {
            var selectedSpecialty = $('#specialties').val();
            var selectedDate = $('#appointment_date').val();

            $.ajax({
                type: "POST",
                url: '/user/appointment/doctor/specialties/time',
                data: {
                    selectedSpecialty: selectedSpecialty,
                    selectedDate: selectedDate
                },
                success: function(data) {
                    var dropdown = $('#appointment_time');
                    dropdown.empty();

                    // Add a default option as the first option in the select
                    dropdown.append($('<option></option>').attr('value', '').text(
                        'Select Available Time'));

                    $.each(data, function(index, time) {
                        dropdown.append($('<option></option>').attr('value', time).text(time));
                    });
                },
                error: function() {
                    console.log('Failed to fetch available time data.');
                }
            });
        });
    </script>
@endsection
