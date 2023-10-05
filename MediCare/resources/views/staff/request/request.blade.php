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
                                <h5 class="m-b-10">Request</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Request</li>
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
                                                    <select id="name_requester" name="name_requester" class="form-control n_requester">
                                                        <option selected disabled>Choose...</option>
                                                        <option value="Angela">Angela</option>
                                                        <option value="Arjay">Arjay</option>
                                                        <option value="Jeremy">Jeremy</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="department">Department</label>
                                                    <select id="department" name="department"class="form-control dept">
                                                        <option selected disabled>Choose...</option>
                                                        <option value="CSR">CSR</option>
                                                        <option value="Pharmacy">Pharmacy</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Date</label>
                                                    <input type="date" class="form-control" id="date" name="date"
                                                        value="{{ isset($_GET['date']) ? $_GET['date'] : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="product_id">Product Name</label>
                                                    <select class="form-control" name="product_id" id="product_id">
                                                        <option selected disabled>Select a Product</option>
                                                        @foreach ($products as $product)
                                                            @if ($product->status == 'Available')
                                                                <option value="{{ $product->id }}"
                                                                    {{ isset($_GET['p_name']) && $_GET['p_name'] == $product->p_name ? 'selected' : '' }}>
                                                                    {{ $product->p_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>                                                
                                                <div class="form-group col-md-4">
                                                    <label for="brand">Brand</label>
                                                    <select class="brand form-control" name="brand" id="brand">
                                                        <option selected disabled>Select Brand</option>
                                                        <option value="Pfizer"
                                                            {{ isset($_GET['brand']) && $_GET['brand'] == 'Pfizer' ? 'selected' : '' }}>
                                                            Pfizer</option>
                                                        <option value="3M"
                                                            {{ isset($_GET['brand']) && $_GET['brand'] == '3M' ? 'selected' : '' }}>
                                                            3M</option>
                                                        <option value="Becton, Dickinson and Company"
                                                            {{ isset($_GET['brand']) && $_GET['brand'] == 'Becton, Dickinson and Company' ? 'selected' : '' }}>
                                                            Becton, Dickinson
                                                            and
                                                            Company</option>
                                                        <option value="Cintas"
                                                            {{ isset($_GET['brand']) && $_GET['brand'] == 'Cintas' ? 'selected' : '' }}>
                                                            Cintas</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="quantity">Quantity</label>
                                                    <input type="number" class="form-control" name="quantity"
                                                        placeholder="Enter Quantity">
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
            $(document).ready(function() {

                $(".n_requester").select2({
                    width: '100%',
                    height: '80%',
                    placeholder: 'Choose Product',
                    tags: true
                });
                $(".dept").select2({
                    width: '100%',
                    placeholder: 'Select Brand',
                    tags: true
                });
            });
        </script>
    @endsection
   