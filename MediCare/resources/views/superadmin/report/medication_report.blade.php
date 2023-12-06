@extends('layouts.analytics_report')

@section('style')
<style>
    @media print {
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
        size: a4;
    }

    .page-break {
        page-break-after: always;
    }

    #medicationDemographicsChartContainer {
        text-align: center;
    }

    #medicationDemographicsChart {
        max-width: 100%;
        display: inline-block;
    }
</style>
@endsection

@section('content')

<div class="container mt-2">
    <div class="row justify-content-first align-items-first my-3">
        <div class="col-7 my-4">
            <h8>Report Type: <i><b>Medication Analytics Report</b></i></h8>
            <br>
            <h8>Date: <i><b>{{ date('M j, Y', strtotime($currentDate)) }}</b></i></h8>
            <br>
            <h8>Time: <i><b>{{ $currentTime }}</b></i></h8>
            <br>
            <h8>Reference: <i><b>{{ $reference }}</b></i></h8>
        </div>
        <div class="col-2"></div>
        <div class="col-1 my-3">
            <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
        </div>
    </div>
    <hr style="border-top: 1px solid #000;">
    <div class="row justify-content-center">
        <div class="col-8 text-center">
            <h3><i>{{ ucwords($specificMedication) }} Line Graph</i></h3>
            <div class="row mb-5 p-3 mx-auto">
                <canvas id="medicationDemographicsChart" style="width: 100%; height: 40;"></canvas>
            </div>
        </div>
        <div class="col-1"></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-1"></div>
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
        <div class="col-1"></div>
    </div>

    <div class="row justify-content-end align-items-end my-5">
        <div class="col-10 text-right">
        <form action="{{ route('superadmin.medication.report.save') }}" method="POST">
            @csrf
            <input type="hidden" name="reference" value="{{ $reference }}">
            <input type="hidden" name="date" value="{{ $currentDate }}">
            <input type="hidden" name="time" value="{{ $currentTime }}">
            <button id="printButton" type="button" class="btn btn-primary">Preview Report</button>
            <button id="done" type="submit" class="btn btn-success">Done</button>
            <a id="back" href="{{ route('superadmin.medication.demo') }}" class="btn btn-danger">Back</a>
            </form>
        </div>
        <div class="col-2"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                            text: 'Medication Count'
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
