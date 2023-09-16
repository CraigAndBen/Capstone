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
                <h5>Report Type: <i><b>Admitted Analytics Report</b></i></h5>
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
        <div class="row">
            <div class="col-7">
                <canvas id="admitPatientDemographicsChart"></canvas>
            </div>
            <div class="col-1">

            </div>
            <div class="col-3 text-center">
                <table class="table table-bordered table-sm">
                    <thead class="bg-primary text-light text-center">
                        <tr>
                            <th>Month</th>
                            <th>Count</th>
                            <th>total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admitPatientCountsByMonth as $data)
                            <tr>
                                @if ($data['count'] > 0)
                                <td>{{ $data['month'] }}</td>
                                <td>{{ $data['count'] }}</td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ $totalAdmittedPatients}}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row justify-content-end align-items-end my-3">
            <div class="col-10 text-right">
                <button id="printButton" class="btn btn-primary">Preview Report</button>
                <a id="back" href="{{ route('superadmin.demographics.admitted') }}" class="btn btn-danger">Back</a>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
       // Prepare data for the bar graph
       var months = {!! json_encode(array_column($admitPatientCountsByMonth, 'month')) !!};
            var admitPatientCounts = {!! json_encode(array_column($admitPatientCountsByMonth, 'count')) !!};

            // Get the chart context and create the bar graph
            var ctx = document.getElementById('admitPatientDemographicsChart').getContext('2d');
            var admitPatientDemographicsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Admit Patients',
                        data: admitPatientCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)', // Blue
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
