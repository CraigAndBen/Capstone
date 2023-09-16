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
                            <h3>Ranked Diagnose This Year</h3>
                            <div class="row">
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-8">
                                    <ul class="list-group list-group-flush mt-3">
                                        @foreach ($limitDiagnosis as $diagnosis)
        
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
                                    <form action="{{ route('superadmin.trend.diagnose.search') }}" method="GET">
                                        @csrf
                                        <select class="form-control p-3" id="diagnose" name="diagnose">
                                            <option value="">Select Diagnose</option>
                                            @foreach ($rankedDiagnosis as $diagnose)
                                                @if ($diagnose->diagnosis == $specificDiagnosis)
                                                <option value="{{ $diagnose->diagnosis }}" selected>{{ $diagnose->diagnosis }}</option>
                                                @else
                                                <option value="{{ $diagnose->diagnosis }}">{{ $diagnose->diagnosis }}</option>
                                                @endif
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
                        <div class="row p-3">
                            <div class="col-md-10"> <!-- Adjust the column width as needed -->
                            </div>
                            <div class="col-md-2 text-right mb-3"> <!-- Adjust the column width as needed -->
                                <form action="{{ route('superadmin.diagnose.trend.report') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="diagnose" id="diagnose" value="{{ $specificDiagnosis }}">
                                    <button type="submit" class="btn btn-success">Generate Report</button>
                                </form>
                            </div>
                            <!-- Create the bar graph for yearly trend -->
                            <div class="my-3">
                                <h3>Yearly Trend - <i>{{$specificDiagnosis}}</i></h3>
                            </div>
                            <hr>
                            <canvas id="yearlyTrendChart" width="400" height="200"></canvas>
                        </div>
                        <hr>
                        <div class="row p-3">
                            <!-- Create the bar graph for yearly trend -->
                            <div class="my-3">
                                <h3>Monthly Trend - <i>{{$specificDiagnosis}}</i></h3>
                            </div>
                            <hr>
                            <canvas id="monthlyTrendChart" width="400" height="200"></canvas>
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
    // Prepare data for the line graph
    var years = {!! json_encode(array_column($yearlyTrendData, 'year')) !!};
    var counts = {!! json_encode(array_column($yearlyTrendData, 'count')) !!};

    // Get the chart context and create the line graph
    var ctx = document.getElementById('yearlyTrendChart').getContext('2d');
    var yearlyTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: years,
            datasets: [{
                label: 'Yearly Trend',
                data: counts,
                fill: true, // Fill area under the line
                borderColor: 'rgba(75, 192, 192, 1)', // Teal
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Teal with opacity
                borderWidth: 2,
                pointRadius: 5, // Increase point size for data points
                pointBackgroundColor: 'rgba(75, 192, 192, 1)', // Teal
                pointBorderColor: '#fff', // White
                pointBorderWidth: 2,
                pointHoverRadius: 7, // Increase point size on hover
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    grid: {
                        display: false,
                    }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...counts) + 2, // Adjust y-axis upper limit
                }
            },
            plugins: {
                legend: {
                    display: false, // Hide legend
                }
            }
        }
    });

    // Prepare data for the line graph
    var months = {!! json_encode(array_column($monthlyTrendData, 'month')) !!};
        var counts = {!! json_encode(array_column($monthlyTrendData, 'count')) !!};

        // Get the chart context and create the line graph
        var ctx = document.getElementById('monthlyTrendChart').getContext('2d');
        var monthlyTrendChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Trend',
                    data: counts,
                    fill: true, // Fill area under the line
                    borderColor: 'rgba(255, 99, 132, 1)', // Red
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Red with opacity
                    borderWidth: 2,
                    pointRadius: 5, // Increase point size for data points
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)', // Red
                    pointBorderColor: '#fff', // White
                    pointBorderWidth: 2,
                    pointHoverRadius: 7, // Increase point size on hover
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        suggestedMax: Math.max(...counts) + 2, // Adjust y-axis upper limit
                    }
                },
                plugins: {
                    legend: {
                        display: false, // Hide legend
                    }
                }
            }
        });
</script>
@endsection
