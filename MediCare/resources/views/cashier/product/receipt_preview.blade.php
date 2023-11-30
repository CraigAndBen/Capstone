@extends('layouts.inner_cashier')

@section('content')
    <div class="pc-container pb-3">
        <div class="pc-content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h1>Purchase</h1>
                                <div class="page-tools">
                                    <div class="action-buttons">
                                        <form method="POST" action="{{ route('cashier.product.purchase.receipt') }}"
                                            id="printForm" target="_blank">
                                            @csrf
                                            <div class="form-group">
                                                <input type="hidden" name="reference" value="{{ $reference }}">
                                                <input type="hidden" name="amount" value="{{ $amount }}">
                                                <input type="hidden" name="change" value="{{ $change }}">
                                            </div>
                                            <button type="submit"
                                                class="btn bg-outline-dark btn-outline-dark mx-1px text-95">
                                                <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                                                Print
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
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

                                <div class="page-content ">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="text-center text-150">
                                                    <img src="{{ asset('logo.jpg') }}" alt="MediCare" style="width: 140px; height: 100px;">
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="row brc-default-l1 mx-n1 mb-4" />

                                        <div class="row">
                                            <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-start">
                                                <hr class="d-sm-none" />
                                                <div class="text-grey-m2">
                                                    <div class="my-2"><i class="text-xs mr-1"></i> <span
                                                            class="text-600 text-90">Report Type:
                                                        </span>Purchase Receipt</div>
                                                    <div class="my-2"><i class="text-xs mr-1"></i> <span
                                                            class="text-600 text-90">Reference:
                                                        </span> {{ $reference }}</div>
                                                    <div class="my-2"><i class="text-xs mr-1"></i> <span
                                                            class="text-600 text-90">Date:</span>
                                                        <span> {{ date('M j, Y', strtotime($currentDate)) }}</span>
                                                    </div>
                                                    <div class="my-2"><i class="text-xs mr-1"></i> <span
                                                            class="text-600 text-90">Time:</span>
                                                        <span> {{ $currentTime }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="row brc-default-l1 mx-n1 mb-4" />
                                        
                                        <div class="text-center">
                                            <div class="table table-sm row">
                                                <div class="col-sm-3">Item Name</div>
                                                <div class="col-sm-3">Unit Price</div>
                                                <div class="col-sm-3">Quantity</div>
                                                <div class="col-sm-3">Sub total</div>
                                            </div>
                                        
                                            <div class="table table-sm row">
                                                @foreach ($cart as $key => $item)
                                                    <div class="col-sm-3">{{ $item['name'] }}</div>
                                                    <div class="col-sm-3">₱{{ number_format($item['price'], 2) }}</div>
                                                    <div class="col-sm-3">{{ $item['quantity'] }}</div>
                                                    <div class="col-sm-3">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        

                                            <hr class="row brc-default-l1 mx-n1 mb-4" />
                                        
                                            
                                            <div class="row border-b-2 brc-default-l2 justify-content-last"></div>
                                                <div class="row mt-3">
                                                    <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0"></div>
                                                    <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                                        <div class="row my-2">
                                                            <div class="col-7 text-right"></div>
                                                            <div class="col-5">
                                                                <span >Total: </span>
                                                                <span class="text-110 text-secondary-d1">
                                                                    ₱{{ number_format(array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart),), 2, ) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row my-2">
                                                            <div class="col-7 text-right">
                                                                
                                                            </div>
                                                            <div class="col-5">
                                                                <span >Amount: </span>
                                                                <span class="text-110 text-secondary-d1">₱{{ number_format($amount, 2) }}</span>
                                                            </div>
                                                        </div>                   
                                                        <div class="row my-2 ">
                                                            <div class="col-7 text-right">
                                                                
                                                            </div>
                                                            <div class="col-5">
                                                                <span >Change: </span>
                                                                <span class="text-110 text-secondary-d1">₱{{ number_format($change, 2) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <hr class="row brc-default-l1 mx-n1 mb-4" />

                                            <div class="row ml-6">
                                                <div class="col-md-6 mb-2">
                                                    <form method="POST" action="{{ route('cashier.product.purchase.confirm') }}">
                                                        @csrf
                                                        <input type="hidden" name="reference" value="{{ $reference }}">
                                                        <input type="hidden" name="amount" value="{{ $amount }}">
                                                        <input type="hidden" name="change" value="{{ $change }}">
                                                        <button type="submit" class="btn btn-info btn-bold px-4">Pay Now</button>
                                                        <a id="back" href="{{ route('cashier.product.purchase') }}" class="btn btn-danger">Back</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function submitReceiptForm() {
            document.getElementById('printForm').submit();
        }
    </script>
@endsection
