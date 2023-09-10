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
                            <div class="row">
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-4">
                                    <form action="{{ route('admin.demographics.diagnose.search') }}" method="POST">
                                        @csrf
                                        <select class="form-control p-3" id="diagnose" name="diagnose">
                                            <option>Select Diagnose</option>
                                            @foreach ($diagnoseData as $diagnose)
                                                @if ($diagnose == $specificDiagnosis)
                                                <option value="{{$diagnose}}" selected>{{$diagnose}}</option>
                                                @else
                                                <option value="{{$diagnose}}">{{$diagnose}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control p-3" id="year" name="year">
                                        <option>Select Year</option>
                                        @foreach ($admittedYears as $year)
                                            @if ($year == $selectedYear)
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
                                    <input type="hidden" name="year" id="year" value="{{ $year }}">
                                    <button type="submit" class="btn btn-success">Generate Report</button>
                                </form>
                            </div>
                            <canvas id="diagnosePatientDemographicsChart" width="800" height="400"></canvas>
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
                label: 'Diagnose Patients',
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
                    }
                },
                y: {
                    beginAtZero: true,
                }
            }
        }
    });
</script>
@endsection
