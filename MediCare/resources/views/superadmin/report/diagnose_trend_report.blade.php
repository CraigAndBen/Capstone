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

            #done {
                display: none;
            }
        }

        @page {
            size: legal landscape;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
@endsection
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-first align-items-first">
            <div class="col-7">
                <h5>Report Type: <i><b>{{ $title }}</b></i></h5>
                <h5>Year: <i><b>{{ $year }}</b></i></h5>
                <h5>Date: <i><b>{{ date('F j, Y', strtotime($currentDate)) }}</b></i></h5>
                <h5>Time: <i><b>{{ $currentTime }}</b></i></h5>
                <h5>Reference: <i><b>{{ $reference }}</b></i></h5>
            </div>
            <div class="col-3">

            </div>
            <div class="col-1 my-2">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>
        </div>
        <hr style="border-top: 1px solid #000;">

        <div class="row justify-content-center mt-5">
            <h3><i>{{ ucwords($specificDiagnosis) }} Yearly Trend Graph</i></h3>
            <br>
        </div>
        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-7 text-center">

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
                <h3><i>{{ ucwords($specificDiagnosis) }} Yearly Trend Table</i></h3>
                <br>
                <table class="table table-bordered table-sm text-center">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Patient</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPatient = 0;
                        @endphp

                        @foreach ($years as $key => $year)
                            <tr>
                                <td>{{ $year }}</td>
                                <td>{{ $patientYearCounts[$key] }}</td>
                            </tr>
                            @php
                                $totalPatient += $patientYearCounts[$key];
                            @endphp
                        @endforeach
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $totalPatient }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-1">

            </div>
        </div>
    </div>

    <div class="page-break my-5 p-5"></div>

    <div class="container my-5">
        <br><br><br>
        <div class="row justify-content-center mt-5">
            <h3><i>{{$year}} {{ ucwords($specificDiagnosis) }} Monthly Trend Graph</i></h3>
            <br>
        </div>
        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-7 text-center">

                <canvas id="monthlyTrendChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div class="page-break my-5"></div>
        <div style="height: 30px"></div>


        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-9 text-center">
                <h3><i>{{$year}} {{ ucwords($specificDiagnosis) }} Monthly Trend Table</i></h3>
                <br>
                <table class="table table-bordered table-sm text-center">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Patient</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPatient = 0;
                        @endphp

                        @foreach ($months as $key => $month)
                            <tr>
                                <td>{{ $month }}</td>
                                <td>{{ $patientMonthCounts[$key] }}</td>
                            </tr>
                            @php
                                $totalPatient += $patientMonthCounts[$key];
                            @endphp
                        @endforeach
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $totalPatient }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-1">

            </div>
        </div>
        <div class="row justify-content-end align-items-end my-5">
            <div class="col-10 text-right">
                <form action="{{ route('superadmin.diagnose.trend.report.save') }}" method="POST">
                    @csrf
                    <input type="hidden" name="reference" value="{{ $reference }}">
                    <input type="hidden" name="date" value="{{ $currentDate }}">
                    <input type="hidden" name="time" value="{{ $currentTime }}">
                    <input type="hidden" name="title" value="{{ $title }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="type" value="gender">
                    <button id="printButton" type="button" class="btn btn-primary">Preview Report</button>
                    <button id="done" type="submit" class="btn btn-success">Done</button>
                    <a id="back" href="{{ route('superadmin.analytics.patient.diagnose_trend') }}"
                        class="btn btn-danger">Back</a>
                </form>
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
        var patientCounts = @json($patientYearCounts);
        var type = @json($type);

        // Create a chart using Chart.js
        var ctx = document.getElementById('yearlyTrendChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line', // Use a line chart for yearly trend
            data: {
                labels: years,
                datasets: [{
                    label: 'Patient',
                    data: patientCounts,
                    borderColor: 'rgba(54, 162, 235, 1)', // Blue
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Lighter blue fill
                    borderWidth: 1,
                    fill: true, // To fill the area under the line
                    pointRadius: 5, // Adjust the size of data points on the line
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Blue data points
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                    }
                }
            }
        });

        // Get the data passed from the controller
        var months = @json($months);
        var patientCounts = @json($patientMonthCounts);

        // Create a chart using Chart.js
        var ctx = document.getElementById('monthlyTrendChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line', // Change chart type to line
            data: {
                labels: months,
                datasets: [{
                    label: 'Patient',
                    data: patientCounts,
                    borderColor: 'rgba(54, 162, 235, 1)', // Blue
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Lighter blue fill
                    borderWidth: 1,
                    fill: true, // To fill the area under the line
                    pointRadius: 5, // Adjust the size of data points on the line
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Blue data points
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                    }
                }
            }
        });
    </script>
@endsection
