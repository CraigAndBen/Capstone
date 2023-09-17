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
                <h5>Report Type: <i><b>Admitted Patient Analytics Report</b></i></h5>
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
                <canvas id="admitPatientDemographicsChart"></canvas>
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
                <table class="table table-bordered table-sm">
                    <thead class="bg-primary text-light text-center">
                        <tr>
                            <th>Month</th>
                            <th>Appointments</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($appointmentCountsByMonth as $data)
                            <tr>
                                <td>{{ $data['month'] }}</td>
                                <td>{{ $data['count'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>Total</td>
                            <td>{{ $totalAppointment }}</td>
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
                <a id="back" href="{{ route('superadmin.demographics.appointment') }}" class="btn btn-danger">Back</a>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        // Get the canvas element
        // Prepare data for the bar graph
        var months = {!! json_encode(array_column($appointmentCountsByMonth, 'month')) !!};
        var admitPatientCounts = {!! json_encode(array_column($appointmentCountsByMonth, 'count')) !!};

        // Get the chart context and create the bar graph
        var ctx = document.getElementById('admitPatientDemographicsChart').getContext('2d');
        var admitPatientDemographicsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Appointment',
                    data: admitPatientCounts,
                    backgroundColor: 'rgba(128, 0, 128, 0.7)', // Purple
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true, // Stack the bars on the x-axis for each month
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Appointment Count'
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
