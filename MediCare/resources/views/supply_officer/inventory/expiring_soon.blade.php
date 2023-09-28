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
                                <h5 class="m-b-10">Product List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Product List</li>
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
                        <h3>Expiring Soon</h3>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            
                        @if ($products->count() > 0)
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Stock</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                        <th>Expiration Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $product->p_name }}</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>{{ $product->brand }}</td>
                                            <td>{{ $product->category->category_name }}</td>
                                            <td>{{ $product->expiration }}</td>
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

  