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
                                <h5 class="m-b-10">Expiring Items</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Expiring Items</li>
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
                            <h3>Expiring Items</h3>
                        </div>
                        <div class="card-body">
                            <div class="container">

                                <div class="d-flex mb-3 justify-content-end">
                                    <div class="form-group">
                                        <a href="{{ route('superadmin.product.expiry.report.view') }}"
                                            class="btn btn-success" target="_blank">View Report</a>
                                        <a href="{{ route('superadmin.product.expiry.report.download') }}"
                                            class="btn btn-success" target="_blank">Download Report</a>
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="startDate">Start</label>
                                            <input type="date" id="startDate" class="form-control"
                                                placeholder="Start Date">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="endDate">End</label>
                                            <input type="date" id="endDate" class="form-control"
                                                placeholder="End Date">
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-4 align-self-end">
                                        <div class="form-group">
                                            <button id="filterByDate" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                </div>



                                @if ($products->count() > 0)
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center">#</th>
                                                <th style="text-align: center">Item Name</th>
                                                <th style="text-align: center">Stock</th>
                                                <th style="text-align: center">Brand</th>
                                                <th style="text-align: center">Category</th>
                                                <th style="text-align: center">Expiration Date</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $counter = 1;
                                            @endphp
                                            @foreach ($products as $product)
                                                <tr>
                                                    <td style="text-align: center">{{ $counter++ }}</td>
                                                    <td style="text-align: center">{{ $product->p_name }}</td>
                                                    <td style="text-align: center">{{ $product->stock }}</td>
                                                    <td style="text-align: center">{{ $product->brand }}</td>
                                                    <td style="text-align: center">{{ $product->category->category_name }}
                                                    </td>
                                                    <td style="text-align: center">
                                                        {{ date('M j, Y', strtotime($product->expiration)) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>No expiring items found.</p>
                                @endif
                            </div>
                        </div>

                    </div>
                    <!-- [ Main Content ] end -->
                </div>
            </div>


        @endsection
        @section('scripts')
            <script>
                $(document).ready(function() {
                    $('#filterByDate').on('click', function() {
                        var startDate = new Date($('#startDate').val());
                        var endDate = new Date($('#endDate').val());
                        filterByDateRange(startDate, endDate);
                    });

                    function filterByDateRange(startDate, endDate) {
                        var rows = document.querySelectorAll("table tbody tr");
                        for (var i = 0; i < rows.length; i++) {
                            var rowDateText = rows[i].querySelector("td:nth-child(6)").textContent.trim();
                            var rowDate = new Date(rowDateText);
                            var formattedRowDate = formatDate(rowDate);

                            if (formattedRowDate >= formatDate(startDate) && formattedRowDate <= formatDate(endDate)) {
                                rows[i].style.display = "";
                            } else {
                                rows[i].style.display = "none";
                            }
                        }
                    }

                    function formatDate(date) {
                        var day = date.getDate();
                        var month = date.getMonth() + 1; // Months are 0-based
                        var year = date.getFullYear();
                        return year + "-" + (month < 10 ? "0" : "") + month + "-" + (day < 10 ? "0" : "") + day;
                    }
                });
            </script>
        @endsection
