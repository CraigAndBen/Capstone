
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
                <h5>Report Type: <i><b>Item (FSN) Analytics Report</b></i></h5>
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
                <h3><i>Item (FSN) Pie Graph</i></h3>
                <h4>Segregates item based on their consumption rate</h4>
                <div class="row mb-5 p-3  mx-auto">
                    <canvas id="productGraph" style="width: 300px; height: 300px;"></canvas>
                </div>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3><i>Medicine Table</i></h3>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Classification</th>
                            <th>Items</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category }}</td>
                                <td>
                                    @if ($category === 'Fast')
                                        @if (count($fastProducts) > 0)
                                            <ul>
                                                @foreach ($fastProducts as $product)
                                                    <li>{{ $product }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            No products in this classification.
                                        @endif
                                    @elseif ($category === 'Slow')
                                        @if (count($slowProducts) > 0)
                                            <ul>
                                                @foreach ($slowProducts as $product)
                                                    <li>{{ $product }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            No products in this classification.
                                        @endif
                                    @elseif ($category === 'Non-Moving')
                                        @if (count($nonMovingProducts) > 0)
                                            <ul>
                                                @foreach ($nonMovingProducts as $product)
                                                    <li>{{ $product }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            No products in this classification.
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $counts[$category] }}</td>
                            </tr>
                        @endforeach
                
                        @php
                            $total = $counts['Fast'] + $counts['Slow'] + $counts['Non-Moving'];
                        @endphp
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>Total Products Here</strong></td>
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
                <button id="printButton" class="btn btn-primary">Preview Report</button>
                <a id="back" href="{{ route('supply_officer.product.demo') }}" class="btn btn-danger">Back</a>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('scripts')
<script>
    var categories = @json($categories);
    var counts = @json($counts);

    var ctx = document.getElementById('productGraph').getContext('2d');

    // Get the product counts as an array
    var productCounts = Object.values(counts);

    // Create an array to store the labels with counts
    var labelsWithCounts = [];
    for (var i = 0; i < categories.length; i++) {
        labelsWithCounts.push(categories[i] + ' (' + productCounts[i] + ')');
    }

    var chartData = {
        labels: labelsWithCounts, // Use labels with counts
        datasets: [{
            data: productCounts, // Use the product counts array
            backgroundColor: [
                'rgb(255, 99, 132, 0.7)',
                'rgb(255, 205, 86, 0.7)',
                'rgb(54, 162, 235, 0.7)',
            ],
            borderColor: [
                'rgb(255, 99, 132, 1)',
                'rgb(255, 205, 86, 1)',
                'rgb(54, 162, 235, 1)',
            ],
        }],
        
    };
   

    var myChart = new Chart(ctx, {
        type: 'pie', // Use pie chart type
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false
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