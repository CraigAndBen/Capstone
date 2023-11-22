@extends('layouts.inner_staff')

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
                                <h5 class="m-b-10">Request Form</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Request Form</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <div class="row">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Request Form</h4>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <div class="row justify-content-start">
                                    <div class="col-md-12">
                                        <form method="POST" action="{{ route('staff.request') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label>Name of Requester</label>
                                                    <input type="text" class="form-control" name="name_requester"
                                                        placeholder="Enter Name" required
                                                        oninvalid="this.setCustomValidity('Please Enter a Name.')"
                                                        oninput="setCustomValidity('')">
                                                    @error('name_requester')
                                                        <div class="alert alert-danger" role="alert">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="department">Department</label>
                                                    <select id="department" name="department"class="form-control dept"
                                                        required
                                                        oninvalid="this.setCustomValidity('Please select a department.')"
                                                        oninput="setCustomValidity('')">
                                                        <option value="">Choose...</option>
                                                        <option value="CSR">CSR</option>
                                                        <option value="ER">ER</option>
                                                        <option value="OR">OR</option>
                                                        <option value="Nursing Service (Private)">Nursing Service (Private)
                                                        </option>
                                                        <option value="Nursing Service (Ward)">Nursing Service (Ward)
                                                        </option>
                                                        <option value="Laboratory">Laboratory</option>
                                                    </select>
                                                    @error('department')
                                                        <div class="alert alert-danger" role="alert">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Date</label>
                                                    <input type="date" class="form-control" id="date" name="date"
                                                        value="{{ isset($_GET['date']) ? $_GET['date'] : '' }}" required
                                                        oninvalid="this.setCustomValidity('Please input a date.')"
                                                        oninput="setCustomValidity('')">
                                                    @error('date')
                                                        <div class="alert alert-danger" role="alert">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div id="dynamicRows">
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="product_id">Item Name</label>
                                                        <select class="form-control" name="product_id" id="product_id"
                                                            required
                                                            oninvalid="this.setCustomValidity('Please select a item.')"
                                                            oninput="setCustomValidity('')">
                                                            <option value="">Select a Item</option>
                                                            @foreach ($products as $product)
                                                                @if ($product->status == 'Available')
                                                                    <option value="{{ $product->id }}"
                                                                        {{ isset($_GET['p_name']) && $_GET['p_name'] == $product->p_name ? 'selected' : '' }}>
                                                                        {{ $product->p_name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @error('product_id')
                                                            <div class="alert alert-danger" role="alert">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="brand">Brand</label>
                                                        <select class="brand form-control" name="brand" id="brand"
                                                            required
                                                            oninvalid="this.setCustomValidity('Please select a brand.')"
                                                            oninput="setCustomValidity('')">
                                                            <option value="">Select Brand</option>
                                                            @foreach ($products->unique('brand') as $product)
                                                                <option value="{{ $product->brand }}"
                                                                    {{ isset($_GET['brand']) && $_GET['brand'] == $product->brand ? 'selected' : '' }}>
                                                                    {{ $product->brand }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('brand')
                                                            <div class="alert alert-danger" role="alert">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="quantity">Quantity</label>
                                                        <input type="number" class="form-control" name="quantity"
                                                            placeholder="Enter Quantity" required
                                                            oninvalid="this.setCustomValidity('Please input a quantity.')"
                                                            oninput="setCustomValidity('')">
                                                        @error('quantity')
                                                            <div class="alert alert-danger" role="alert">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-1">
                                                        <div class="form-group col-md-4">
                                                            <!-- Adjust the width as needed -->
                                                        </div>
                                                        <div class="form-group col-md-8">
                                                            <a href="#" class="btn btn-info addRow">+</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </form>
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
            $(document).ready(function() {

                $('#viewModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = JSON.parse(button.data('id'));
                    var title = JSON.parse(button.data('title'));
                    var message = JSON.parse(button.data('message'));
                    var date = JSON.parse(button.data('date'));
                    var time = JSON.parse(button.data('time'));
                    var is_read = JSON.parse(button.data('is-read'));
                    var modal = $(this);

                    modal.find('#id').val(id);
                    modal.find('#title').text(title);
                    modal.find('#message').text(message);
                    modal.find('#date').text(date);
                    modal.find('#time').text(time);
                    modal.find('#is_read').val(is_read);
                });
            });
        </script>
        <script>
           // Add row
$(".addRow").click(function() {
    var html = '<div class="row">' +
        '<div class="form-group col-md-4">' +
        '<label for="product_id">Item Name</label>' +
        '<select class="form-control" name="additional_product_ids[]" required>' +
        '<option value="">Select a Item</option>' +
        '@foreach ($products as $product)' +
        '@if ($product->status == "Available")' +
        '<option value="{{ $product->id }}">{{ $product->p_name }}</option>' +
        '@endif' +
        '@endforeach' +
        '</select>' +
        '</div>' +
        '<div class="form-group col-md-4">' +
        '<label for="brand">Brand</label>' +
        '<select class="form-control" name="additional_brands[]" required>' +
        '<option value="">Select a Brand</option>' +
        '@foreach ($products->unique("brand") as $product)' +
        '<option value="{{ $product->brand }}">{{ $product->brand }}</option>' +
        '@endforeach' +
        '</select>' +
        '</div>' +
        '<div class="form-group col-md-3">' +
        '<label for="quantity">Quantity</label>' +
        '<input type="number" class="form-control" name="additional_quantities[]" placeholder="Enter Quantity" required>' +
        '</div>' +
        '<div class="form-group col-md-1">' +
        '<div class="form-group col-md-4"></div>' +
        '<div class="form-group col-md-8">' +
        '<a href="#" class="btn btn-danger removeRow">-</a>' +
        '</div>' +
        '</div>' +
        '</div>';

    $("#dynamicRows").append(html);

    // Save rows to local storage
    saveRowsToLocalStorage();
});

        </script>
    @endsection
