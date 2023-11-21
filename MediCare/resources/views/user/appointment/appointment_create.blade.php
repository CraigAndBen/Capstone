@extends('layouts.inner_home')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.css" rel="stylesheet">
    <!-- Moment.js for date handling -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <!-- FullCalendar JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.js"></script>

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
                                            <p class="f-16 mt-2">Select your preferred date, fill out the form below, and we will get back to you soon with more updates to plan your appointment.</p>
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
                                <div class="mb-3">
                                    <div id="calendar" style="max-height: 700px; max-width: 100%"></div>
                                </div>

                                <div class="container my-5">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6 text-center">
                                            <h3>Event Color Legend</h3>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Event Type</th>
                                                        <th>Color</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Holiday</td>
                                                        <td style="background-color: green;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Appointment (Pending)</td>
                                                        <td style="background-color: #E1AA74;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Appointment (Confirmed)</td>
                                                        <td style="background-color: #3876BF;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Appointment (Done)</td>
                                                        <td style="background-color: #192655;"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <div id="datePlaceholder"></div>

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
                                                                <input type="text" class="form-control"
                                                                    id="floatingInput street" name="street"
                                                                    placeholder="Street" />
                                                                <label for="floatingInput">Street <span
                                                                        style="color: red;">*</span></label>
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
                                                            <div class=" form-floating mb-3">
                                                                <input type="text" class="form-control"
                                                                    id="floatingInput province" name="province"
                                                                    placeholder="Province" />
                                                                <label for="floatingInput">Province <span
                                                                        style="color: red;">*</span></label>
                                                            </div>
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
                                            <div class="modal-header d-flex justify-content-center bg-primary">
                                                <h3 class="modal-title text-white" id="staticBackdropLabel">Doctor
                                                    Appointment
                                                </h3>
                                            </div>
                                            <div class="modal-body">
                                                <h4 id="eventName" class="pb-3"></h4>
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
                                            <div class="modal-header d-flex justify-content-center bg-primary">
                                                <h3 class="modal-title text-white" id="staticBackdropLabel">Doctor
                                                    Appointment
                                                </h3>
                                            </div>
                                            <div class="modal-body">
                                                <h4 id="cancelEventName" class="pb-3"></h4>
                                                <p><strong>Appointment Start:</strong> <span id="cancelEventStartDate"></span>
                                                </p>
                                                <p><strong>Appointment End:</strong> <span id="cancelEventEndDate"></span>
                                                <p><strong>Appointment Status:</strong> <span id="cancelStatus"></span>
                                                </p>
                                                <form action="{{route('user.appointment.calendar.cancel')}}" method="POST">
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
                                            <div class="modal-header d-flex justify-content-center bg-primary">
                                                <h3 class="modal-title text-white" id="staticBackdropLabel">Holiday
                                                </h3>
                                            </div>
                                            <div class="modal-body text-center">
                                                <h4 id="holidayName" class="pb-3"></h4>
                                                <p><strong>Date:</strong> <span id="date"></span>
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

                                                <p>&nbsp;&nbsp;The protection of your privacy is of utmost importance to us.
                                                    Our
                                                    service collects and stores personal data as part of the appointment
                                                    booking process. For details on how your personal information is
                                                    collected, used, and protected, please refer to our Privacy Policy,
                                                    which outlines our commitment to safeguarding your data.</p>

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

                                                <p>&nbsp;&nbsp;These terms and conditions are governed by the laws of
                                                    Philippines, and any disputes or legal matters will be subject to the
                                                    jurisdiction of the appropriate courts in that jurisdiction.</p>

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
        document.addEventListener('DOMContentLoaded', function() {
            var holidayDates = []; // Initialize an empty array

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

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                eventSources: [{
                        url: '/user/appointment/event', // Your existing event source
                    },
                    {
                        url: '/user/appointment/holiday', // New static holiday event source
                    },
                ],

                dateClick: function(info) {
                    var clickedDate = moment(info.date);

                    // Check if the clicked date is a Saturday or Sunday
                    if (clickedDate.day() === 0 || clickedDate.day() === 6) {
                        return; // Do nothing if it's a Saturday or Sunday
                    }

                    // Check if the clicked date is before the current date
                    if (clickedDate.isSameOrBefore(moment(), 'day')) {
                        return; // Do nothing if it's a past date
                    }

                    var currentDate = new Date();
                    var nextYearDate = new Date(currentDate.getFullYear() + 1, 0,
                        1); // January 1st of next year
                    if (clickedDate.isAfter(nextYearDate)) {  
                        return; // Do nothing if it's in the next year
                    }

                    var clickedDateString = clickedDate.format("YYYY-MM-DD");

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
                                        doctor.firstName + ' ' + doctor.lastName + ' - ' + doctor.specialty));
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

                    // Open a modal for event creation and set the date
                    openEventModal(info.dateStr);
                },
                eventClick: function(info) {
                    if (info.event.extendedProps.type === 'holiday') {
                        // This is a holiday event, you can perform specific actions
                        // For example, show holiday-related information

                        displayHolidayDetails(info.event);
                    }

                    var statusLowerCase = info.event.extendedProps.status.toLowerCase();

                    if (statusLowerCase === 'pending') {
                        cancelEventDetails(info.event);
                    } else {
                        displayEventDetails(info.event);
                    }
                },
                eventContent: function(arg) {
                    var event = arg.event;
                    var containerEl = document.createElement('div');

                    // Customize the appearance of events based on the 'type' property in event.extendedProps
                    if (event.extendedProps.type === 'holiday') {
                        containerEl.style.backgroundColor =
                            'green'; // Set a red background color for holiday events
                    } else if (event.extendedProps.type === 'appointment') {

                        var statusLowerCase = event.extendedProps.status.toLowerCase();

                        if (statusLowerCase === 'pending') {
                            containerEl.style.backgroundColor =
                                '#E1AA74'; // Set a blue background color
                            containerEl.style.color =
                                'white'; // Set the text color to white a green background color for availability events
                        } else if (statusLowerCase === 'confirmed') {
                            containerEl.style.backgroundColor =
                                '#3876BF'; // Set a blue background color
                            containerEl.style.color =
                                'white'; // Set the text color to white a green background color for availability events
                        } else if (statusLowerCase === 'done') {
                            containerEl.style.backgroundColor =
                                '#192655'; // Set a blue background color
                            containerEl.style.color =
                                'white'; // Set the text color to white a green background color for availability events
                        }

                    }
                    containerEl.style.textAlign = 'center'; // Center the event content
                    containerEl.style.margin = '0 auto';
                    containerEl.style.width = '100%';

                    // Add a custom CSS class to the event container
                    containerEl.classList.add(
                        'custom-event'); // Add a custom CSS class to the event container

                    // You can further customize the content inside the event container (e.g., event title)
                    var titleEl = document.createElement('div');
                    titleEl.innerText = event.title;

                    // Apply padding to the title element
                    titleEl.style.padding = '10px'; // Adjust the padding value as needed
                    titleEl.style.textAlign = 'center'; // Center the text

                    containerEl.appendChild(titleEl);

                    // Return the customized event content
                    return {
                        domNodes: [containerEl]
                    };
                }
            });

            calendar.render();

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

                if (event.extendedProps.status == 'Pending') {
                    $('#status').text('Waiting for confimation.');
                } else {
                    $('#status').text(event.extendedProps.status);
                }
            }

            function displayCancelEventInfo(event) {
                $('#cancelId').val(event.extendedProps.appointment_id);
                $('#cancelEventName').text(event.title);
                $('#cancelEventStartDate').text(moment(event.start).format('LLLL'));
                $('#cancelEventEndDate').text(moment(event.end).format('LLLL'));

                if (event.extendedProps.status == 'Pending') {
                    $('#cancelStatus').text('Waiting for confimation.');
                } else {
                    $('#cancelStatus').text(event.extendedProps.status);
                }
            }

            function displayHolidayInfo(event) {
                $('#holidayName').text(event.title);
                $('#date').text(moment(event.start).format('LLLL'));
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
