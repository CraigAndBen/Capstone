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
                                    <form method="POST" action="{{ route('doctor.social.update') }}" enctype="multipart/form-data">
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

                                        <div class="form-floating my-2">
                                            <input type="text" name="facebook" class="form-control"
                                                id="facebook" placeholder="Facebook Link" value="{{$doctor->facebook_link}}" />
                                            <label for="floatingInput">Facebook Link</label>
                                        </div>
                                        <div class="form-floating my-2">
                                            <input type="text" name="twitter" class="form-control"
                                                id="twitter" placeholder="Twitter Link" value="{{$doctor->twitter_link}}" />
                                            <label for="floatingInput">Twitter Link</label>
                                        </div>
                                        <div class="form-floating my-2">
                                            <input type="text" name="instagram" class="form-control"
                                                id="instagram" placeholder="Instagram Link" value="{{$doctor->instagram_link}}" />
                                            <label for="floatingInput">Instagram Link</label>
                                        </div>
                                        <div class="form-floating my-2">
                                            <input type="text" name="linkedin" class="form-control"
                                                id="linkedin" placeholder="Linkedin Link" value="{{$doctor->linkedin_link}}" />
                                            <label for="floatingInput">Linkedin Link</label> 
                                        </div>
                                        <hr>
                                            <div class="col-md-6 text-start">
                                                <input type="file" name="image" id="image" value="{{ asset($doctor->image_data) }}">
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
