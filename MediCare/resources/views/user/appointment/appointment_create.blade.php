@extends('layouts.inner_home')

@section('content')

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
                                            <p class="f-16 mt-2">Fill the form below and we will get back soon to you for
                                                more updates and plan your appointment.</p>
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
                                <div class="mb-3">
                                    <div id="calendar" style="max-height: 700px; max-width: 100%"></div>
                                </div>


                                <div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false"
                                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-light">
                                                <h3 class="modal-title" id="staticBackdropLabel">Create Doctor Appointment
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
                                                                <label for="floatingInput">First Name</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-floating mb-3 ">
                                                                <input type="text" class="form-control"
                                                                    id="floatingInput middle_name" placeholder="Middle Name"
                                                                    name="middle_name" />
                                                                <label for="floatingInput">Middle Name</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-floating mb-3 ">
                                                                <input type="text" class="form-control"
                                                                    id="floatingInput last_name" placeholder="Last Name"
                                                                    name="last_name" />
                                                                <label for="floatingInput">Last Name</label>
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
                                                                <label for="floatingInput">Street</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class=" form-floating mb-3">
                                                                <input type="text" class="form-control"
                                                                    id="floatingInput brgy" name="brgy"
                                                                    placeholder="Brgy" />
                                                                <label for="floatingInput">State/Barangay</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class=" form-floating mb-3">
                                                                <input type="text" class="form-control"
                                                                    id="floatingInput city" name="city"
                                                                    placeholder="City" />
                                                                <label for="floatingInput">City</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class=" form-floating mb-3">
                                                                <input type="text" class="form-control"
                                                                    id="floatingInput province" name="province"
                                                                    placeholder="Province" />
                                                                <label for="floatingInput">Province</label>
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
                                                                <label for="floatingInput">Date of Birth</label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 col-md-6">
                                                            <select class="form-control  p-3" id="gender"
                                                                name="gender">
                                                                <option>Select Gender</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                                <option value="diagnostic appointment">Others</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-floating mb-3">
                                                        <input type="number" class="form-control"
                                                            id="floatingInput phone" name="phone"
                                                            placeholder="Phone" />
                                                        <label for="floatingInput">Phone</label>
                                                    </div>
                                                    <hr>
                                                    <div class="form-floating mb-3">
                                                        <input type="email" class="form-control"
                                                            id="floatingInput email" name="email"
                                                            placeholder="Email Address" />
                                                        <label for="floatingInput">Email Address</label>
                                                    </div>
                                                    <hr>
                                                    <div class="row mt-4">
                                                        <h5>Which specialist do you want to appoint of?</h5>
                                                        <div class="form-floating mb-3">
                                                            <select class="form-control p-3" id="specialties"
                                                                name="specialties">
                                                                <option>Select Specialist</option>
                                                                @foreach ($infos as $info)
                                                                    <option value="{{ $info->specialties }}">
                                                                        {{ $info->specialties }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row mt-4">
                                                        <div class="form-floating mb-3">
                                                            <h5>Which procedure do you want to make an appointment for?</h5>
                                                            <select class="form-control  p-3" id="appointment_type"
                                                                name="appointment_type">
                                                                <option value="">Select a Type of Appointment
                                                                </option>
                                                                <option value="regular check-up">Regular Check-up</option>
                                                                <option value="Follow-up appointment">Follow-up Appointment
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
                                                                <option>Select Time of Appointment</option>
                                                                @foreach ($timeList as $time)
                                                                    <option value="{{ $time }}">
                                                                        {{ $time }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <hr class="mb-3">
                                                    <h5>Reason for appointment</h5>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control"
                                                            id="floatingInput reason" name="reason"
                                                            placeholder="Reason For Appointment" />
                                                        <label for="floatingInput">Reason for Appointment</label>
                                                    </div>
                                                    <div class="d-flex mt-1 justify-content-between">
                                                        <div class="form-check">
                                                            <input class="form-check-input input-primary" type="checkbox"
                                                                id="check" name="check" />
                                                            <label class="form-check-label text-muted"
                                                                for="customCheckc1">I
                                                                agree to the
                                                                terms and conditions</label>
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
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '/user/appointment/event',
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

                    // Open a modal for event creation and set the date
                    openEventModal(info.dateStr);
                },
                eventClick: function(info) {
                    // Display event details in a modal
                    displayEventDetails(info.event);
                },
                eventRender: function(info) {
                    // Customize the appearance of events (e.g., add custom CSS classes)
                    info.el.classList.add('custom-event');
                }
            });
            calendar.render();

            function openEventModal(date) {
                // Example: Open a modal for event creation and set the date
                $('#createModal').modal('show');
                $('#appointment_date').val(date);
            }

            function displayEventDetails(event) {
                // Example: Display event details in a modal
                $('#infoModal').modal('show');
                displayEventInfo(event);
            }

            function displayEventInfo(event) {
                // Example: Populate and display event details
                $('#eventName').text(event.title);
                $('#eventStartDate').text(moment(event.start).format('LLLL'));
                $('#eventEndDate').text(moment(event.end).format('LLLL'));
                $('#status').text(event.extendedProps.status);
            }
        });
    </script>
@endsection
