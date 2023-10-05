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
                <canvas id="productChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div style="height: 150px"></div>

        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3><i>Inventory Table</i></h3>
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
                <button id="printButton" class="btn btn-primary">Preview Report</button>
                <a id="back" href="{{ route('supply_officer.inventory.demo') }}" class="btn btn-danger">Back</a>
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

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: productData.map(data => data.label), // Use 'label' instead of 'category'
                    datasets: [{
                        label: 'Count',
                        data: productData.map(data => data.count),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
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
    @endif
@endsection
