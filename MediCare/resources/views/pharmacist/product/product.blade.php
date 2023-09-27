@extends('layouts.inner_pharmacist')

@section('content')
    <!-- [ Main Content ] start -->
    <div class="pc-container pb-2">
        <div class="pc-content ">
            <br>
            <br>
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <div class="receipt-container">
                                    <div class="receipt-header">
                                        <h4>Receipt</h4>
                                        <p>Date: August 28, 2023</p>
                                        <p>Reference: 478124816</p>
                                    </div>
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th scope="col">Item</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="receipt-total">
                                        <p></p>

                                    </div>
                                </div>
                                {{-- <button type="submit" class="btn btn-success"
                                    href="{{ route('cashier.page') }}">Confirm</button> --}}

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <form method="POST" action="">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="mb-2">
                                                <label for="item">Item</label>
                                                <select class="form-control ml-2 " id="p_name" name="p_name">
                                                    <option disabled selected>Select an item</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->p_name }}">{{ $product->p_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="quantity">Quantity</label>
                                                <input type="number" class="form-control ml-2" id="quantity"
                                                    placeholder="" name="quantity" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="price">Price</label>
                                                <input type="number" class="form-control ml-2" id="price"
                                                    placeholder="" name="price" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
