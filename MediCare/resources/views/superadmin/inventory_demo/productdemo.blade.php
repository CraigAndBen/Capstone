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
                                <h5 class="m-b-10">Product Demographics</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Product Demographics</li>
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
                            <h2>Product Demographics</h2>
                        </div>
                        <div class="card-body">
                            <h4>Segregates products based on their consumption rate</h4>
                            <div class="row justify-content-end">
                                <div class="col-md-2 mt-2">
                                    <a href="{{ route('superadmin.products.report') }}" class="btn btn-success">Generate
                                        Report</a>
                                </div>
                            </div>
                            <div class="row mb-5 p-3  mx-auto">
                                <canvas id="productGraph" width="100%" height="50"></canvas>
                            </div>
                            <p>
                                <strong>Fast Moving</strong>
                                @if (count($fastProducts) > 0)
                                <ul>
                                    @foreach ($fastProducts as $product)
                                    <li>{{ $product }}</li>
                                    @endforeach
                                </ul>
                                @else
                                No products in this classification.
                                @endif
                            </p>
                            <p>
                                <strong>Slow Moving:</strong>
                                @if (count($slowProducts) > 0)
                                <ul>
                                    @foreach ($slowProducts as $product)
                                    <li>{{ $product }}</li>
                                    @endforeach
                                </ul>
                                @else
                                No products in this classification.
                                @endif
                            </p>
                            <p>
                                <strong>Non-Moving:</strong>
                                @if (count($nonMovingProducts) > 0)
                                <ul>
                                    @foreach ($nonMovingProducts as $product)
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
</script>



@endsection
