@extends('layouts.inner_cashier')

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
                                <h5 class="m-b-10">Cashier Account</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Cashier Account</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <section class="inner-page ">

                {{-- profile update --}}
                <div class="container mt-5">
                    <div class="auth-form">
                        <div class="card my-3 shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex justify-content-center">
                                        <div class="auth-header text-center">
                                            <h2 class="text-primary mt-5"><b>Update Password</b></h2>
                                            <p class="f-16 mt-2">Ensure your account is using a long, random password to
                                                stay secure.</p>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('supply_officer.password.update') }}">
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

                                        <div class="row mt-2">
                                            <div class="col-md-6 offset-md-3">
                                                <div class="form-floating mb-3 ">
                                                    <input type="password" class="form-control ml-2"
                                                        id="floatingInput current_password" name="current_password"
                                                        placeholder="Current Password" />
                                                    <label for="floatingInput">Current Password</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6 offset-md-3">
                                                <div class="form-floating mb-3 ">
                                                    <input type="password" class="form-control ml-2"
                                                        id="floatingInput password" placeholder="New Password"
                                                        name="password" />
                                                    <label for="floatingInput">New Password</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6 offset-md-3">
                                                <div class="form-floating mb-3 ">
                                                    <input type="password" class="form-control ml-2"
                                                        id="floatingInput password_confirmation" placeholder="New Password"
                                                        name="password_confirmation" />
                                                    <label for="floatingInput">Confirm Password</label>
                                                </div>
                                            </div>
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
