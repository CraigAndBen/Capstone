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
                                <h5 class="m-b-10">Expiring Soon List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Expiring Soon List</li>
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
                        <h3>Expiring Products</h3>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row mb-3 justify-content-end">
                                <div class="col-md-2">
                                    <input type="date" id="startDate" class="form-control" placeholder="Start Date">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" id="endDate" class="form-control" placeholder="End Date">
                                </div>
                                <div class="col-md-2">
                                    <button id="filterByDate" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                            
                               
                            
                        @if ($products->count() > 0)
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">#</th>
                                        <th style="text-align: center">Product Name</th>
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
                                            <td style="text-align: center">{{ $product->category->category_name }}</td>
                                            <td style="text-align: center">{{ $product->expiration }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <p>No expiring products found.</p>
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
                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();
                    filterByDateRange(startDate, endDate);
                });

                function filterByDateRange(startDate, endDate) {
                    var rows = document.querySelectorAll("table tbody tr");
                    for (var i = 0; i < rows.length; i++) {
                        var rowDate = rows[i].querySelector("td:nth-child(6)").textContent.trim();
                        if (rowDate >= startDate && rowDate <= endDate) {
                            rows[i].style.display = "";
                        } else {
                            rows[i].style.display = "none";
                        }
                    }
                }
            });
        </script>
    @endsection