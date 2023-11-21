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
                <h8>Date: <i><b>{{ date('M j, Y', strtotime($currentDateTime)) }}</b></i></h8>
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
                <h3><i>Sale Analytics</i></h3>
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
                                @foreach ($filteredDates as $date)
                                    <th>{{ date('M j, Y', strtotime($date)) }}</th>
                                @endforeach
                                <th>Total</th> <!-- Add a column for the total sales -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesData as $productName => $productSales)
                                <tr>
                                    <td>{{ $productName }}</td>
                                    @php
                                        $totalSales = 0;
                                    @endphp
                                    @foreach ($filteredDates as $date)
                                        @php
                                            $salesEntry = array_values(array_filter($datesWithSales[$productName], fn($entry) => $entry['date'] === $date))[0] ?? null;
                                            $quantity = $salesEntry ? $salesEntry['quantity'] : 0;
                                            $totalSales += $quantity;
                                        @endphp
                                        <td>{{ $quantity }}</td>
                                    @endforeach
                                    <td><strong>{{ $totalSales }}</strong></td>
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

        // Define an array to store formatted dates
        var formattedDates = dateRange.map(function(dateString) {
            // Parse the date string
            var date = new Date(dateString);

            // Format the date as "MMM d, yyyy" (e.g., "Jan 1, 2023")
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        });

        // Function to generate a random light color
        function getRandomLightColor() {
            var randomColor = function() {
                return Math.floor(Math.random() * 200 + 56); // Ensure the color is in a light range
            };
            var rgb = `${randomColor()}, ${randomColor()}, ${randomColor()}`;
            return {
                backgroundColor: `rgba(${rgb}, 0.7)`,
                borderColor: `rgba(${rgb}, 0.7)`
            };
        }

        // Create an array to store datasets
        var datasets = [];

        // Create a dataset for each product
        for (var productName in salesData) {
            var randomColors = getRandomLightColor();
            datasets.push({
                label: productName,
                data: salesData[productName],
                backgroundColor: randomColors.backgroundColor, // Use a random light color for the background
                borderColor: randomColors.borderColor, // Use the same color for the border
                borderWidth: 2,
                fill: false
            });
        }

        // Create a chart using Chart.js
        var ctx = document.getElementById('salesGraph').getContext('2d');
        var salesGraph = new Chart(ctx, {
            type: 'line',
            data: {
                labels: formattedDates, // Use the formatted dates
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
