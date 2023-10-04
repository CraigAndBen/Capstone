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
                <h5>Report Type: <i><b>Age Analytics Report</b></i></h5>
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

        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3><i>Age Bar Graph</i></h3>
                <br>
                <canvas id="ageDemographicsChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div style="height: 150px"></div>

        <div class="row justify-content-center">
            <div class="col-1">

            </div>
            <div class="col-9 text-center">
                <h3><i>Age Table</i></h3>
                <br>
                <table class="table table table-bordered table-sm text-center">
                    <thead class="bg-primary text-light text-center">
                        <tr>
                            <th>Month</th>
                            @foreach ($labels as $ageGroup)
                                <th>{{ $ageGroup }}</th>
                            @endforeach
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($datasets as $data)
                            <tr>
                                <td>{{ $data['month'] }}</td>
                                @php
                                    $total = array_sum($data['data']);
                                @endphp
                                @foreach ($data['data'] as $count)
                                    <td>{{ $count }}</td>
                                @endforeach
                                <td>{{ $total }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>Total</td>
                            @foreach ($labels as $ageGroup)
                                @php
                                    $ageGroupTotal = 0;
                                    foreach ($datasets as $data) {
                                        $ageGroupTotal += $data['data'][$loop->index];
                                    }
                                @endphp
                                <td>{{ $ageGroupTotal }}</td>
                            @endforeach
                            <td>{{ $totalPatientCount }}</td>
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
                <a id="back" href="{{ route('superadmin.demographics.age') }}" class="btn btn-danger">Back</a>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        // Prepare data for the bar graph
        var labels = {!! json_encode($labels) !!};
        var datasets = {!! json_encode($datasets) !!};

        // Define a color palette for the bar graph
        var colors = [
            'rgba(54, 162, 235, 0.7)', // Blue
            'rgba(255, 99, 132, 0.7)', // Red
            'rgba(75, 192, 192, 0.7)', // Green
            'rgba(255, 206, 86, 0.7)', // Yellow
            'rgba(153, 102, 255, 0.7)', // Purple
            'rgba(255, 159, 64, 0.7)', // Orange
            'rgba(255, 0, 0, 0.7)', // Bright Red
            'rgba(0, 255, 0, 0.7)', // Bright Green
            'rgba(0, 0, 255, 0.7)', // Bright Blue
            'rgba(128, 128, 0, 0.7)', // Olive
            'rgba(128, 0, 128, 0.7)', // Purple
            'rgba(0, 128, 128, 0.7)', // Teal
        ];

        // Get the chart context and create the bar graph
        var ctx = document.getElementById('ageDemographicsChart').getContext('2d');
        var ageDemographicsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets.map(function(data, index) {
                    return {
                        label: data.month,
                        data: data.data,
                        backgroundColor: colors[index % colors
                            .length], // Use the predefined colors from the palette
                        borderWidth: 1,
                    };
                })
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
                            text: 'Age Count'
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
