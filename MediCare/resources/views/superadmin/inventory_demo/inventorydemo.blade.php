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
                                <h5 class="m-b-10">Inventory Demographics</h5> 
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Inventory Demographics</li>
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
                            <h2>Inventory Demographics</h2>
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
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-4">
                                    <form action="{{ route('superadmin.inventory.demo.search') }}" method="GET">
                                        @csrf
                                        <select class="form-control p-3" name="select" id="select">
                                            <option value="">Select</option>
                                            <option value="Category">Category</option>
                                            <option value="Brand">Brand</option>
                                        </select>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <button type="submit" class="btn btn-primary">Select</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <hr>
                        <div class="container">
                            <div class="alert alert-success">
                                Select Brand or Category.
                            </div>
                        </div>
                    </div>

                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>
    @endsection
