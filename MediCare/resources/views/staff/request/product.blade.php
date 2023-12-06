@extends('layouts.inner_staff')

@section('content')
    <!-- [ Main Content ] start -->
    <div class="pc-container pb-3">
        <div class="pc-content ">
            <br>
            <br>
            <br>

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Stock</h3>
                        </div>
                        <div class="card-body">
                            <div class="container">
                               
                                <table id="stocktable" class="table ">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">Item Name</th>
                                            <th class="text-center"scope="col">Category</th>
                                            <th class="text-center"scope="col">Brand</th>
                                            <th class="text-center" scope="col">Quantity</th>
                                            <th class="text-center" scope="col">Exp Date</th>
                                            <th class="text-center" scope="col">Status</th>
                                            <th class="text-center" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td class="text-center">{{ $product->p_name }}</td>
                                                <td class="text-center">{{ $product->category->category_name }}</td>
                                                <td class="text-center">{{ $product->brand }}</td>
                                                <td class="text-center">{{ $product->stock }}</td>
                                                <td class="text-center">{{ date('M j, Y', strtotime ($product->expiration)) }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge  {{ $product->status === 'Unavailable' ? 'bg-danger' : 'bg-success' }} text-lg">
                                                        {{ $product->status }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($product->status == 'Available')
                                                        <button class="btn btn-info btn-sm"
                                                            onclick="redirectToRequestForm('{{ $product->p_name }}', 
                                                                '{{ $product->brand }}', '{{ $product->date }}')">Request</button>
                                                    @else
                                                        <button class="btn btn-info btn-sm" disabled>Unavailable</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                      
                            </div>

                            <!-- [ Main Content ] end -->
                        @endsection
                        @section('scripts')
                            <script>
                                function redirectToRequestForm(productName, brand, expirationDate) {
                                    // Construct the URL with query parameters using the route function
                                    const url = "{{ route('staff.request_form') }}";
                                    const encodedProductName = encodeURIComponent(productName);
                                    const encodedBrand = encodeURIComponent(brand);

                                    // Get the current date in the user's timezone
                                    const currentDate = new Date();
                                    const offset = currentDate.getTimezoneOffset();
                                    currentDate.setMinutes(currentDate.getMinutes() - offset);

                                    // Format the date as YYYY-MM-DD
                                    const formattedDate = currentDate.toISOString().split('T')[0];
                                    const encodedDate = encodeURIComponent(formattedDate);

                                    const redirectUrl = `${url}?p_name=${encodedProductName}&brand=${encodedBrand}&date=${encodedDate}`;

                                    // Redirect to the request form view with filled input fields
                                    window.location.href = redirectUrl;
                                }
                            </script>
                             <script>
                                $(document).ready( function () {
                                     $('#stocktable').DataTable();
                                 });
                             </script>
                        @endsection
