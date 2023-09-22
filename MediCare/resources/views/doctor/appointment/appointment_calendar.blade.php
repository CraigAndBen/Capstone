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
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: '/doctor/appointment/calendar/events',
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
                        var statusLowerCase = info.event.extendedProps.status.toLowerCase();

                        if (statusLowerCase === 'pending') {
                            // Your code for handling pending events here
                            ConfirmEventDetails(info.event);
                        } else {
                            // Your code for handling other event statuses here
                            displayEventDetails(info.event);
                        }
                    },
                    eventRender: function(info) {
                        // Customize the appearance of events (e.g., add custom CSS classes)
                        info.el.classList.add('custom-event');
                    }
                });
                calendar.render();

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
            });
        </script>
    @endsection
