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
            size: portrait;
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
                <h8>Report Type: <i><b>Sale Analytics Report</b></i></h8>
                <br>
                <h8>Date: <i><b>{{ $currentDate }}</b></i></h8>
                <br>
                <h8>Time: <i><b>{{ $currentTime }}</b></i></h8>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>

        </div>

        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3><i>Sale Bar Graph</i></h3>
                <br>
                <canvas id="salesGraph"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div style="height: 150px"></div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-10 text-center">
                <h3><i>Sale Table</i></h3>
                <br>
                <div class="table-flex">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                @foreach ($dateRange as $date)
                                    <th>{{ $date }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesData as $productName => $productSales)
                                <tr>
                                    <td>{{ $productName }}</td>
                                    @foreach ($productSales as $quantity)
                                        <td>{{ $quantity }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
            <div class="col-1">

            </div>
        </div>
        <div class="row justify-content-end align-items-end my-5">
            <div class="col-10 text-right">
                <button id="printButton" class="btn btn-primary">Preview Report</button>
                <a id="back" href="{{ route('supply_officer.sale.demo') }}" class="btn btn-danger">Back</a>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
@section('scripts')
<script>
    // Get the PHP data from the PHP variables
    var dateRange = <?php echo json_encode($dateRange); ?>;
    var salesData = <?php echo json_encode($salesData); ?>;

    // Define an array of static colors
    var staticColors = [
        'rgba(75, 192, 192, 0.7)', // Color for the first dataset
        'rgba(255, 99, 132, 0.7)', // Color for the second dataset
        'rgba(255, 205, 86, 0.7)', // Color for the third dataset
        // Add more colors as needed
    ];

    // Create an array to store datasets
    var datasets = [];

    // Create a dataset for each product
    var i = 0; // Index to select colors from staticColors array
    for (var productName in salesData) {
        datasets.push({
            label: productName,
            data: salesData[productName],
            backgroundColor: staticColors[i % staticColors.length], // Get a color from the array
            borderColor: staticColors[i % staticColors.length], // Use the same color for the border
            borderWidth: 2,
            fill: false
        });
        i++; // Increment the index to cycle through colors
    }

    // Create a chart using Chart.js
    var ctx = document.getElementById('salesGraph').getContext('2d');
    var salesGraph = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dateRange,
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
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
