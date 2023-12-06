@extends('layouts.analytics_report')
@section('style')
    <style>
        @media print {

            /* Hide the button when printing */
            #printButton {
                display: none;
            }

            #back {
                display: none;
            }
            #done {
                display: none;
            }
        }

        @page {
            size: a4;
        }

        .page-break {
            page-break-after: always;
        }

        #requestChartContainer {
        text-align: center;
    }

    #requestChart {
        max-width: 100%; /* Make the chart responsive */
        display: inline-block;
    }
     
    </style>
@endsection
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-first align-items-first my-3">
            <div class="col-7 my-4">
                <h5>Report Type: <i><b>
                            @if ($reportType === 'item')
                                Most Requested Item Analytics Report
                            @elseif ($reportType === 'department')
                                Most Requesting Department Analytics Report
                            @endif
                        </b></i></h5>
                <h5>Date: <i><b>{{ date('M j, Y', strtotime($currentDateTime)) }}</b></i></h5>
                <h5>Time: <i><b>{{ $currentTime }}</b></i></h5>
                <h5>Reference: <i><b>{{ $reference }}</b></i></h5>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>
        </div>
        <hr style="border-top: 1px solid #000;">
        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3 style="margin-left: 40px"><i>
                        @if ($reportType === 'item')
                            Most Requested Item
                        @elseif ($reportType === 'department')
                            Most Requesting Department
                        @endif
                    </i></h3>
                <br>
                <canvas id="requestChart"></canvas>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div style="height: 100px"></div>

        <div class="row justify-content-center">
            <div class="col-8 text-center">
                <h3 style="margin-left: 65px"><i>{{ $reportType === 'item' ? 'Item' : 'Department' }} Table</i></h3>
                <br>
                <table class="table table-bordered" style="margin-left: 40px">
                    <thead>
                        <tr>
                            <th>{{ $reportType === 'item' ? 'Item' : 'Department' }}</th>
                            <th>Date</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($result as $item)
                            <tr>
                                <td>{{ $item->label }}</td>
                                <td>{{ date('M j, Y', strtotime($item->request_date)) }}</td>
                                <td>{{ $item->data }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>{{ $result->sum('data') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-1">

            </div>
        </div>
        <div class="row justify-content-end align-items-end my-5">
            <div class="col-10 text-right">
                <form action="{{ route('superadmin.request.report.save') }}" method="POST">
                    @csrf
                    <input type="hidden" name="reference" value="{{ $reference }}">
                    <input type="hidden" name="date" value="{{ $currentDateTime }}">
                    <input type="hidden" name="time" value="{{ $currentTime }}">
                    <button id="printButton" type="button" class="btn btn-primary">Preview Report</button>
                    <button id="done" type="submit" class="btn btn-success">Done</button>
                    <a id="back" href="{{ route('superadmin.request.demo') }}" class="btn btn-danger">Back</a>
                </form>
            </div>
            <div class="col-2">
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    @if (isset($chartData))
        <script>
            var ctx = document.getElementById('requestChart').getContext('2d');
            var chartData = @json($chartData);
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: @json($range),
                        data: @json($chartData['data']),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
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
