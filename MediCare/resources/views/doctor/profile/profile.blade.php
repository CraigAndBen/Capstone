@extends('layouts.inner_doctor')

@section('content')
    <!-- [ Main Content ] start -->
    <div class="pc-container pb-3">
        <div class="pc-content ">
            <!-- [ breadcrumb ] start -->
            <div class="page-header my-4">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Doctor Account</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Doctor Account</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <section class="inner-page">

                {{-- profile update --}}
                <div class="container mt-5">
                    <div class="auth-form">
                        <div class="card my-3 shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="auth-header text-center">
                                            <h1 class="text-primary mt-5"><b>Profile Information</b></h1>
                                            <p class="f-16 mt-2">Update your account's profile information and email
                                                address.</p>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('doctor.profile.update') }}">
                                        @csrf

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <strong>Whoops!</strong> There were some problems with your input. Please
                                                fix the
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

                                        <div class="row my-2">
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control first_name"
                                                        id="first_name" placeholder="First Name" name="first_name" value="{{$profile->first_name}}"
                                                        />
                                                    <label for="floatingInput">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control middle_name"
                                                        id="middle_name" placeholder="Middle Name" name="middle_name" value="{{$profile->middle_name}}"
                                                        />
                                                    <label for="floatingInput">Middle Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="phone" class="form-control" id="last_name" value="{{$profile->last_name}}"
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
                                                        id="street" placeholder="Street" value="{{$doctor->street}}" />
                                                    <label for="floatingInput">Street</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="brgy" class="form-control"
                                                        id="brgy" placeholder="Brgy" value="{{$doctor->brgy}}" />
                                                    <label for="floatingInput">Brgy</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="city" class="form-control"
                                                        id="city" placeholder="City" value="{{$doctor->city}}" />
                                                    <label for="floatingInput">City</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" name="province" class="form-control"
                                                        id="province" placeholder="Province" value="{{$doctor->province}}"/>
                                                    <label for="floatingInput">Province</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-floating my-2">
                                            <input type="text" name="specialties" class="form-control"
                                                id="specialties" placeholder="Specialties" value="{{$doctor->specialties}}" />
                                            <label for="floatingInput">Specialties</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating my-2">
                                                    <input type="number" class="form-control ml-2" id="age"
                                                        placeholder="Age" name="age"  value="{{$doctor->age}}"/>
                                                    <label for="floatingInput">Age</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control p-3" id="gender" name="gender">
                                                    <option>Select a Gender</option>
                                                    @foreach($genders as $value => $text)
                                                        <option value="{{ $value }}" {{ $value == $doctor->gender ? 'selected' : '' }}>{{ $text }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-floating my-2">
                                            <input type="text" name="qualification" class="form-control"
                                                id="qualification" placeholder="Qualifications"  value="{{$doctor->qualification}}"/>
                                            <label for="floatingInput">Qualifications</label>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 ">
                                                    <input type="date" class="form-control ml-2"
                                                        id="employment_date" placeholder="Employment Date" name="employment_date"  value="{{$doctor->employment_date}}"/>
                                                    <label for="floatingInput">Employment Date</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" id="years_of_experience"
                                                        placeholder="Years of Experience" name="years_of_experience"  value="{{$doctor->years_of_experience}}"/>
                                                    <label for="floatingInput">Years of Experience</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control ml-2" id="birthdate"
                                                        placeholder="Birthdate" name="birthdate"  value="{{$doctor->birthdate}}"/>
                                                    <label for="floatingInput">Birthdate</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" id="phone"
                                                        placeholder="Phone" name="phone"  value="{{$doctor->phone}}"/>
                                                    <label for="floatingInput">Phone</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-floating my-2">
                                            <input type="email" name="email" class="form-control" id="email"
                                                placeholder="Email"  value="{{$profile->email}}"/>
                                            <label for="floatingInput">Email</label>
                                        </div>
                                        <div class="text-center mt-4 mb-3">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

            </section>

            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
    </div>
@endsection
