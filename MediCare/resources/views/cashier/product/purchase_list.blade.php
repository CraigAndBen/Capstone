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
                                <h5 class="m-b-10">Purchase Transaction</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Purchase Transaction</li>
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
                            <h1>Purchase Transaction</h1>
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
                                        <span class="fa fa-check-circle"></span> {{ session('info') }}
                                    </div>
                                @endif

                                @if ($purchases->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Notification Yet.
                                    </div>
                                @else
                                    <table class="table table-bordered">
                                        <thead class="bg-primary text-light text-center">
                                            <tr>
                                                <th>Reference</th>
                                                <th>Total Quantity</th>
                                                <th>Total Price</th>
                                                <th>Amount</th>
                                                <th>Change</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($purchases as $purchase)
                                                <tr class="p-3">
                                                    <td>{{ $purchase->reference }}</td>
                                                    <td>{{ $purchase->total_quantity }}</td>
                                                    <td>₱{{ $purchase->total_price }}</td>
                                                    <td>₱{{ $purchase->amount }}</td>
                                                    <td>₱{{ $purchase->change }}</td> 
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                                data-toggle="dropdown">
                                                                Actions
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item btn btn-primary" data-toggle="modal"
                                                                    data-target="#viewModal"
                                                                    data-id="{{ json_encode($purchase->id) }}"
                                                                    data-reference="{{ json_encode(ucwords($purchase->reference)) }}"
                                                                    data-total-quantity="{{ json_encode($purchase->total_quantity) }}"
                                                                    data-total-price="{{ json_encode($purchase->total_price) }}"
                                                                    data-amount="{{ json_encode($purchase->amount) }}"
                                                                    data-change="{{ json_encode($purchase->change) }}">View</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center my-3">
                                        {{ $purchases->links('pagination::bootstrap-4') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>


                    {{-- View modal --}}
                    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Patient Information</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <div class="form-floating mb-3">
                                                    <input type="number" name="reference" class="form-control"
                                                        id="reference" placeholder="Reference" disabled />
                                                    <label for="floatingInput">Reference</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="number" name="total_quantity" class="form-control"
                                                    id="total_quantity" placeholder="Total Quantity" disabled />
                                                <label for="floatingInput">Total Quantity</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="number" name="total_price" class="form-control"
                                                    id="total_price" placeholder="Total Price" disabled />
                                                <label for="floatingInput">Total Price</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <div class="form-floating mb-3">
                                                    <input type="number" name="amount" class="form-control" id="amount"
                                                        placeholder="Amount" disabled />
                                                    <label for="floatingInput">Amount</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="number" name="change" class="form-control" id="change"
                                                    placeholder="Change" disabled />
                                                <label for="floatingInput">Change</label>
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
                    {{-- End View Modal --}}


                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>


    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {

                $('#viewModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = JSON.parse(button.data('id'));
                    var reference = JSON.parse(button.data('reference'));
                    var total_quantity = JSON.parse(button.data('total-quantity'));
                    var total_price = JSON.parse(button.data('total-price'));
                    var amount = JSON.parse(button.data('amount'));
                    var change = JSON.parse(button.data('change'));
                    var modal = $(this);

                    modal.find('#id').val(id);
                    modal.find('#reference').val(reference);
                    modal.find('#total_quantity').val(total_quantity);
                    modal.find('#total_price').val(total_price);
                    modal.find('#amount').val(amount);
                    modal.find('#change').val(change);
                });
            });
        </script>
    @endsection
