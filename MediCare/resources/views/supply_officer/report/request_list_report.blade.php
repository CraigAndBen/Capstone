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
                <h5>Report Type: <i><b>Request Report</b></i></h5>
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
                <h3><i>Request Table</i></h3>
                <br>
                <table class="table table-bordered">
                    <thead class="bg-primary text-light">
                        <tr>
                            <th>Requester Name</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Quantity</th>
                            <!-- Add more table headers for other product attributes -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr>
                                <td>{{ $request->name_requester }}</td>
                                <td>{{ $request->department }}</td>
                                <td>{{ $request->date }}</td>
                                @foreach ($products as $product)
                                    @if ($product->id === $request->product_id)
                                        <td>{{ $product->p_name }}</td>
                                    @endif
                                @endforeach
                                <td>{{ $request->brand }}</td>
                                <td>{{ $request->quantity }}</td>
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
                <a id="back" href="{{ route('supply_officer.request') }}" class="btn btn-danger">Back</a>
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
