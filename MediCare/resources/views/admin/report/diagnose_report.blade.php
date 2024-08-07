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
            <h3><i>{{$year}} {{ ucwords($specificDiagnosis) }} Line Graph</i></h3>
            <br>
        </div>
        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-7 text-center">

                <canvas id="diagnosePatientDemographicsChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div style="height: 150px"></div>

        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-8 text-center">
                <h3><i>{{$year}} {{ ucwords($specificDiagnosis) }} Table</i></h3>
                <br>
                <table class="table table-bordered table-sm text-center">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>{{ ucwords($specificDiagnosis) }} Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalDiagnosedPatients = 0;
                        @endphp
                        @foreach ($diagnosePatientCountsByMonth as $patientCount)
                            <tr>
                                <td>{{ $patientCount['month'] }}</td>
                                <td>{{ $patientCount['count'] }}</td>
                                @php
                                    $totalDiagnosedPatients += $patientCount['count'];
                                @endphp
                            </tr>
                        @endforeach
                        <tr>
                            <td>Total</td>
                            <td>{{ $totalDiagnosedPatients }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-1">

            </div>
        </div>
        <div class="row justify-content-end align-items-end my-5">
            <div class="col-10 text-right">
                <form action="{{ route('admin.diagnose.report.save') }}" method="POST">
                    @csrf
                    <input type="hidden" name="reference" value="{{ $reference }}">
                    <input type="hidden" name="date" value="{{ $currentDate }}">
                    <input type="hidden" name="time" value="{{ $currentTime }}">
                    <input type="hidden" name="title" value="{{ $title }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="type" value="gender">
                    <button id="printButton" type="button" class="btn btn-primary">Preview Report</button>
                    <button id="done" type="submit" class="btn btn-success">Done</button>
                    <a id="back" href="{{ route('admin.analytics.patient.diagnose') }}"
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
        // Prepare data for the line graph
        var months = {!! json_encode(array_column($diagnosePatientCountsByMonth, 'month')) !!};
        var diagnosePatientCounts = {!! json_encode(array_column($diagnosePatientCountsByMonth, 'count')) !!};

        // Get the chart context and create the line graph
        var ctx = document.getElementById('diagnosePatientDemographicsChart').getContext('2d');
        var diagnosePatientDemographicsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: {!! json_encode(ucwords($specificDiagnosis)) !!},
                    data: diagnosePatientCounts,
                    borderColor: 'rgba(54, 162, 235, 1)', // Blue
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Lighter blue fill
                    borderWidth: 2,
                    fill: true, // To fill the area under the line
                    pointRadius: 5, // Adjust the size of data points on the line
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Blue data points
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
                            text: 'Months'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Diagnose Count'
                        }
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
