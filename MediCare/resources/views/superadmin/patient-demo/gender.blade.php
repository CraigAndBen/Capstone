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
                                <h5 class="m-b-10">Gender Demographics</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Gender Demographics</li>
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
                            <h1>Gender Demographics</h1>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-8">
                                    <form action="{{route('superadmin.demographics.gender.search')}}" method="POST">
                                        @csrf
                                    <select class="form-control p-3" id="gender" name="gender">
                                        <option>Select Year</option>
                                        @foreach ($admittedYears as $year)
                                            @if ($year == $currentYear)
                                            <option value="{{$year}}" selected>{{$year}}</option>
                                            @else
                                            <option value="{{$year}}">{{$year}}</option>
                                            @endif
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <button type="submit" class="btn btn-primary">Select</button>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <canvas id="genderDemographicsChart"></canvas>
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
        // Prepare data for the bar graph
        var months = {!! json_encode(array_column($genderCountsByMonth, 'month')) !!};
        var maleData = {!! json_encode(array_column($genderCountsByMonth, 'male')) !!};
        var femaleData = {!! json_encode(array_column($genderCountsByMonth, 'female')) !!};

        // Get the chart context and create the bar graph
        var ctx = document.getElementById('genderDemographicsChart').getContext('2d');
        var genderDemographicsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Male',
                    data: maleData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // Blue
                    borderWidth: 1,
                }, {
                    label: 'Female',
                    data: femaleData,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)', // Red
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true, // Stack the bars on the x-axis for each month
                    },
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    </script>
    @endsection
