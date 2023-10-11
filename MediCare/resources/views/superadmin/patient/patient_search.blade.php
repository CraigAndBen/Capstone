@extends('layouts.inner_superadmin')

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
                                <h5 class="m-b-10">Patient List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Patient List</li>
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
                            <h1>Patient List</h1>
                        </div>
                        <div class="card-body">
                            <div class="container">

                                <div class="d-flex justify-content-end">
                                    <div class="m-1">
                                        <button class="btn btn-primary" data-toggle="modal"
                                            data-target="#createPatientModal">Add
                                            Patient</button>
                                    </div>
                                    <div class="m-1">
                                        <button class="btn btn-primary" data-toggle="modal"
                                            data-target="#createOutpatientModal">Add
                                            Outpatient</button>
                                    </div>
                                    <div class="m-1">
                                        <a href="{{ route('superadmin.patient') }}" class="btn btn-success">Show All</a>
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
                                        <span class="fa fa-check-circle"></span> No Patient Exist.
                                    </div>
                                @else
                                    <form action="{{ route('superadmin.patient.search') }}" method="GET">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-2">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control ml-2"
                                                        id="floatingInput search" placeholder="Search" name="search" />
                                                    <label for="floatingInput">Search</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2 mt-2">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                    </form>

                                    <table class="table table-bordered">
                                        <thead class="bg-primary text-light text-center">
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Physician</th>
                                                <th>Type</th>
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
                                                    @if ($patient->type == 'outpatient')
                                                        <td>Outpatient</td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle"
                                                                    type="button" data-toggle="dropdown">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#updateaOutpatientModal"
                                                                        data-id="{{ json_encode($patient->id) }}"
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
                                                                        data-date="{{ json_encode($patient->date) }}"
                                                                        data-time="{{ json_encode($patient->time) }}"
                                                                        data-physician="{{ json_encode($patient->physician) }}"
                                                                        data-medical-condition="{{ json_encode($patient->medical_condition) }}"
                                                                        data-diagnosis="{{ json_encode($patient->diagnosis) }}"
                                                                        data-guardian-first_name="{{ json_encode($patient->guardian_first_name) }}"
                                                                        data-guardian-last_name="{{ json_encode($patient->guardian_last_name) }}"
                                                                        data-guardian-birthdate="{{ json_encode($patient->guardian_birthdate) }}"
                                                                        data-relationship="{{ json_encode($patient->relationship) }}"
                                                                        data-guardian-phone="{{ json_encode($patient->guardian_phone) }}"
                                                                        data-guardian-email="{{ json_encode($patient->guardian_email) }}"
                                                                        data-medication="{{ json_encode($patient->medication) }}">Update</a>
                                                                    <a class="dropdown-item btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#viewOutpatientModal"
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
                                                                        data-date="{{ json_encode($patient->date) }}"
                                                                        data-time="{{ json_encode($patient->time) }}"
                                                                        data-physician="{{ json_encode($patient->physician) }}"
                                                                        data-medical-condition="{{ json_encode($patient->medical_condition) }}"
                                                                        data-diagnosis="{{ json_encode($patient->diagnosis) }}"
                                                                        data-guardian-first_name="{{ json_encode($patient->guardian_first_name) }}"
                                                                        data-guardian-last_name="{{ json_encode($patient->guardian_last_name) }}"
                                                                        data-guardian-birthdate="{{ json_encode($patient->guardian_birthdate) }}"
                                                                        data-relationship="{{ json_encode($patient->relationship) }}"
                                                                        data-guardian-phone="{{ json_encode($patient->guardian_phone) }}"
                                                                        data-guardian-email="{{ json_encode($patient->guardian_email) }}"
                                                                        data-medication="{{ json_encode($patient->medication) }}">View</a>
                                                                    <form
                                                                        action="{{ route('superadmin.patient.report') }}"
                                                                        method="GET">
                                                                        @csrf
                                                                        <input type="hidden" name="patient_id"
                                                                            id="patient_id" value="{{ $patient->id }}">
                                                                        <button type="submit"
                                                                            class="dropdown-item btn btn-primary">Generate
                                                                            Report</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @else
                                                        <td>Admitted Patient</td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle"
                                                                    type="button" data-toggle="dropdown">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#updateAdmittedpatientModal"
                                                                        data-id="{{ json_encode($patient->id) }}"
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
                                                                        data-discharged-date="{{ json_encode($patient->discharged_date) }}"
                                                                        data-room-no="{{ json_encode($patient->room_number) }}"
                                                                        data-bed-no="{{ json_encode($patient->bed_number) }}"
                                                                        data-physician="{{ json_encode($patient->physician) }}"
                                                                        data-medical-condition="{{ json_encode($patient->medical_condition) }}"
                                                                        data-diagnosis="{{ json_encode($patient->diagnosis) }}"
                                                                        data-guardian-first_name="{{ json_encode($patient->guardian_first_name) }}"
                                                                        data-guardian-last_name="{{ json_encode($patient->guardian_last_name) }}"
                                                                        data-guardian-birthdate="{{ json_encode($patient->guardian_birthdate) }}"
                                                                        data-relationship="{{ json_encode($patient->relationship) }}"
                                                                        data-guardian-phone="{{ json_encode($patient->guardian_phone) }}"
                                                                        data-guardian-email="{{ json_encode($patient->guardian_email) }}"
                                                                        data-medication="{{ json_encode($patient->medication) }}">Update</a>

                                                                    <a class="dropdown-item btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#viewAdmittedPatientModal"
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
                                                                        data-discharged-date="{{ json_encode($patient->discharged_date) }}"
                                                                        data-room-no="{{ json_encode($patient->room_number) }}"
                                                                        data-bed-no="{{ json_encode($patient->bed_number) }}"
                                                                        data-physician="{{ json_encode($patient->physician) }}"
                                                                        data-medical-condition="{{ json_encode($patient->medical_condition) }}"
                                                                        data-diagnosis="{{ json_encode($patient->diagnosis) }}"
                                                                        data-guardian-first_name="{{ json_encode($patient->guardian_first_name) }}"
                                                                        data-guardian-last_name="{{ json_encode($patient->guardian_last_name) }}"
                                                                        data-guardian-birthdate="{{ json_encode($patient->guardian_birthdate) }}"
                                                                        data-relationship="{{ json_encode($patient->relationship) }}"
                                                                        data-guardian-phone="{{ json_encode($patient->guardian_phone) }}"
                                                                        data-guardian-email="{{ json_encode($patient->guardian_email) }}"
                                                                        data-medication="{{ json_encode($patient->medication) }}">View</a>
                                                                    <form
                                                                        action="{{ route('superadmin.patient.report') }}"
                                                                        method="GET">
                                                                        @csrf
                                                                        <input type="hidden" name="patient_id"
                                                                            id="patient_id" value="{{ $patient->id }}">
                                                                        <button type="submit"
                                                                            class="dropdown-item btn btn-primary">Generate
                                                                            Report</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center my-3">
                                        {{ $patients->links('pagination::bootstrap-4') }}
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
                                    <h2 class="modal-title text-light" id="myModalLabel">Adding Patient</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('superadmin.patient.store') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control ml-2"
                                                        id="floatingInput first_name" placeholder="First Name"
                                                        name="first_name" required />
                                                    <label for="floatingInput">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control ml-2"
                                                        id="floatingInput middle_name" placeholder="Middle Name"
                                                        name="middle_name" required />
                                                    <label for="floatingInput">Middle Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control"
                                                        id="floatingInput last_name" placeholder="Last Name"
                                                        name="last_name" required />
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
                                                    <input type="number" name="phone" class="form-control"
                                                        id="floatingInput phone" placeholder="Phone" />
                                                    <label for="floatingInput">Phone</label>
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
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="date" name="admitted_date" class="form-control"
                                                        id="floatingInput admitted_date" placeholder="Admitted Date" />
                                                    <label for="floatingInput">Admitted Date</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="date" name="discharged_date" class="form-control"
                                                        id="floatingInput discharged_date"
                                                        placeholder="Discharged Date" />
                                                    <label for="floatingInput">Discharged Date</label>
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
                                            <input type="text" name="medical_condtion" class="form-control"
                                                id="floatingInput medical_condition" placeholder="Medical Condition" />
                                            <label for="floatingInput">Medical Condition</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="diagnosis" class="form-control"
                                                id="floatingInput diagnosis" placeholder="Diagnosis" />
                                            <label for="floatingInput">Diagnosis</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="medication" class="form-control"
                                                id="floatingInput medication" placeholder="Medication" />
                                            <label for="floatingInput">Medication</label>
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
                                                        id="floatingInput guardian_birthdate"
                                                        placeholder="Guardian Birthdate" />
                                                    <label for="floatingInput">Guardian Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="phone" class="form-control"
                                                        id="floatingInput guardian_phone" placeholder="Guardian Phone"
                                                        name="guardian_phone" />
                                                    <label for="floatingInput">Guardian Phone</label>
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

                    {{-- Create Outpatient modal --}}
                    <div class="modal fade" id="createOutpatientModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Adding Outpatient</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('superadmin.outpatient.store') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control ml-2"
                                                        id="floatingInput first_name" placeholder="First Name"
                                                        name="first_name" required />
                                                    <label for="floatingInput">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control ml-2"
                                                        id="floatingInput middle_name" placeholder="Middle Name"
                                                        name="middle_name" required />
                                                    <label for="floatingInput">Middle Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="phone" class="form-control"
                                                        id="floatingInput last_name" placeholder="Last Name"
                                                        name="last_name" required />
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
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3">
                                                    <input type="number" name="phone" class="form-control"
                                                        id="floatingInput phone" placeholder="Phone" />
                                                    <label for="floatingInput">Phone</label>
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
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                        </div>
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
                                            <input type="text" name="medical_condtion" class="form-control"
                                                id="floatingInput medical_condition" placeholder="Medical Condition" />
                                            <label for="floatingInput">Medical Condition</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="diagnosis" class="form-control"
                                                id="floatingInput diagnosis" placeholder="Diagnosis" />
                                            <label for="floatingInput">Diagnosis</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="medication" class="form-control"
                                                id="floatingInput medication" placeholder="Medication" />
                                            <label for="floatingInput">Medication</label>
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
                                                        id="floatingInput guardian_birthdate"
                                                        placeholder="Guardian Birthdate" />
                                                    <label for="floatingInput">Guardian Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="phone" class="form-control"
                                                        id="floatingInput guardian_phone" placeholder="Guardian Phone"
                                                        name="guardian_phone" />
                                                    <label for="floatingInput">Guardian Phone</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="phone" class="form-control"
                                                        id="floatingInput guardian_phone" placeholder="Guardian Email"
                                                        name="guardian_phone" />
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
                    {{-- End Create Outpatient Modal --}}


                    {{-- Update Admitted Patient modal --}}
                    <div class="modal fade" id="updateAdmittedpatientModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Update Patient Information</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('superadmin.patient.update') }}">
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
                                                    <input type="number" name="phone" class="form-control"
                                                        id="phone" placeholder="Phone" />
                                                    <label for="floatingInput">Phone</label>
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
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="date" name="admitted_date" class="form-control"
                                                        id="admitted_date" placeholder="Admitted Date" />
                                                    <label for="floatingInput">Admitted Date</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="date" name="discharged_date" class="form-control"
                                                        id="discharged_date" placeholder="Discharged Date" />
                                                    <label for="floatingInput">Discharged Date</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="room_number" class="form-control"
                                                        id="room_number" placeholder="Room No" />
                                                    <label for="floatingInput">Room No</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="bed_number" class="form-control"
                                                        id="bed_number" placeholder="Bed No" />
                                                    <label for="floatingInput">Bed No</label>
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
                                            <input type="text" name="medical_condtion" class="form-control"
                                                id="medical_condtion" placeholder="Medical Condition" />
                                            <label for="floatingInput">Medical Condition</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="diagnosis" class="form-control" id="diagnosis"
                                                placeholder="Diagnosis" />
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
                                                <div class="form-floating mb-3 ">
                                                    <input type="phone" class="form-control" id="guardian_phone"
                                                        placeholder="Guardian Phone" name="guardian_phone" />
                                                    <label for="floatingInput">Guardian Phone</label>
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


                    {{-- Update Outpatient modal --}}
                    <div class="modal fade" id="updateaOutpatientModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Update Patient Information</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('superadmin.patient.update') }}">
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
                                                    <input type="number" name="phone" class="form-control"
                                                        id="phone" placeholder="Phone" />
                                                    <label for="floatingInput">Phone</label>
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
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="date" name="date" class="form-control"
                                                        id="date" placeholder="Date" />
                                                    <label for="floatingInput">Date</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="time" name="time" class="form-control"
                                                        id="time" placeholder="Time" />
                                                    <label for="floatingInput">Time</label>
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
                                            <input type="text" name="medical_condtion" class="form-control"
                                                id="medical_condtion" placeholder="Medical Condition" />
                                            <label for="floatingInput">Medical Condition</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="diagnosis" class="form-control" id="diagnosis"
                                                placeholder="Diagnosis" />
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
                                                    <input type="date" name="guardian_birthdate"
                                                        class="form-control" id="guardian_birthdate"
                                                        placeholder="Guardian Birthdate" />
                                                    <label for="floatingInput">Guardian Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="phone" class="form-control" id="guardian_phone"
                                                        placeholder="Guardian Phone" name="guardian_phone" />
                                                    <label for="floatingInput">Guardian Phone</label>
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
                    {{-- End Update Outpatient Modal --}}


                    {{-- View Admitted Patient modal --}}
                    <div class="modal fade" id="viewAdmittedPatientModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"
                            role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Patient Information</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="hidden" id="id" name="id">
                                                <input type="text" class="form-control ml-2" id="first_name"
                                                    placeholder="First Name" name="first_name" disabled />
                                                <label for="floatingInput">First Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control ml-2" id="middle_name"
                                                    placeholder="Middle Name" name="middle_name" disabled />
                                                <label for="floatingInput">Middle Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="phone" class="form-control" id="last_name"
                                                    placeholder="Last Name" name="last_name" disabled />
                                                <label for="floatingInput">Last Name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="street" class="form-control"
                                                    id="street" placeholder="Street" disabled />
                                                <label for="floatingInput">Street</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="brgy" class="form-control"
                                                    id="brgy" placeholder="Brgy"disabled />
                                                <label for="floatingInput">Brgy</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="city" class="form-control"
                                                    id="city" placeholder="City" disabled />
                                                <label for="floatingInput">City</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="province" class="form-control"
                                                    id="province" placeholder="Street" disabled />
                                                <label for="floatingInput">Province</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="number" name="phone" class="form-control"
                                                    id="phone" placeholder="Phone" disabled />
                                                <label for="floatingInput">Phone</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="date" name="birthdate" class="form-control"
                                                    id="birthdate" placeholder="Birthdate" disabled />
                                                <label for="floatingInput">Birthdate</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control p-3" id="gender" name="gender" disabled>
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
                                                <input type="date" name="admitted_date" class="form-control"
                                                    id="admitted_date" placeholder="Admitted Date" disabled />
                                                <label for="floatingInput">Admitted Date</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="date" name="discharged_date" class="form-control"
                                                    id="discharged_date" placeholder="Discharged Date" disabled />
                                                <label for="floatingInput">Discharged Date</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="room_number" class="form-control"
                                                    id="room_number" placeholder="Room No" disabled />
                                                <label for="floatingInput">Room No</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="bed_number" class="form-control"
                                                    id="bed_number" placeholder="Bed No" disabled />
                                                <label for="floatingInput">Bed No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select class="form-control p-3" id="physician" name="physician" disabled>
                                            <option>Select physician</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">Dr.
                                                    {{ ucwords($doctor->first_name) }}
                                                    {{ ucwords($doctor->last_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="medical_condtion" class="form-control"
                                            id="medical_condtion" placeholder="Medical Condition" disabled />
                                        <label for="floatingInput">Medical Condition</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="diagnosis" class="form-control" id="diagnosis"
                                            placeholder="Diagnosis" disabled />
                                        <label for="floatingInput">Diagnosis</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="medication" class="form-control" id="medication"
                                            placeholder="Medication" disabled />
                                        <label for="floatingInput">Medication</label>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control ml-2"
                                                    id="guardian_first_name" placeholder="Guardian First Name"
                                                    name="guardian_first_name" disabled />
                                                <label for="floatingInput">Guardian First Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control ml-2"
                                                    id="guardian_last_name" placeholder="Guardian Last Name"
                                                    name="guardian_last_name" disabled />
                                                <label for="floatingInput">Guardian Last Name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3 ">
                                        <select class="form-control p-3" id="relationship" name="relationship"
                                            disabled>
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
                                                <input type="date" name="guardian_birthdate" class="form-control"
                                                    id="guardian_birthdate" placeholder="Guardian Birthdate" disabled />
                                                <label for="floatingInput">Guardian Birthdate</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="phone" class="form-control" id="guardian_phone"
                                                    placeholder="Guardian Phone" name="guardian_phone" disabled />
                                                <label for="floatingInput">Guardian Phone</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control" id="guardian_email"
                                                    placeholder="Guardian Email" name="guardian_email" disabled />
                                                <label for="floatingInput">Guardian Email</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End View Admitted Patient Modal --}}

                    {{-- View OutPatient modal --}}
                    <div class="modal fade" id="viewOutpatientModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"
                            role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Patient Information</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="hidden" id="id" name="id">
                                                <input type="text" class="form-control ml-2" id="first_name"
                                                    placeholder="First Name" name="first_name" disabled />
                                                <label for="floatingInput">First Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control ml-2" id="middle_name"
                                                    placeholder="Middle Name" name="middle_name" disabled />
                                                <label for="floatingInput">Middle Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="phone" class="form-control" id="last_name"
                                                    placeholder="Last Name" name="last_name" disabled />
                                                <label for="floatingInput">Last Name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="street" class="form-control"
                                                    id="street" placeholder="Street" disabled />
                                                <label for="floatingInput">Street</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="brgy" class="form-control"
                                                    id="brgy" placeholder="Brgy"disabled />
                                                <label for="floatingInput">Brgy</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="city" class="form-control"
                                                    id="city" placeholder="City" disabled />
                                                <label for="floatingInput">City</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="province" class="form-control"
                                                    id="province" placeholder="Street" disabled />
                                                <label for="floatingInput">Province</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="number" name="phone" class="form-control"
                                                    id="phone" placeholder="Phone" disabled />
                                                <label for="floatingInput">Phone</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="date" name="birthdate" class="form-control"
                                                    id="birthdate" placeholder="Birthdate" disabled />
                                                <label for="floatingInput">Birthdate</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control p-3" id="gender" name="gender" disabled>
                                                <option>Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="others">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="date" name="date" class="form-control"
                                                    id="date" placeholder="Date" disabled />
                                                <label for="floatingInput">Date</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="time" name="time" class="form-control"
                                                    id="time" placeholder="Time" disabled />
                                                <label for="floatingInput">Time</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-floating mb-3">
                                        <select class="form-control p-3" id="physician" name="physician" disabled>
                                            <option>Select physician</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">Dr.
                                                    {{ ucwords($doctor->first_name) }}
                                                    {{ ucwords($doctor->last_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="medical_condtion" class="form-control"
                                            id="medical_condtion" placeholder="Medical Condition" disabled />
                                        <label for="floatingInput">Medical Condition</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="diagnosis" class="form-control" id="diagnosis"
                                            placeholder="Diagnosis" disabled />
                                        <label for="floatingInput">Diagnosis</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="medication" class="form-control" id="medication"
                                            placeholder="Medication" disabled />
                                        <label for="floatingInput">Medication</label>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control ml-2"
                                                    id="guardian_first_name" placeholder="Guardian First Name"
                                                    name="guardian_first_name" disabled />
                                                <label for="floatingInput">Guardian First Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control ml-2"
                                                    id="guardian_last_name" placeholder="Guardian Last Name"
                                                    name="guardian_last_name" disabled />
                                                <label for="floatingInput">Guardian Last Name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3 ">
                                        <select class="form-control p-3" id="relationship" name="relationship"
                                            disabled>
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
                                                <input type="date" name="guardian_birthdate" class="form-control"
                                                    id="guardian_birthdate" placeholder="Guardian Birthdate" disabled />
                                                <label for="floatingInput">Guardian Birthdate</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="phone" class="form-control" id="guardian_phone"
                                                    placeholder="Guardian Phone" name="guardian_phone" disabled />
                                                <label for="floatingInput">Guardian Phone</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control" id="guardian_email"
                                                    placeholder="Guardian Email" name="guardian_email" disabled />
                                                <label for="floatingInput">Guardian Email</label>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- View OutPatient Modal --}}

                    <!-- [ Main Content ] end -->
                </div>
            </div>


        @endsection

        @section('scripts')
            <script>
                $(document).ready(function() {

                    $('#updateAdmittedpatientModal').on('show.bs.modal', function(event) {
                        var button = $(event.relatedTarget); // Button that triggered the modal
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
                        var admitted_date = JSON.parse(button.data('admitted-date'));
                        var discharged_date = JSON.parse(button.data('discharged-date'));
                        var room_number = JSON.parse(button.data('room-no'));
                        var bed_number = JSON.parse(button.data('bed-no'));
                        var physician = JSON.parse(button.data('physician'));
                        var medical_condition = JSON.parse(button.data('medical-condition'));
                        var diagnosis = JSON.parse(button.data('diagnosis'));
                        var medication = JSON.parse(button.data('medication'));
                        var guardian_first_name = JSON.parse(button.data('guardian-first_name'));
                        var guardian_last_name = JSON.parse(button.data('guardian-last_name'));
                        var guardian_birthdate = JSON.parse(button.data('guardian-birthdate'));
                        var relationship = JSON.parse(button.data('relationship'));
                        var guardian_phone = JSON.parse(button.data('guardian-phone'));
                        var guardian_email = JSON.parse(button.data('guardian-email'));
                        var modal = $(this);

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
                        modal.find('#admitted_date').val(admitted_date);
                        modal.find('#discharged_date').val(discharged_date);
                        modal.find('#room_number').val(room_number);
                        modal.find('#bed_number').val(bed_number);
                        modal.find('#physician').val(physician);
                        modal.find('#medical_condition').val(medical_condition);
                        modal.find('#diagnosis').val(diagnosis);
                        modal.find('#medication').val(medication);
                        modal.find('#guardian_first_name').val(guardian_first_name);
                        modal.find('#guardian_last_name').val(guardian_last_name);
                        modal.find('#guardian_birthdate').val(guardian_birthdate);
                        modal.find('#relationship').val(relationship);
                        modal.find('#guardian_phone').val(guardian_phone);
                        modal.find('#guardian_email').val(guardian_email);
                    });

                    $('#updateaOutpatientModal').on('show.bs.modal', function(event) {
                        var button = $(event.relatedTarget); // Button that triggered the modal
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
                        var date = JSON.parse(button.data('date'));
                        var time = JSON.parse(button.data('time'));
                        var physician = JSON.parse(button.data('physician'));
                        var medical_condition = JSON.parse(button.data('medical-condition'));
                        var diagnosis = JSON.parse(button.data('diagnosis'));
                        var medication = JSON.parse(button.data('medication'));
                        var guardian_first_name = JSON.parse(button.data('guardian-first_name'));
                        var guardian_last_name = JSON.parse(button.data('guardian-last_name'));
                        var guardian_birthdate = JSON.parse(button.data('guardian-birthdate'));
                        var relationship = JSON.parse(button.data('relationship'));
                        var guardian_phone = JSON.parse(button.data('guardian-phone'));
                        var guardian_email = JSON.parse(button.data('guardian-email'));
                        var modal = $(this);

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
                        modal.find('#date').val(date);
                        modal.find('#time').val(time);
                        modal.find('#physician').val(physician);
                        modal.find('#medical_condition').val(medical_condition);
                        modal.find('#diagnosis').val(diagnosis);
                        modal.find('#medication').val(medication);
                        modal.find('#guardian_first_name').val(guardian_first_name);
                        modal.find('#guardian_last_name').val(guardian_last_name);
                        modal.find('#guardian_birthdate').val(guardian_birthdate);
                        modal.find('#relationship').val(relationship);
                        modal.find('#guardian_phone').val(guardian_phone);
                        modal.find('#guardian_email').val(guardian_email);
                    });

                    $('#viewAdmittedPatientModal').on('show.bs.modal', function(event) {
                        var button = $(event.relatedTarget); // Button that triggered the modal
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
                        var admitted_date = JSON.parse(button.data('admitted-date'));
                        var discharged_date = JSON.parse(button.data('discharged-date'));
                        var room_number = JSON.parse(button.data('room-no'));
                        var bed_number = JSON.parse(button.data('bed-no'));
                        var physician = JSON.parse(button.data('physician'));
                        var medical_condition = JSON.parse(button.data('medical-condition'));
                        var diagnosis = JSON.parse(button.data('diagnosis'));
                        var medication = JSON.parse(button.data('medication'));
                        var diagnosis = JSON.parse(button.data('diagnosis'));
                        var medication = JSON.parse(button.data('medication'));
                        var guardian_first_name = JSON.parse(button.data('guardian-first_name'));
                        var guardian_last_name = JSON.parse(button.data('guardian-last_name'));
                        var guardian_birthdate = JSON.parse(button.data('guardian-birthdate'));
                        var relationship = JSON.parse(button.data('relationship'));
                        var guardian_phone = JSON.parse(button.data('guardian-phone'));
                        var guardian_email = JSON.parse(button.data('guardian-email'));
                        var modal = $(this);

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
                        modal.find('#admitted_date').val(admitted_date);
                        modal.find('#discharged_date').val(discharged_date);
                        modal.find('#room_number').val(room_number);
                        modal.find('#bed_number').val(bed_number);
                        modal.find('#physician').val(physician);
                        modal.find('#medical_condition').val(medical_condition);
                        modal.find('#diagnosis').val(diagnosis);
                        modal.find('#medication').val(medication);
                        modal.find('#guardian_first_name').val(guardian_first_name);
                        modal.find('#guardian_last_name').val(guardian_last_name);
                        modal.find('#guardian_birthdate').val(guardian_birthdate);
                        modal.find('#relationship').val(relationship);
                        modal.find('#guardian_phone').val(guardian_phone);
                        modal.find('#guardian_email').val(guardian_email);
                    });

                    $('#viewOutpatientModal').on('show.bs.modal', function(event) {
                        var button = $(event.relatedTarget); // Button that triggered the modal
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
                        var date = JSON.parse(button.data('date'));
                        var time = JSON.parse(button.data('time'));
                        var physician = JSON.parse(button.data('physician'));
                        var medical_condition = JSON.parse(button.data('medical-condition'));
                        var diagnosis = JSON.parse(button.data('diagnosis'));
                        var medication = JSON.parse(button.data('medication'));
                        var guardian_first_name = JSON.parse(button.data('guardian-first_name'));
                        var guardian_last_name = JSON.parse(button.data('guardian-last_name'));
                        var guardian_birthdate = JSON.parse(button.data('guardian-birthdate'));
                        var relationship = JSON.parse(button.data('relationship'));
                        var guardian_phone = JSON.parse(button.data('guardian-phone'));
                        var guardian_email = JSON.parse(button.data('guardian-email'));
                        var modal = $(this);

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
                        modal.find('#date').val(date);
                        modal.find('#time').val(time);
                        modal.find('#physician').val(physician);
                        modal.find('#medical_condition').val(medical_condition);
                        modal.find('#diagnosis').val(diagnosis);
                        modal.find('#medication').val(medication);
                        modal.find('#guardian_first_name').val(guardian_first_name);
                        modal.find('#guardian_last_name').val(guardian_last_name);
                        modal.find('#guardian_birthdate').val(guardian_birthdate);
                        modal.find('#relationship').val(relationship);
                        modal.find('#guardian_phone').val(guardian_phone);
                        modal.find('#guardian_email').val(guardian_email);
                    });
                });
            </script>
        @endsection
