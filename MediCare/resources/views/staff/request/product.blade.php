@extends('layouts.inner_staff')

@section('content')
    <!-- [ Main Content ] start -->
    <div class="pc-container pb-3">
        <div class="pc-content ">
            <br>
            <br>
            <br>

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Stock</h3>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <div class="card-body">
                                    <div class="container">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">Product Name</th>
                                                    <th class="text-center"scope="col">Category</th>
                                                    <th class="text-center" scope="col">Quantity</th>
                                                    <th class="text-center" scope="col">Expiration Date</th>
                                                    <th class="text-center" scope="col">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr>
                                                        <td class="text-center">{{ $product->p_name }}</td>
                                                        <td class="text-center">{{ $product->category->category_name }}</td>
                                                        <td class="text-center">{{ $product->stock }}</td>
                                                        <td class="text-center">{{ $product->expiration }}</td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge  {{ $product->status === 'Unavailable' ? 'bg-danger' : 'bg-success' }} text-lg">
                                                                {{ $product->status }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- [ Main Content ] end -->
                        @endsection
