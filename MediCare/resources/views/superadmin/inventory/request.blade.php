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
                                <h5 class="m-b-10">Request List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
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
                                    <div class="col"></div>
                                    <a href="{{route('superadmin.request.list.report')}}" class="btn btn-success">Generate Report</a>

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
                                <div class="row justify-content-end">
                                    <div class="form-group col-sm-4">
                                        <input type="text" id="requestSearch" class="form-control"
                                            placeholder="Search Requests">
                                    </div>
                                </div>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center">#</th>
                                                <th style="text-align: center">Name Of Requester</th>
                                                <th style="text-align: center">Department</th>
                                                <th style="text-align: center">Date</th>
                                                <th style="text-align: center">Product Name</th>
                                                <th style="text-align: center">Brand</th>
                                                <th style="text-align: center">Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;
                                            @endphp
                                            @foreach ($requests as $request)
                                                <tr>
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
                                    <div class="d-flex justify-content-center my-3">
                                        {{ $requests->links('pagination::bootstrap-4') }}
                                    </div>
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
                $('#requestSearch').on('keyup', function() {
                    var searchText = $(this).val().toLowerCase();
                    filterRequests(searchText);
                });

                function filterRequests(searchText) {
                    var rows = document.querySelectorAll("table tbody tr");
                    for (var i = 0; i < rows.length; i++) {
                        var requestName = rows[i].querySelector("td:nth-child(2)").textContent.toLowerCase();
                        var department = rows[i].querySelector("td:nth-child(3)").textContent.toLowerCase();
                        var date = rows[i].querySelector("td:nth-child(4)").textContent.toLowerCase();
                        var productName = rows[i].querySelector("td:nth-child(5)").textContent.toLowerCase();
                        var brand = rows[i].querySelector("td:nth-child(6)").textContent.toLowerCase();
                        var quantity = rows[i].querySelector("td:nth-child(7)").textContent.toLowerCase();

                        if (
                            requestName.includes(searchText) ||
                            department.includes(searchText) ||
                            date.includes(searchText) ||
                            productName.includes(searchText) ||
                            brand.includes(searchText) ||
                            quantity.includes(searchText)
                        ) {
                            rows[i].style.display = "";
                        } else {
                            rows[i].style.display = "none";
                        }
                    }
                }
            });
        </script>
    @endsection
