@extends('layouts.inner_pharmacist')

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
                                <h5 class="m-b-10">Product Details</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('pharmacist.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pharmacist.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Product Details</li>
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
                            <h1>Product Details</h1>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <!-- Button trigger modal -->
                                <div class="d-flex mb-4 justify-content-start">
                                    <a href="{{ route('pharmacist.inventory')}}" type="button" class="btn btn-primary">Back</a>
                                </div>
                                <div class="card-body">
                                    <div class="container">
                                        <table class="table table-bordered">
                                            <thead >
                                                
                                                <tr>
                                                    <th scope="row">Product</th>
                                                    <td>{{ $product->p_name }}</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Category</th>
                                                    <td>{{ $product->category->category_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Brand</th>
                                                    <td>{{ $product->brand }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Available Stock</th>
                                                    <td>{{ $product->stock }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Status</th>
                                                    <td>{{ $product->status }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Description</th>
                                                    <td>{{ $product->description }}</td>
                                                </tr>
                                               
                                            </tbody>
                                        </table>
                                    </div>
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

    @endsection
