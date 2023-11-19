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
                                <h5 class="m-b-10">Appointment List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
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
                            <h1 class="display-6">Appointment List </h1>
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
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Type</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @foreach ($appointments as $appointment)
                                                    <tr>
                                                        <td>{{ ucwords($appointment->first_name) }}</td>
                                                        <td>{{ ucwords($appointment->last_name) }}</td>
                                                        <td>{{ ucwords($appointment->appointment_type) }}</td>
                                                        <td>{{ date('F j, Y', strtotime($appointment->appointment_date)) }}</td>
                                                        <td>{{ ucwords($appointment->appointment_time) }}</td>
                                                        <td>{{ ucwords($appointment->status) }}</td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                                    data-toggle="dropdown">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item btn btn-primary" data-toggle="modal"
                                                                        data-target="#viewModal"
                                                                        data-first-name="{{ json_encode($appointment->first_name) }}"
                                                                        data-middle-name="{{ json_encode($appointment->middle_name) }}"
                                                                        data-last-name="{{ json_encode($appointment->last_name) }}"
                                                                        data-street="{{ json_encode($appointment->street) }}"
                                                                        data-brgy="{{ json_encode($appointment->brgy) }}"
                                                                        data-city="{{ json_encode($appointment->city) }}"
                                                                        data-province="{{ json_encode($appointment->province) }}"
                                                                        data-email="{{ json_encode($appointment->email) }}"
                                                                        data-birthdate="{{ json_encode($appointment->birthdate) }}"
                                                                        data-gender="{{ json_encode($appointment->gender) }}"
                                                                        data-phone="{{ json_encode($appointment->phone) }}"
                                                                        data-specialties="{{ json_encode($appointment->specialties) }}"
                                                                        data-appointment-type="{{ json_encode($appointment->appointment_type) }}"
                                                                        data-appointment-date="{{ json_encode($appointment->appointment_date) }}"
                                                                        data-appointment-time="{{ json_encode($appointment->appointment_time) }}"
                                                                        data-reason="{{ json_encode($appointment->reason) }}">View</a>
    
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


                    <div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-light">
                                    <h3 class="modal-title text-light" id="staticBackdropLabel">Appointment Information</h3>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mt-4 text-start">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control ml-2" id="first_name"
                                                    placeholder="First Name" name="first_name" disabled />
                                                <label for="floatingInput">First Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control" id="middle_name"
                                                    placeholder="Middle Name" name="middle_name" disabled />
                                                <label for="floatingInput">Middle Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control" id="last_name"
                                                    placeholder="Last Name" name="last_name" disabled />
                                                <label for="floatingInput">Last Name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class=" form-floating mb-3">
                                                <input type="text" class="form-control" id="street" name="street"
                                                    placeholder="Street" disabled />
                                                <label for="floatingInput">Street</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class=" form-floating mb-3">
                                                <input type="text" class="form-control" id="brgy" name="brgy"
                                                    placeholder="Brgy" disabled />
                                                <label for="floatingInput">State/Barangay</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class=" form-floating mb-3">
                                                <input type="text" class="form-control" id="city" name="city"
                                                    placeholder="City" disabled />
                                                <label for="floatingInput">City</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class=" form-floating mb-3">
                                                <input type="text" class="form-control" id="province"
                                                    name="province" placeholder="Province" disabled />
                                                <label for="floatingInput">Province</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row" form-floating mb-3>
                                        <div class="col-md-6">
                                            <div class=" form-floating mb-3">
                                                <input type="date" class="form-control" id="birthdate"
                                                    name="birthdate" placeholder="Date of Birth" disabled />
                                                <label for="floatingInput">Date of Birth</label>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <select class="form-control  p-3" id="gender" name="gender" disabled>
                                                <option value="">Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="diagnostic appointment">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            placeholder="Phone" disabled />
                                        <label for="floatingInput">Phone</label>
                                    </div>
                                    <hr>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Email Address" disabled />
                                        <label for="floatingInput">Email Address</label>
                                    </div>
                                    <hr>
                                    <div class="row mt-4">
                                        <h5>Which specialist do you want to appoint of?</h5>
                                        <div class="form-floating mb-3">
                                            <select class="form-control p-3" id="specialties" name="specialties"
                                                disabled>
                                                <option>Select Specialist</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->specialties }}">{{ $doctor->specialties }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="form-floating mb-3">
                                            <h5>Which procedure do you want to make an appointment for?</h5>
                                            <select class="form-control  p-3" id="appointment_type"
                                                name="appointment_type" disabled>
                                                <option>Select a Type of Appointment</option>
                                                <option value="regular check-up">Regular Check-up</option>
                                                <option value="Follow-up appointment">Follow-up Appointment</option>
                                                <option value="diagnostic appointment">Diagnostic Appointment</option>
                                                <option value="specialist consultation">Specialist Consultation</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mt-4">
                                        <h5>Preffered Appointment Date and Time <i>(Monday - Friday)</i></h5>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="date" class="form-control" id="appointment_date"
                                                    name="appointment_date" placeholder="Date" min="<?= date('Y-m-d') ?>"
                                                    disabled />
                                                <label for="floatingInput">Appointment Date</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-control  p-3" id="appointment_time"
                                                    name="appointment_time" disabled>
                                                    <option>Select Time of Appointment</option>
                                                    @foreach ($amTime as $time)
                                                        <option value="{{ $time }} AM">{{ $time }} AM
                                                        </option>
                                                    @endforeach
                                                    @foreach ($pmTime as $time)
                                                        <option value="{{ $time }} PM">{{ $time }} PM
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mb-3">
                                    <h5>Reason for appointment</h5>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="reason" name="reason"
                                            placeholder="Reason For Appointment" disabled />
                                        <label for="floatingInput">Reason for Appointment</label>
                                    </div>
                                    <hr>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Back</button>
                                    </div>
                                    </form>
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

    @section('scripts')
        <script>
            $(document).ready(function() {

                $('#viewModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var first_name = JSON.parse(button.data('first-name'));
                    var middle_name = JSON.parse(button.data('middle-name'));
                    var last_name = JSON.parse(button.data('last-name'));
                    var street = JSON.parse(button.data('street'));
                    var brgy = JSON.parse(button.data('brgy'));
                    var city = JSON.parse(button.data('city'));
                    var province = JSON.parse(button.data('province'));
                    var email = JSON.parse(button.data('email'));
                    var birthdate = JSON.parse(button.data('birthdate'));
                    var gender = JSON.parse(button.data('gender'));
                    var phone = JSON.parse(button.data('phone'));
                    var specialties = JSON.parse(button.data('specialties'));
                    var appointment_type = JSON.parse(button.data('appointment-type'));
                    var appointment_date = JSON.parse(button.data('appointment-date'));
                    var appointment_time = JSON.parse(button.data('appointment-time'));
                    var reason = JSON.parse(button.data('reason'));
                    var modal = $(this);

                    modal.find('#first_name').val(first_name);
                    modal.find('#middle_name').val(middle_name);
                    modal.find('#last_name').val(last_name);
                    modal.find('#street').val(street);
                    modal.find('#brgy').val(brgy);
                    modal.find('#city').val(city);
                    modal.find('#province').val(province);
                    modal.find('#email').val(email);
                    modal.find('#birthdate').val(birthdate);
                    modal.find('#gender').val(gender);
                    modal.find('#phone').val(phone);
                    modal.find('#specialties').val(specialties);
                    modal.find('#appointment_type').val(appointment_type);
                    modal.find('#appointment_date').val(appointment_date);
                    modal.find('#appointment_time').val(appointment_time);
                    modal.find('#reason').val(reason);
                });
            });
        </script>
    @endsection
