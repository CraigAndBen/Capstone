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
            size: portrait;
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
                <h8>Report Type: <i><b>Item Price Report</b></i></h8>
                <br>
                <h8>Date: <i><b>{{ $currentDate }}</b></i></h8>
                <br>
                <h8>Time: <i><b>{{ $currentTime }}</b></i></h8>
            </div>
            <div class="col-2">

            </div>
            <div class="col-1 my-3">
                <img src="{{ asset('logo.jpg') }}" alt="" class="" style="max-width: 200px; max-height: 160px">
            </div>

        </div>

        <div class="row justify-content-center">
            <div class="col-10 text-center">
                <h3><i>Item Price Table</i></h3>
                <br>
                <table class="table table-bordered">
                    <thead class="bg-primary text-dark text-center">
                        <tr>
                            <th>Item Name</th>
                            <th>Category Name</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($products as $product)
                            <tr>
                                @foreach ($categories as $category)
                                    @foreach ($products_price as $price)
                                        @if ($price->product_id == $product->id)
                                            @if ($product->category_id == $category->id)
                                                <td>{{ ucwords($product->p_name) }}</td>
                                                <td>{{ ucwords($category->category_name) }}</td>
                                            @endif
                                            <td>₱{{ number_format($price->price, 2) }}</td>
                                        @endif
                                    @endforeach
                                @endforeach
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
                <a id="back" href="{{ route('pharmacist.product') }}" class="btn btn-danger">Back</a>
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
