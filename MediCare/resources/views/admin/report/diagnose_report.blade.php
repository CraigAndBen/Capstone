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
    </style>
@endsection
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-first align-items-first my-3">
            <div class="col-7 my-4">
                <h5>Report Type: <i><b>Diagnose Analytics Report</b></i></h5>
                <h5>Year: <i><b>{{ $year }}</b></i></h5>
                <h5>Diagnose: <i><b>{{ ucwords($diagnose) }}</b></i></h5>
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
                <canvas id="diagnosePatientDemographicsChart"></canvas>
            </div>
            <div class="col-2">
            </div>
        </div>
        <div class="row justify-content-end align-items-end my-3">
            <div class="col-10 text-right">
                <button id="printButton" class="btn btn-primary">Preview Report</button>
                <a id="back" href="{{ route('admin.demographics.diagnose') }}" class="btn btn-danger">Back</a>
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
                    label: 'Diagnose Patients',
                    data: diagnosePatientCounts,
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 0.7)', // Blue
                    borderWidth: 2,
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
