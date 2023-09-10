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
                <h5>Report Type: <i><b>Gender Analytics Report</b></i></h5>
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
        <div class="row justify-content-first">
            <div class="col">

            </div>
        </div>
        <div class="row justify-content-center align-items-center">
            <div class="col-10">
                <canvas id="genderDemographicsChart"></canvas>
            </div>
            <div class="col-2">
            </div>
        </div>
        <div class="row justify-content-end align-items-end my-3">
            <div class="col-10 text-right">
                <button id="printButton" class="btn btn-primary">Preview Report</button>
                <a id="back" href="{{route('admin.demographics.gender')}}" class="btn btn-danger">Back</a>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        var months = {!! json_encode(array_column($genderCountsByMonth, 'month')) !!};
        var maleData = {!! json_encode(array_column($genderCountsByMonth, 'male')) !!};
        var femaleData = {!! json_encode(array_column($genderCountsByMonth, 'female')) !!};

        // Get the chart context and create the bar graph
        var ctx = document.getElementById('genderDemographicsChart').getContext('2d');
        var genderDemographicsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Male',
                    data: maleData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // Blue
                    borderWidth: 1,
                }, {
                    label: 'Female',
                    data: femaleData,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)', // Red
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true, // Stack the bars on the x-axis for each month
                    },
                    y: {
                        beginAtZero: true,
                    }
                }
            },
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
