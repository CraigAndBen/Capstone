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
                                <h5 class="m-b-10">Medication Analytics</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Medication Analytics</li>
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
                            <h1 class="display-6">Medication Analytics</h1>
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
                                <div class="col-md-4">
                                    <form action="{{ route('supply_officer.medication.demo.search') }}" method="GET">
                                        @csrf
                                        <select class="form-control p-3" id="medication" name="medication">
                                            <option value="">Select Medication</option>
                                            @foreach ($medicationData as $medication)
                                                @if ($medication == $specificMedication)
                                                    <option value="{{ $medication }}" selected>{{ ucwords($medication) }}
                                                    </option>
                                                @else
                                                    <option value="{{ $medication }}">{{ ucwords($medication) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control p-3" id="year" name="year">
                                        <option value="">Select Year</option>
                                        @foreach ($years as $year)
                                            @if ($year == $selectedYear)
                                                <option value="{{ $year }}" selected>{{ $year }}</option>
                                            @else
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <button type="submit" class="btn btn-primary">Select</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <hr>
                        <div class="row mb-5 p-3">
                            <div class="col-md-10"> <!-- Adjust the column width as needed -->
                            </div>
                            <div class="col-md-2 text-right mb-3"> <!-- Adjust the column width as needed -->
                                <form action="{{ route('supply_officer.medication.report') }}" method="GET">
                                    @csrf
                                    <input type="hidden" name="medication" id="medication" value="{{ $specificMedication }}">
                                    <input type="hidden" name="year" id="year" value="{{ $selectedYear }}">
                                    <button type="submit" class="btn btn-success">Generate Report</button>
                                </form>
                            </div>
                            <canvas id="medicationDemographicsChart" width="100%" height="40"></canvas>
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
        // Prepare data for the line graph
        var months = {!! json_encode(array_column($medicationCountsByMonth, 'month')) !!};
        var medicationCounts = {!! json_encode(array_column($medicationCountsByMonth, 'count')) !!};

        // Get the chart context and create the line graph
        var ctx = document.getElementById('medicationDemographicsChart').getContext('2d');
        var medicationDemographicsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: {!! json_encode(ucwords($specificMedication)) !!},
                    data: medicationCounts,
                    borderColor: 'rgba(54, 162, 235, 1)', // Blue
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Lighter blue fill
                    borderWidth: 2,
                    fill: true, // To fill the area under the line
                    pointRadius: 5, // Adjust the size of data points on the line
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Blue data points
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Diagnose Count'
                        }
                    }
                }
            }
        });
    </script>
@endsection
