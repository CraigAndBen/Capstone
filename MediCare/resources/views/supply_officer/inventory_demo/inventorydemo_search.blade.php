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
                                <h5 class="m-b-10">Inventory Demographics</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Inventory Demographics</li>
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
                            <h2>Inventory Demographics</h2>
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
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-4">
                                    <form action="{{ route('supply_officer.inventory.demo.search') }}" method="GET">
                                        @csrf
                                        <select class="form-control p-3" name="select" id="select">
                                            <option value="">Select</option>
                                            <option value="Category">Category</option>
                                            <option value="Brand">Brand</option>

                                        </select>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <button type="submit" class="btn btn-primary">Select</button>
                                </div>
                            </div>

                            </form>
                        </div>
                        <hr>
                        <div class="row justify-content-end">
                            <div class="col-md-2 mt-2">
                                <form action="{{ route('supply_officer.inventory.report') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="select" id="select" value="{{ $selectedOption }}">
                                    <button type="submit" class="btn btn-success">Generate Report</button>
                                </form>
                            </div>
                        </div>
                        <div class="row mb-5 p-3">
                            <canvas id="productChart" width="100%" height="40"></canvas>
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
        @if (isset($chartData))
        <script>
            var ctx = document.getElementById('productChart').getContext('2d');
            var productData = @json($chartData);
        
            // Define an array to store labels with both name and number
            var labelsWithNamesAndNumbers = productData.map(data => `${data.label} (${data.count})`);
        
            // Dynamically generate an array of colors based on the number of data points
            var colors = generateColors(productData.length);
        
            function generateColors(numColors) {
                var colorsArray = [];
                for (var i = 0; i < numColors; i++) {
                    // You can use any method to generate colors dynamically, e.g., random colors
                    var randomColor = 'rgba(' +
                        Math.floor(Math.random() * 256) + ',' +
                        Math.floor(Math.random() * 256) + ',' +
                        Math.floor(Math.random() * 256) + ', 0.2)';
                    colorsArray.push(randomColor);
                }
                return colorsArray;
            }
        
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelsWithNamesAndNumbers, // Use labels with both name and number
                    datasets: [{
                        label: 'Data',
                        data: productData.map(data => data.count),
                        backgroundColor: colors, // Use the dynamically generated colors array
                        borderColor: colors.map(color => color.replace('0.2', '1')), // Set border color with full opacity
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
