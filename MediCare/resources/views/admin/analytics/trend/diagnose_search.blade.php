@extends('layouts.inner_admin')

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
                                <h5 class="m-b-10">Diagnose Trend Trend</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
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
                                                        <h5 class="mb-0">{{ ucwords($diagnosis['diagnose']) }}</h5>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h5 class="mb-0">
                                                            {{ ucwordS($diagnosis['total_occurrences']) }}<span
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
                                    <form action="{{ route('admin.analytics.trend.diagnose.search') }}" method="GET">
                                        @csrf
                                        <select class="form-control p-3" id="diagnose" name="diagnose">
                                            <option>Select Diagnose</option>
                                            @foreach ($rankedDiagnosis as $diagnose)
                                                @if ($diagnose['diagnose'] == $specificDiagnosis)
                                                    <option value="{{ $diagnose['diagnose'] }}" selected>
                                                        {{ ucwords($diagnose['diagnose']) }}</option>
                                                @else
                                                    <option value="{{ $diagnose['diagnose'] }}">
                                                        {{ ucwords($diagnose['diagnose']) }}
                                                    </option>
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
                                <form action="{{ route('admin.diagnose.trend.report') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="diagnose" id="diagnose" value="{{ $specificDiagnosis }}">
                                    <button type="submit" class="btn btn-success">Generate Report</button>
                                </form>
                            </div>
                            <!-- Create the bar graph for yearly trend -->
                            <div class="my-3">
                                <h3>Yearly Trend - <i>{{ ucwords($specificDiagnosis) }}</i></h3>
                            </div>
                            <hr>
                            <canvas id="yearlyTrendChart" width="100%" height="35"></canvas>
                        </div>
                        <hr>
                        <div class="row p-3">
                            <!-- Create the bar graph for yearly trend -->
                            <div class="my-3">
                                <h3>Monthly Trend - <i>{{ ucwords($specificDiagnosis) }}</i></h3>
                            </div>
                            <hr>
                            <canvas id="monthlyTrendChart" width="100%" height="35"></canvas>
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
        // Get the data passed from the controller
        var years = @json($years);
        var admittedCounts = @json($admittedYearCounts);
        var outpatientCounts = @json($outpatientYearCounts);

        // Create a chart using Chart.js
        var ctx = document.getElementById('yearlyTrendChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line', // Use a line chart for yearly trend
            data: {
                labels: years,
                datasets: [{
                        label: 'Admitted',
                        data: admittedCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false // Ensure the line chart is not filled
                    },
                    {
                        label: 'Outpatient',
                        data: outpatientCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        fill: false // Ensure the line chart is not filled
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        // Get the data passed from the controller
    var months = @json($months);
    var admittedCounts = @json($admittedMonthCounts);
    var outpatientCounts = @json($outpatientMonthCounts);

    // Create a chart using Chart.js
    var ctx = document.getElementById('monthlyTrendChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line', // Change chart type to line
        data: {
            labels: months,
            datasets: [{
                label: 'Admitted',
                data: admittedCounts,
                borderColor: 'rgba(75, 192, 192, 1)', // Remove backgroundColor
                borderWidth: 2, // Increase borderWidth for lines
                fill: false // Do not fill the area under the line
            },
            {
                label: 'Outpatient',
                data: outpatientCounts,
                borderColor: 'rgba(255, 99, 132, 1)', // Remove backgroundColor
                borderWidth: 2, // Increase borderWidth for lines
                fill: false // Do not fill the area under the line
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
@endsection
