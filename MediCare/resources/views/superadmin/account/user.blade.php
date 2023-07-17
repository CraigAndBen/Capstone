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
                                <h5 class="m-b-10">User Account</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">User Account</li>
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
                            <h1>User Accounts</h1>
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

                                <div class=" d-flex mb-3 justify-content-end">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#createModal">Add Account</button>
                                </div>

                                @if (session('info'))
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> {{ session('info') }}
                                    </div>
                                @endif

                                @if ($users->isEmpty())
                                <div class="alert alert-info">
                                    <span class="fa fa-check-circle"></span> No User Account Yet.
                                </div>
                                @else


                                <div class=" d-flex mb-3 justify-content-end">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#createModal">Add Account</button>
                                </div>
                                <table class="table table-bordered">
                                    <thead class="bg-primary text-light text-center">
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->first_name }}</td>
                                                <td>{{ $user->last_name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->status }}</td>
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                                            data-toggle="dropdown">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu">

                                                            @foreach ($users_info as $info)
                                                                @if ($user->id === $info->account_id)
                                                                    <a class="dropdown-item btn btn-primary"
                                                                        data-toggle="modal" data-target="#updateModal"
                                                                        data-user-id="{{ json_encode($user->id) }}"
                                                                        data-first-name="{{ json_encode($user->first_name) }}"
                                                                        data-last-name="{{ json_encode($user->last_name) }}"
                                                                        data-middle-name="{{ json_encode($user->middle_name) }}"
                                                                        data-age="{{ json_encode($info->age) }}"
                                                                        data-gender="{{ json_encode($info->gender) }}"
                                                                        data-birthdate="{{ json_encode($info->birthdate) }}"
                                                                        data-occupation="{{ json_encode($info->occupation) }}"
                                                                        data-address="{{ json_encode($info->address) }}"
                                                                        data-phone="{{ json_encode($info->phone) }}"
                                                                        data-email="{{ json_encode($user->email) }}">Update
                                                                        Account Profile</a>
                                                                    <a class="dropdown-item" data-toggle="modal"
                                                                        data-target="#updatePasswordModal"
                                                                        data-user-id="{{ json_encode($user->id) }}">Update
                                                                        Password</a>

                                                                    <form
                                                                        action="{{ route('superadmin.user.update.status') }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="user_id"
                                                                            value="{{ $user->id }}">
                                                                        <input type="hidden" name="status"
                                                                            value="{{ $user->status }}">
                                                                        @if ($user->status === 'active')
                                                                            <button type="submit"
                                                                                class="dropdown-item btn btn-primary">Deactivate</button>
                                                                        @else
                                                                            <button type="submit"
                                                                                class="dropdown-item btn btn-primary">Activate</button>
                                                                        @endif
                                                                    </form>

                                                                    <a class="dropdown-item btn btn-primary"
                                                                        data-toggle="modal" data-target="#viewModal"
                                                                        data-first-name="{{ json_encode($user->first_name) }}"
                                                                        data-last-name="{{ json_encode($user->last_name) }}"
                                                                        data-middle-name="{{ json_encode($user->middle_name) }}"
                                                                        data-age="{{ json_encode($info->age) }}"
                                                                        data-gender="{{ json_encode($info->gender) }}"
                                                                        data-occupation="{{ json_encode($info->occupation) }}"
                                                                        data-address="{{ json_encode($info->address) }}"
                                                                        data-birthdate="{{ json_encode($info->birthdate) }}"
                                                                        data-phone="{{ json_encode($info->phone) }}"
                                                                        data-email="{{ json_encode($user->email) }}">View
                                                                        Profile</a>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Update Doctor Account</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('superadmin.update.user') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="hidden" id="user_id" name="user_id" />
                                                    <input type="text" class="form-control ml-2 first_name"
                                                        id="first_name" placeholder="First Name" name="first_name"
                                                        required />
                                                    <label for="floatingInput">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="text" class="form-control ml-2 middle_name"
                                                        id="middle_name" placeholder="Middle Name" name="middle_name"
                                                        required />
                                                    <label for="floatingInput">Middle Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 ">
                                                    <input type="phone" class="form-control" id="last_name"
                                                        placeholder="Last Name" name="last_name" required />
                                                    <label for="floatingInput">Last Name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="occupation" class="form-control" id="occupation"
                                                placeholder="Occupation" />
                                            <label for="floatingInput">Occupation</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 ">
                                                    <input type="number" class="form-control ml-2" id="age"
                                                        placeholder="Age" name="age" required />
                                                    <label for="floatingInput">Age</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control p-3" id="gender" name="gender">
                                                    <option>Select a Gender</option>
                                                    <option value="female">Female</option>
                                                    <option value="male">Male</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="address" class="form-control" id="address"
                                                placeholder="Address" />
                                            <label for="floatingInput">Address</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 ">
                                                    <input type="date" class="form-control ml-2" id="birthdate"
                                                        placeholder="Birthdate" name="birthdate" />
                                                    <label for="floatingInput">Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 ">
                                                    <input type="number" class="form-control" id="phone"
                                                        placeholder="Phone" name="phone" required />
                                                    <label for="floatingInput">Phone</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="email" name="email" class="form-control" id="email"
                                                placeholder="Email" required />
                                            <label for="floatingInput">Email</label>
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


                    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">View Account</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control ml-2 first_name"
                                                    id="first_name" placeholder="First Name" name="first_name"
                                                    readonly />
                                                <label for="floatingInput">First Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="text" class="form-control ml-2 middle_name"
                                                    id="middle_name" placeholder="Middle Name" name="middle_name"
                                                    readonly />
                                                <label for="floatingInput">Middle Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3 ">
                                                <input type="phone" class="form-control" id="last_name"
                                                    placeholder="Last Name" name="last_name" readonly />
                                                <label for="floatingInput">Last Name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="occupation" class="form-control" id="occupation"
                                            placeholder="Occupation" readonly />
                                        <label for="floatingInput">Occupation</label>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3 ">
                                                <input type="number" class="form-control ml-2" id="age"
                                                    placeholder="Age" name="age" readonly />
                                                <label for="floatingInput">Age</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control p-3" id="gender" name="gender" disabled>
                                                <option>Select a Gender</option>
                                                <option value="female">Female</option>
                                                <option value="male">Male</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="address" class="form-control" id="address"
                                            placeholder="Address" readonly />
                                        <label for="floatingInput">Address</label>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3 ">
                                                <input type="date" class="form-control ml-2" id="birthdate"
                                                    placeholder="Date" name="birthdate" readonly />
                                                <label for="floatingInput">Date</label>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3 ">
                                                <input type="number" class="form-control" id="phone"
                                                    placeholder="Phone" name="phone" readonly />
                                                <label for="floatingInput">Phone</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" name="email" class="form-control" id="email"
                                            placeholder="Email" readonly />
                                        <label for="floatingInput">Email</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Edit modal --}}
                    <div class="modal fade" id="createModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Create Account</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('superadmin.store.user') }}">
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
                                                <div class="form-floating mb-3 ">
                                                    <input type="number" class="form-control ml-2"
                                                        id="floatingInput age" placeholder="Age" name="age"
                                                        required />
                                                    <label for="floatingInput">Age</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control p-3" id="gender" name="gender">
                                                    <option>Select a Gender</option>
                                                    <option value="female">Female</option>
                                                    <option value="male">Male</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="occupation" class="form-control"
                                                id="floatingInput occupation" placeholder="Occupation" />
                                            <label for="floatingInput">Occupation</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" name="address" class="form-control"
                                                id="floatingInput address" placeholder="Address" required />
                                            <label for="floatingInput">Address</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 ">
                                                    <input type="date" class="form-control ml-2"
                                                        id="floatingInput birthdate" placeholder="Birthdate"
                                                        name="birthdate" required />
                                                    <label for="floatingInput">Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 ">
                                                    <input type="number" class="form-control" id="floatingInput phone"
                                                        placeholder="Last Name" name="phone" required />
                                                    <label for="floatingInput">Phone</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="email" name="email" class="form-control"
                                                id="floatingInput email" placeholder="Email Address" required />
                                            <label for="floatingInput">Email Address</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-floating mb-3 ">
                                                    <input type="password" name="password" class="form-control"
                                                        id="password" placeholder="New Password" />
                                                    <label for="floatingInput">Password</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-floating mt-2 input-group-append">
                                                    <button class="btn btn-outline-primary" type="button"
                                                        id="passwordTogglePassword">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-floating mb-3 ">
                                                    <input type="password" name="password_confirmation"
                                                        class="form-control" id="password_confirmation"
                                                        placeholder="Password Confirmation" />
                                                    <label for="floatingInput">Password Confirmation</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-floating mt-2 input-group-append">
                                                    <button class="btn btn-outline-primary" type="button"
                                                        id="confirmationPassword">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
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
                    {{-- End Edit Modal --}}

                    <div class="modal fade" id="updatePasswordModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Update Account Password</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('superadmin.user.password.update') }}">
                                        @csrf
                                        <input type="hidden" name="user_id" class="form-control" id="user_id" />
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-floating mb-3 ">
                                                    <input type="password" name="current_password" class="form-control"
                                                        id="current_password" placeholder="Current Password" />
                                                    <label for="floatingInput">Current Password</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-floating mt-2 input-group-append">
                                                    <button class="btn btn-outline-primary toggle-password" type="button"
                                                        id="currentPassTogglePassword">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-floating mb-3 ">
                                                    <input type="password" name="password" class="form-control"
                                                        id="password" placeholder="New Password" />
                                                    <label for="floatingInput">New Password</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-floating mt-2 input-group-append">
                                                    <button class="btn btn-outline-primary toggle-password" type="button"
                                                        id="passwordTogglePassword">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-floating mb-3 ">
                                                    <input type="password" name="password_confirmation"
                                                        class="form-control" id="password_confirmation"
                                                        placeholder="Password Confirmation" />
                                                    <label for="floatingInput">Password Confirmation</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-floating mt-2 input-group-append">
                                                    <button class="btn btn-outline-primary toggle-password" type="button"
                                                        id="confirmationPassword">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
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

                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>


    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {

                $('#updateModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var user_id = JSON.parse(button.data('user-id'));
                    var first_name = JSON.parse(button.data('first-name'));
                    var last_name = JSON.parse(button.data('last-name'));
                    var middle_name = JSON.parse(button.data('middle-name'));
                    var occupation = JSON.parse(button.data('occupation'));
                    var age = JSON.parse(button.data('age'));
                    var gender = JSON.parse(button.data('gender'));
                    var address = JSON.parse(button.data('address'));
                    var birthdate = JSON.parse(button.data('birthdate'));
                    var phone = JSON.parse(button.data('phone'));
                    var email = JSON.parse(button.data('email'));
                    var modal = $(this);

                    modal.find('#first_name').val(first_name);
                    modal.find('#last_name').val(last_name);
                    modal.find('#middle_name').val(middle_name);
                    modal.find('#occupation').val(occupation);
                    modal.find('#age').val(age);
                    modal.find('#gender').val(gender);
                    modal.find('#address').val(address);
                    modal.find('#birthdate').val(birthdate);
                    modal.find('#phone').val(phone);
                    modal.find('#user_id').val(user_id);
                    modal.find('#email').val(email);
                });

                $('#viewModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var first_name = JSON.parse(button.data('first-name'));
                    var last_name = JSON.parse(button.data('last-name'));
                    var middle_name = JSON.parse(button.data('middle-name'));
                    var occupation = JSON.parse(button.data('occupation'));
                    var age = JSON.parse(button.data('age'));
                    var gender = JSON.parse(button.data('gender'));
                    var address = JSON.parse(button.data('address'));
                    var birthdate = JSON.parse(button.data('birthdate'));
                    var phone = JSON.parse(button.data('phone'));
                    var email = JSON.parse(button.data('email'));
                    var modal = $(this);

                    modal.find('#first_name').val(first_name);
                    modal.find('#last_name').val(last_name);
                    modal.find('#middle_name').val(middle_name);
                    modal.find('#occupation').val(occupation);
                    modal.find('#age').val(age);
                    modal.find('#gender').val(gender);
                    modal.find('#address').val(address);
                    modal.find('#birthdate').val(birthdate);
                    modal.find('#phone').val(phone);
                    modal.find('#email').val(email);
                });

                $('#updatePasswordModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var user_id = JSON.parse(button.data('user-id'));
                    var modal = $(this);
                    modal.find('#user_id').val(user_id);
                });
            });
        </script>
    @endsection
