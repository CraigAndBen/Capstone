@extends('layouts.analytics_report')
@section('style')
    <style>
        @media print {

            /* Hide the button when printing */
            #printButton {
                display: none;
            }

            #back {
                display: none;
            }

            #pay {
                display: none;
            }

        }

        @page {
            size: landscape;
        }

        .page-break {
            page-break-after: always;


        }
    </style>
@endsection
@section('content')
    <div class="page-content container">
        <div class="page-header text-blue-d2">
            <div class="page-tools">
                <div class="action-buttons">
                    <a id="printButton" class="btn bg-white btn-light mx-1px text-95" href="#" data-title="Print">
                        <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                        Print
                    </a>
                </div>
            </div>
        </div>

        <div class="container px-0">
            <div class="row mt-4">
                <div class="col-12 col-lg-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center text-150">
                                <img src="{{ asset('logo.jpg') }}" alt="MediCare" class=""
                                    style="max-width: 160px; max-height: 120px">
                            </div>
                        </div>
                    </div>

                    <hr class="row brc-default-l1 mx-n1 mb-4" />

                    <div class="row">
                        <div class=" text-95 col-sm-6 align-self-start d-sm-flex justify-content-start">
                            <hr class="d-sm-none" />
                            <div class="text-grey-m2">
                                <div class="my-2"><i class="text-xs mr-1"></i> <span class="text-600 text-90">Report Type:
                                    </span>Purchase Receipt</div>
                                <div class="my-2"><i class="text-xs mr-1"></i> <span class="text-600 text-90">Reference:
                                    </span> {{ $reference }}</div>
                                <div class="my-2"><i class="text-xs mr-1"></i> <span class="text-600 text-90">Date:</span>
                                    <span> {{ $currentDate }}</span>
                                </div>
                                <div class="my-2"><i class="text-xs mr-1"></i> <span class="text-600 text-90">Time:</span>
                                    <span> {{ $currentTime }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="row brc-default-l1 mx-n1 mb-4" />
                    
                    <div class="mt-4">
                        <div class="table table-sm row ">
                            <div class="d-none d-sm-block col-2">Item Id</div>
                            <div class="col-9 col-sm-3">Item Name</div>
                            <div class="d-none d-sm-block col-sm-2">Unit Price</div>
                            <div class="d-none d-sm-block col-sm-2">Quantity</div>
                            <div class="col-9 col-sm-3">Sub total</div>
                        </div>

                        <div class="text-95 text-secondary-d3">
                            @foreach ($cart as $key => $item)
                                <div class="row mb-2 mb-sm-0 py-25">
                                    <div class="d-none d-sm-block col-2">{{ $item['product_id'] }}</div>
                                    <div class="col-9 col-sm-3">{{ $item['name'] }}</div>
                                    <div class="d-none d-sm-block col-2">₱{{ number_format($item['price'], 2) }}</div>
                                    <div class="d-none d-sm-block col-2 text-95">{{ $item['quantity'] }}</div>
                                    <div class="col-9 col-sm-3">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                                </div>
                            @endforeach
                        </div>

                        <hr class="row brc-default-l1 mx-n1 mb-4" />
                        
                        <div class="row border-b-2 brc-default-l2 justify-content-last"></div>
                        <div class="row mt-3">
                            <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                            </div>
                            <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                <div class="row my-2">
                                    <div class="col-7 text-right">
                                        Total:
                                    </div>
                                    <div class="col-5">
                                        <span class="text-110 text-secondary-d1">
                                            ₱{{ number_format(array_sum(array_map(function ($item) 
                                                {return $item['price'] * $item['quantity'];}, $cart)), 2) }}</span>
                                    </div>
                                </div>

                                <div class="row my-2">
                                    <div class="col-7 text-right">
                                        Amount:
                                    </div>
                                    <div class="col-5">
                                        <span class="text-110 text-secondary-d1">₱{{ number_format($amount, 2) }}</span>
                                    </div>
                                </div>

                                <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                    <div class="col-7 text-right">
                                        Change:
                                    </div>
                                    <div class="col-5">
                                        <span class="text-110 text-secondary-d1">₱{{ number_format($change, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div>
                            <form method="POST" action="{{ route('cashier.product.purchase.confirm') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="hidden" name="reference" value="{{ $reference }}">
                                            <input type="hidden" name="amount" value="{{ $amount }}">
                                            <input type="hidden" name="change" value="{{ $change }}">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-info btn-bold px-4 float-right mt-3 mt-lg-0"
                                    id="pay">Pay Now</button>
                            </form>
                            <a id="back" href="{{ route('cashier.product.purchase') }}"
                                class="btn btn-danger">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            // Attach a click event handler to the button
            $("#printButton").click(function() {
                // Call the window.print() function to open the print dialog
                window.print();
            });
        });
    </script>
@endsection
