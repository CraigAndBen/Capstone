@extends('layouts.inner_superadmin')

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
                                <h5 class="m-b-10">Sale Demographics</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Sale Demographics</li>
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
                            <h2>Sale Demographics</h2>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input. Please fix the
                                    following errors: <br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success">
                                    <span class="fa fa-check-circle"></span> {{ session('success') }}
                                </div>
                            @endif

                            @if (session('info'))
                                <div class="alert alert-info">
                                    {{ session('info') }}
                                </div>
                            @endif
                            <div class="row justify-content-center">
                                <div class="col-md-1"></div>
                                <div class="col-md-4">
                                    <form action="{{ route('superadmin.sale.demo.search') }}" method="GET">
                                        @csrf
                                        <div class="form-group">
                                            <label for="from">From</label>
                                            <input type="date" class="form-control" name="start" id="from">
                                        </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="to">To</label>
                                        <input type="date" class="form-control" name="end" id="to">
                                    </div>
                                </div>
                                <div class="col-md-2 mt-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Select</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <hr>
                            <div class="row justify-content-end">
                                <div class="col-md-2 mt-2">
                                    <form action="{{ route('superadmin.sale.report') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="select" id="select" value="{{ $selectedOption }}">
                                        <input type="hidden" name="start" id="start" value="{{ $fromDate }}">
                                        <input type="hidden" name="end" id="end" value="{{ $toDate }}">
                                        <button type="submit" class="btn btn-success">Generate Report</button>
                                    </form>
                                </div>
                            </div>
                            <div class="row mb-5 p-3">
                                <canvas id="salesGraph" width="100%" height="40"></canvas>
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
                            beginAtZero: true,
                            precision: 0 // Display only whole numbers on the y-axis
                        }
                    }
                }
            });
        </script>
        
        @endsection
