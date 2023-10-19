@extends('layouts.patient_report')
@section('style')
    <style>
        @media print {

            /* Hide the button when printing */
            #printButton {
                display: none;
            }

            #goBack {
                display: none;
            }

            #done {
                display: none;
            }
        }

        @page {
            size: vertical;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
@endsection
@section('content')
    <div class="container mt-2">

        <div class="row justify-content-first align-items-first my-3">
            <div class="col-7 my-5">
                <h5>Report Type: <i><b>Doctor Appointment Report</b></i></h5>
                <h5>Reference Number: <i><b>{{ $reference }}</b></i></h5>
                <h5>Date: <i><b>{{ date('F j, Y', strtotime($currentDate)) }}</b></i></h5>
                <h5>Time: <i><b>{{ $currentTime }}</b></i></h5>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>

        </div>
        <div class="container mb-3">
            <div class="row mb-5 justify-content-center">
                <h4><i>Doctor Appointment Document</i></h4>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" value="{{ $appointment->first_name }}">
                </div>
                <div class="col-md-4">
                    <label for="middleName" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middleName" value="{{ $appointment->middle_name }}">
                </div>
                <div class="col-md-4">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" value="{{ $appointment->last_name }}">
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <label for="street" class="form-label">Street</label>
                    <input type="text" class="form-control" id="street" value="{{ $appointment->street }}">
                </div>
                <div class="col-md-6">
                    <label for="brgy" class="form-label">Brgy</label>
                    <input type="text" class="form-control" id="brgy" value="{{ $appointment->brgy }}">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" value="{{ $appointment->city }}">
                </div>
                <div class="col-md-6">
                    <label for="province" class="form-label">Province</label>
                    <input type="text" class="form-control" id="province" value="{{ $appointment->province }}">
                </div>
            </div>
            <hr>
            <div class="row mt-2">
                <div class="col-md-4">
                    <label for="number" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" value="{{ $appointment->phone }}">
                </div>
                <div class="col-md-4">
                    <label for="dateOfBirth" class="form-label">Birthdate</label>
                    <input type="date" class="form-control" id="birthdate" value="{{ $appointment->birthdate }}">
                </div>
                <div class="col-md-4">
                    <label for="gender" class="form-label">Gender</label>
                    <input type="text" class="form-control" id="gender" value="{{ ucwords($appointment->gender) }}">
                </div>
            </div>
            <hr>

            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="dateOfBirth" class="form-label">Date Date</label>
                    <input type="date" class="form-control" id="date" value="{{ $appointment->appointment_date }}">
                </div>
                <div class="col-md-6">
                    <label for="dateOfBirth" class="form-label">Time</label>
                    <input type="text" class="form-control" id="time" value="{{ $appointment->appointment_time }}">
                </div>
            </div>
            <hr>

            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="number" class="form-label">Physician</label>
                    <input type="text" class="form-control" id="physician"
                        value="Dr. {{ ucwords($doctor->first_name) }} {{ ucwords($doctor->last_name) }}">
                </div>
                <div class="col-md-6">
                    <label for="number" class="form-label">Specialties</label>
                    <input type="text" class="form-control" id="specialties"
                        value="{{ ucwords($appointment->specialties) }}">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="number" class="form-label">Reason</label>
                    <input type="text" class="form-control" id="reason"
                        value="{{ ucwords($appointment->reason) }}">
                </div>
                <div class="col-md-6">
                    <label for="number" class="form-label">Status</label>
                    <input type="text" class="form-control" id="medical_condition"
                        value="{{ ucwords($appointment->status) }}">
                </div>
            </div>
            <hr>

        </div>
        <div class="row justify-content-end my-5">
            <div class="col-2">

            </div>
            <div class="col-10">
                <div class="d-flex justify-content-end align-items-end">
                    <button id="printButton" class="btn btn-primary">Preview Report</button>
                    <form action="{{ route('doctor.appointment.report.save') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $appointment->id }}" name="appointment_id">
                        <input type="hidden" value="{{ $appointment->account_id }}" name="user_id">
                        <input type="hidden" value="{{ $reference }}" name="reference">
                        <input type="hidden" value="{{ $currentDate }}" name="date">
                        <input type="hidden" value="{{ $currentTime }}" name="time">
                        <button id='done' type="submit" class="btn btn-success ml-2">Done</button>
                    </form>
                    <a id="goBack" href="{{ route('doctor.appointment') }}" class="btn btn-danger ml-2">Back</a>
                </div>
            </div>
            <div class="col-2">
            </div>
        </div>


    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            // Attach a click event handler to the button
            $("#printButton").click(function() {
                // Call the window.print() function to open the print dialog
                window.print();
            });
        });
    </script>
@endsection
