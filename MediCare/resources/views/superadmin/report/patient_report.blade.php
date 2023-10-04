@extends('layouts.patient_report')
@section('style')
    <style>
        @media print {

            /* Hide the button when printing */
            #printButton {
                display: none;
            }

            #back {
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
                <h5>Report Type: <i><b>Patient Information Document</b></i></h5>
                <h5>Date: <i><b>{{ $currentDate }}</b></i></h5>
                <h5>Time: <i><b>{{ $currentTime }}</b></i></h5>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>

        </div>
        <div class="container">
            <div class="row mb-5 justify-content-center">
                <h4><i>Patient Information Document</i></h4>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName"
                        value="{{ $patient->first_name }}">
                </div>
                <div class="col-md-4">
                    <label for="middleName" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middleName"
                        value="{{ $patient->middle_name }}">
                </div>
                <div class="col-md-4">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" value="{{$patient->last_name}}">
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <label for="street" class="form-label">Street</label>
                    <input type="text" class="form-control" id="street" value="{{$patient->street}}">
                </div>
                <div class="col-md-6">
                    <label for="brgy" class="form-label">Brgy</label>
                    <input type="text" class="form-control" id="brgy" value="{{$patient->brgy}}">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" value="{{$patient->city}}">
                </div>
                <div class="col-md-6">
                    <label for="province" class="form-label">Province</label>
                    <input type="text" class="form-control" id="province" value="{{$patient->province}}">
                </div>
            </div>
            <hr>
            <div class="row mt-2">
                <div class="col-md-4">
                    <label for="number" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" value="{{$patient->phone}}">
                </div>
                <div class="col-md-4">
                    <label for="dateOfBirth" class="form-label">Birthdate</label>
                    <input type="date" class="form-control" id="birthdate" value="{{$patient->birthdate}}">
                </div>
                <div class="col-md-4">
                    <label for="gender" class="form-label">Gender</label>
                    <input type="text" class="form-control" id="gender" value="{{ucwords($patient->gender)}}">
                </div>
            </div>
            <hr>

            @if ($patient->type == 'admitted_patient')
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="dateOfBirth" class="form-label">Admitted Date</label>
                        <input type="date" class="form-control" id="admitted_date" value="{{$patient->admitted_date}}">
                    </div>
                    <div class="col-md-6">
                        <label for="dateOfBirth" class="form-label">Discharged Date</label>
                        <input type="date" class="form-control" id="discharged_date" value="{{$patient->discharged_date}}">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="number" class="form-label">Room No</label>
                        <input type="text" class="form-control" id="room_number" value="{{$patient->room_number}}">
                    </div>
                    <div class="col-md-6">
                        <label for="number" class="form-label">Bed No</label>
                        <input type="text" class="form-control" id="bed_number" value="{{$patient->bed_number}}">
                    </div>
                </div>
                <hr>
            @endif

            @if ($patient->type == 'outpatient')
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="dateOfBirth" class="form-label">Date Date</label>
                        <input type="date" class="form-control" id="date" value="{{$patient->date}}">
                    </div>
                    <div class="col-md-6">
                        <label for="dateOfBirth" class="form-label">Time</label>
                        <input type="time" class="form-control" id="time" value="{{$patient->time}}">
                    </div>
                </div>
                <hr>
            @endif

            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="number" class="form-label">Physician</label>
                    <input type="text" class="form-control" id="physician" value="Dr. {{ucwords($doctor->first_name)}} {{ucwords($doctor->last_name)}}">
                </div>
                <div class="col-md-6">
                    <label for="number" class="form-label">Medical Condition</label>
                    <input type="text" class="form-control" id="medical_condition" value="{{ucwords($patient->medical_condition)}}">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label for="number" class="form-label">Diagnosis</label>
                    <input type="text" class="form-control" id="diagnosis" value="{{ucwords($patient->diagnosis)}}">
                </div>
                <div class="col-md-6">
                    <label for="number" class="form-label">Medication</label>
                    <input type="text" class="form-control" id="medical_condition" value="{{ucwords($patient->medication)}}">
                </div>
            </div>
            <hr>
            <div class="row mt-2">
                <div class="col-md-4">
                    <label for="number" class="form-label">Guardian First Name</label>
                    <input type="text" class="form-control" value="{{ucwords($patient->guardian_first_name)}}">
                </div>
                <div class="col-md-4">
                    <label for="number" class="form-label">Guardian Last Name</label>
                    <input type="text" class="form-control" value="{{ucwords($patient->guardian_last_name)}}">
                </div>
                <div class="col-md-4">
                    <label for="number" class="form-label">Relationship</label>
                    <input type="text" class="form-control" value="{{ucwords($patient->relationship)}}">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <label for="number" class="form-label">Guardian Birthdate</label>
                    <input type="date" class="form-control" value="{{$patient->guardian_birthdate}}">
                </div>
                <div class="col-md-4">
                    <label for="number" class="form-label">Guardian Phone</label>
                    <input type="text" class="form-control" value="{{$patient->guardian_phone}}">
                </div>
                <div class="col-md-4">
                    <label for="number" class="form-label">Guardian Email</label>
                    <input type="text" class="form-control" value="{{$patient->guardian_email}}">
                </div>
            </div>

        </div>
        <div class="row justify-content-end align-items-end my-5">
            <div class="col-10 text-right">
                <button id="printButton" class="btn btn-primary">Preview Report</button>
                <a id="back" href="{{ route('admin.patient') }}" class="btn btn-danger">Back</a>
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
