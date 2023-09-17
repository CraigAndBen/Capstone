@extends('layouts.superadmin')

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
                                <h5 class="m-b-10">Admit Demographics</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Admit Demographics</li>
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
                            <h1>Admit Demographics</h1>
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
                                    <form action="{{ route('superadmin.demographics.admit.search') }}" method="POST">
                                        @csrf
                                        <select class="form-control p-3" id="year" name="year">
                                            <option>Select Year</option>
                                            @foreach ($admittedYears as $admittedYear)
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
                            <div class="row">
                                <div class="col-md-10"> <!-- Adjust the column width as needed -->
                                </div>
                                <div class="col-md-2 text-right mb-3"> <!-- Adjust the column width as needed -->
                                    <form action="{{ route('superadmin.admit.report') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="year" id="year" value="{{ $year }}">
                                        <button type="submit" class="btn btn-success">Generate Report</button>
                                    </form>
                                </div>
                                <canvas id="admitPatientDemographicsChart" width="800" height="400"></canvas>
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
            var months = {!! json_encode(array_column($admitPatientCountsByMonth, 'month')) !!};
            var admitPatientCounts = {!! json_encode(array_column($admitPatientCountsByMonth, 'count')) !!};

            // Get the chart context and create the bar graph
            var ctx = document.getElementById('admitPatientDemographicsChart').getContext('2d');
            var admitPatientDemographicsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Admit Patients',
                        data: admitPatientCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)', // Blue
                        borderWidth: 1,
                    }]
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
