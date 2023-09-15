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
                                <h5 class="m-b-10">Patient List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
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

                                <div class="d-flex justify-content-end m-4">
                                    <div class="m-1">
                                        <a href="{{ route('doctor.patient') }}" class="btn btn-success">Show All</a>
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
                                        <span class="fa fa-check-circle"></span> {{ session('info') }}
                                    </div>
                                @endif

                                @if ($patients->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Patient Exist.
                                    </div>
                                @else
                                    <form action="{{ route('doctor.patient.search') }}" method="POST">
                                        @csrf
                                        <div class="row d-flex justify-content-center">
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
                                                <th>Diagnosis</th>
                                                <th>Type</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($patients as $patient)
                                                <tr>
                                                    <td>{{ ucwords($patient->first_name) }}</td>
                                                    <td>{{ ucwords($patient->last_name) }}</td>
                                                    <td>{{ ucwords($patient->diagnosis) }}</td>

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
                                                                        data-target="#updateOutpatientModal"
                                                                        data-id="{{ json_encode($patient->id) }}"
                                                                        data-first-name="{{ json_encode($patient->first_name) }}"
                                                                        data-middle-name="{{ json_encode($patient->middle_name) }}"
                                                                        data-last-name="{{ json_encode($patient->last_name) }}"
                                                                        data-date="{{ json_encode($patient->date) }}"
                                                                        data-time="{{ json_encode($patient->time) }}"
                                                                        data-physician="{{ json_encode($patient->physician) }}"
                                                                        data-medical-condition="{{ json_encode($patient->medical_condition) }}"
                                                                        data-diagnosis="{{ json_encode($patient->diagnosis) }}"
                                                                        data-medication="{{ json_encode($patient->medication) }}">Update</a>
                                                                    <a class="dropdown-item btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#viewOutpatientModal"
                                                                        data-first-name="{{ json_encode($patient->first_name) }}"
                                                                        data-middle-name="{{ json_encode($patient->middle_name) }}"
                                                                        data-last-name="{{ json_encode($patient->last_name) }}"
                                                                        data-date="{{ json_encode($patient->date) }}"
                                                                        data-time="{{ json_encode($patient->time) }}"
                                                                        data-physician="{{ json_encode($patient->physician) }}"
                                                                        data-medical-condition="{{ json_encode($patient->medical_condition) }}"
                                                                        data-diagnosis="{{ json_encode($patient->diagnosis) }}"
                                                                        data-guardian-first_name="{{ json_encode($patient->guardian_first_name) }}"
                                                                        data-medication="{{ json_encode($patient->medication) }}">View</a>
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
                                                                        data-admitted-date="{{ json_encode($patient->admitted_date) }}"
                                                                        data-discharged-date="{{ json_encode($patient->discharged_date) }}"
                                                                        data-room-no="{{ json_encode($patient->room_number) }}"
                                                                        data-bed-no="{{ json_encode($patient->bed_number) }}"
                                                                        data-physician="{{ json_encode($patient->physician) }}"
                                                                        data-medical-condition="{{ json_encode($patient->medical_condition) }}"
                                                                        data-diagnosis="{{ json_encode($patient->diagnosis) }}"
                                                                        data-medication="{{ json_encode($patient->medication) }}">Update</a>

                                                                    <a class="dropdown-item btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#viewAdmittedPatientModal"
                                                                        data-first-name="{{ json_encode($patient->first_name) }}"
                                                                        data-middle-name="{{ json_encode($patient->middle_name) }}"
                                                                        data-last-name="{{ json_encode($patient->last_name) }}"
                                                                        data-admitted-date="{{ json_encode($patient->admitted_date) }}"
                                                                        data-discharged-date="{{ json_encode($patient->discharged_date) }}"
                                                                        data-room-no="{{ json_encode($patient->room_number) }}"
                                                                        data-bed-no="{{ json_encode($patient->bed_number) }}"
                                                                        data-physician="{{ json_encode($patient->physician) }}"
                                                                        data-medical-condition="{{ json_encode($patient->medical_condition) }}"
                                                                        data-diagnosis="{{ json_encode($patient->diagnosis) }}"
                                                                        data-medication="{{ json_encode($patient->medication) }}">View</a>
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

                    {{-- Update Outpatient modal --}}
                    <div class="modal fade" id="updateOutpatientModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Update Patient Information</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('doctor.patient.update') }}">
                                        @csrf
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
                                        <div class="form-floating mb-3">
                                            <select class="form-control p-3" id="physician" name="physician" disabled>
                                                <option>Select physician</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->first_name }}
                                                        {{ $doctor->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <hr>
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

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Update Outpatient Modal --}}

                    {{-- View Outpatient modal --}}
                    <div class="modal fade" id="viewOutpatientModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                                                <input type="date" name="date" class="form-control" id="date"
                                                    placeholder="Date" disabled />
                                                <label for="floatingInput">Date</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="time" name="time" class="form-control" id="time"
                                                    placeholder="Discharged Time" disabled />
                                                <label for="floatingInput">Time</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select class="form-control p-3" id="physician" name="physician" disabled>
                                            <option>Select physician</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">Dr. {{ $doctor->first_name }}
                                                    {{ $doctor->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <hr>
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
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End View Outpatient Modal --}}

                    {{-- Update admitted Patient modal --}}
                    <div class="modal fade" id="updateAdmittedpatientModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Update Patient Information</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('doctor.patient.update') }}">
                                        @csrf
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
                                                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->first_name }}
                                                        {{ $doctor->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <hr>
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

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Update Admitted Patient Modal --}}

                    {{-- View Admitted Patient modal --}}
                    <div class="modal fade" id="viewAdmittedPatientModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                                                <option value="{{ $doctor->id }}">Dr. {{ $doctor->first_name }}
                                                    {{ $doctor->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <hr>
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
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End View Admitted Patient Modal --}}


                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>


    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {

                $('#updateOutpatientModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = JSON.parse(button.data('id'));
                    var first_name = JSON.parse(button.data('first-name'));
                    var middle_name = JSON.parse(button.data('middle-name'));
                    var last_name = JSON.parse(button.data('last-name'));
                    var date = JSON.parse(button.data('date'));
                    var time = JSON.parse(button.data('time'));
                    var physician = JSON.parse(button.data('physician'));
                    var medical_condition = JSON.parse(button.data('medical-condition'));
                    var diagnosis = JSON.parse(button.data('diagnosis'));
                    var medication = JSON.parse(button.data('medication'));
                    var modal = $(this);

                    modal.find('#id').val(id);
                    modal.find('#first_name').val(first_name);
                    modal.find('#middle_name').val(middle_name);
                    modal.find('#last_name').val(last_name);
                    modal.find('#date').val(date);
                    modal.find('#time').val(time);
                    modal.find('#physician').val(physician);
                    modal.find('#medical_condition').val(medical_condition);
                    modal.find('#diagnosis').val(diagnosis);
                    modal.find('#medication').val(medication);
                });

                $('#viewOutpatientModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var first_name = JSON.parse(button.data('first-name'));
                    var middle_name = JSON.parse(button.data('middle-name'));
                    var last_name = JSON.parse(button.data('last-name'));
                    var date = JSON.parse(button.data('date'));
                    var time = JSON.parse(button.data('time'));
                    var physician = JSON.parse(button.data('physician'));
                    var medical_condition = JSON.parse(button.data('medical-condition'));
                    var diagnosis = JSON.parse(button.data('diagnosis'));
                    var medication = JSON.parse(button.data('medication'));
                    var modal = $(this);

                    modal.find('#first_name').val(first_name);
                    modal.find('#middle_name').val(middle_name);
                    modal.find('#last_name').val(last_name);
                    modal.find('#date').val(date);
                    modal.find('#time').val(time);
                    modal.find('#physician').val(physician);
                    modal.find('#medical_condition').val(medical_condition);
                    modal.find('#diagnosis').val(diagnosis);
                    modal.find('#medication').val(medication);
                });

                $('#updateAdmittedpatientModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = JSON.parse(button.data('id'));
                    var first_name = JSON.parse(button.data('first-name'));
                    var middle_name = JSON.parse(button.data('middle-name'));
                    var last_name = JSON.parse(button.data('last-name'));
                    var admitted_date = JSON.parse(button.data('admitted-date'));
                    var discharged_date = JSON.parse(button.data('discharged-date'));
                    var room_number = JSON.parse(button.data('room-no'));
                    var bed_number = JSON.parse(button.data('bed-no'));
                    var physician = JSON.parse(button.data('physician'));
                    var medical_condition = JSON.parse(button.data('medical-condition'));
                    var diagnosis = JSON.parse(button.data('diagnosis'));
                    var medication = JSON.parse(button.data('medication'));
                    var modal = $(this);

                    modal.find('#id').val(id);
                    modal.find('#first_name').val(first_name);
                    modal.find('#middle_name').val(middle_name);
                    modal.find('#last_name').val(last_name);
                    modal.find('#admitted_date').val(admitted_date);
                    modal.find('#discharged_date').val(discharged_date);
                    modal.find('#room_number').val(room_number);
                    modal.find('#bed_number').val(bed_number);
                    modal.find('#physician').val(physician);
                    modal.find('#medical_condition').val(medical_condition);
                    modal.find('#diagnosis').val(diagnosis);
                    modal.find('#medication').val(medication);
                });

                $('#viewAdmittedPatientModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var first_name = JSON.parse(button.data('first-name'));
                    var middle_name = JSON.parse(button.data('middle-name'));
                    var last_name = JSON.parse(button.data('last-name'));
                    var admitted_date = JSON.parse(button.data('admitted-date'));
                    var discharged_date = JSON.parse(button.data('discharged-date'));
                    var room_number = JSON.parse(button.data('room-no'));
                    var bed_number = JSON.parse(button.data('bed-no'));
                    var physician = JSON.parse(button.data('physician'));
                    var medical_condition = JSON.parse(button.data('medical-condition'));
                    var diagnosis = JSON.parse(button.data('diagnosis'));
                    var medication = JSON.parse(button.data('medication'));
                    var modal = $(this);

                    modal.find('#first_name').val(first_name);
                    modal.find('#middle_name').val(middle_name);
                    modal.find('#last_name').val(last_name);
                    modal.find('#admitted_date').val(admitted_date);
                    modal.find('#discharged_date').val(discharged_date);
                    modal.find('#room_number').val(room_number);
                    modal.find('#bed_number').val(bed_number);
                    modal.find('#physician').val(physician);
                    modal.find('#medical_condition').val(medical_condition);
                    modal.find('#diagnosis').val(diagnosis);
                    modal.find('#medication').val(medication);
                });
            });
        </script>
    @endsection
