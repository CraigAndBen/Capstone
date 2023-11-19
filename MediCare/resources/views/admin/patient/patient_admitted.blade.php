@extends('layouts.inner_admin')

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
                                <h5 class="m-b-10">Patient Admitted List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Patient Admitted List</li>
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
                            <h1 class="display-6">Admitted Patient List</h1>
                        </div>
                        <div class="card-body">
                            <div class="container">

                                <div class="d-flex justify-content-end">
                                    <div class="m-1">
                                        <button class="btn btn-primary" data-toggle="modal"
                                            data-target="#createPatientModal">Add
                                            Patient</button>
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
                                        {{ session('info') }}
                                    </div>
                                @endif

                                @if ($patients->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Patient.
                                    </div>
                                @else
                                <div class="row my-4">
                                    <table class="table table-hover" id="patientTable">
                                        <thead class="table-primary text-light text-center">
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Physician</th>
                                                <th>Admitted Date</th>
                                                <th>Admitted Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($patients as $patient)
                                                <tr>
                                                    <td>{{ ucwords($patient->first_name) }}</td>
                                                    <td>{{ ucwords($patient->last_name) }}</td>
                                                    @foreach ($doctors as $doctor)
                                                        @if ($patient->physician == $doctor->id)
                                                            <td>Dr. {{ ucwords($doctor->first_name) }}
                                                                {{ ucwords($doctor->last_name) }}</td>
                                                        @endif
                                                    @endforeach
                                                    <td>{{ date('F j, Y', strtotime($patient->admitted_date)) }}</td>
                                                    <td>{{ date('g:i A', strtotime($patient->admitted_time)) }}</td>
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle"
                                                                type="button" data-toggle="dropdown">
                                                                Actions
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item btn btn-primary"
                                                                    data-toggle="modal"
                                                                    data-target="#updatePatientModal"
                                                                    data-id="{{ json_encode($patient->id) }}"
                                                                    data-type="{{ json_encode($patient->type) }}"
                                                                    data-first-name="{{ json_encode($patient->first_name) }}"
                                                                    data-middle-name="{{ json_encode($patient->middle_name) }}"
                                                                    data-last-name="{{ json_encode($patient->last_name) }}"
                                                                    data-street="{{ json_encode($patient->street) }}"
                                                                    data-brgy="{{ json_encode($patient->brgy) }}"
                                                                    data-city="{{ json_encode($patient->city) }}"
                                                                    data-province="{{ json_encode($patient->province) }}"
                                                                    data-phone="{{ json_encode($patient->phone) }}"
                                                                    data-birthdate="{{ json_encode($patient->birthdate) }}"
                                                                    data-gender="{{ json_encode($patient->gender) }}"
                                                                    data-admitted-date="{{ json_encode($patient->admitted_date) }}"
                                                                    data-admitted-time="{{ json_encode($patient->admitted_time) }}"
                                                                    data-discharged-date="{{ json_encode($patient->discharged_date) }}"
                                                                    data-discharged-time="{{ json_encode($patient->discharged_time) }}"
                                                                    data-room-no="{{ json_encode($patient->room_number) }}"
                                                                    data-bed-no="{{ json_encode($patient->bed_number) }}"
                                                                    data-date="{{ json_encode($patient->date) }}"
                                                                    data-time="{{ json_encode($patient->time) }}"
                                                                    data-physician="{{ json_encode($patient->physician) }}"
                                                                    data-medical-condition="{{ json_encode($patient->medical_condition) }}"
                                                                    data-guardian-first_name="{{ json_encode($patient->guardian_first_name) }}"
                                                                    data-guardian-last_name="{{ json_encode($patient->guardian_last_name) }}"
                                                                    data-guardian-birthdate="{{ json_encode($patient->guardian_birthdate) }}"
                                                                    data-relationship="{{ json_encode($patient->relationship) }}"
                                                                    data-guardian-phone="{{ json_encode($patient->guardian_phone) }}"
                                                                    data-guardian-email="{{ json_encode($patient->guardian_email) }}"
                                                                    data-medication="{{ json_encode($patient->medication) }}">Update</a>

                                                                <form action="{{ route('admin.patient.report.view') }}"
                                                                    method="GET" target="_blank">
                                                                    @csrf
                                                                    <input type="hidden" name="patient_id"
                                                                        id="patient_id" value="{{ $patient->id }}">
                                                                    <button type="submit"
                                                                        class="dropdown-item btn btn-primary">View Report</button>
                                                                </form>
                                                                <form action="{{ route('admin.patient.report.download') }}"
                                                                method="GET">
                                                                @csrf
                                                                <input type="hidden" name="patient_id"
                                                                    id="patient_id" value="{{ $patient->id }}">
                                                                <button type="submit"
                                                                    class="dropdown-item btn btn-primary">Download Report</button>
                                                            </form>

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

                    {{-- Create Admitted Patient modal --}}
                    <div class="modal fade" id="createPatientModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Patient Form</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('admin.patient.store') }}">
                                        @csrf
                                        <div class="row">
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
                                                    <input type="text" class="form-control ml-2"
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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="street" class="form-control"
                                                        id="floatingInput street" placeholder="Street" />
                                                    <label for="floatingInput">Street</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="brgy" class="form-control"
                                                        id="floatingInput brgy" placeholder="Brgy" />
                                                    <label for="floatingInput">Brgy</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="city" class="form-control"
                                                        id="floatingInput city" placeholder="City" />
                                                    <label for="floatingInput">City</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="province" class="form-control"
                                                        id="floatingInput province" placeholder="Street" />
                                                    <label for="floatingInput">Province</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="phone"
                                                        name="phone" placeholder="Phone" 
                                                        oninput="formatPhoneNumber(this);" />
                                                    <label for="phoneInput">Phone</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3">
                                                    <input type="date" name="birthdate" class="form-control"
                                                        id="floatingInput birthdate" placeholder="Birthdate" />
                                                    <label for="floatingInput">Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control p-3" id="gender" name="gender">
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-floating mb-3">
                                            <select class="form-control p-3" id="patientType" name="type">
                                                <option value="">Select Patient Type</option>
                                                <option value="admitted_patient">Admitted Patient</option>
                                                <option value="outpatient">Outpatient</option>
                                            </select>
                                        </div>
                                        <hr>
                                        <div id="admitted" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="date" name="admitted_date" class="form-control"
                                                            id="floatingInput admitted_date"
                                                            placeholder="Admitted Date" />
                                                        <label for="floatingInput">Admitted Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="time" name="admitted_time" class="form-control"
                                                            id="floatingInput admitted_time"
                                                            placeholder="Admitted Time" />
                                                        <label for="floatingInput">Admitted Time</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="date" name="discharged_date" class="form-control"
                                                            id="floatingInput discharged_date"
                                                            placeholder="Discharged Date" />
                                                        <label for="floatingInput">Discharged Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="time" name="discharged_date" class="form-control"
                                                            id="floatingInput discharged_time"
                                                            placeholder="Discharged Time" />
                                                        <label for="floatingInput">Discharged Time</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" name="room_number" class="form-control"
                                                            id="floatingInput room_number" placeholder="Room No" />
                                                        <label for="floatingInput">Room No</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" name="bed_number" class="form-control"
                                                            id="floatingInput bed_number" placeholder="Bed No" />
                                                        <label for="floatingInput">Bed No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="outpatient" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="date" name="date" class="form-control"
                                                            id="floatingInput date" placeholder="Date" />
                                                        <label for="floatingInput">Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="time" name="time" class="form-control"
                                                            id="floatingInput time" placeholder="Time" />
                                                        <label for="floatingInput">Time</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-floating mb-3">
                                            <select class="form-control p-3" id="physician" name="physician">
                                                <option value="">Select physician</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}">Dr.
                                                        {{ ucwords($doctor->first_name) }}
                                                        {{ ucwords($doctor->last_name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="medical_condition" class="form-control"
                                                id="floatingInput medical_condition" placeholder="Medical Condition" />
                                            <label for="floatingInput">Medical Condition</label>
                                        </div>
                                        <div id="diagnosisContainer">
                                            <div class="row mb-3 diagnosisInput">
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="date" name="diagnosesDate[]" class="form-control"
                                                            id="diagnosisDate" placeholder="Diagnose Date" />
                                                        <label for="floatingInput">Diagnose Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="time" name="diagnosesTime[]" class="form-control"
                                                            id="diagnoseTime" placeholder="Diagnose Time">
                                                        <label for="floatingInputDiagnosis">Diagnose Time</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-floating">
                                                        <input type="text" name="diagnoses[]" class="form-control"
                                                            id="floatingInputDiagnosis" placeholder="Diagnose">
                                                        <label for="floatingInputDiagnosis">Diagnose</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <button type="button"
                                                        class="btn btn-danger removeDiagnosis">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center mb-3">
                                            <div class="col-md-10 text-center">
                                                <button type="button" class="btn btn-primary" id="addDiagnosis"> Add
                                                    Another Diagnosis</button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div id="medicationContainer">
                                            <div class="row mb-3 medicationInput">
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="date" name="medicationDate[]"
                                                            class="form-control" id="medicationDate"
                                                            placeholder="Medication Date" />
                                                        <label for="floatingInput">Medication Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="time" name="medicationTime[]"
                                                            class="form-control" id="medicationTime"
                                                            placeholder="Medication Time" />
                                                        <label for="floatingInput">Medication Time</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-floating">
                                                        <input type="text" name="medicationName[]"
                                                            class="form-control" id="floatingInputMedication"
                                                            placeholder="Medication Name">
                                                        <label for="floatingInputMedication">Medication Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mt-5">
                                                    <button type="button"
                                                        class="btn btn-danger removeMedication">Remove</button>
                                                </div>
                                                <br>
                                                <div class="col-md-5">
                                                    <div class="form-floating">
                                                        <input type="text" name="medicationDosage[]"
                                                            class="form-control" id="floatingInputMedication"
                                                            placeholder="Medication Dosage">
                                                        <label for="floatingInputMedication">Medication Dosage</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-floating">
                                                        <input type="text" name="medicationDuration[]"
                                                            class="form-control" id="floatingInputMedication"
                                                            placeholder="Medication Name">
                                                        <label for="floatingInputMedication">Medication
                                                            Duration</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center mb-3">
                                            <div class="col-md-6 text-center">
                                                <button type="button" class="btn btn-primary" id="addMedication">Add
                                                    Another Medication</button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control ml-2"
                                                        id="floatingInput guardian_first_name"
                                                        placeholder="Guardian First Name" name="guardian_first_name" />
                                                    <label for="floatingInput">Guardian First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control ml-2"
                                                        id="floatingInput guardian_last_name"
                                                        placeholder="Guardian Last Name" name="guardian_last_name" />
                                                    <label for="floatingInput">Guardian Last Name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3 ">
                                            <select class="form-control p-3" id="relationship" name="relationship">
                                                <option value=""> Select Relationship</option>
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
                                                    <input type="date" name="guardian_birthdate" class="form-control"
                                                        id="floatingInput guardian_birthdate"
                                                        placeholder="Guardian Birthdate" />
                                                    <label for="floatingInput">Guardian Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="phone"
                                                        name="guardian_phone" placeholder="Phone" 
                                                        oninput="formatPhoneNumber(this);" />
                                                    <label for="phoneInput">Phone</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="phone" class="form-control"
                                                        id="floatingInput guardian_email" placeholder="Guardian Email"
                                                        name="guardian_email" />
                                                    <label for="floatingInput">Guardian Email</label>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Create Admitted Patient Modal --}}

                    {{-- Update Admitted Patient modal --}}
                    <div class="modal fade" id="updatePatientModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Update Patient Information</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('admin.patient.update') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="hidden" id="id" name="id">
                                                    <input type="text" class="form-control ml-2" id="first_name"
                                                        placeholder="First Name" name="first_name" />
                                                    <label for="floatingInput">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control ml-2" id="middle_name"
                                                        placeholder="Middle Name" name="middle_name" />
                                                    <label for="floatingInput">Middle Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="phone" class="form-control" id="last_name"
                                                        placeholder="Last Name" name="last_name" />
                                                    <label for="floatingInput">Last Name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="street" class="form-control"
                                                        id="street" placeholder="Street" />
                                                    <label for="floatingInput">Street</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="brgy" class="form-control"
                                                        id="brgy" placeholder="Brgy" />
                                                    <label for="floatingInput">Brgy</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="city" class="form-control"
                                                        id="city" placeholder="City" />
                                                    <label for="floatingInput">City</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="province" class="form-control"
                                                        id="province" placeholder="Street" />
                                                    <label for="floatingInput">Province</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="phone"
                                                        name="phone" placeholder="Phone"
                                                        oninput="formatPhoneNumber(this);" />
                                                    <label for="phoneInput">Phone</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3">
                                                    <input type="date" name="birthdate" class="form-control"
                                                        id="birthdate" placeholder="Birthdate" />
                                                    <label for="floatingInput">Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control p-3" id="gender" name="gender">
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div id="inputsPatientType">
                                            <!-- Dynamic fields will be appended here -->
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select class="form-control p-3" id="physician" name="physician">
                                                <option value="">Select physician</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}">Dr.
                                                        {{ ucwords($doctor->first_name) }}
                                                        {{ ucwords($doctor->last_name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="medical_condition" class="form-control"
                                                id="medical_condition" placeholder="Medical Condition" />
                                            <label for="floatingInput">Medical Condition</label>
                                        </div>
                                        <hr>
                                        <div id="diagnoses-list">
                                        </div>
                                        <div class="row justify-content-center mb-3 mt-3">
                                            <div class="col-md-10 text-center">
                                                <button type="button" class="btn btn-primary" id="addDiagnosisButtonForUpdate"> Add
                                                    Another Diagnosis</button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div id="medications-list">
                                        </div>
                                        <div class="row justify-content-center mb-3">
                                            <div class="col-md-10 text-center">
                                                <button id="addMedicationButtonForUpdate" class="btn btn-primary"> Add
                                                    Medication</button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control ml-2"
                                                        id="guardian_first_name" placeholder="Guardian First Name"
                                                        name="guardian_first_name" />
                                                    <label for="floatingInput">Guardian First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control ml-2"
                                                        id="guardian_last_name" placeholder="Guardian Last Name"
                                                        name="guardian_last_name" />
                                                    <label for="floatingInput">Guardian Last Name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3 ">
                                            <select class="form-control p-3" id="relationship" name="relationship">
                                                <option value="">Select Relationship</option>
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
                                                    <input type="date" name="guardian_birthdate" class="form-control"
                                                        id="guardian_birthdate" placeholder="Guardian Birthdate" />
                                                    <label for="floatingInput">Guardian Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="guardian_phone"
                                                        name="guardian_phone" placeholder="Phone" 
                                                        oninput="formatPhoneNumber(this);" />
                                                    <label for="phoneInput">Phone</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control" id="guardian_email"
                                                        placeholder="Guardian Email" name="guardian_email" />
                                                    <label for="floatingInput">Guardian Email</label>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Update Admitted Patient Modal --}}

                    <!-- [ Main Content ] end -->
                </div>
            </div>


        @endsection

        @section('scripts')
            <script>
                // JavaScript for adding and removing input fields
                document.getElementById("addDiagnosis").addEventListener("click", function() {
                    const diagnosisContainer = document.getElementById("diagnosisContainer");
                    const newDiagnosisInput = document.createElement("div");
                    newDiagnosisInput.classList.add("row", "mb-3", "diagnosisInput");
                    newDiagnosisInput.innerHTML = `
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="date" name="diagnosesDate[]" class="form-control" id="diagnosisDate" placeholder="Diagnose Date" />
                            <label for="floatingInput">Diagnose Date</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="time" name="diagnosesTime[]" class="form-control" id="diagnoseTime" placeholder="Diagnose Time">
                            <label for="floatingInputDiagnosis">Diagnose Time</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="diagnoses[]" class="form-control" id="floatingInputDiagnosis" placeholder="Diagnose">
                            <label for="floatingInputDiagnosis">Diagnose</label>
                        </div>
                    </div>
                    <div class="col-md-2 mt-2">
                        <button type="button" class="btn btn-danger removeDiagnosis">Remove</button>
                    </div>
                    `;
                    diagnosisContainer.appendChild(newDiagnosisInput);

                    // Add a click event listener to the new "Remove" button
                    newDiagnosisInput.querySelector(".removeDiagnosis").addEventListener("click", function() {
                        diagnosisContainer.removeChild(newDiagnosisInput);
                    });
                });

                document.getElementById("addMedication").addEventListener("click", function() {
                    const medicationContainer = document.getElementById("medicationContainer");
                    const newMedicationInput = document.createElement("div");
                    newMedicationInput.classList.add("row", "mb-3", "medicationInput");
                    newMedicationInput.innerHTML = `
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="date" name="medicationDate[]"
                                                            class="form-control" id="medicationDate"
                                                            placeholder="Medication Date" />
                                                        <label for="floatingInput">Medication Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="time" name="medicationTime[]"
                                                            class="form-control" id="medicationTime"
                                                            placeholder="Medication Time" />
                                                        <label for="floatingInput">Medication Time</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-floating">
                                                        <input type="text" name="medicationName[]"
                                                            class="form-control" id="floatingInputMedication"
                                                            placeholder="Medication Name">
                                                        <label for="floatingInputMedication">Medication Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mt-5">
                                                    <button type="button"
                                                        class="btn btn-danger removeMedication">Remove</button>
                                                </div>
                                                <br>
                                                <div class="col-md-5">
                                                    <div class="form-floating">
                                                        <input type="text" name="medicationDosage[]"
                                                            class="form-control" id="floatingInputMedication"
                                                            placeholder="Medication Dosage">
                                                        <label for="floatingInputMedication">Medication Dosage</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-floating">
                                                        <input type="text" name="medicationDuration[]"
                                                            class="form-control" id="floatingInputMedication"
                                                            placeholder="Medication Name">
                                                        <label for="floatingInputMedication">Medication
                                                            Duration</label>
                                                    </div>
                                                </div>
                                            
            `;
                    medicationContainer.appendChild(newMedicationInput);

                    // Add a click event listener to the new "Remove" button
                    newMedicationInput.querySelector(".removeMedication").addEventListener("click", function() {
                        medicationContainer.removeChild(newMedicationInput);
                    });
                });
                // Add click event listeners for the initial "Remove" buttons
                const removeDiagnosisButtons = document.querySelectorAll(".removeDiagnosis");
                removeDiagnosisButtons.forEach(function(button) {
                    button.addEventListener("click", function() {
                        const diagnosisInput = button.parentNode.parentNode;
                        const diagnosisContainer = document.getElementById("diagnosisContainer");
                        diagnosisContainer.removeChild(diagnosisInput);
                    });
                });

                const removeMedicationButtons = document.querySelectorAll(".removeMedication");
                removeMedicationButtons.forEach(function(button) {
                    button.addEventListener("click", function() {
                        const medicationInput = button.parentNode.parentNode;
                        const medicationContainer = document.getElementById("medicationContainer");
                        medicationContainer.removeChild(medicationInput);
                    });
                });

                const patientTypeSelect = document.getElementById("patientType");
                const admittedInputs = document.getElementById("admitted");
                const outpatientInputs = document.getElementById("outpatient");

                patientTypeSelect.addEventListener("change", function() {
                    const selectedType = patientTypeSelect.value;
                    if (selectedType === "admitted_patient") {
                        admittedInputs.style.display = "block";
                        outpatientInputs.style.display = "none";
                    } else if (selectedType === "outpatient") {
                        admittedInputs.style.display = "none";
                        outpatientInputs.style.display = "block";
                    }
                });

                $(document).ready(function() {

                    $('#updatePatientModal').on('show.bs.modal', function(event) {
                        var button = $(event.relatedTarget); // Button that triggered the modal
                        var patientType = JSON.parse(button.data('type'));

                        var modal = $(this);
                        var diagnosesList = modal.find('#diagnoses-list');
                        var medicationsList = modal.find('#medications-list');
                        var medicationsList = modal.find('#medications-list');
                        var admitted = document.getElementById("admittedUpdate");
                        var outpatient = document.getElementById("outpatientUpdate");
                        // var outpatient = modal.find('outpatientUpdate');

                        // Extract common and specific data
                        var id = JSON.parse(button.data('id'));
                        var first_name = JSON.parse(button.data('first-name'));
                        var middle_name = JSON.parse(button.data('middle-name'));
                        var last_name = JSON.parse(button.data('last-name'));
                        var street = JSON.parse(button.data('street'));
                        var brgy = JSON.parse(button.data('brgy'));
                        var city = JSON.parse(button.data('city'));
                        var province = JSON.parse(button.data('province'));
                        var birthdate = JSON.parse(button.data('birthdate'));
                        var gender = JSON.parse(button.data('gender'));
                        var phone = JSON.parse(button.data('phone'));
                        var physician = JSON.parse(button.data('physician'));
                        var medical_condition = JSON.parse(button.data('medical-condition'));
                        var guardian_first_name = JSON.parse(button.data('guardian-first_name')); // Note the underscore
                        var guardian_last_name = JSON.parse(button.data('guardian-last_name')); // Note the underscore
                        var guardian_birthdate = JSON.parse(button.data('guardian-birthdate'));
                        var relationship = JSON.parse(button.data('relationship'));
                        var guardian_phone = JSON.parse(button.data('guardian-phone'));
                        var guardian_email = JSON.parse(button.data('guardian-email'));

                        var admitted_date = JSON.parse(button.data('admitted-date'));
                        var admitted_time = JSON.parse(button.data('admitted-time'));
                        var discharged_date = JSON.parse(button.data('discharged-date'));
                        var discharged_time = JSON.parse(button.data('discharged-time'));
                        var room_number = JSON.parse(button.data('room-no'));
                        var bed_number = JSON.parse(button.data('bed-no'));

                        var date = JSON.parse(button.data('date'));
                        var time = JSON.parse(button.data('time'));
                        // Populate common data
                        modal.find('#id').val(id);
                        modal.find('#first_name').val(first_name);
                        modal.find('#middle_name').val(middle_name);
                        modal.find('#last_name').val(last_name);
                        modal.find('#street').val(street);
                        modal.find('#brgy').val(brgy);
                        modal.find('#city').val(city);
                        modal.find('#province').val(province);
                        modal.find('#birthdate').val(birthdate);
                        modal.find('#gender').val(gender);
                        modal.find('#phone').val(phone);
                        modal.find('#physician').val(physician);
                        modal.find('#medical_condition').val(medical_condition);
                        modal.find('#guardian_first_name').val(guardian_first_name);
                        modal.find('#guardian_last_name').val(guardian_last_name);
                        modal.find('#guardian_birthdate').val(guardian_birthdate);
                        modal.find('#relationship').val(relationship);
                        modal.find('#guardian_phone').val(guardian_phone);
                        modal.find('#guardian_email').val(guardian_email);

                        var container = $('#inputsPatientType'); // Container for dynamic fields

                        // Clear any existing content in the container
                        container.empty();

                        if (patientType === 'admitted_patient') {
                            // Append input fields for admitted patients
                            container.append(`
                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="date" name="admitted_date" class="form-control"
                                                            id="floatingInput admitted_date"
                                                            placeholder="Admitted Date" value="${admitted_date}"/>
                                                        <label for="floatingInput">Admitted Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="time" name="admitted_time" class="form-control"
                                                            id=" admitted_time"
                                                            placeholder="Admitted Time" value="${admitted_time}"/>
                                                        <label for="floatingInput">Admitted Time</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="date" name="discharged_date" class="form-control"
                                                            id=" discharged_date"
                                                            placeholder="Discharged Date" value="${discharged_date}"/>
                                                        <label for="floatingInput">Discharged Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="time" name="discharged_date" class="form-control"
                                                            id=" discharged_time"
                                                            placeholder="Discharged Time" value="${discharged_time}"/>
                                                        <label for="floatingInput">Discharged Time</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" name="room_number" class="form-control"
                                                            id=" room_number" placeholder="Room No" value="${room_number !== null ? room_number : ''}"/>
                                                        <label for="floatingInput">Room No</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" name="bed_number" class="form-control"
                                                            id=" bed_number" placeholder="Bed No" value="${bed_number !== null ? bed_number : ''}"/>
                                                        <label for="floatingInput">Bed No</label>
                                                    </div>
                                                </div>
                                            </div>
                            `);
                        } else if (patientType === 'outpatient') {
                            // Append input fields for outpatients
                            container.append(`
                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="date" name="date" class="form-control"
                                                            id="floatingInput date" placeholder="Date" value="${date}"/>
                                                        <label for="floatingInput">Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="time" name="time" class="form-control"
                                                            id="floatingInput time" placeholder="Time" value="${time}"/>
                                                        <label for="floatingInput">Time</label>
                                                    </div>
                                                </div>
                                            </div>
                            `);
                        }
                        // Make an AJAX request to fetch diagnoses
                        $.get('/admin/patient/' + id + '/diagnoses')
                            .done(function(diagnoses) {
                                diagnosesList.empty();
                                diagnosesList.append('<h3>Diagnoses List</h3>');
                                $.each(diagnoses, function(index, diagnoses) {
                                    var diagnosisHtml = `
                                    <div class="row mb-2">
                                    <div class="col-md-4">
                                                    <div class="form-floating">
                                                        <input type="date" name="diagnosesDate[]" class="form-control"
                                                            id="diagnosisDate" placeholder="Diagnose Date" value="${diagnoses.date}"/>
                                                        <label for="floatingInput">Diagnose Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-floating">
                                                        <input type="time" name="diagnosesTime[]" class="form-control"
                                                            id="diagnoseTime" placeholder="Diagnose Time" value="${diagnoses.time}">
                                                        <label for="floatingInputDiagnosis">Diagnose Time</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-floating">
                                                        <input type="text" name="diagnoses[]" class="form-control"
                                                            id="floatingInputDiagnosis" placeholder="Diagnose" value="${diagnoses.diagnose}">
                                                        <label for="floatingInputDiagnosis">Diagnose</label>
                                                    </div>
                                                </div>
                                    </div>
                                `;
                                    diagnosesList.append(diagnosisHtml);
                                });
                            })
                            .fail(function(jqXHR, textStatus, errorThrown) {
                                console.error("Error: " + errorThrown);
                            });

                        // Make an AJAX request to fetch diagnoses
                        $.get('/admin/patient/' + id + '/medications')
                            .done(function(medications) {
                                medicationsList.empty();
                                medicationsList.append('<h3>Medications List</h3>');
                                $.each(medications, function(index, medications) {
                                    var medicationHtml = `
                                    <div class="row mb-3 medicationInput">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-floating">
                                                            <input type="date" name="medicationDate[]"
                                                                class="form-control" id="medicationDate"
                                                                placeholder="Medication Date" value="${medications.date}"/>
                                                            <label for="floatingInput">Medication Date</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-floating">
                                                            <input type="time" name="medicationTime[]"
                                                                class="form-control" id="medicationTime"
                                                                placeholder="Medication Time" value="${medications.time}"/>
                                                            <label for="floatingInput">Medication Time</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-floating">
                                                            <input type="text" name="medicationName[]"
                                                                class="form-control" id="floatingInputMedication"
                                                                placeholder="Medication Name" value="${medications.medication_name}">
                                                            <label for="floatingInputMedication">Medication Name</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <div class="form-floating">
                                                            <input type="text" name="medicationDosage[]"
                                                                class="form-control" id="floatingInputMedication"
                                                                placeholder="Medication Dosage" value="${medications.dosage}">
                                                            <label for="floatingInputMedication">Medication Dosage</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-floating">
                                                            <input type="text" name="medicationDuration[]"
                                                                class="form-control" id="floatingInputMedication"
                                                                placeholder="Medication Name" value="${medications.duration}">
                                                            <label for="floatingInputMedication">Medication
                                                                Duration</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                `;
                                    medicationsList.append(medicationHtml);
                                });
                            })
                            .fail(function(jqXHR, textStatus, errorThrown) {
                                console.error("Error: " + errorThrown);
                            });
                    });
                });

                function formatPhoneNumber(input) {
                    // Remove any non-numeric characters
                    input.value = input.value.replace(/[^0-9+]/g, '');

                    // Check if the input starts with "09" and change it to "+639"
                    if (input.value.startsWith('09')) {
                        input.value = '+639' + input.value.substring(2);
                    }
                }

                $('#addDiagnosisButtonForUpdate').click(function(event) {
                    event.preventDefault(); // Prevent the default form submission behavior

                    var newDiagnosisHtml = `
                    <div class="row mb-2">
                        <div class="row mb-3 diagnosisInput">
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="date" name="diagnosesDate[]" class="form-control"
                                                            id="diagnosisDate" placeholder="Diagnose Date" />
                                                        <label for="floatingInput">Diagnose Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="time" name="diagnosesTime[]" class="form-control"
                                                            id="diagnoseTime" placeholder="Diagnose Time">
                                                        <label for="floatingInputDiagnosis">Diagnose Time</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-floating">
                                                        <input type="text" name="diagnoses[]" class="form-control"
                                                            id="floatingInputDiagnosis" placeholder="Diagnose">
                                                        <label for="floatingInputDiagnosis">Diagnose</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <button type="button"
                                                        class="btn btn-danger removeDiagnosisForUpdate">Remove</button>
                                                </div>
                                            </div>
                    </div>                        
                    `;
                    $('#diagnoses-list').append(newDiagnosisHtml);
                });

                // Add a click event handler to remove the diagnosis input fields
                $('#diagnoses-list').on('click', '.removeDiagnosisForUpdate', function() {
                    $(this).closest('.row').remove();
                });

                $('#addMedicationButtonForUpdate').click(function(event) {
                    event.preventDefault(); // Prevent the default form submission behavior

                    var newDiagnosisHtml = `
                    <div class="row mb-3 medicationInput">
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="date" name="medicationDate[]"
                                                            class="form-control" id="medicationDate"
                                                            placeholder="Medication Date" />
                                                        <label for="floatingInput">Medication Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <input type="time" name="medicationTime[]"
                                                            class="form-control" id="medicationTime"
                                                            placeholder="Medication Time" />
                                                        <label for="floatingInput">Medication Time</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-floating">
                                                        <input type="text" name="medicationName[]"
                                                            class="form-control" id="floatingInputMedication"
                                                            placeholder="Medication Name">
                                                        <label for="floatingInputMedication">Medication Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mt-5">
                                                    <button type="button"
                                                        class="btn btn-danger removeMedicationForUpdate">Remove</button>
                                                </div>
                                                <br>
                                                <div class="col-md-5">
                                                    <div class="form-floating">
                                                        <input type="text" name="medicationDosage[]"
                                                            class="form-control" id="floatingInputMedication"
                                                            placeholder="Medication Dosage">
                                                        <label for="floatingInputMedication">Medication Dosage</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-floating">
                                                        <input type="text" name="medicationDuration[]"
                                                            class="form-control" id="floatingInputMedication"
                                                            placeholder="Medication Name">
                                                        <label for="floatingInputMedication">Medication
                                                            Duration</label>
                                                    </div>
                                                </div>
                                            </div>                       
                    `;
                    $('#medications-list').append(newDiagnosisHtml);
                });
                $('#medications-list').on('click', '.removeMedicationForUpdate', function() {
                    $(this).closest('.row').remove();
                });
            </script>
        @endsection
