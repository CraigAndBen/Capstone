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
                                <h5 class="m-b-10">Diagnose Demographics</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Diagnose Demographics</li>
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
                            <h1>Diagnose Demographics</h1>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-4">
                                    <form action="{{ route('superadmin.demographics.diagnose.search') }}" method="POST">
                                        @csrf
                                        <select class="form-control p-3" id="diagnose" name="diagnose">
                                            <option>Select Diagnose</option>
                                            @foreach ($diagnoseData as $diagnose)
                                                <option value="{{ $diagnose }}">{{ $diagnose }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control p-3" id="year" name="year">
                                        <option>Select Year</option>
                                        @foreach ($admittedYears as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
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
                                Select Diagnose and Year First.
                            </div>
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

@endsection
