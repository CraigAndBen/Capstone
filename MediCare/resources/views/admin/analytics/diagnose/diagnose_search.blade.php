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
                                <h5 class="m-b-10">Diagnose Demographics</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Diagnose Demographics</li>
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
                            <h1>Diagnose Demographics</h1>
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
                                    <form action="{{ route('admin.analytics.diagnose.search') }}" method="GET">
                                        @csrf
                                        <input type="hidden" name="type" value="{{$type}}">
                                        <select class="form-control p-3" id="diagnose" name="diagnose">
                                            <option value="">Select Diagnose</option>
                                            @foreach ($AdmittedDiagnoseData as $diagnose)
                                                @if ($diagnose == $specificDiagnosis)
                                                    <option value="{{ $diagnose }}" selected>{{ ucwords($diagnose) }}
                                                    </option>
                                                @else
                                                    <option value="{{ $diagnose }}">{{ ucwords($diagnose) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control p-3" id="year" name="year">
                                        <option value="">Select Year</option>
                                        @foreach ($uniqueCombinedYears as $year)
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
                                <form action="{{ route('admin.diagnose.report') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="diagnose" id="diagnose" value="{{ $specificDiagnosis }}">
                                    <input type="hidden" name="type" value="{{ $type }}">
                                    <input type="hidden" name="year" id="year" value="{{ $year }}">
                                    <button type="submit" class="btn btn-success">Generate Report</button>
                                </form>
                            </div>
                            <canvas id="diagnosePatientDemographicsChart" width="100%" height="40"></canvas>
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
        var months = {!! json_encode(array_column($diagnosePatientCountsByMonth, 'month')) !!};
        var diagnosePatientCounts = {!! json_encode(array_column($diagnosePatientCountsByMonth, 'count')) !!};

        // Get the chart context and create the line graph
        var ctx = document.getElementById('diagnosePatientDemographicsChart').getContext('2d');
        var diagnosePatientDemographicsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: {!! json_encode(ucwords($specificDiagnosis)) !!},
                    data: diagnosePatientCounts,
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 0.7)', // Blue
                    borderWidth: 2,
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
