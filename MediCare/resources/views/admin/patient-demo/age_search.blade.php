@extends('layouts.inner_admin')

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
                                <h5 class="m-b-10">Age Demographics</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Age Demographics</li>
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
                            <h1>Gender Demographics</h1>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">

                                </div>
                                    <div class="col-md-8">
                                        <form action="{{route('admin.demographics.age.search')}}" method="POST">
                                            @csrf
                                        <select class="form-control p-3" id="year" name="year">
                                            <option>Select Year</option>
                                            @foreach ($admittedYears as $year)
                                                @if ($year == $yearSelected)
                                                <option value="{{$year}}" selected>{{$year}}</option>
                                                @else
                                                <option value="{{$year}}">{{$year}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <button type="submit" class="btn btn-primary">Select</button>
                                    </div>
                                </form>
                            </div>
                            <hr>
                            <div class="container">
                                <canvas id="ageDemographicsChart" width="800" height="400"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>
    @endsection

    @section('scripts')
    <script>
        // Prepare data for the bar graph
        var labels = {!! json_encode($labels) !!};
        var datasets = {!! json_encode($datasets) !!};

        // Define a color palette for the bar graph
        var colors = [
            'rgba(54, 162, 235, 0.7)', // Blue
            'rgba(255, 99, 132, 0.7)', // Red
            'rgba(75, 192, 192, 0.7)', // Green
            'rgba(255, 206, 86, 0.7)', // Yellow
            'rgba(153, 102, 255, 0.7)', // Purple
            // Add more colors if needed
        ];

        // Get the chart context and create the bar graph
        var ctx = document.getElementById('ageDemographicsChart').getContext('2d');
        var ageDemographicsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets.map(function(data, index) {
                    return {
                        label: data.month,
                        data: data.data,
                        backgroundColor: colors[index % colors.length], // Use the predefined colors from the palette
                        borderWidth: 1,
                    };
                })
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true, // Stack the bars on the x-axis for each month
                    },
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    </script>
    @endsection
