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
                                <h5 class="m-b-10">Admin Account</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Admin Account</li>
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
                            <h1>Admin Accounts</h1>
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

                                <div class=" d-flex mb-3 justify-content-end">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#createModal">Add Account</button>
                                </div>

                                @if ($users->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Admin Account Yet.
                                    </div>
                                @else

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

                                                            @foreach ($admins as $admin)
                                                                @if ($user->id === $admin->account_id)
                                                                    <a class="dropdown-item btn btn-primary"
                                                                        data-toggle="modal" data-target="#updateModal"
                                                                        data-user-id="{{ json_encode($user->id) }}"
                                                                        data-first-name="{{ json_encode($user->first_name) }}"
                                                                        data-last-name="{{ json_encode($user->last_name) }}"
                                                                        data-middle-name="{{ json_encode($user->middle_name) }}"
                                                                        data-access-level="{{ json_encode($admin->access_level) }}"
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
                                                                        data-access-level="{{ json_encode($admin->access_level) }}"
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
                                    <form method="POST" action="{{ route('superadmin.update.admin') }}">
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
                                            <select class="form-control p-3" id="access_level" name="access_level">
                                                <option>Select a Access Level</option>
                                                <option value="limited access">Limited Access</option>
                                                <option value="full access">Full Access</option>
                                            </select>
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
                                        <select class="form-control p-3" id="access_level" name="access_level" disabled>
                                            <option>Select a Access Level</option>
                                            <option value="limited access">Limited Access</option>
                                            <option value="full access">Full Access</option>
                                        </select>
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
                                    <form method="POST" action="{{ route('superadmin.store.admin') }}">
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
                                        <div class="form-floating mb-3">
                                            <select class="form-control p-3" id="access_level" name="access_level">
                                                <option>Select a Access Level</option>
                                                <option value="limited access">Limited Access</option>
                                                <option value="full access">Full Access</option>
                                            </select>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="email" name="email" class="form-control"
                                                id="floatingInput email" placeholder="Email Address" required />
                                            <label for="floatingInput">Email Address</label>
                                        </div>
                                        <div class="form-floating mb-3 ">
                                            <input type="password" name="password" class="form-control"
                                                id="password" placeholder="New Password" />
                                            <label for="floatingInput">Password</label>
                                        </div>
                                        <div class="form-floating mb-3 ">
                                            <input type="password" name="password_confirmation"
                                                class="form-control" id="password_confirmation"
                                                placeholder="Password Confirmation" />
                                            <label for="floatingInput">Password Confirmation</label>
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
                    var access_level = JSON.parse(button.data('access-level'));
                    var email = JSON.parse(button.data('email'));
                    var modal = $(this);

                    modal.find('#first_name').val(first_name);
                    modal.find('#last_name').val(last_name);
                    modal.find('#middle_name').val(middle_name);
                    modal.find('#access_level').val(access_level);
                    modal.find('#user_id').val(user_id);
                    modal.find('#email').val(email);
                });

                $('#viewModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var first_name = JSON.parse(button.data('first-name'));
                    var last_name = JSON.parse(button.data('last-name'));
                    var middle_name = JSON.parse(button.data('middle-name'));
                    var access_level = JSON.parse(button.data('access-level'));
                    var email = JSON.parse(button.data('email'));
                    var modal = $(this);

                    modal.find('#first_name').val(first_name);
                    modal.find('#last_name').val(last_name);
                    modal.find('#middle_name').val(middle_name);
                    modal.find('#access_level').val(access_level);
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