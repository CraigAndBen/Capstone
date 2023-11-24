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

                                <div class="d-flex justify-content-end">
                                    <div class="m-1 form-group">
                                        <a href="{{ route('cashier.purchase.report.view') }}" 
                                        class="btn btn-success" target="_blank">View Report</a>
                                        <a href="{{ route('cashier.purchase.report.download') }}" 
                                        class="btn btn-success" target="_blank">Download Report</a>
                                    </div>
                                </div>
                                <br>    
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
                                    <table id="purchaseListtable" class="table table-bordered responsive">
                                        <thead class="bg-primary text-light text-center">
                                            <tr>
                                                <th class="text-center">Reference</th>
                                                <th class="text-center">Total Quantity</th>
                                                <th class="text-center">Total Price</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center">Change</th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($purchases as $purchase)
                                                <tr class="p-3">
                                                    <td>{{ $purchase->reference }}</td>
                                                    <td>{{ $purchase->total_quantity }}</td>
                                                    <td>₱{{ number_format($purchase->total_price, 2) }}</td>
                                                    <td>₱{{ number_format($purchase->amount, 2) }}</td>
                                                    <td>₱{{ number_format($purchase->change, 2) }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                            data-target="#viewModal{{ $purchase->id }}">
                                                            View Receipt
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>


                    {{-- View modal --}}
                
                    @foreach ($purchases as $purchase)
                    <div class="modal fade" id="viewModal{{ $purchase->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Purchase Receipt</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="container px-0">
                                        <div class="row mt-4">
                                            <div class="col-12 col-lg-12">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="text-center text-150">
                                                            <img src="{{ asset('logo.jpg') }}" alt="MediCare"
                                                                class="" style="max-width: 160px; max-height: 120px">
                                                        </div>
                                                    </div>
                                                </div>
                    
                                                <hr class="row brc-default-l1 mx-n1 mb-4" />
                                                
                                                <div class="row">
                                                    <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-start">
                                                        <hr class="d-sm-none" />
                                                        <div class="text-grey-m2">
                                                            <div class="my-2"><i class="text-xs mr-1"></i> 
                                                                <span class="text-600 text-90">Report Type:
                                                                </span>Purchase Receipt</div>
                                                            <div class="my-2"><i class="text-xs mr-1"></i> 
                                                                <span class="text-600 text-90">Reference:
                                                                </span> {{ $purchase->reference }}</div>
                                                            <div class="my-2"><i class="text-xs mr-1"></i> 
                                                                <span class="text-600 text-90">Date:</span>
                                                                <span> {{ date('M j, Y', strtotime($purchase->created_at->toDateString())) }}</span>
                                                            </div>
                                                            <div class="my-2"><i class="text-xs mr-1"></i> 
                                                                <span class="text-600 text-90">Time:</span>
                                                                <span> {{ $purchase->created_at->format('h:i A') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                    
                                                <hr class="row brc-default-l1 mx-n1 mb-6" />
                    
                                                <div class="mt-6">
                                                    <div class="table table-sm row">
                                                        <div class="d-none d-sm-block col-2 text-center">Item Id</div>
                                                        <div class="col-9 col-sm-3 text-center">Item Name</div>
                                                        <div class="d-none d-sm-block col-sm-2 text-center">Unit Price</div>
                                                        <div class="d-none d-sm-block col-sm-2 text-center">Quantity</div>
                                                        <div class="col-9 col-sm-3 text-center">Sub total</div>
                                                    </div>
                    
                                                    <div class="table table-sm row">
                                                        @foreach ($purchaseDetails as $purchaseDetail)
                                                            @if ($purchaseDetail->reference === $purchase->reference)
                                                                <div class="d-none d-sm-block col-2 text-center">{{ $purchaseDetail->product_id }}</div>
                                                                <div class="col-9 col-sm-3 text-center">{{ $purchaseDetail->product->p_name }}</div>
                                                                <div class="d-none d-sm-block col-sm-2 text-center">₱{{ $purchaseDetail->price }}</div>
                                                                <div class="d-none d-sm-block col-sm-2 text-center">{{ $purchaseDetail->quantity }}</div>
                                                                <div class="col-9 col-sm-3 text-center">₱{{ number_format($purchaseDetail['price'] * $purchaseDetail['quantity'], 2) }}</div>
                                                            @endif
                                                        @endforeach                                                   
                                                    </div>
                    
                                                    <hr class="row brc-default-l1 mx-n1 mb-4" />
                    
                                                    <div class="row border-b-2 justify-content-last"></div>
                                                    <div class="row mt-3">
                                                        <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                                                            <!-- Left column content, if any -->
                                                        </div>
                                                        <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                                            <div class="row my-2">
                                                                <div class="col-7">
                                                                    <span>Total: </span>
                                                                </div>
                                                                <div class="col-5">
                                                                    <span class="text-110 text-secondary-d1">
                                                                        ₱{{ number_format($purchase->total_price, 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="row my-2">
                                                                <div class="col-7">
                                                                    <span>Amount: </span>
                                                                </div>
                                                                <div class="col-5">
                                                                    <span class="text-110 text-secondary-d1">
                                                                        ₱{{ number_format($purchase->amount, 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="row my-2">
                                                                <div class="col-7">
                                                                    <span>Change: </span>
                                                                </div>
                                                                <div class="col-5">
                                                                    <span class="text-110 text-secondary-d1">
                                                                        ₱{{ number_format($purchase->change, 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                </div>
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
                    @endforeach
                   
                    
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
                $('#purchaseListtable').DataTable();
            });
        </script>
    @endsection
