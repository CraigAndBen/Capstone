@extends('layouts.inner_demo')

@section('content')
    <!-- [ Main Content ] start -->

    <div class="pc-container pb-3">
        <div class="pc-content ">
            <!-- [ breadcrumb ] start -->
            <div class="page-header mt-4">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Medicine Demographics</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Medicine Demographics</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">

                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>Medicine Demographics</h2>
                        </div>
                        <div class="card-body">
                            <h4>Prioritizes based on the value of the items and their importance</h4>
                            <div class="row justify-content-end">
                                <div class="col-md-2 mt-2">
                                    <a href="{{ route('supply_officer.medicines.report') }}" class="btn btn-success">Generate
                                        Report</a>
                                </div>
                            </div>
                            
                            <div class="row mb-5 p-3 mx-auto">
                                <canvas id="medicineGraph" width="100%" height="50"></canvas>
                            </div>
                           <p>
                                <strong>Most Valued:</strong>
                                @if (count($mostValuedProducts) > 0)
                                    <ul>
                                        @foreach ($mostValuedProducts as $product)
                                            <li>{{ $product }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    No products in this classification.
                                @endif
                            </p>
                            <p>
                                <strong>Medium Valued:</strong>
                                @if (count($mediumValuedProducts) > 0)
                                    <ul>
                                        @foreach ($mediumValuedProducts as $product)
                                            <li>{{ $product }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    No products in this classification.
                                @endif
                            </p>
                            <p>
                                <strong>Low Valued:</strong>
                                @if (count($lowValuedProducts) > 0)
                                    <ul>
                                        @foreach ($lowValuedProducts as $product)
                                            <li>{{ $product }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    No products in this classification.
                                @endif
                            </p>
                        </div>

                        <!-- [ sample-page ] end -->
                    </div>
                    <!-- [ Main Content ] end -->
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
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script>


    
@endsection
