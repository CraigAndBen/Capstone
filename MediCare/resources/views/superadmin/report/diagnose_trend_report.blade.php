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
                    /* Define a page break after the content on the first page */

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
                <h5>Report Type: <i><b>Yearly Trend Report</b></i></h5>
                <h5>Year: <i><b>{{ $year }}</b></i></h5>
                <h5>Diagnose: <i><b>{{ ucwords($specificDiagnosis) }}</b></i></h5>
                <h5>Date: <i><b>{{ $currentDate }}</b></i></h5>
                <h5>Time: <i><b>{{ $currentTime }}</b></i></h5>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>

        </div>
        <div class="row justify-content-first">
            <div class="col">

            </div>
        </div>
        <div class="row justify-content-center align-items-center">
            <div class="col-10">
                <canvas id="yearlyTrendChart"></canvas>
            </div>
            <div class="col-2">
            </div>
        </div>
    </div>
      <!-- Page break -->
      <div class="page-break"></div>

      <div class="container mt-2">
        <div class="row justify-content-first align-items-first my-3">
            <div class="col-7 my-4">
                <h5>Report Type: <i><b>Monthly Trend Report</b></i></h5>
                <h5>Year: <i><b>{{ $year }}</b></i></h5>
                <h5>Diagnose: <i><b>{{ ucwords($specificDiagnosis) }}</b></i></h5>
                <h5>Date: <i><b>{{ $currentDate }}</b></i></h5>
                <h5>Time: <i><b>{{ $currentTime }}</b></i></h5>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>

        </div>
        <div class="row justify-content-first">
            <div class="col">

            </div>
        </div>
        <div class="row justify-content-center align-items-center">
            <div class="col-10">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
            <div class="col-2">
            </div>
        </div>
        <div class="row justify-content-end align-items-end my-3">
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
        $(document).ready(function() {
            // Attach a click event handler to the button
            $("#printButton").click(function() {
                // Call the window.print() function to open the print dialog
                window.print();
            });
        });
    </script>
@endsection
