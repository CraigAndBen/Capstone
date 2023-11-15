@extends('layouts.inner_supplyofficer')

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
                                <h5 class="m-b-10">Request List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Request List</li>
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
                            <h1>Request List</h1>
                        </div>

                        <div class="card-body">
                            <div class="container">


                                <div class="d-flex mb-3 justify-content-end">
                                    <div class="form-group">
                                        <a href="{{ route('supply_officer.request.list.report.view') }}"
                                            class="btn btn-success" target="_blank">View Report</a>
                                        <a href="{{ route('supply_officer.request.list.report.download') }}"
                                            class="btn btn-success" target="_blank">Download Report</a>
                                    </div>
                                </div>


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
                                        <span class="fa fa-check-circle"></span> {{ session('info') }}
                                    </div>
                                @endif

                                @if ($requests->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Request Yet.
                                    </div>
                                @else
                                    <table id="requesttable" class="display">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center">#</th>
                                                <th style="text-align: center">Name Of Requester</th>
                                                <th style="text-align: center">Department</th>
                                                <th style="text-align: center">Date</th>
                                                <th style="text-align: center">Item Name</th>
                                                <th style="text-align: center">Brand</th>
                                                <th style="text-align: center">Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;
                                            @endphp
                                            @foreach ($requests as $request)
                                                <tr
                                                    @if ($request->date == now()->format('Y-m-d')) style="background-color: yellow" @endif>
                                                    <td style="text-align: center">{{ $counter++ }}</td>
                                                    <td style="text-align: center">{{ $request->name_requester }}</td>
                                                    <td style="text-align: center">{{ $request->department }}</td>
                                                    <td style="text-align: center">{{ $request->date }}</td>
                                                    <td style="text-align: center">{{ $request->product->p_name }}</td>
                                                    <td style="text-align: center">{{ $request->brand }}</td>
                                                    <td style="text-align: center">{{ $request->quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
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
                $('#requesttable').DataTable();
            });
        </script>
    @endsection
