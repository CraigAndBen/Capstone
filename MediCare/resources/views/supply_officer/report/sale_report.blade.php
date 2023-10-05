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
                <h5>Report Type: <i><b>Sale Analytics Report</b></i></h5>
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
                <h3><i>Inventory Bar Graph</i></h3>
                <br>
                <canvas id="salesGraph"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div style="height: 150px"></div>

        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3><i>Sale Table</i></h3>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
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

        // Create an array to store datasets
        var datasets = [];

        // Create a dataset for each product
        for (var productName in salesData) {
            datasets.push({
                label: productName,
                data: salesData[productName],
                borderColor: getRandomColor(),
                borderWidth: 2,
                fill: false
            });
        }

        // Create a chart using Chart.js
        var ctx = document.getElementById('salesGraph').getContext('2d');
        var salesGraph = new Chart(ctx, {
            type: 'bar',
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

        // Function to generate random colors for chart lines
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        $(document).ready(function() {
            // Attach a click event handler to the button
            $("#printButton").click(function() {
                // Call the window.print() function to open the print dialog
                window.print();
            });
        });
    </script>
@endsection
