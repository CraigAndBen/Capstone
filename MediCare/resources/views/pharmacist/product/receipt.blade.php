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
    <div class="container mt-2">
        <div class="row justify-content-first align-items-first my-3">
            <div class="col-7 my-4">
                <h5>Report Type: <i><b>Purchase Receipt</b></i></h5>
                <h5>Reference: <i><b>{{ $reference }}</b></i></h5>
                <h5>Date: <i><b>{{ $currentDate }}</b></i></h5>
                <h5>Time: <i><b>{{ $currentTime }}</b></i></h5>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>

        </div>
        <div style="height: 80px"></div>
        <div class="row justify-content-center">
            <div class="col-7">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Id</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $key => $item)
                            <!-- Use $key to identify each item -->
                            <tr>
                                <td>{{ $item['product_id'] }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>${{ $item['price'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>${{ $item['price'] * $item['quantity'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p>Total:
                    ${{ array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart)) }}
                </p>
                <p>Amount:
                    {{ $amount }}
                </p>
                <p>Change:
                    {{ $change }}
                </p>
            </div>
            <div class="col-1">

            </div>
        </div>

        <div class="row justify-content-end align-items-end my-5">
            <div class="col-10 text-right">
                <button id="printButton" class="btn btn-primary">Preview Report</button>
                <form method="POST" action="{{ route('pharmacist.product.purchase.confirm') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="reference" value="{{ $reference }}">
                                <input type="hidden" name="amount" value="{{ $amount }}">
                                <input type="hidden" name="change" value="{{ $change }}">
                            </div>
                        </div>
                        <div class="col-md-6 mt-4">
                            <button type="submit" class="btn btn-success">Confirm Payment</button>
                        </div>
                    </div>
                </form>
                <a id="back" href="{{ route('pharmacist.product.purchase') }}" class="btn btn-danger">Back</a>
            </div>
            <div class="col-2">
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
