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
                                    <div class="alert alert-warning">
                                        <span class="fa fa-xmark-circle"></span> {{ session('info') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                <div class="alert alert-danger">
                                    <span class="fa fa-xmark-circle"></span> {{ session('error') }}
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
                                                            <th>Item Name</th>
                                                            <th>Unit Price</th>
                                                            <th>Quantity</th>
                                                            <th>Sub total</th>
                                                            <th>Action</th> <!-- Add a new column for the "Clear" button -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($cart as $key => $item)
                                                            <!-- Use $key to identify each item -->
                                                            <tr>
                                                                <td>{{ $item['name'] }}</td>
                                                                <td>₱{{ number_format($item['price'], 2) }}</td></td>
                                                                <td>{{ $item['quantity'] }}</td>
                                                                <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
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
                                                <p>Total: ₱{{ number_format(array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart)), 2) }}</p>
                                                <form method="POST" action="{{ route('cashier.product.purchase.receipt.preview') }}" >
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
                                                <label for="product_id">Select a Item</label>
                                                <select name="product_id" id="product_id" class="form-control">
                                                    @foreach ($products as $product)
                                                        @foreach ($prices as $price)
                                                            @if ($price->product_id == $product->id)
                                                                <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">
                                                                    {{ $product->p_name }} - ₱{{ number_format($price->price, 2) }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Quantity</label>
                                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-placement="left" 
                                                     id="stock-tooltip" data-delay="{ 'show': 100, 'hide': 100 }"></i>
                                                <input type="number" name="quantity" id="quantity" class="form-control"
                                                    min="1" value="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');"> 
                                            </div>

                                            <button type="submit" class="btn btn-primary">Add Item</button>
                                        </form> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Create modal --}}
                    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Adding Item</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('pharmacist.product.create') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <select class="form-control p-3" id="product" name="product">
                                                        <option>Select Item</option>
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
                                    <h2 class="modal-title text-light" id="myModalLabel">Update Item</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('pharmacist.product.update') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <select class="form-control p-3" id="product_id" name="product_id">
                                                        <option>Select Item</option>
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
                                                    <option>Select Item</option>
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
        <script>
            $(document).ready(function() {
                // Define a function to update the stock information in the tooltip
                function updateStockTooltip() {
                    var productId = $("#product_id").val();
                    var selectedOption = $("#product_id option:selected");
                    var stock = selectedOption.data("stock");
                    var stockInfo = "Available Stock: " + stock;
        
                    // Set the stock information as the tooltip title
                    $("#stock-tooltip").attr("title", stockInfo);
                }
        
                // Initialize the updateStockTooltip function on page load
                updateStockTooltip();
        
                // Call the updateStockTooltip function whenever the product selection changes
                $("#product_id").on("change", function() {
                    updateStockTooltip();
                });
            });
        </script>   
    @endsection
