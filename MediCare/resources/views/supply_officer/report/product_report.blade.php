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
                <h8>Report Type: <i><b>Item Report</b></i></h8>
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
                <h3><i>Item Table</i></h3>
                <br>
                <table class="table table-bordered">
                    <thead class="bg-primary text-dark">
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Brand</th>
                            <th>Expiration Date</th>
                            <!-- Add more table headers for other product attributes -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->p_name }}</td>
                                @foreach ($categories as $category)
                                    @if ($category->id === $product->category_id)
                                        <td>{{ $category->category_name }}</td>
                                    @endif
                                @endforeach
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->brand }}</td>
                                <td>{{ date('M j, Y', strtotime($product->expiration)) }}</td>
                                <!-- Add more table cells for other product attributes -->
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
                <a id="back" href="{{ route('supply_officer.product') }}" class="btn btn-danger">Back</a>
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
