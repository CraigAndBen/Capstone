@extends('layouts.inner_superadmin')

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

    <!-- [ Main Content ] start -->
    <div class="pc-container pb-3">
        <div class="pc-content ">
            <!-- [ breadcrumb ] start -->
            <div class="page-header mt-4">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Calendar</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Calendar</li>
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
                            <h1 class="display-6">Calendar</h1>
                        </div>
                        <div class="card-body">
                            <div class="container">

                                <div class="d-flex justify-content-end mr-3">
                                    <div class="m-1">
                                        <form action="{{ route('superadmin.default.create.holiday') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="year" value="{{ $currentYear }}">
                                            <button type="submit" class="btn btn-primary">Add Default Holidays</button>
                                        </form>
                                    </div>
                                </div>
                                <hr>

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


                                <div class="m-3 p-3">
                                    <div id="calendar"></div>
                                </div>

                                <div class="modal fade" id="holidayModal" data-bs-backdrop="static" data-bs-keyboard="false"
                                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex justify-content-center "
                                                style="background: red">
                                                <h3 class="modal-title text-white" id="staticBackdropLabel">Holiday
                                                </h3>
                                            </div>
                                            <form action="{{ route('superadmin.holiday.update') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="holidayName"
                                                            name="name" placeholder="Holiday Name" />
                                                        <label for="floatingInput">Holiday Name</label>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="date" class="form-control" id="holidayDate"
                                                            name="date" />
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="form-floating mb-3">
                                                            <input type="hidden" id="holidayId" name="id">
                                                            <select class="form-control  p-3" id="holidayType"
                                                                name="type">
                                                                <option value="">Select Type</option>
                                                                <option value="Default Holiday">Default Holiday</option>
                                                                <option value="Modified Holiday">Modified Holiday</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                            </form>
                                            <form action="{{ route('superadmin.holiday.delete') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="holidayId" id="deleteHolidayId">
                                                <button type="submit" class="btn btn-danger">Remove</button>
                                            </form>
                                            <button type="button" class="btn btn-primary"
                                            data-bs-dismiss="modal">Back</button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="createHolidayModal" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header d-flex justify-content-center " style="background: red">
                                            <h3 class="modal-title text-white" id="staticBackdropLabel">Holiday
                                            </h3>
                                        </div>
                                        <form action="{{ route('superadmin.holiday.create') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="div mb-3">
                                                    <h5>Date: <span id="createHolidayDate"></span></h5>
                                                </div>

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="Holiday Name" />
                                                    <label for="floatingInput">Holiday Name</label>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="row mt-2">
                                                        <div class="form-floating mb-3">
                                                            <input type="hidden" id="holidayCreateDate" name="date">
                                                            <select class="form-control  p-3" name="type">
                                                                <option value="">Select Type</option>
                                                                <option value="Default Holiday">Default Holiday
                                                                </option>
                                                                <option value="Modified Holiday">Modified Holiday
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Add</button>
                                        </form>
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Back</button>
                                    </div>
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
        $(document).ready(function() {

            // var holidayDates = [];

            // $.ajax({
            //     url: '/doctor/appointment/calendar/holiday',
            //     method: 'GET',
            //     success: function(data) {
            //         holidayDates = data.map(function(event) {
            //             return event.start;
            //         });
            //     },
            //     error: function() {
            //         console.log('Failed to fetch holiday data from the server.');
            //     }
            // });

            $('#calendar').fullCalendar({
                selectable: true,
                selectHelper: true,

                select: function(start, end, allDay) {
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
                    url: '/superadmin/calendar/holidays',
                    method: 'GET',
                    textColor: 'white',
                }, ],

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
                    var eventColor = 'red';

                    element.css('background-color', eventColor);

                },

                selectAllow: function(selectInfo) {
                    var selectedStartDate = moment(selectInfo.start);
                },
                eventClick: function(info) {
                    displayHolidayDetails(info);

                },
            });

            function openEventModal(date) {
                const dateObj = new Date(date);
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const formattedDate = dateObj.toLocaleDateString('en-US', options);

                $('#createHolidayModal').modal('show');
                $('#createHolidayDate').text(formattedDate);
                $('#holidayCreateDate').val(date);
            }

            function displayHolidayDetails(event) {
                // Example: Display event details in a modal
                $('#holidayModal').modal('show');
                displayHolidayInfo(event);
            }

            function updateHolidayDetails(event) {
                // Example: Display event details in a modal
                $('#holidayModal').modal('show');
                displayHolidayInfo(event);
            }


            function displayAvailabilityDetails(event) {
                // Example: Display event details in a modal
                $('#updateAvailabilityModal').modal('show');
                displayAvailabilityInfo(event);
            }

            function displayHolidayInfo(event) {
                var currentDate = moment(event.start).format('YYYY-MM-DD');

                $('#holidayName').val(event.title);
                $('#holidayDate').val(moment(event.start).format('YYYY/MM/DD'));
                $('#holidayDate').val(currentDate);
                $('#holidayType').val(event.type);
                $('#holidayId').val(event.holiday_id);
                $('#deleteHolidayId').val(event.holiday_id);
            }

        });
    </script>
@endsection
