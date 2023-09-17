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
                                <h5 class="m-b-10">Diagnose Trend</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Diagnose Trend</li>
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
                            <h1>Diagnose Trend</h1>
                        </div>
                        <div class="card-body">
                            <h3>Ranked Diagnose</h3>
                            <div class="row">
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-8">
                                    <ul class="list-group list-group-flush mt-3">
                                        @foreach ($rankedDiagnosis as $diagnosis)
        
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-start">
                                                <div class="col">
                                                    <h5 class="mb-0">{{$diagnosis->diagnosis}}</h5>
                                                </div>
                                                <div class="col-auto">
                                                    <h5 class="mb-0">{{$diagnosis->total_occurrences}}<span
                                                            class="ms-2 align-top avtar avtar-xxs bg-light-success"><i
                                                                class="ti ti-chevron-up text-success"></i></span></h5>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-2">

                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-8">
                                    <form action="{{ route('superadmin.trend.diagnose.search') }}" method="POST">
                                        @csrf
                                        <select class="form-control p-3" id="diagnose" name="diagnose">
                                            <option>Select Diagnose</option>
                                            @foreach ($rankedDiagnosis as $diagnose)
                                                <option value="{{ $diagnose->diagnosis }}">{{ $diagnose->diagnosis }}</option>
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
                                Select Diagnose and number of years to analyze first.
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
