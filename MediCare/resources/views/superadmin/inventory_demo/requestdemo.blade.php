@extends('layouts.inner_superadmin')

@section('content')
    <!-- [ Main Content ] start -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <div class="pc-container pb-3">
        <div class="pc-content ">
            <!-- [ breadcrumb ] start -->
            <div class="page-header mt-4">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                Request </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Request Demographics</li>
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
                            <h2>Request Demographics</h2>
                        </div>
                        <div class="card-body">
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
                            <div class="row justify-content-center">
                                <div class="col-md-1"></div>
                                <div class="col-md-3">
                                    <form action="{{ route('superadmin.request.demo.search') }}" method="GET">
                                        @csrf
                                        <div class="form-group">
                                            <label for="from">From</label>
                                            <input type="date" class="form-control" name="start" id="from">
                                        </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="to">To</label>
                                        <input type="date" class="form-control" name="end" id="to">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="select">Most request</label>
                                        <select class="form-control" name="select" id="select">
                                            <option value="">Select</option>
                                            <option value="Item">Item</option>
                                            <option value="Department">Department</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Select</button>
                                    </div>
                                </div>
                                </form>


                            </div>
                            <hr>
                            <div class="container">
                                <div class="alert alert-success">
                                    Select Date Range and Most Request.
                                </div>
                            </div>
                        </div>

                        <!-- [ sample-page ] end -->
                    </div>
                    <!-- [ Main Content ] end -->
                </div>
            </div>
        @endsection
