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
            size: portrait;
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
                <h5>Report Type: <i><b>Medication Analytics Report</b></i></h5>
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
            <div class="col-8 text-center">
                <h3><i>{{ ucwords($specificMedication) }} Line Graph</i></h3>
                <div class="row mb-5 p-3 mx-auto">
                    <canvas id="medicationDemographicsChart" style="width: 300px; height: 300px;"></canvas>
                </div>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-8 text-center">
                <h3><i>{{ ucwords($specificMedication) }} Table</i></h3>
                <br>
                <table class="table table-bordered table-sm text-center">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>{{ ucwords($specificMedication) }} Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalMedication = 0;
                        @endphp
                        @foreach ($medicationCountsByMonth as $medicationCount)
                            <tr>
                                <td>{{ $medicationCount['month'] }}</td>
                                <td>{{ $medicationCount['count'] }}</td>
                                @php
                                    $totalMedication += $medicationCount['count'];
                                @endphp
                            </tr>
                        @endforeach
                        <tr>
                            <td>Total</td>
                            <td>{{ $totalMedication }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-1">

            </div>
        </div>

    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('scripts')
    <script>
        // Prepare data for the line graph
        var months = {!! json_encode(array_column($medicationCountsByMonth, 'month')) !!};
        var medicationCounts = {!! json_encode(array_column($medicationCountsByMonth, 'count')) !!};

        // Get the chart context and create the line graph
        var ctx = document.getElementById('medicationDemographicsChart').getContext('2d');
        var medicationDemographicsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: {!! json_encode(ucwords($specificMedication)) !!},
                    data: medicationCounts,
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
