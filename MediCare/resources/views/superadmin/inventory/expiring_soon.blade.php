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

                                <div class="d-flex mb-3 justify-content-end align-items-center">
                                    <div class="form-group d-flex">
                                        <a href="{{ route('superadmin.product.expiry.report.view') }}" 
                                        class="btn btn-success mr-2" target="_blank">View Report</a>
                                        <form action="{{ route('superadmin.product.expiry.report.download') }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-success" style="margin-left: 10px;" 
                                            target="_blank">Download Report</button>
                                        </form>
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
  


    
