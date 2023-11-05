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
    <div class="container">
        <div class="row justify-content-first align-items-first">
            <div class="col-7">
                <h5>Report Type: <i><b>Gender Analytics Report</b></i></h5>
                <h5>Year: <i><b>{{ $year }}</b></i></h5>
                <h5>Date: <i><b>{{ $currentDate }}</b></i></h5>
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
            <h3><i>Gender Bar Graph</i></h3>
            <br>
        </div>
        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-7 text-center">

                <canvas id="genderDemographicsChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div style="height: 150px"></div>

        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-9 text-center">
                <h3><i>Gender Table</i></h3>
                <br>
                <table class="table table table-bordered table-sm text-center">
                    <thead class="bg-primary text-light text-center">
                        <tr>
                            <th>Month</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @php
                            $totalMaleCount = 0;
                            $totalFemaleCount = 0;
                        @endphp
                
                        @foreach ($genderCountsByMonth as $data)
                            <tr>
                                <td>{{ $data['month'] }}</td>
                                <td>{{ $data['male'] }}</td>
                                <td>{{ $data['female'] }}</td>
                                <td>{{ $data['male'] + $data['female'] }}</td>
                            </tr>
                            @php
                                $totalMaleCount += $data['male'];
                                $totalFemaleCount += $data['female'];
                            @endphp
                        @endforeach
                
                        <tr>
                            <td>Total</td>
                            <td>{{ $totalMaleCount }}</td>
                            <td>{{ $totalFemaleCount }}</td>
                            <td>{{ $totalMaleCount + $totalFemaleCount }}</td>
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
                <a id="back" href="{{ route('superadmin.analytics.patient.gender') }}" class="btn btn-danger">Back</a>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
       // Prepare data for the bar graph
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
                            beginAtZero: true,
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
                                text: 'Gender Count'
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


