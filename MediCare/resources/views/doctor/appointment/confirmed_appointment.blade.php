@extends('layouts.inner_doctor')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container pb-3">
        <div class="pc-content ">
            <!-- [ breadcrumb ] start -->
            <div class="page-header mt-4">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Confirmed Appointment List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Confirmed Appointment List</li>
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
                            <h1 class="display-6">Confirmed Appointment List </h1>
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

                                @if ($appointments->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Appointment Yet.
                                    </div>
                                @else
                                    <div class="row my-4">
                                        <table class="table table-hover" id="patientTable">
                                            <thead class="table-primary text-light text-center">
                                                <tr>
                                                    <th>Appointment Type</th>
                                                    <th>Patient Name</th>
                                                    <th>Appointment Date</th>
                                                    <th>Appointment Time</th>
                                                    <th>Appointment Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @foreach ($appointments as $appointment)
                                                    <tr>
                                                        <td>{{ ucwords($appointment->appointment_type) }}</td>
                                                        <td>{{ ucwords($appointment->first_name) }}
                                                            {{ ucwords($appointment->last_name) }}</td>
                                                        <td>{{ date('M d, Y', strtotime($appointment->appointment_date)) }}
                                                        </td>
                                                        <td>{{ ucwords($appointment->appointment_time) }}</td>
                                                        @if ($appointment->status == 'pending')
                                                            <td>Waiting for confirmation</td>
                                                        @else
                                                            <td>{{ ucwords($appointment->status) }}</td>
                                                        @endif
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                                    data-toggle="dropdown">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    @if ($appointment->status == 'pending')
                                                                        <form
                                                                            action="{{ route('doctor.confirm.appointment') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="appointment_id"
                                                                                value="{{ $appointment->id }}">
                                                                            <input type="hidden" name="status"
                                                                                value="{{ $appointment->status }}">
                                                                            <button type="submit"
                                                                                class="dropdown-item btn btn-primary">Confirm</button>
                                                                        </form>
                                                                    @endif
                                                                    @if ($appointment->status == 'confirmed')
                                                                        <form action="{{ route('doctor.finish.appointment') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="appointment_id"
                                                                                value="{{ $appointment->id }}">
                                                                            <input type="hidden" name="status"
                                                                                value="{{ $appointment->status }}">
                                                                            <button type="submit"
                                                                                class="dropdown-item btn btn-primary">Done</button>
                                                                        </form>
                                                                    @endif
                                                                    @if ($appointment->status != 'pending')
                                                                        <form
                                                                            action="{{ route('doctor.appointment.report.view') }}"
                                                                            target="_blank" method="GET">
                                                                            @csrf
                                                                            <input type="hidden" name="appointment_id"
                                                                                id="appointment_id"
                                                                                value="{{ $appointment->id }}">
                                                                            <button type="submit"
                                                                                class="dropdown-item btn btn-primary">View
                                                                                Report</button>
                                                                        </form>
                                                                        <form
                                                                            action="{{ route('doctor.appointment.report.download') }}"
                                                                            method="GET">
                                                                            @csrf
                                                                            <input type="hidden" name="appointment_id"
                                                                                id="appointment_id"
                                                                                value="{{ $appointment->id }}">
                                                                            <button type="submit"
                                                                                class="dropdown-item btn btn-primary">Download
                                                                                Report</button>
                                                                        </form>
                                                                    @endif
    
    
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
    
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>


    @endsection

    @section('scripts')
        <script>
        </script>
    @endsection
