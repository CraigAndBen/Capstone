@extends('layouts.inner_home')

@section('content')

    <section class="breadcrumbs">
        <div class="container" style="margin-top: 85px">

            <div class="d-flex justify-content-between align-items-center">
                <h2><b>Done Appointment</b></h2>
                <ol>
                    <li><a href="user/dashboard">Home</a></li>
                    <li>Done Appointment</li>
                </ol>
            </div>

        </div>
    </section><!-- End Breadcrumbs Section -->

    <section class="inner-page">
        <div class="container">
            <div class="auth-main">
                <div class="auth-wrapper v3">
                    <div class="auth-form">
                        <div class="card my-3 shadow">
                            <div class="row m-4">
                                <h2>Done Appointment List</h2>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div class="m-5">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <strong>Whoops!</strong> There were some problems with your input. Please fix
                                            the
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
                                            No Done Appointment Yet.
                                        </div>
                                    @else
                                        <table class="table table-bordered">
                                            <thead class="bg-primary text-light text-center">
                                                <tr>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Specialties</th>
                                                    <th>Type</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @foreach ($appointments as $appointment)
                                                    <tr class="p-3">
                                                        <td>{{ ucwords($appointment->first_name) }}</td>
                                                        <td>{{ ucwords($appointment->last_name) }}</td>
                                                        <td>{{ ucwords($appointment->specialties) }}</td>
                                                        <td>{{ ucwords($appointment->appointment_type) }}</td>
                                                        <td>{{ ucwords($appointment->appointment_date) }}</td>
                                                        <td>{{ ucwords($appointment->appointment_time) }}</td>
                                                        <td>{{ ucwords($appointment->status) }}</td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle"
                                                                    type="button" data-toggle="dropdown">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item btn btn-primary"
                                                                        data-toggle="modal" data-target="#viewModal"
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
                                        <div class="d-flex justify-content-center my-3">
                                            {{ $appointments->links('pagination::bootstrap-4') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-light">
                    <h3 class="modal-title" id="staticBackdropLabel">Appointment Update</h3>
                </div>
                <div class="modal-body">
                    <div class="row mt-4 text-start">
                        <div class="col-md-4">
                            <div class="form-floating mb-3 ">
                                <input type="text" class="form-control ml-2" id="first_name" placeholder="First Name"
                                    name="first_name" disabled />
                                <label for="floatingInput">First Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3 ">
                                <input type="text" class="form-control" id="middle_name" placeholder="Middle Name"
                                    name="middle_name" disabled />
                                <label for="floatingInput">Middle Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3 ">
                                <input type="text" class="form-control" id="last_name" placeholder="Last Name"
                                    name="last_name" disabled />
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
                                <input type="text" class="form-control" id="province" name="province"
                                    placeholder="Province" disabled />
                                <label for="floatingInput">Province</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row" form-floating mb-3>
                        <div class="col-md-6">
                            <div class=" form-floating mb-3">
                                <input type="date" class="form-control" id="birthdate" name="birthdate"
                                    placeholder="Date of Birth" disabled />
                                <label for="floatingInput">Date of Birth</label>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <select class="form-control  p-3" id="gender" name="gender" disabled>
                                <option>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="diagnostic appointment">Others</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone"
                            disabled />
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
                            <select class="form-control p-3" id="specialties" name="specialties" disabled>
                                <option>Select Specialist</option>
                                @foreach ($infos as $info)
                                    <option value="{{ $info->specialties }}">{{ $info->specialties }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="form-floating mb-3">
                            <h5>Which procedure do you want to make an appointment for?</h5>
                            <select class="form-control  p-3" id="appointment_type" name="appointment_type" disabled>
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
                                <input type="date" class="form-control" id="appointment_date" name="appointment_date"
                                    placeholder="Date" min="<?= date('Y-m-d') ?>" disabled />
                                <label for="floatingInput">Appointment Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-control  p-3" id="appointment_time" name="appointment_time" disabled>
                                    <option>Select Time of Appointment</option>
                                    @foreach ($timeList as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
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
