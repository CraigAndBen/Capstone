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
                                <h5 class="m-b-10">Category List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('supply_officer.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Category List</li>
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
                            <h1>Category List</h1>
                        </div>
                        <div class="card-body">
                            <div class="container">

                                <div class="d-flex mb-3 justify-content-end">
                                    <div class="form-group">
                                        <a href="{{ route('supply_officer.category.report.view') }}"
                                            class="btn btn-success" target="_blank">View Report</a>
                                            <a href="{{ route('supply_officer.category.report.download') }}"
                                            class="btn btn-success" target="_blank">Download Report</a>
                                    </div>
                                </div>
                                <div class="d-flex mb-3 justify-content-end">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#createCategory">Add Category</button>
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

                                @if ($categories->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Notification Yet.
                                    </div>
                                @else
                                    <table id="categorytable" class="table table-bordered">
                                        <thead class="bg-primary text-light text-center">
                                            <tr>
                                                <th class="text-center">Category Name</th>
                                                <th class="text-center">Category Code</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td class="text-center">{{ $category->category_name }}</td>
                                                    <td class="text-center">{{ $category->category_code }}</td>
                                                    <td class="text-center">
                                                        <a type="icon" class="icon-trigger" data-toggle="modal"
                                                            data-target="#updateCategory{{ $category->id }}" href="">
                                                            <i class="bi bi-pencil-fill"></i></a>
                                                        <a href="{{ url('/supply_officer/category' . $category->id) }}"><i
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
                    <div class="modal fade" id="createCategory" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h2 class="modal-title text-light" id="myModalLabel">Add category</h2>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('supply_officer.category.create') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Category Name</label>
                                            <div class="form-floating mb-3">
                                                <select class="form-control category_n " name="category_name" required oninvalid="this.setCustomValidity('Please input a category name.')" oninput="setCustomValidity('')">
                                                    <option>{{ old('category_name') }}</option>
                                                    <option value="Pharmaceutical">Pharmaceutical</option>
                                                    <option value="PPE">PPE</option>
                                                    <option value="Medical Supply">Medical Supply</option>
                                                    <option value="Linen and Uniform">Linen and Uniform</option>
                                                </select>
                                            </div>
                                            @error('category_name')
                                                <div class="alert alert-danger" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label>Category Code</label>
                                            <div class="form-floating mb-3">
                                                <select class="form-control category_c" name="category_code" required oninvalid="this.setCustomValidity('Please input a category code.')" oninput="setCustomValidity('')">
                                                    <option>{{ old('category_code') }}</option>
                                                    <option value="1001">1001</option>
                                                    <option value="1002">1002</option>
                                                    <option value="1003">1003</option>
                                                    <option value="1004">1004</option>
                                                </select>
                                            </div>
                                            @error('category_code')
                                                <div class="alert alert-danger" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Create Modal --}}

                    {{-- Update modal --}}
                    @foreach ($categories as $category)
                        <div class="modal fade" id="updateCategory{{ $category->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h2 class="modal-title text-light" id="myModalLabel">Update category</h2>
                                        {{ $category->id }}
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="{{ route('supply_officer.category.update', $category->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Category Name</label>
                                                    <div class="form-floating mb-3">
                                                        <select class="form-control p-3 category_n" name="category_name">
                                                            <option value="{{ $category->category_name }}">
                                                                {{ $category->category_name }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Category Code</label>
                                                    <div class="form-floating mb-3">
                                                        <select class="form-control p-3 category_c" name="category_code">
                                                            <option value="{{ $category->category_code }}">
                                                                {{ $category->category_code }}</option>
                                                        </select>
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

                $(".category_n").select2({
                    width: '100%',
                    placeholder: 'Choose Category Name',
                    tags: true
                });
                $(".category_c").select2({
                    width: '100%',
                    placeholder: 'Choose Category Code',
                    tags: true
                });

            });
        </script>
        <script>
           $(document).ready( function () {
                $('#categorytable').DataTable();
            });
        </script>
    @endsection
