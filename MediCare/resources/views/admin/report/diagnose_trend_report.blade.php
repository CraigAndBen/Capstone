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
            <div class="col-8 my-4">
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

        <div class="row justify-content-center">
            <div class="col-7 text-center">
                <h3><i>{{ucwords($specificDiagnosis)}} Yearly Trend Line Graph</i></h3>
                <br>
                <canvas id="yearlyTrendChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div class="page-break my-5"></div>
        <div style="height: 80px"></div>

        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-8 text-center">
                <h3><i>{{ucwords($specificDiagnosis)}} Yearly Trend Table</i></h3>
                <br>
                <table class="table table-bordered table-sm text-center">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Admitted</th>
                            <th>Outpatient</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAdmitted = 0;
                            $totalOutpatient = 0;
                        @endphp

                        @foreach ($years as $key => $year)
                            <tr>
                                <td>{{ $year }}</td>
                                <td>{{ $admittedYearCounts[$key] }}</td>
                                <td>{{ $outpatientYearCounts[$key] }}</td>
                                <td>{{ $admittedYearCounts[$key] + $outpatientYearCounts[$key] }}</td>
                            </tr>
                            @php
                                $totalAdmitted += $admittedYearCounts[$key];
                                $totalOutpatient += $outpatientYearCounts[$key];
                            @endphp
                        @endforeach

                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $totalAdmitted }}</strong></td>
                            <td><strong>{{ $totalOutpatient }}</strong></td>
                            <td><strong>{{ $totalAdmitted + $totalOutpatient }}</strong></td>
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

        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3><i>{{ucwords($specificDiagnosis)}} Monthly Trend Table</i></h3>
                <br>
                <canvas id="monthlyTrendChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div class="page-break my-5"></div>
        <div style="height: 80px"></div>


        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-9 text-center">
                <h3><i>{{ucwords($specificDiagnosis)}} Monthly Trend Table</i></h3>
                <br>
                <table class="table table-bordered table-sm text-center">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Admitted</th>
                            <th>Outpatient</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAdmitted = 0;
                            $totalOutpatient = 0;
                        @endphp

                        @foreach ($months as $key => $month)
                            <tr>
                                <td>{{ $month }}</td>
                                <td>{{ $admittedMonthCounts[$key] }}</td>
                                <td>{{ $outpatientMonthCounts[$key] }}</td>
                                <td>{{ $admittedMonthCounts[$key] + $outpatientMonthCounts[$key] }}</td>
                            </tr>
                            @php
                                $totalAdmitted += $admittedMonthCounts[$key];
                                $totalOutpatient += $outpatientMonthCounts[$key];
                            @endphp
                        @endforeach

                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $totalAdmitted }}</strong></td>
                            <td><strong>{{ $totalOutpatient }}</strong></td>
                            <td><strong>{{ $totalAdmitted + $totalOutpatient }}</strong></td>
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
    </script>
@endsection
