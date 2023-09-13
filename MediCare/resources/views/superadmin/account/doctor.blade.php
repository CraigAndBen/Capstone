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
                                <h5 class="m-b-10">Doctor Account</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Doctor Account</li>
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
                            <h1>Doctor Accounts</h1>
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
                                        <span class="fa fa-check-circle"></span> No Doctor Account Yet.
                                    </div>
                                @else
                                    <table class="table table-bordered">
                                        <thead class="bg-primary text-light text-center">
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Specialties</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ ucwords($user->first_name) }}</td>
                                                    <td>{{ ucwords($user->last_name) }}</td>
                                                    <td>{{ ucwords($user->email) }}</td>
                                                    @foreach ($doctors as $doctor)
                                                        @if ($user->id == $doctor->account_id)
                                                            <td>{{ ucwords($doctor->specialties) }}</td>
                                                        @endif
                                                    @endforeach
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                                data-toggle="dropdown">
                                                                Actions
                                                            </button>
                                                            <div class="dropdown-menu">

                                                                @foreach ($doctors as $doctor)
                                                                    @if ($user->id === $doctor->account_id)
                                                                        <a class="dropdown-item btn btn-primary"
                                                                            data-toggle="modal" data-target="#updateModal"
                                                                            data-user-id="{{ json_encode($user->id) }}"
                                                                            data-first-name="{{ json_encode($user->first_name) }}"
                                                                            data-last-name="{{ json_encode($user->last_name) }}"
                                                                            data-middle-name="{{ json_encode($user->middle_name) }}"
                                                                            data-age="{{ json_encode($doctor->age) }}"
                                                                            data-gender="{{ json_encode($doctor->gender) }}"
                                                                            data-qualification="{{ json_encode($doctor->qualification) }}"
                                                                            data-birthdate="{{ json_encode($doctor->birthdate) }}"
                                                                            data-employment-date="{{ json_encode($doctor->employment_date) }}"
                                                                            data-years-of-experience="{{ json_encode($doctor->years_of_experience) }}"
                                                                            data-specialties="{{ json_encode($doctor->specialties) }}"
                                                                            data-street="{{ json_encode($doctor->street) }}"
                                                                            data-brgy="{{ json_encode($doctor->brgy) }}"
                                                                            data-city="{{ json_encode($doctor->city) }}"
                                                                            data-province="{{ json_encode($doctor->province) }}"
                                                                            data-phone="{{ json_encode($doctor->phone) }}"
                                                                            data-facebook="{{ json_encode($doctor->facebook_link) }}"
                                                                            data-twitter="{{ json_encode($doctor->twitter_link) }}"
                                                                            data-instagram="{{ json_encode($doctor->instagram_link) }}"
                                                                            data-linkedin="{{ json_encode($doctor->linkedin_link) }}"
                                                                            data-email="{{ json_encode($user->email) }}">Update Profile</a>
                                                                        <a class="dropdown-item btn btn-primary"
                                                                            data-toggle="modal"
                                                                            data-target="#updatePasswordModal"
                                                                            data-user-id="{{ json_encode($user->id) }}">Update
                                                                            Password</a>

                                                                        <a class="dropdown-item btn btn-primary"
                                                                            data-toggle="modal" data-target="#viewModal"
                                                                            data-first-name="{{ json_encode($user->first_name) }}"
                                                                            data-last-name="{{ json_encode($user->last_name) }}"
                                                                            data-middle-name="{{ json_encode($user->middle_name) }}"
                                                                            data-age="{{ json_encode($doctor->age) }}"
                                                                            data-gender="{{ json_encode($doctor->gender) }}"
                                                                            data-qualification="{{ json_encode($doctor->qualification) }}"
                                                                            data-years-of-experience="{{ json_encode($doctor->years_of_experience) }}"
                                                                            data-specialties="{{ json_encode($doctor->specialties) }}"
                                                                            data-street="{{ json_encode($doctor->street) }}"
                                                                            data-brgy="{{ json_encode($doctor->brgy) }}"
                                                                            data-city="{{ json_encode($doctor->city) }}"
                                                                            data-province="{{ json_encode($doctor->province) }}"
                                                                            data-phone="{{ json_encode($doctor->phone) }}"
                                                                            data-facebook="{{ json_encode($doctor->facebook_link) }}"
                                                                            data-twitter="{{ json_encode($doctor->twitter_link) }}"
                                                                            data-instagram="{{ json_encode($doctor->instagram_link) }}"
                                                                            data-linkedin="{{ json_encode($doctor->linkedin_link) }}"
                                                                            data-birthdate="{{ json_encode($doctor->birthdate) }}"
                                                                            data-phone="{{ json_encode($doctor->phone) }}"
                                                                            data-email="{{ json_encode($user->email) }}">View Profile</a>
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
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Update Doctor Account</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('superadmin.update.doctor') }}"  enctype="multipart/form-data">
                                        @csrf
                                        <div class="row my-2">
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="hidden" id="user_id" name="user_id" />
                                                    <input type="text" class="form-control ml-2 first_name"
                                                        id="first_name" placeholder="First Name" name="first_name" />
                                                    <label for="floatingInput">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control ml-2 middle_name"
                                                        id="middle_name" placeholder="Middle Name" name="middle_name" />
                                                    <label for="floatingInput">Middle Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="phone" class="form-control" id="last_name"
                                                        placeholder="Last Name" name="last_name" />
                                                    <label for="floatingInput">Last Name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="street" class="form-control"
                                                        id="street" placeholder="Street" />
                                                    <label for="floatingInput">Street</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="brgy" class="form-control"
                                                        id="brgy" placeholder="Brgy" />
                                                    <label for="floatingInput">Brgy</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="city" class="form-control"
                                                        id="city" placeholder="City" />
                                                    <label for="floatingInput">City</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="province" class="form-control"
                                                        id="province" placeholder="Province" />
                                                    <label for="floatingInput">Province</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-floating my-2">
                                            <input type="text" name="specialties" class="form-control"
                                                id="specialties" placeholder="Specialties" />
                                            <label for="floatingInput">Specialties</label>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control ml-2" id="age"
                                                        placeholder="Age" name="age" />
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
                                        <div class="form-floating my-2">
                                            <input type="text" name="qualification" class="form-control"
                                                id="qualification" placeholder="Qualifications" />
                                            <label for="floatingInput">Qualifications</label>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control ml-2" id="employment_date"
                                                        placeholder="Employment Date" name="employment_date" />
                                                    <label for="floatingInput">Employment Date</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" id="years_of_experience"
                                                        placeholder="Years of Experience" name="years_of_experience" />
                                                    <label for="floatingInput">Years of Experience</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control ml-2" id="birthdate"
                                                        placeholder="Birthdate" name="birthdate" />
                                                    <label for="floatingInput">Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" id="phone"
                                                        placeholder="Phone" name="phone" />
                                                    <label for="floatingInput">Phone</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="facebook" class="form-control"
                                                        id="facebook" placeholder="Facebook Link" />
                                                    <label for="floatingInput">Facebook Link</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="twitter" class="form-control"
                                                        id="twitter" placeholder="twitter Link" />
                                                    <label for="floatingInput">Twitter Link</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="instagram" class="form-control"
                                                        id="instagram" placeholder="Instagram Link" />
                                                    <label for="floatingInput">Instagram Link</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="linkedin" class="form-control"
                                                        id="linkedin" placeholder="Linkedin Link" />
                                                    <label for="floatingInput">Linkedin Link</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row my-3">
                                            <div class="col-md-3">
                                                <label for="floatingInput">Change Profile Image:</label>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-floating">
                                                    <input type="file" name="image" id="image">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-floating my-2">
                                            <input type="email" name="email" class="form-control" id="email"
                                                placeholder="Email" />
                                            <label for="floatingInput">Email</label>
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


                    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">View Account Profile</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="row my-2">
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="hidden" id="user_id" name="user_id" />
                                                <input type="text" class="form-control ml-2 first_name"
                                                    id="first_name" placeholder="First Name" name="first_name" disabled/>
                                                <label for="floatingInput">First Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="text" class="form-control ml-2 middle_name"
                                                    id="middle_name" placeholder="Middle Name" name="middle_name" disabled/>
                                                <label for="floatingInput">Middle Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="phone" class="form-control" id="last_name"
                                                    placeholder="Last Name" name="last_name" disabled/>
                                                <label for="floatingInput">Last Name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row my-2">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="street" class="form-control" id="street"
                                                    placeholder="Street" disabled/>
                                                <label for="floatingInput">Street</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="brgy" class="form-control" id="brgy"
                                                    placeholder="Brgy" disabled/>
                                                <label for="floatingInput">Brgy</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="city" class="form-control" id="city"
                                                    placeholder="City" disabled/>
                                                <label for="floatingInput">City</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="province" class="form-control"
                                                    id="province" placeholder="Province" disabled/>
                                                <label for="floatingInput">Province</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-floating my-2">
                                        <input type="text" name="specialties" class="form-control" id="specialties"
                                            placeholder="Specialties" disabled/>
                                        <label for="floatingInput">Specialties</label>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" class="form-control ml-2" id="age"
                                                    placeholder="Age" name="age" disabled/>
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
                                    <div class="form-floating my-2">
                                        <input type="text" name="qualification" class="form-control"
                                            id="qualification" placeholder="Qualifications" disabled/>
                                        <label for="floatingInput">Qualifications</label>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="date" class="form-control ml-2" id="employment_date"
                                                    placeholder="Employment Date" name="employment_date" disabled/>
                                                <label for="floatingInput">Employment Date</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" id="years_of_experience"
                                                    placeholder="Years of Experience" name="years_of_experience" disabled/>
                                                <label for="floatingInput">Years of Experience</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="date" class="form-control ml-2" id="birthdate"
                                                    placeholder="Birthdate" name="birthdate" disabled/>
                                                <label for="floatingInput">Birthdate</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" id="phone"
                                                    placeholder="Phone" name="phone" disabled/>
                                                <label for="floatingInput">Phone</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row my-2">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="facebook" class="form-control"
                                                    id="facebook" placeholder="Facebook Link" disabled/>
                                                <label for="floatingInput">Facebook Link</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="twitter" class="form-control"
                                                    id="twitter" placeholder="twitter Link" disabled/>
                                                <label for="floatingInput">Twitter Link</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="instagram" class="form-control"
                                                    id="instagram" placeholder="Instagram Link" disabled/>
                                                <label for="floatingInput">Instagram Link</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="linkedin" class="form-control"
                                                    id="linkedin" placeholder="Linkedin Link" disabled/>
                                                <label for="floatingInput">Linkedin Link</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-floating my-2">
                                        <input type="email" name="email" class="form-control" id="email"
                                            placeholder="Email" disabled/>
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
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Create Doctor Account</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('superadmin.store.doctor') }}">
                                        @csrf
                                        <div class="row my-2">
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control ml-2"
                                                        id="floatingInput first_name" placeholder="First Name"
                                                        name="first_name" required />
                                                    <label for="floatingInput">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control ml-2"
                                                        id="floatingInput middle_name" placeholder="Middle Name"
                                                        name="middle_name" required />
                                                    <label for="floatingInput">Middle Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating ">
                                                    <input type="phone" class="form-control"
                                                        id="floatingInput last_name" placeholder="Last Name"
                                                        name="last_name" required />
                                                    <label for="floatingInput">Last Name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="street" class="form-control"
                                                        id="floatingInput street" placeholder="Street" />
                                                    <label for="floatingInput">Street</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="brgy" class="form-control"
                                                        id="floatingInput brgy" placeholder="Brgy" />
                                                    <label for="floatingInput">Brgy</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="city" class="form-control"
                                                        id="floatingInput city" placeholder="City" />
                                                    <label for="floatingInput">City</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="province" class="form-control"
                                                        id="floatingInput province" placeholder="Province" />
                                                    <label for="floatingInput">Province</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-floating my-2">
                                            <input type="text" name="specialties" class="form-control"
                                                id="floatingInput specialties" placeholder="Email Address" />
                                            <label for="floatingInput">Specialties</label>
                                        </div>
                                        <div class="row my-2">
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
                                        <div class="form-floating my-2">
                                            <input type="text" name="qualification" class="form-control"
                                                id="floatingInput qualification" placeholder="Qualifications" />
                                            <label for="floatingInput">Qualifications</label>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control ml-2"
                                                        id="floatingInput employment_date" placeholder="Employment Date"
                                                        name="employment_date" required />
                                                    <label for="floatingInput">Employment Date</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control"
                                                        id="floatingInput years_of_experience"
                                                        placeholder="Years of Experience" name="years_of_experience" />
                                                    <label for="floatingInput">Years of Experience</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control ml-2"
                                                        id="floatingInput birthdate" placeholder="Birthdate"
                                                        name="birthdate" required />
                                                    <label for="floatingInput">Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" id="floatingInput phone"
                                                        placeholder="Last Name" name="phone" />
                                                    <label for="floatingInput">Phone</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="facebook" class="form-control"
                                                        id="facebook" placeholder="Facebook Link" />
                                                    <label for="floatingInput">Facebook Link</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="twitter" class="form-control"
                                                        id="twitter" placeholder="twitter Link" />
                                                    <label for="floatingInput">Twitter Link</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="instagram" class="form-control"
                                                        id="instagram" placeholder="Instagram Link" />
                                                    <label for="floatingInput">Instagram Link</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="linkedin" class="form-control"
                                                        id="linkedin" placeholder="Linkedin Link" />
                                                    <label for="floatingInput">Linkedin Link</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row my-3">
                                            <div class="col-md-2">
                                                <label for="floatingInput">Change Profile Image:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-floating">
                                                    <input type="file" name="image" id="image">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-floating my-2">
                                            <input type="email" name="email" class="form-control"
                                                id="floatingInput email" placeholder="Email Address" />
                                            <label for="floatingInput">Email Address</label>
                                        </div>
                                        <div class="form-floating my-2">
                                            <input type="password" name="password" class="form-control" id="password"
                                                placeholder="New Password" />
                                            <label for="floatingInput">Password</label>
                                        </div>
                                        <div class="form-floating my-2">
                                            <input type="password" name="password_confirmation" class="form-control"
                                                id="password_confirmation" placeholder="Password Confirmation" />
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
                                    <form method="POST" action="{{ route('superadmin.doctor.password.update') }}">
                                        @csrf
                                        <input type="hidden" name="user_id" class="form-control" id="user_id" />
                                        <div class="form-floating my-2">
                                            <input type="password" name="current_password" class="form-control"
                                                id="current_password" placeholder="Current Password" />
                                            <label for="floatingInput">Current Password</label>
                                        </div>
                                        <div class="form-floating my-2">
                                            <input type="password" name="password" class="form-control" id="password"
                                                placeholder="New Password" />
                                            <label for="floatingInput">New Password</label>
                                        </div>
                                        <div class="form-floating my-2">
                                            <input type="password" name="password_confirmation" class="form-control"
                                                id="password_confirmation" placeholder="Password Confirmation" />
                                            <label for="floatingInput">Password Confirmation</label>
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
                    var specialties = JSON.parse(button.data('specialties'));
                    var qualification = JSON.parse(button.data('qualification'));
                    var employment_date = JSON.parse(button.data('employment-date'));
                    var years_of_experience = JSON.parse(button.data('years-of-experience'));
                    var age = JSON.parse(button.data('age'));
                    var gender = JSON.parse(button.data('gender'));
                    var street = JSON.parse(button.data('street'));
                    var brgy = JSON.parse(button.data('brgy'));
                    var city = JSON.parse(button.data('city'));
                    var province = JSON.parse(button.data('province'));
                    var birthdate = JSON.parse(button.data('birthdate'));
                    var phone = JSON.parse(button.data('phone'));
                    var facebook = JSON.parse(button.data('facebook'));
                    var twitter = JSON.parse(button.data('twitter'));
                    var instagram = JSON.parse(button.data('instagram'));
                    var linkedin = JSON.parse(button.data('linkedin'));
                    var email = JSON.parse(button.data('email'));
                    var modal = $(this);

                    modal.find('#first_name').val(first_name);
                    modal.find('#last_name').val(last_name);
                    modal.find('#middle_name').val(middle_name);
                    modal.find('#specialties').val(specialties);
                    modal.find('#qualification').val(qualification);
                    modal.find('#years_of_experience').val(years_of_experience);
                    modal.find('#age').val(age);
                    modal.find('#gender').val(gender);
                    modal.find('#street').val(street);
                    modal.find('#brgy').val(brgy);
                    modal.find('#city').val(city);
                    modal.find('#province').val(province);
                    modal.find('#birthdate').val(birthdate);
                    modal.find('#phone').val(phone);
                    modal.find('#facebook').val(facebook);
                    modal.find('#twitter').val(twitter);
                    modal.find('#instagram').val(instagram);
                    modal.find('#linkedin').val(linkedin);
                    modal.find('#user_id').val(user_id);
                    modal.find('#email').val(email);
                    modal.find('#employment_date').val(employment_date);
                });

                $('#viewModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var first_name = JSON.parse(button.data('first-name'));
                    var last_name = JSON.parse(button.data('last-name'));
                    var middle_name = JSON.parse(button.data('middle-name'));
                    var specialties = JSON.parse(button.data('specialties'));
                    var qualification = JSON.parse(button.data('qualification'));
                    var years_of_experience = JSON.parse(button.data('years-of-experience'));
                    var age = JSON.parse(button.data('age'));
                    var gender = JSON.parse(button.data('gender'));
                    var street = JSON.parse(button.data('street'));
                    var brgy = JSON.parse(button.data('brgy'));
                    var city = JSON.parse(button.data('city'));
                    var province = JSON.parse(button.data('province'));
                    var birthdate = JSON.parse(button.data('birthdate'));
                    var phone = JSON.parse(button.data('phone'));
                    var facebook = JSON.parse(button.data('facebook'));
                    var twitter = JSON.parse(button.data('twitter'));
                    var instagram = JSON.parse(button.data('instagram'));
                    var linkedin = JSON.parse(button.data('linkedin'));
                    var email = JSON.parse(button.data('email'));
                    var modal = $(this);

                    modal.find('#first_name').val(first_name);
                    modal.find('#last_name').val(last_name);
                    modal.find('#middle_name').val(middle_name);
                    modal.find('#specialties').val(specialties);
                    modal.find('#qualification').val(qualification);
                    modal.find('#years_of_experience').val(years_of_experience);
                    modal.find('#age').val(age);
                    modal.find('#gender').val(gender);
                    modal.find('#street').val(street);
                    modal.find('#brgy').val(brgy);
                    modal.find('#city').val(city);
                    modal.find('#province').val(province);
                    modal.find('#birthdate').val(birthdate);
                    modal.find('#phone').val(phone);
                    modal.find('#facebook').val(facebook);
                    modal.find('#twitter').val(twitter);
                    modal.find('#instagram').val(instagram);
                    modal.find('#linkedin').val(linkedin);
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
