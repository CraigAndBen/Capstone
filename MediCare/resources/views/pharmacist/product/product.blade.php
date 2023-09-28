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
                                <h5 class="m-b-10">Product List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('pharmacist.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pharmacist.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Product List List</li>
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
                            <h1>Product List</h1>
                        </div>
                        <div class="card-body">
                            <div class="container">

                                <div class="d-flex justify-content-end">
                                    <div class="m-1">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add
                                            Product</button>
                                    </div>
                                </div>
                                <hr>

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
                                        {{ session('info') }}
                                    </div>
                                @endif

                                @if ($products_price->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Product Yet.
                                    </div>
                                @else
                                    <table class="table table-bordered">
                                        <thead class="bg-primary text-light text-center">
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Category Name</th>
                                                <th>Price</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($products as $product)
                                                <tr>
                                                    @foreach ($categories as $category)
                                                        @if ($product->category_id == $category->id)
                                                            <td>{{ ucwords($product->p_name) }}</td>
                                                            <td>{{ ucwords($category->category_name) }}</td>
                                                            @foreach ($products_price as $price)
                                                                @if ($price->product_id == $product->id)
                                                                    <td>{{ ucwords($price->price) }}</td>
                                                                    <td class="text-center">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-primary dropdown-toggle"
                                                                                type="button" data-toggle="dropdown">
                                                                                Actions
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item btn btn-primary"
                                                                                    data-toggle="modal"
                                                                                    data-target="#updateModal"
                                                                                    data-id="{{ json_encode($price->id) }}"
                                                                                    data-product-price="{{ json_encode($price->price) }}"
                                                                                    data-product-name="{{ json_encode($product->id) }}">Update</a>

                                                                                <a class="dropdown-item btn btn-primary"
                                                                                    data-toggle="modal"
                                                                                    data-target="#viewModal"
                                                                                    data-id="{{ json_encode($price->id) }}"
                                                                                    data-product-price="{{ json_encode($price->price) }}"
                                                                                    data-product-name="{{ json_encode($product->id) }}"">View</a>
                                                                                <form method="POST" action="{{ route('pharmacist.product.delete', ['id' => $price->id]) }}">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                    
                                                                                        <button type="submit" class="btn btn-danger dropdown-item ">Delete</button>
                                                                                    </form>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    {{-- <div class="d-flex justify-content-center my-3">
                                        {{ $products_price->links('pagination::bootstrap-4') }}
                                    </div> --}}
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Create modal --}}
                    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Adding Product</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('pharmacist.product.create') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <select class="form-control p-3" id="product" name="product">
                                                        <option>Select Product</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}">
                                                                {{ ucwords($product->p_name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="number" name="price" class="form-control"
                                                        id="floatingInput price" placeholder="Price" />
                                                    <label for="floatingInput">Price</label>
                                                </div>
                                            </div>
                                        </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Create Modal --}}


                    {{-- Update modal --}}
                    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Update Product</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('pharmacist.product.update') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <select class="form-control p-3" id="product_id" name="product_id">
                                                        <option>Select Product</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}">
                                                                {{ ucwords($product->p_name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="hidden" id="id" name="id">
                                                    <input type="number" name="price" class="form-control"
                                                        id="price" placeholder="Price" />
                                                    <label for="floatingInput">Price</label>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Update Modal --}}

                    {{-- Update modal --}}
                    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Patient Information</h2>
                                </div>
                                <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <select class="form-control p-3" id="product_id" name="product_id" disabled>
                                                        <option>Select Product</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}">
                                                                {{ ucwords($product->p_name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="hidden" id="id" name="id">
                                                    <input type="number" name="price" class="form-control"
                                                        id="price" placeholder="Price" disabled/>
                                                    <label for="floatingInput">Price</label>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End Update Modal --}}


                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>


    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {

                $('#updateModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = JSON.parse(button.data('id'));
                    var product_name = JSON.parse(button.data('product-name'));
                    var product_price = JSON.parse(button.data('product-price'));
                    var modal = $(this);

                    modal.find('#id').val(id);
                    modal.find('#product_id').val(product_name);
                    modal.find('#price').val(product_price);
                });

                $('#viewModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = JSON.parse(button.data('id'));
                    var product_name = JSON.parse(button.data('product-name'));
                    var product_price = JSON.parse(button.data('product-price'));
                    var modal = $(this);

                    modal.find('#id').val(id);
                    modal.find('#product_id').val(product_name);
                    modal.find('#price').val(product_price);
                });
            });
        </script>
    @endsection
