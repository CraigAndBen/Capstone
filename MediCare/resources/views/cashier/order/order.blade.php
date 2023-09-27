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
                                <h5 class="m-b-10">Notification List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Notification List</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">

                <!-- [ sample-page ] start -->
                <div class="col-sm-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
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
                                                        <th>Amoxicillin</th>
                                                        <td>2</td>
                                                        <td>5</td>
                                                        <td>15</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Mefenamic</th>
                                                        <td>5</td>
                                                        <td>5</td>
                                                        <td>25</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="receipt-total">
                                                <p>Total: $40.00</p>
                                                <p>Amount Paid: $50.00</p>
                                                <p>Change: $10.00</p>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success" data-toggle="modal"
                                            data-target="#payModal">Pay</button>
                                        <button type="cancel" class="btn btn-danger">cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Create modal --}}
                <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                {{-- <form method="POST" action="{{ route('product.page') }}"> --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="amount_paid">Amount Paid</label>
                                            <div class="form-floating mb-2">
                                                <input type="number" class="form-control" id="amount_paid" placeholder="">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Payment</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Create Modal --}}
                </div>
            </div>
        </div>


    @endsection

    @section('scripts')

    @endsection
