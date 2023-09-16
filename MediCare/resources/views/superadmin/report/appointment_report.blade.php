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
                <h5>Report Type: <i><b>Appointment Analytics Report</b></i></h5>
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
                <canvas id="appointmentChart"></canvas>
            </div>
            <div class="col-2">
            </div>
        </div>
        <div class="row justify-content-end align-items-end my-3">
            <div class="col-10 text-right">
                <button id="printButton" class="btn btn-primary">Preview Report</button>
                <a id="back" href="{{ route('superadmin.demographics.appointment') }}" class="btn btn-danger">Back</a>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        var ctx = document.getElementById('appointmentChart').getContext('2d');
        var labels = @json($appointmentLabels);
        var data = @json($appointmentData);

        // Format the labels to display only the month names
        labels = labels.map(function(dateString) {
            var date = new Date(dateString);
            var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December'
            ];
            return monthNames[date.getMonth()];
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Appointment Count',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'category', // Use 'category' for category labels
                        labels: labels, // Provide the labels
                        beginAtZero: true,
                        min: labels[0], // Specify the minimum label
                        max: labels[labels.length - 1], // Specify the maximum label
                    },
                    y: {
                        beginAtZero: true
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
