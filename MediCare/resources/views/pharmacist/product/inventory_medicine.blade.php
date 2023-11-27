@extends('layouts.inner_pharmacist')

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
                                <h5 class="m-b-10">Medicine List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('pharmacist.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pharmacist.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Medicine List</li>
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
                            <h1>Medicine List</h1>
                        </div>
                        <div class="card-body">
                            <div class="container">

                                <div class="d-flex mb-3 justify-content-end">
                                    <div class="form-group d-flex">
                                        <a href="{{ route('pharmacist.medicine.report.view') }}" 
                                        class="btn btn-success mr-2" target="_blank">View Report</a>
                                        <form action="{{ route('pharmacist.medicine.report.download') }}" method="GET">
                                            @csrf
                                        <button class="btn btn-success" style="margin-left: 10px;" target="_blank">Download Report</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="d-flex mb-3 justify-content-end">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#createMedicine">Add Item</button>
                                </div>

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

                                @if ($products->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Item Yet.
                                    </div>
                                @else
                                <table id="medicinetable" class="table table-bordered">
                                    <thead class="bg-primary text-light text-center">
                                        <tr>
                                            <th class="text-center">Item Name</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Stock Available</th>
                                            <th class="text-center">Brand</th>
                                            <th class="text-center">Expiration Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach ($products as $product)
                                            <tr>
                                                <td class="text-center">{{ $product->p_name }}</td>
                                                <td class="text-center">{{ $product->category->category_name }}</td>
                                                <td class="text-center">{{ $product->stock }}</td>
                                                <td class="text-center">{{ $product->brand }}</td>
                                                <td class="text-center">{{ date('M j, Y', strtotime($product->expiration)) }}</td>
                                                <td class="text-center">{{ $product->status }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('pharmacist.medicine.details', $product->id) }}"><i class="bi bi-eye-fill"></i></a>
                                                    <a type="icon" class="icon-trigger editIcon" data-toggle="modal"
                                                        data-target="#updateMedicine{{ $product->id }}" href="">
                                                        <i class="bi bi-pencil-fill"></i></a>
                                                    <a href="{{ url('/pharmacist/inventory_medicine/delete/' . $product->id) }}"><i
                                                            class="bi bi-trash-fill"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                @endif
                            </div>
                        </div>
                    </div>


                    {{-- Create modal --}}
                    <div class="modal fade" id="createMedicine" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Add Item</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('pharmacist.medicine.create') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Item</label>
                                                <div class="form-floating mb-3">
                                                    <select class="form-control p-3 p_name" name="p_name" required
                                                        oninvalid="this.setCustomValidity('Please input a item.')"
                                                        oninput="setCustomValidity('')">
                                                        <option value="">Select Item</option>
                                                        @foreach ($products as $product)
                                                            @foreach ($categories as $category)
                                                                @if ($product->category_id == $category->id)
                                                                    <option value="{{ $product->id }}">
                                                                        {{ ucwords($product->p_name) }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('p_name')
                                                    <div class="alert alert-danger" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label>Brand</label>
                                                <div class="form-floating mb-3">
                                                    <select class="form-control p-3 brand" name="brand" required
                                                        oninvalid="this.setCustomValidity('Please input a brand.')"
                                                        oninput="setCustomValidity('')">
                                                        <option></option>
                                                        <option value="Pfizer">Pfizer</option>
                                                        <option value="3M">3M</option>
                                                        <option value="Becton, Dickinson and Company">BD</option>
                                                        <option value="Cintas">Cintas</option>
                                                    </select>
                                                </div>
                                                @error('brand')
                                                    <div class="alert alert-danger" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Stock</label>
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control p-3"
                                                        placeholder="Stock Available" name="stock" required
                                                        oninvalid="this.setCustomValidity('Please input a stock.')"
                                                        oninput="setCustomValidity('')" />
                                                </div>
                                                @error('stock')
                                                    <div class="alert alert-danger" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label>Category</label>
                                                <div class="form-floating mb-3">
                                                    <select class="form-control p-3" name="category_id" required
                                                        oninvalid="this.setCustomValidity('Please input a category.')"
                                                        oninput="setCustomValidity('')">
                                                        <option value="">Select a Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->category_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('category_id')
                                                    <div class="alert alert-danger" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Expiration Date</label>
                                                <div class="form-floating mb-3">
                                                    <input type="date" name="expiration" class="form-control p-3"
                                                        required
                                                        oninvalid="this.setCustomValidity('Please input a expiration date.')"
                                                        oninput="setCustomValidity('')" />
                                                </div>
                                                @error('expiration')
                                                    <div class="alert alert-danger" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label>Status</label>
                                                <div class="form-floating mb-3">
                                                    <select class="form-control p-3" name="status" required
                                                        oninvalid="this.setCustomValidity('Please select a status.')"
                                                        oninput="setCustomValidity('')">
                                                        <option value="">Select Status</option>
                                                        <option value="Available">Available</option>
                                                        <option value="Unavailable">Unavailable</option>
                                                    </select>
                                                </div>
                                                @error('status')
                                                    <div class="alert alert-danger" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Description</label>
                                                <div class="form-floating mb-2">
                                                    <input type="text" name="description" class="form-control"
                                                        placeholder="Description" required
                                                        oninvalid="this.setCustomValidity('Please input a description.')"
                                                        oninput="setCustomValidity('')" />
                                                    <label for="floatingInput">Description</label>
                                                </div>
                                            </div>
                                            @error('description')
                                                <div class="alert alert-danger" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Create Modal --}}


                    {{-- Update modal --}}
                    @foreach ($products as $product)
                        <div class="modal fade" id="updateMedicine{{ $product->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h2 class="modal-title text-light" id="myModalLabel">Update item</h2>
                                        {{ $product->id }}
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST"
                                            action="{{ route('pharmacist.medicine.update', $product->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Item Name</label>
                                                    <div class="form-floating mb-3">
                                                        <select class="form-control p-3 p_name" name="p_name">
                                                            <option value="{{ $product->p_name }}">
                                                                {{ $product->p_name }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Brand</label>
                                                    <div class="form-floating mb-3">
                                                        <select class="form-control p-3 brand" name="brand">
                                                            <option value="{{ $product->brand }}">
                                                                {{ $product->brand }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Stock</label>
                                                    <div class="form-floating mb-3">
                                                        <input type="number" class="form-control p-3" name="stock"
                                                            value="{{ $product->stock }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Category Name</label>
                                                    <div class="form-floating mb-3">
                                                        <select class="form-control p-3" name="category_id" disabled>
                                                            <option value="{{ $product->category->id }}">
                                                                {{ $product->category->category_name }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Expiration</label>
                                                    <div class="form-floating mb-3">
                                                        <input type="date" name="expiration" class="form-control p-3"
                                                            value="{{ $product->expiration }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Status</label>
                                                    <div class="form-floating mb-3">
                                                        <select class="form-control p-3" name="status">
                                                            <option value="Available"
                                                                {{ $product->status == 'Available' ? 'selected' : '' }}>
                                                                Available</option>
                                                            <option value="Unavailable"
                                                                {{ $product->status == 'Unavailable' ? 'selected' : '' }}>
                                                                Unavailable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Description</label>
                                                        <div class="form-floating mb-2">
                                                            <input type="text" name="description"
                                                                class="form-control p-3"
                                                                value="{{ $product->description }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{-- End Update Modal --}}
                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>
  


    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {

                $(".p_name").select2({
                    width: '100%',
                    placeholder: 'Choose Item',
                    tags: true
                });
                $(".brand").select2({
                    width: '100%',
                    placeholder: 'Select Brand',
                    tags: true
                });
            });
        </script>
       
        <script>
            $(document).ready(function() {
                $('#medicinetable').DataTable();
            });
        </script>
    @endsection
