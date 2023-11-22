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
                                    <table id="requesttable" class="table">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center">#</th>
                                                <th style="text-align: center">Name Of Requester</th>
                                                <th style="text-align: center">Department</th>
                                                <th style="text-align: center">Date</th>
                                                <th style="text-align: center">Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;
                                                $uniqueRows = [];
                                            @endphp
                                            @foreach ($requests as $request)
                                                @php
                                                    // Generate a unique identifier for the row
                                                    $rowIdentifier = $request->name_requester . $request->department . $request->date . $request->created_at;
                                                @endphp

                                                @if (!in_array($rowIdentifier, $uniqueRows))
                                                    <tr @if ($request->date == now()->format('Y-m-d')) style="background-color: lightblue" @endif
                                                        class="clickable-row" data-toggle="modal"
                                                        data-target="#viewModal{{ $request->id }}">
                                                        <td style="text-align: center">{{ $counter++ }}</td>
                                                        <td style="text-align: center">{{ $request->name_requester }}</td>
                                                        <td style="text-align: center">{{ $request->department }}</td>
                                                        <td style="text-align: center">{{ date('M j, Y', strtotime($request->date)) }}</td>
                                                       <td style="text-align: center">{{ date('g:i A', strtotime($request->created_at)) }}</td>
                                                    </tr>

                                                    @php
                                                        // Add the identifier to the list of unique rows
                                                        $uniqueRows[] = $rowIdentifier;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- View modal --}}
                    @foreach ($requests as $request)
                        <div class="modal fade" id="viewModal{{ $request->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h2 class="modal-title text-light" id="myModalLabel">Request</h2>
                                    </div>
                                    <!-- Modal Content -->
                                    <div class="modal-body">
                                        <form>
                                            <div class="row form-group">
                                                <div class="col-md-4">
                                                    <div class="mb-2">
                                                        <label for="name_of_requester">Name of requester</label>
                                                        <input type="text" class="form-control" id="name_requester"
                                                            name="name_requester" value="{{ $request->name_requester }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-2">
                                                        <label for="department">Department</label>
                                                        <input type="text" class="form-control" id="department"
                                                            name="department" value="{{ $request->department }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-2">
                                                        <label for="date">Date</label>
                                                        <input type="text" class="form-control" id="date"
                                                            name="date"
                                                            value="{{ date('M j, Y', strtotime($request->date)) }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <div class="rounded border p-3">
                                                    <div class="table table-sm row">
                                                        <div class="col-12 col-sm-4">
                                                            <div class="d-none d-sm-block text-center"><strong>Item</strong>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-4">
                                                            <div class="d-none d-sm-block text-center">
                                                                <strong>Brand</strong></div>
                                                        </div>
                                                        <div class="col-12 col-sm-4">
                                                            <div class="d-none d-sm-block text-center">
                                                                <strong>Quantity</strong></div>
                                                        </div>
                                                    </div>

                                                    <div class="text-95 text-secondary-d3">
                                                        @foreach ($requests as $productRequest)
                                                            @if (
                                                                $productRequest->name_requester == $request->name_requester &&
                                                                    $productRequest->department == $request->department &&
                                                                    $productRequest->date == $request->date &&
                                                                    $productRequest->created_at == $request->created_at)
                                                                <div class="table table-sm row mb-2 mb-sm-0 py-25">
                                                                    <div class="col-12 col-sm-4 text-center">
                                                                        {{ $productRequest->product->p_name }}</div>
                                                                    <div class="col-12 col-sm-4 text-center">
                                                                        {{ $productRequest->brand }}</div>
                                                                    <div class="col-12 col-sm-4 text-center">
                                                                        {{ $productRequest->quantity }}</div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{-- End View Modal --}}
                </div>
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
