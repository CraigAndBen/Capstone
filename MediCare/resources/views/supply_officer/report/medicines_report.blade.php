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
                <h5>Report Type: <i><b>Medicine Analytics Report</b></i></h5>
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
                <h3><i>Medicine Pie Graph</i></h3>
                <div class="row mb-5 p-3 mx-auto">
                    <canvas id="medicineGraph" style="width: 300px; height: 300px;"></canvas>
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
                            <th>Label</th>
                            <th>Products</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0; // Initialize total count
                        @endphp
                
                        @foreach ($chartData as $value)
                            <tr>
                                <td>{{ $value['label'] }}</td>
                                <td>
                                    @if ($value['label'] === 'Most Valued')
                                        @foreach ($mostValuedProducts as $product)
                                            {{ $product }}<br>
                                        @endforeach
                                    @elseif ($value['label'] === 'Medium Valued')
                                        @foreach ($mediumValuedProducts as $product)
                                            {{ $product }}<br>
                                        @endforeach
                                    @elseif ($value['label'] === 'Low Valued')
                                        @foreach ($lowValuedProducts as $product)
                                            {{ $product }}<br>
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $value['count'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><strong>Most Valued</strong></td>
                            <td>
                                @foreach ($mostValuedProducts as $product)
                                    {{ $product }}<br>
                                @endforeach
                            </td>
                            <td>{!! $mostValuedPercentage !!} %</td>
                        </tr>
                        <tr>
                            <td><strong>Medium Valued</strong></td>
                            <td>
                                @foreach ($mediumValuedProducts as $product)
                                    {{ $product }}<br>
                                @endforeach
                            </td>
                            <td>{!! $mediumValuedPercentage !!} %</td>
                        </tr>
                        <tr>
                            <td><strong>Low Valued</strong></td>
                            <td>
                                @foreach ($lowValuedProducts as $product)
                                    {{ $product }}<br>
                                @endforeach
                            </td>
                            <td>{!! $lowValuedPercentage !!} %</td>
                        </tr>
                
                        @php
                            $total = $mostValuedPercentage + $mediumValuedPercentage + $lowValuedPercentage;
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
                <a id="back" href="{{ route('supply_officer.medicine.demo') }}" class="btn btn-danger">Back</a>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('scripts')
<script>
    var ctx = document.getElementById('medicineGraph').getContext('2d');
    var medicineGraph = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [
                'Most Valued ' + {{ $mostValuedPercentage }} + '%',
                'Medium Valued ' + {{ $mediumValuedPercentage }} + '%',
                'Low Valued ' + {{ $lowValuedPercentage }} + '%'
            ],
            datasets: [{
                data: [
                    {{ $mostValuedPercentage }},
                    {{ $mediumValuedPercentage }},
                    {{ $lowValuedPercentage }}
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)', // Green for Most Valued
                    'rgba(54, 162, 235, 0.7)', // Blue for Medium Valued
                    'rgba(255, 99, 132, 0.7)'  // Red for Low Valued
                ],
                borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            }]
        },
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
