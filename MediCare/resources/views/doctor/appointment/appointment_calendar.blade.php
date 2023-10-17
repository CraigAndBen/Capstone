@extends('layouts.inner_doctor')

@section('content')
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.css" rel="stylesheet">
    <!-- Moment.js for date handling -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <!-- FullCalendar JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.js"></script>
    <!-- [ Main Content ] start -->
    <div class="pc-container pb-3">
        <div class="pc-content ">
            <!-- [ breadcrumb ] start -->
            <div class="page-header mt-4">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Appointment List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Appointment List</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">

                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h1>Appointment Calendar</h1>
                        </div>
                        <div class="card-body">
                            <div class="container">

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

                                <div class="m-3 p-3">
                                    <div id="calendar" style="max-height: 540px;"></div>
                                </div>

                                <div class="modal fade" id="infoModal" data-bs-backdrop="static" data-bs-keyboard="false"
                                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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

                                <div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false"
                                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex justify-content-center bg-primary">
                                                <h3 class="modal-title text-white" id="staticBackdropLabel">Doctor
                                                    Appointment
                                                </h3>
                                            </div>

                                            <div class="modal-body">
                                                <h4 id="eventName" class="pb-3"></h4>
                                                <p><strong>Appointment Start:</strong> <span
                                                        id="confirmEventStartDate"></span>
                                                </p>
                                                <p><strong>Appointment End:</strong> <span id="confirmEventEndDate"></span>
                                                <p><strong>Appointment Status:</strong> <span id="confirmStatus"></span>
                                                </p>
                                            </div>
                                            <form action="{{ route('doctor.appointment.calendar.confirm') }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="appointment_id" id="id">
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Confirm</button>
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Back</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="availabilityModal" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex justify-content-center bg-primary">
                                                <h3 class="modal-title text-white" id="staticBackdropLabel">Doctor
                                                    Availability
                                                </h3>
                                            </div>
                                            <form action="{{ route('doctor.appointment.availability') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row mt-2">
                                                        <h3>Date: <i><span id="date"></span></i></h3>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="form-floating mb-3">
                                                            <input type="hidden" id="availabilityDate"
                                                                name="availabilityDate">
                                                            <select class="form-control  p-3" id="availability"
                                                                name="availability">
                                                                <option value="">Select availability</option>
                                                                <option value="available">Available</option>
                                                                <option value="unavailable">Unavaiable</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control"
                                                            id="floatingInput reason" name="reason"
                                                            placeholder="Reason" />
                                                        <label for="floatingInput">Reason</label>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Confirm</button>
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Back</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="updateAvailabilityModal" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex justify-content-center bg-primary">
                                                <h3 class="modal-title text-white" id="staticBackdropLabel">Doctor
                                                    Availability
                                                </h3>
                                            </div>
                                            <form action="{{ route('doctor.appointment.update.availability') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row mt-2">
                                                        <h3>Date: <i><span id="Updatedate"></span></i></h3>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="form-floating mb-3">
                                                            <input type="hidden" id="availability_id"
                                                                name="availability_id">
                                                            <select class="form-control  p-3" id="updateAvailability"
                                                                name="availability">
                                                                <option value="">Select availability</option>
                                                                <option value="available">Available</option>
                                                                <option value="unavailable">Unavaiable</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control"
                                                            id="updateReason" name="reason"
                                                            placeholder="Reason" />
                                                        <label for="floatingInput">Reason</label>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Back</button>
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
                            </div>
                        </div>
                    </div>

                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>


    @endsection

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var holidayDates = []; // Initialize an empty array
                var availabilityDates = []; // Initialize an empty array

                $.ajax({
                    url: '/doctor/appointment/calendar/holiday',
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

                $.ajax({
                    url: '/doctor/appointment/calendar/availability/dates',
                    method: 'GET',
                    success: function(data) {
                        availabilityDates = data.map(function(event) {
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
                            url: '/doctor/appointment/calendar/event', // Your existing event source
                        },
                        {
                            url: '/doctor/appointment/calendar/holiday', // New static holiday event source
                        },
                        {
                            url: '/doctor/appointment/calendar/availability/dates', // New static holiday event source
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

                        if (availabilityDates.includes(clickedDateString)) {
                            return; // Do nothing if it's a holiday
                        }

                        // Open a modal for event creation and set the date
                        openEventModal(info.dateStr);
                    },
                    eventClick: function(info) {

                        if (info.event.extendedProps.type === 'holiday') {
                            // This is a holiday event, you can perform specific actions
                            // For example, show holiday-related information
                            displayHolidayDetails(info.event);
                        }

                        if (info.event.extendedProps.type === 'availability') {
                            // This is a holiday event, you can perform specific actions
                            // For example, show holiday-related information
                            displayAvailabilityDetails(info.event);
                        }

                        var statusLowerCase = info.event.extendedProps.status.toLowerCase();

                        if (statusLowerCase === 'pending') {
                            // Your code for handling pending events here
                            ConfirmEventDetails(info.event);
                        } else {
                            // Your code for handling other event statuses here
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
                        } else if (event.extendedProps.type === 'availability') {
                            containerEl.style.backgroundColor =
                                'red'; // Set a green background color for availability events
                        }

                        // Add a custom CSS class to the event container
                        containerEl.classList.add(
                            'custom-event'); // Add a custom CSS class to the event container

                        // You can further customize the content inside the event container (e.g., event title)
                        var titleEl = document.createElement('div');
                        titleEl.innerText = event.title;

                        containerEl.appendChild(titleEl);

                        // Return the customized event content
                        return {
                            domNodes: [containerEl]
                        };
                    }
                });
                calendar.render();

                function openEventModal(date) {
                    // Example: Open a modal for event creation and set the date
                    $('#availabilityModal').modal('show');
                    $('#date').text(date);
                    $('#availabilityDate').val(date);
                    // $('#date').val(date);
                }

                function displayEventDetails(event) {
                    // Example: Display event details in a modal
                    $('#infoModal').modal('show');
                    displayEventInfo(event);
                }

                function ConfirmEventDetails(event) {
                    // Example: Display event details in a modal
                    $('#confirmModal').modal('show');
                    displayConfirmInfo(event);
                }

                function displayHolidayDetails(event) {
                    // Example: Display event details in a modal
                    $('#holidayModal').modal('show');
                    displayHolidayInfo(event);
                }

                function displayAvailabilityDetails(event) {
                    // Example: Display event details in a modal
                    $('#updateAvailabilityModal').modal('show');
                    displayAvailabilityInfo(event);
                }

                function displayEventInfo(event) {
                    // Example: Populate and display event details
                    $('#eventName').text(event.title);
                    $('#eventStartDate').text(moment(event.start).format('LLLL'));
                    $('#eventEndDate').text(moment(event.end).format('LLLL'));
                    $('#status').text(event.extendedProps.status);
                }

                function displayConfirmInfo(event) {
                    $('#id').val(event.extendedProps.appointment_id);
                    $('#confirmEventName').text(event.title);
                    $('#confirmEventStartDate').text(moment(event.start).format('LLLL'));
                    $('#confirmEventEndDate').text(moment(event.end).format('LLLL'));
                    $('#confirmStatus').text(event.extendedProps.status);
                }

                function displayHolidayInfo(event) {
                    // Example: Populate and display event details
                    $('#holidayName').text(event.title);
                    $('#holidayDate').text(moment(event.start).format('LLLL'));
                }

                function displayAvailabilityInfo(event) {
                    // Example: Populate and display event details
                    $('#updateDate').text(moment(event.start).format('LLLL'));
                    $('#availability_id').val(event.extendedProps.availability_id);
                    $('#updateAvailability').val(event.extendedProps.availability);
                    $('#updateReason').val(event.extendedProps.reason);

                    
                }
            });
        </script>
    @endsection
