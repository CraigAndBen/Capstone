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
        {{-- <div class="row justify-content-center">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-floating mb-3 ">
                        <input type="hidden" id="id" name="id">
                        <input type="text" class="form-control ml-2" id="first_name" placeholder="First Name"
                            name="first_name" value="{{$patient->first_name}}"/>
                        <label for="floatingInput">First Name</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating mb-3 ">
                        <input type="text" class="form-control ml-2" id="middle_name" placeholder="Middle Name"
                            name="middle_name" />
                        <label for="floatingInput">Middle Name</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating mb-3 ">
                        <input type="phone" class="form-control" id="last_name" placeholder="Last Name"
                            name="last_name" />
                        <label for="floatingInput">Last Name</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" name="street" class="form-control" id="street" placeholder="Street" />
                        <label for="floatingInput">Street</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" name="brgy" class="form-control" id="brgy" placeholder="Brgy" />
                        <label for="floatingInput">Brgy</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" name="city" class="form-control" id="city" placeholder="City" />
                        <label for="floatingInput">City</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" name="province" class="form-control" id="province" placeholder="Street" />
                        <label for="floatingInput">Province</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input type="number" name="phone" class="form-control" id="phone" placeholder="Phone" />
                        <label for="floatingInput">Phone</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input type="date" name="birthdate" class="form-control" id="birthdate"
                            placeholder="Birthdate" />
                        <label for="floatingInput">Birthdate</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-control p-3" id="gender" name="gender">
                        <option>Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="others">Others</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="date" name="admitted_date" class="form-control" id="admitted_date"
                            placeholder="Admitted Date" />
                        <label for="floatingInput">Admitted Date</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="date" name="discharged_date" class="form-control" id="discharged_date"
                            placeholder="Discharged Date" />
                        <label for="floatingInput">Discharged Date</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" name="room_number" class="form-control" id="room_number"
                            placeholder="Room No" />
                        <label for="floatingInput">Room No</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" name="bed_number" class="form-control" id="bed_number"
                            placeholder="Bed No" />
                        <label for="floatingInput">Bed No</label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-floating mb-3">
                <select class="form-control p-3" id="physician" name="physician">
                    <option>Select physician</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr.
                            {{ ucwords($doctor->first_name) }}
                            {{ ucwords($doctor->last_name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="medical_condtion" class="form-control" id="medical_condtion"
                    placeholder="Medical Condition" />
                <label for="floatingInput">Medical Condition</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="diagnosis" class="form-control" id="diagnosis" placeholder="Diagnosis" />
                <label for="floatingInput">Diagnosis</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="medication" class="form-control" id="medication"
                    placeholder="Medication" />
                <label for="floatingInput">Medication</label>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3 ">
                        <input type="text" class="form-control ml-2" id="guardian_first_name"
                            placeholder="Guardian First Name" name="guardian_first_name" />
                        <label for="floatingInput">Guardian First Name</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3 ">
                        <input type="text" class="form-control ml-2" id="guardian_last_name"
                            placeholder="Guardian Last Name" name="guardian_last_name" />
                        <label for="floatingInput">Guardian Last Name</label>
                    </div>
                </div>
            </div>
            <div class="form-floating mb-3 ">
                <select class="form-control p-3" id="relationship" name="relationship">
                    <option>Select Relationship</option>
                    <option value="parent">Parent</option>
                    <option value="legal guardian">Legal Guardian</option>
                    <option value="spouse">Spouse</option>
                    <option value="sibling">Siblings</option>
                    <option value="grandparent">Grandparent</option>
                    <option value="aunt/Uncle">Aunt/Uncle</option>
                    <option value="cousin">Cousin</option>
                    <option value="extended family member">Extended Family Member</option>
                    <option value="foster Parent">Foster Parent</option>
                    <option value="close friend">Close Friend</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input type="date" name="guardian_birthdate" class="form-control" id="guardian_birthdate"
                            placeholder="Guardian Birthdate" />
                        <label for="floatingInput">Guardian Birthdate</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating mb-3 ">
                        <input type="phone" class="form-control" id="guardian_phone" placeholder="Guardian Phone"
                            name="guardian_phone" />
                        <label for="floatingInput">Guardian Phone</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating mb-3 ">
                        <input type="text" class="form-control" id="guardian_email" placeholder="Guardian Email"
                            name="guardian_email" />
                        <label for="floatingInput">Guardian Email</label>
                    </div>
                </div>
            </div>
        </div> --}}

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
