@extends('layouts.inner_home')

@section('content')

    <section class="breadcrumbs">
        <div class="container" style="margin-top: 88px">

            <div class="d-flex justify-content-between align-items-center">
                <h2><b>Profile</b></h2>
                <ol>
                    <li><a href="user/dashboard">Home</a></li>
                    <li>Profile</li>
                </ol>
            </div>

        </div>
    </section><!-- End Breadcrumbs Section -->

    <section class="inner-page">

        {{-- profile update --}}
        <div class="container">
            <div class="auth-form">
                <div class="card my-3 shadow">
                    <div class="card-body">
                        <div class="row p-3">
                            <div class="d-flex justify-content-center">
                                <div class="auth-header text-center my-5">
                                    <h2 class="text-primary mt-5"><b>Profile Information</b></h2>
                                    <p class="f-16 mt-2">Update your account's profile information and email address.</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('user.profile.update') }}">
                                @csrf

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
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3 ">
                                            <input type="text" class="form-control ml-2" id="first_name"
                                                placeholder="First Name" name="first_name"
                                                value="{{ $user->first_name }}" />
                                            <label for="floatingInput">First Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3 ">
                                            <input type="text" class="form-control ml-2" id="middle_name"
                                                placeholder="Middle Name" name="middle_name"
                                                value="{{ $user->middle_name }}" />
                                            <label for="floatingInput">Middle Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3 ">
                                            <input type="text" class="form-control" id="last_name"
                                                placeholder="Last Name" name="last_name" value="{{ $user->last_name }}" />
                                            <label for="floatingInput">Last Name</label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 ">
                                            <input type="text" class="form-control" id="floatingInput street"
                                                placeholder="{{ $user_info->street }}" value="{{ $user_info->street }}"
                                                name="street" value="{{ $user->street }}" />
                                            <label for="floatingInput">Street</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 ">
                                            <input type="text" class="form-control" id="floatingInput brgy"
                                                placeholder="{{ $user_info->brgy }}" value="{{ $user_info->brgy }}"
                                                name="brgy" value="{{ $user->brgy }}" />
                                            <label for="floatingInput">Brgy</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 ">
                                            <input type="text" class="form-control" id="floatingInput city"
                                                placeholder="{{ $user_info->city }}" value="{{ $user_info->city }}"
                                                name="city" value="{{ $user->city }}" />
                                            <label for="floatingInput">City</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 ">
                                            <input type="text" class="form-control" id="floatingInput province"
                                                placeholder="{{ $user_info->province }}"
                                                value="{{ $user_info->province }}" name="province"
                                                value="{{ $user->province }}" />
                                            <label for="floatingInput">Province</label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 ">
                                            <select class="form-control  p-3" id="gender" name="gender">
                                                <option value="" {{ $user_info->gender == '' ? 'selected' : '' }}>Select Gender</option>
                                                <option value="male" {{ $user_info->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ $user_info->gender == 'female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 ">
                                            <input type="text" class="form-control" id="floatingInput phone"
                                                placeholder="{{ $user_info->phone }}" value="{{ $user_info->phone }}"
                                                name="phone" oninput="formatPhoneNumber(this);" />
                                            <label for="floatingInput">Phone</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 ">
                                            <input type="number" class="form-control" id="floatingInput age"
                                                placeholder="{{ $user_info->age }}" value="{{ $user_info->age }}"
                                                name="age" />
                                            <label for="floatingInput">Age</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 ">
                                            <input type="date" class="form-control" id="floatingInput birthdate"
                                                name="birthdate" placeholder="Date of Birth"
                                                value="{{ $user_info->birthdate }}" />
                                            <label for="floatingInput">Date of Birth</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3 ">
                                            <input type="text" class="form-control" id="floatingInput occupation"
                                                placeholder="{{ $user_info->occupation }}"
                                                value="{{ $user_info->occupation }}" name="occupation" />
                                            <label for="floatingInput">Occupation</label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3 ">
                                            <input type="email" class="form-control" id="floatingInput email"
                                                placeholder="{{ $user->email }}" value="{{ $user->email }}"
                                                name="email" />
                                            <label for="floatingInput">Email</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end mt-5 mb-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

    </section>
    <script>
        function formatPhoneNumber(input) {
            // Remove any non-numeric characters
            input.value = input.value.replace(/[^0-9+]/g, '');

            // Check if the input starts with "09" and change it to "+639"
            if (input.value.startsWith('09')) {
                input.value = '+639' + input.value.substring(2);
            }
        }
    </script>
@endsection
