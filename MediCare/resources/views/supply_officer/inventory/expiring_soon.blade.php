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
                                <h5 class="m-b-10">Expiring Items</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Dashboard</a></li>
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
                                    <div class="form-group d-flex">
                                        <a href="{{ route('supply_officer.product.expiry.report.view') }}"
                                            class="btn btn-success btn-sm" target="_blank">View Report</a>
                                        <form action="{{ route('supply_officer.product.expiry.report.download') }}" method="GET">
                                            @csrf
                                            <button class="btn btn-success btn-sm" style="margin-left: 10px;"  target="_blank">Download Report</button>
                                        </form>
                                    </div>
                                </div>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input. Please fix the following errors: <br>
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

                                @if ($products->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Expiring Item Yet.
                                    </div>
                                @else

                                    <table class="table table-hover table-responsive-sm">
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
                                                    <td style="text-align: center">{{ $product->category->category_name }} </td>
                                                    <td style="text-align: center">{{ date('M j, Y', strtotime($product->expiration)) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                @endif <!-- End of $categories->isEmpty() check -->
                            </div>
                        </div>
                    </div>
                    <!-- [ Main Content ] end -->
                </div>
            </div>
        </div>
    </div>
@endsection
