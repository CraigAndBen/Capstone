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
                            <div class="row">
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-8">
                                    <form action="{{ route('admin.demographics.patient.age.search') }}" method="GET">
                                        @csrf
                                        <select class="form-control p-3" id="year" name="year">
                                            <option value="">Select Year</option>
                                            @foreach ($uniqueCombinedYears as $admittedYear)
                                                @if ($admittedYear == $year)
                                                    <option value="{{ $admittedYear }}" selected>{{ $admittedYear }}
                                                    </option>
                                                @else
                                                    <option value="{{ $admittedYear }}">{{ $admittedYear }}</option>
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
                            <div class="p-5">
                                <h3>Age Total - <i>{{ $totalPatientCount }}</i></h3>
                            </div>
                            <div class="row m-5">
                                <div class="col-md-10"> <!-- Adjust the column width as needed -->
                                </div>
                                <div class="col-md-2 text-right mb-5"> <!-- Adjust the column width as needed -->
                                    <form action="{{ route('admin.age.report') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="year" id="year" value="{{ $year }}">
                                        <input type="hidden" name="type" id="type" value="patient">
                                        <button type="submit" class="btn btn-success">Generate Report</button>
                                    </form>
                                </div>
                                <canvas id="ageDemographicsChart" width="100%" height="40"></canvas>
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
            // Get age data from Laravel controller
            var ageData = @json($filteredAgeData);

            // Create labels for months
            var months = [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ];

            // Define an array of custom colors for age groups
            var customColors = [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                // Add more custom colors as needed
            ];

            // Create a chart
            var ctx = document.getElementById('ageDemographicsChart').getContext('2d');
            var ageChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months, // Use months as labels
                    datasets: []
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Add datasets to the chart for each age group
            var i = 0; // Counter for custom colors
            for (var age in ageData) {
                var ageDataFiltered = months.map(function(month) {
                    return ageData[age][months.indexOf(month) + 1] || 0;
                });

                if (ageDataFiltered.some(count => count >= 1)) {
                    // Only add a dataset if there is at least one count of 1 or greater
                    ageChart.data.datasets.push({
                        label: age + ' years old',
                        data: ageDataFiltered,
                        backgroundColor: customColors[i], // Assign a custom color
                        borderColor: customColors[i], // Assign a custom border color
                        borderWidth: 1
                    });
                }

                i++; // Move to the next custom color
            }

            // Update the chart
            ageChart.update();
        </script>
    @endsection
