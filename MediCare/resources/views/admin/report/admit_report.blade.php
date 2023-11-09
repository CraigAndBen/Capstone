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
                <h5>Report Type: <i><b>{{$title}}</b></i></h5>
                <h5>Year: <i><b>{{ $year }}</b></i></h5>
                <h5>Date: <i><b>{{ date('F j, Y', strtotime($currentDate))}}</b></i></h5>
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
            <h3><i>Admitted Bar Graph</i></h3>
            <br>
        </div>
        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-7 text-center">

                <canvas id="admitPatientDemographicsChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div style="height: 150px"></div>

        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-8 text-center">
                <h3><i>Admitted Table</i></h3>
                <br>
                <table class="table table-bordered table-sm text-center">
                    <thead class="bg-primary text-light text-center">
                        <tr>
                            <th>Month</th>
                            <th>Admitted Patients</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($admitPatientCountsByMonth as $data)
                            <tr>
                                <td>{{ $data['month'] }}</td>  
                                <td>{{ $data['count'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>Total</td>
                            <td>{{ $totalAdmittedPatients }}</td>
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
                <a id="back" href="{{ route('admin.analytics.admitted') }}" class="btn btn-danger">Back</a>
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
                            text: 'Admitted'
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
