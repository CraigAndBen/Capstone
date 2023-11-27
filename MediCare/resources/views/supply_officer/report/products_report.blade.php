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
                <h8>Report Type: <i><b>Item (FSN) Analytics Report</b></i></h8>
                <br>
                <h8>Date: <i><b>{{ date('M j, Y', strtotime($currentDate)) }}</b></i></h8>
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

        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3 style="margin-left: 65px"><i>Item (FSN) Pie Graph</i></h3>
                <h5 style="margin-left: 65px">Segregates items based on their consumption rate</h5>
                <div class="row mb-5 p-3 mx-auto">
                    <canvas id="productGraph" style="width: 300px; height: 300px;"></canvas>
                </div>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3 style="margin-left: 65px"><i>Medicine Table</i></h3>
                <br>
                <table class="table table-bordered" style="margin-left: 40px">
                    <thead>
                        <tr class="text-center">
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
                                                    <li class="text-left">{{ $product }}</li>
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
                                            No item in this classification.
                                        @endif
                                    @elseif ($category === 'Non-Moving')
                                        @if (count($nonMovingProducts) > 0)
                                            <ul>
                                                @foreach ($nonMovingProducts as $product)
                                                    <li>{{ $product }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            No item in this classification.
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
                            <td><strong></strong></td>
                            <td><strong></strong></td>
                            <td class="text-center"><strong>{{ $total }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div class="row justify-content-end align-items-end my-5">
            <div class="col-10 text-right">
                <form action="{{route('supply_officer.products.report.save')}}" method="POST">
                    @csrf
                    <input type="hidden" name="reference" value="{{$reference}}">
                    <input type="hidden" name="date" value="{{$currentDate}}">
                    <input type="hidden" name="time" value="{{$currentTime}}">
                    <button id="printButton" type="button" class="btn btn-primary">Preview Report</button>
                    <button id="done" type="submit" class="btn btn-success">Done</button>
                    <a id="back" href="{{ route('supply_officer.product.demo') }}" class="btn btn-danger">Back</a>
                </form>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>

    <!-- Include the necessary JavaScript for chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart.js Script -->
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

        // Attach a click event handler to the button
        $(document).ready(function () {
            $("#printButton").click(function () {
                // Call the window.print() function to open the print dialog
                window.print();
            });
        });

    </script>
@endsection
