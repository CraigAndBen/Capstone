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
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Dashboard</a>
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
                            <div class="row justify-content-end">
                                <div class="col-md-2 mt-2">
                                    <a href="{{ route('supply_officer.products.report') }}" class="btn btn-success">Generate
                                        Report</a>
                                </div>
                            </div>
                            <div class="row mb-5 p-3">
                                <canvas id="productGraph" width="400" height="400"></canvas>
                            </div>
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
</script>



@endsection
