@extends('layouts.inner_cashier')

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
                                <h5 class="m-b-10">Purchase List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Purchase</li>
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
                            <h1>Purchase </h1>
                        </div>
                        <div class="card-body">
                            <div class="container">

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

                                <div class="row">
                                    <div class="col-md-6">
                                        <h2>Cart Items</h2>
                                        @if ($cart)
                                            @if (count($cart) > 0)
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Product Name</th>
                                                            <th>Price</th>
                                                            <th>Quantity</th>
                                                            <th>Total Price</th>
                                                            <th>Action</th> <!-- Add a new column for the "Clear" button -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($cart as $key => $item)
                                                            <!-- Use $key to identify each item -->
                                                            <tr>
                                                                <td>{{ $item['name'] }}</td>
                                                                <td>${{ $item['price'] }}</td>
                                                                <td>{{ $item['quantity'] }}</td>
                                                                <td>${{ $item['price'] * $item['quantity'] }}</td>
                                                                <td>
                                                                    <form method="POST"
                                                                        action="{{ route('cashier.product.purchase.remove', ['key' => $key]) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-danger btn-sm">Clear</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <p>Total:
                                                    ${{ array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart)) }}
                                                </p>
                                                <form method="POST" action="{{ route('cashier.product.purchase.receipt') }}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="amount">Amount Paid</label>
                                                                <input type="number" name="amount" id="amount" class="form-control"
                                                                    min="1" value="1">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mt-4">
                                                            <button type="submit" class="btn btn-success">Preview Receipt</button>
                                                        </div>
                                                    </div>

 
                                                </form>
                                            @else
                                                <p>Your cart is empty.</p>
                                            @endif
                                        @else
                                            <p>Your cart is empty.</p>
                                        @endif

                                    </div>
                                    <div class="col-md-6">
                                        <h1>Cashier Form</h1>
                                        <form method="POST" action="{{ route('cashier.product.purchase.add') }}">
                                            @csrf
                                            <div class="form-group">
                                                <label for="product_id">Select a Product</label>
                                                <select name="product_id" id="product_id" class="form-control">
                                                    @foreach ($products as $product)
                                                        @foreach ($prices as $price)
                                                            @if ($price->product_id == $product->id)
                                                                <option value="{{ $product->id }}">{{ $product->p_name }}
                                                                    - ₱{{ $price->price }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Quantity</label>
                                                <input type="number" name="quantity" id="quantity" class="form-control"
                                                    min="1" value="1">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Add Product</button>
                                        </form>
                                    </div>
                                </div>
                                {{-- @if ($products_price->isEmpty())
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
                                {{-- @endif --}} --
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
                                                <select class="form-control p-3" id="product_id" name="product_id"
                                                    disabled>
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
                                                <input type="number" name="price" class="form-control" id="price"
                                                    placeholder="Price" disabled />
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
