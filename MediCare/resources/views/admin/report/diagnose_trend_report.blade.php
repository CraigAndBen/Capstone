@extends('layouts.analytics_report')
@section('style')
    <style>
        @media print {

            /* Hide the button when printing */
            #printButton {
                display: none;
            }

            #back {
                display: none;
            }
        }

        @page {
            size: landscape;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
@endsection
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-first align-items-first my-3">
            <div class="col-7 my-4">
                <h5>Report Type: <i><b>Yearly Trend Analytics Report</b></i></h5>
                <h5>Year: <i><b>{{ $year }}</b></i></h5>
                <h5>Date: <i><b>{{ $currentDate }}</b></i></h5>
                <h5>Time: <i><b>{{ $currentTime }}</b></i></h5>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>

        </div>
        <div style="height: 80px"></div>
        <div class="row justify-content-center">
            <div class="col-7">
                <canvas id="yearlyTrendChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div class="page-break my-5"></div>
        <div style="height: 100px"></div>

        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-9">
                <table class="table table-bordered table-sm text-center">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>{{$specificDiagnosis}} Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalCount = 0;
                        @endphp
                        @foreach ($yearlyTrendData as $trend)
                            <tr>
                                <td>{{ $trend['year'] }}</td>
                                <td>{{ $trend['count'] }}</td>
                            </tr>
                            @php
                                $totalCount += $trend['count'];
                            @endphp
                        @endforeach
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $totalCount }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-1">

            </div>
        </div>
    </div>

    <div class="page-break my-5"></div>

    <div class="container mt-2">
        <div class="row justify-content-first align-items-first my-3">
            <div class="col-7 my-4">
                <h5>Report Type: <i><b>Monthly Trend Analytics Report</b></i></h5>
                <h5>Year: <i><b>{{ $year }}</b></i></h5>
                <h5>Date: <i><b>{{ $currentDate }}</b></i></h5>
                <h5>Time: <i><b>{{ $currentTime }}</b></i></h5>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class=""
                    style="max-width: 200px; max-height: 160px">
            </div>

        </div>
        <div style="height: 80px"></div>
        <div class="row justify-content-center">
            <div class="col-7">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div class="page-break my-5"></div>
        <div style="height: 100px"></div>

        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-9">
                <table class="table table-bordered table-sm text-center">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>{{$specificDiagnosis}} Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $allMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            $totalCount = 0;
                        @endphp
                        @foreach ($allMonths as $monthName)
                            @php
                                $trendData = collect($monthlyTrendData)->firstWhere('month', $monthName);
                                $count = $trendData ? $trendData['count'] : 0;
                                $totalCount += $count;
                            @endphp
                            <tr>
                                <td>{{ $monthName }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $totalCount }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-1">

            </div>
        </div>
        <div class="row justify-content-end align-items-end my-5">
            <div class="col-10 text-right">
                <button id="printButton" class="btn btn-primary">Preview Report</button>
                <a id="back" href="{{ route('admin.trend.diagnose') }}" class="btn btn-danger">Back</a>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            // Attach a click event handler to the button
            $("#printButton").click(function() {
                // Call the window.print() function to open the print dialog
                window.print();
            });
        });
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
                        },
                        title: {
                            display: true,
                            text: 'Years'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        suggestedMax: Math.max(...counts) + 2, // Adjust y-axis upper limit
                        title: {
                            display: true,
                            text: 'Diagnose Count'
                        }
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
    <script>
        var ctx = document.getElementById('monthlyTrendChart').getContext('2d');

        var monthlyData = @json($monthlyTrendData);

        var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
            'November', 'December'
        ];
        var monthlyCounts = Array.from({
            length: 12
        }, (_, i) => {
            var monthData = monthlyData.find(item => item.month === months[i]);
            return monthData ? monthData.count : 0;
        });

        var monthlyTrendChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Trend',
                    data: monthlyCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // Blue
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Count'
                        }
                    }
                }
            }
        });
    </script>
@endsection
