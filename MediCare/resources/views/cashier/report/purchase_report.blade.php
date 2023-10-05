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
                <h5>Report Type: <i><b>Purchase Report</b></i></h5>
                <h5>Date: <i><b>{{ $currentDate }}</b></i></h5>
                <h5>Time: <i><b>{{ $currentTime }}</b></i></h5>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>

        </div>

        <div class="row justify-content-center">
            <div class="col-10 text-center">
                <h3><i>Purchase Table</i></h3>
                <br>
                <table class="table table-bordered">
                    <thead class="bg-primary text-light text-center">
                        <tr>
                            <th>Reference</th>
                            <th>Total Quantity</th>
                            <th>Total Price</th>
                            <th>Amount</th>
                            <th>Change</th>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-1">

            </div>
        </div>
        <div class="row justify-content-end align-items-end my-5">
            <div class="col-10 text-right">
                <button id="printButton" class="btn btn-primary">Preview Report</button>
                <a id="back" href="{{ route('cashier.product.purchase.list') }}" class="btn btn-danger">Back</a>
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
