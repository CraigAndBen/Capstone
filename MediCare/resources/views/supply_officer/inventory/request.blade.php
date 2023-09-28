@extends('layouts.inner_supplyofficer')

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
                                <h5 class="m-b-10">Request List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Request List</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">

                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h1>Product List</h1>
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
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> {{ session('info') }}
                                    </div>
                                @endif

                                @if ($requests->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Request Yet.
                                    </div>
                                @else
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Department</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $counter = 1;
                                            @endphp
                                            @foreach ($requests as $request)
                                                <tr data-toggle="modal" data-target="#viewModal{{ $request->id }}">
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $request->name_requester }}</td>
                                                    <td>{{ $request->department }}</td>
                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center my-3">
                                        {{ $requests->links('pagination::bootstrap-4') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- View modal --}}
                    @foreach ($requests as $request)
                        <div class="modal fade" id="viewModal{{ $request->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h2 class="modal-title text-light" id="myModalLabel">Request</h2>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="{{ route('staff.request') }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="name_of_requester">Name of requester</label>
                                                        <input type="text" class="form-control" id="name_requester"
                                                            name="name_requester" value="{{ $request->name_requester }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="department">Department</label>
                                                        <input type="text" class="form-control" id="department"
                                                            name="department" value="{{ $request->department }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="date">Date</label>
                                                        <input type="text" class="form-control" id="date"
                                                            name="date" value="{{ $request->date }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="product_name">Product Name</label>
                                                        <input type="text" class="form-control" id="product_id"
                                                            name="product_id[]" value="{{ $request->product->p_name }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="brand">Brand</label>
                                                        <input type="text" class="form-control" id="brand"
                                                            name="brand[]" value="{{ $request->brand }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="qantity"> Quantity</label>
                                                        <input type="text" class="form-control" id="quantity"
                                                            name="quantity[]" value="{{ $request->quantity }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">

                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{-- End View Modal --}}
                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>


    @endsection
