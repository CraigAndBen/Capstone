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

        #productChartContainer {
        text-align: center;
    }

    #productChart {
        max-width: 100%; /* Make the chart responsive */
        display: inline-block;
       
    }
       
    </style>
@endsection
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-first align-items-first my-3">
            <div class="col-7 my-4">
                <h8>Report Type: <i><b>
                            @if ($chartTitle === 'category')
                                Category Analytics Report
                            @elseif ($chartTitle === 'brand')
                                Brand Analytics Report
                            @endif
                        </b></i></h8>
                <br>
                <h8>Date: <i><b>{{ date('M j, Y', strtotime($currentDateTime)) }}</b></i></h8>
                <br>
                <h8>Time: <i><b>{{ $currentTime }}</b></i></h8>
                <br>
                <h8>Reference: <i><b>{{ $reference }}</b></i></h8>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>
        </div>
        <hr style="border-top: 1px solid #000;">
        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3><i>
                        @if ($chartTitle === 'category')
                            Category Analytics
                        @elseif ($chartTitle === 'brand')
                            Brand Analytics
                        @endif
                    </i></h3>
                <br>
                <canvas id="productChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div style="height: 100px"></div>

        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3><i>
                    @if ($chartTitle === 'category')
                            Category Data
                        @elseif ($chartTitle === 'brand')
                            Brand Data
                        @endif
                </i></h3>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>@if ($chartTitle === 'category')
                                Category 
                            @elseif ($chartTitle === 'brand')
                                Brand 
                            @endif</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0; // Initialize total count
                        @endphp

                        @foreach ($chartData as $item)
                            <tr>
                                <td>{{ $item['label'] }}</td>
                                <td>{{ $item['count'] }}</td>
                            </tr>
                            @php
                                $total += $item['count']; // Add to the total count
                            @endphp
                        @endforeach

                        <!-- Total row -->
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $total }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-1">

            </div>
        </div>
        <div class="row justify-content-end align-items-end my-5">
            <div class="col-10 text-right">
                <form action="{{ route('supply_officer.inventory.report.save') }}" method="POST">
                    @csrf
                    <input type="hidden" name="reference" value="{{ $reference }}">
                    <input type="hidden" name="date" value="{{ $currentDateTime }}">
                    <input type="hidden" name="time" value="{{ $currentTime }}">
             
                    <button id="printButton" type="button" class="btn btn-primary">Preview Report</button>
                    <button id="done" type="submit" class="btn btn-success">Done</button>
                    <a id="back" href="{{ route('supply_officer.inventory.demo') }}" class="btn btn-danger">Back</a>
                </form>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    @if (isset($chartData))
        <script>
             var ctx = document.getElementById('productChart').getContext('2d');
        var productData = @json($chartData);
    
        // Define an array to store labels with both name and number
        var labelsWithNamesAndNumbers = productData.map(data => `${data.label} (${data.count})`);
    
        // Extract original labels without counts
        var originalLabels = productData.map(data => data.label);
    
        // Dynamically generate an array of colors based on the number of data points
        var colors = generateColors(productData.length);
    
        function generateColors(numColors) {
            var colorsArray = [];
            for (var i = 0; i < numColors; i++) {
                // You can use any method to generate colors dynamically, e.g., random colors
                var randomColor = 'rgba(' +
                    Math.floor(Math.random() * 256) + ',' +
                    Math.floor(Math.random() * 256) + ',' +
                    Math.floor(Math.random() * 256) + ')';
                colorsArray.push(randomColor);
            }
            return colorsArray;
        }
    
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsWithNamesAndNumbers, // Use labels with both name and number
                datasets: [{
                    label: 'Data',
                    data: productData.map(data => data.count),
                    backgroundColor: colors, // Use the dynamically generated colors array
                    borderColor: colors.map(color => color.replace('0.2', '1')), // Set border color with full opacity
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            generateLabels: function (chart) {
                                return originalLabels.map(function (label, i) {
                                    return {
                                        text: label,
                                        fillStyle: chart.data.datasets[0].backgroundColor[i]
                                    };
                                });
                            }
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
    @endif
@endsection
