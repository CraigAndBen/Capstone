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
                <h5>Report Type: <i><b>Product (FSN) Analytics Report</b></i></h5>
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
                <h3><i>Product (FSN) Pie Graph</i></h3>
                <br>
                <canvas id="productGraph"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div style="height: 150px"></div>

        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3><i>Medicine Table</i></h3>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Label</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0; // Initialize total count
                        @endphp

                        @foreach ($chartData as $values)
                            <tr>
                                <td>{{ $values['label'] }}</td>
                                <td>{{ $values['count'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><strong>Fast Moving Products</strong></td>
                            <td>{!! $fastCount !!} </td>
                        </tr>
                        <tr>
                            <td><strong>Slow Moving Products</strong></td>
                            <td>{!! $slowCount !!} </td>
                        </tr>
                        <tr>
                            <td><strong>Non-Moving Products</strong></td>
                            <td>{!! $nonMovingCount !!} </td>
                        </tr>
                        
                        @php
                            $total = $fastCount + $slowCount + $nonMovingCount;
                        @endphp
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $total }} </strong></td>
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
                'rgb(255, 99, 132)',
                'rgb(255, 205, 86)',
                'rgb(54, 162, 235)',
            ],
        }],
    };

    var myChart = new Chart(ctx, {
        type: 'pie', // Use pie chart type
        data: chartData,
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
