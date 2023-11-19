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
                                <h5 class="m-b-10">Report History</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Report Historyt</li>
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
                            <h1 class="display-6">Report History</h1>
                        </div>
                        <div class="card-body">
                            <div class="container">

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

                                @if ($reports->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Appointment Yet.
                                    </div>
                                @else
                                    <div class="row my-4">
                                        <table class="table table-hover" id="patientTable">
                                            <thead class="table-primary text-light text-center">
                                                <tr>
                                                    <th>Reference Number</th>
                                                    <th>Report Type</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @foreach ($reports as $report)
                                                    <tr>
                                                        <td>{{ ucwords($report->reference_number) }}</td>
                                                        <td>{{ ucwords($report->report_type) }}</td>
                                                        <td>{{ date('F j, Y', strtotime($report->date)) }}</td>
                                                        <td>{{ ucwords($report->time) }}</td>
    
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                                    data-toggle="dropdown">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item btn btn-primary" data-toggle="modal"
                                                                        data-target="#viewModal"
                                                                        data-reference="{{ json_encode($report->reference_number) }}"
                                                                        data-report-type="{{ json_encode($report->report_type) }}"
                                                                        @foreach ($users as $user)
                                                                            @if ($report->user_id == $user->id)
                                                                                data-author="{{ json_encode($user->first_name . ' ' . $user->last_name) }}"
                                                                                data-author-type="{{ json_encode(ucwords(str_replace('_', ' ', $report->author_type))) }}"
                                                                            @endif
                                                                        @endforeach
                                                                        data-date="{{ json_encode(date('F j, Y', strtotime($report->date))) }}"
                                                                        data-time="{{ json_encode($report->time) }}">View</a>
    
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
    
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Report Information</h2>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Reference Number:</strong> <span id="reference"></span> </p>
                                    <p><strong>Report Type:</strong> <span id="report_type"></span> </p>
                                    <p><strong>Author:</strong> <span id="author"></span> </p>
                                    <p><strong>User Type:</strong> <span id="author_type"></span> </p>
                                    <p><strong>Date:</strong> <span id="date"></span> </p>
                                    <p><strong>Time:</strong> <span id="time"></span> </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
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
            $(document).ready(function() {

                $('#viewModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var reference = JSON.parse(button.data('reference'));
                    var report_type = JSON.parse(button.data('report-type'));
                    var author = JSON.parse(button.data('author'));
                    var author_type = JSON.parse(button.data('author-type'));
                    var date = JSON.parse(button.data('date'));
                    var time = JSON.parse(button.data('time'));
                    var modal = $(this);
                    console.log(reference)

                    modal.find('#reference').text(reference);
                    modal.find('#report_type').text(report_type);
                    modal.find('#author').text(author);
                    modal.find('#author_type').text(author_type);
                    modal.find('#date').text(date);
                    modal.find('#time').text(time);
                });
            });
        </script>
    @endsection
