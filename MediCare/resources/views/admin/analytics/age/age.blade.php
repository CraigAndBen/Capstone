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
                                <h5 class="m-b-10">{{$title}}</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">{{$title}}</li>
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
                            <h1>{{$title}}</h1>
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
                                    <form action="{{ route('admin.analytics.patient.age.search') }}" method="GET">
                                        @csrf
                                        <input type="hidden" name="type" value="{{$type}}">
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
                                <h3>Patient Total - <i>{{ $totalPatientCount }}</i></h3>
                            </div>
                            <div class="row m-5">
                                <div class="col-md-10"> <!-- Adjust the column width as needed -->
                                </div>
                                <div class="col-md-2 text-right mb-5"> <!-- Adjust the column width as needed -->
                                    <form action="{{ route('admin.age.report') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="year" id="year" value="{{ $year }}">
                                        <input type="hidden" name="type" id="type" value="{{ $type }}">
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
            const datasets = @json($datasets);
            const labels = @json($labels);

            // Define an array of colors for each age group
            var colors = [
                'rgba(54, 162, 235, 0.7)', // Blue
                'rgba(255, 99, 132, 0.7)', // Red
                'rgba(75, 192, 192, 0.7)', // Green
                'rgba(255, 206, 86, 0.7)', // Yellow
                'rgba(153, 102, 255, 0.7)', // Purple
                'rgba(255, 159, 64, 0.7)', // Orange
                'rgba(255, 0, 0, 0.7)', // Bright Red
                'rgba(100, 190, 0, 0.7)', // Bright Green
                'rgba(0, 0, 255, 0.7)', // Bright Blue
                'rgba(128, 128, 0, 0.7)', // Olive
                'rgba(128, 0, 128, 0.7)', // Purple
                'rgba(0, 128, 128, 0.7)', // Teal
            ];

            // Filter out datasets with 0 counts
            const filteredDatasets = labels.map((label, index) => {
                const data = datasets.map(data => data.data[index]);
                if (data.some(count => count > 0)) {
                    return {
                        label: `Age Group: ${label}`,
                        data: data,
                        backgroundColor: colors[index], // Use the corresponding color
                        borderColor: colors[index], // Use the corresponding color
                        borderWidth: 1,
                    };
                }
                return null;
            }).filter(dataset => dataset !== null);

            // Create a Chart.js chart
            const ctx = document.getElementById('ageDemographicsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: datasets.map(data => data.month),
                    datasets: filteredDatasets,
                },
                options: {
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Month'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        },
                    },
                },
            });
        </script>
    @endsection
