@extends('layouts.inner_superadmin')

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
                                <h5 class="m-b-10">Notification List</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Notification List</li>
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
                            <h1 class="display-6">Notification List</h1>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <div class="d-flex justify-content-end">
                                    <div class="m-1">
                                        <form action="{{ route('superadmin.notification.delete.all') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Delete All</button>
                                        </form>
                                    </div>
                                </div>
                                <hr>
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

                                @if ($notifications->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Notification Yet.
                                    </div>
                                @else
                                    <div class="row my-4">
                                        <table class="table table-hover" id="patientTable">
                                            <thead class="table-primary text-light text-center">
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Message</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @foreach ($notifications as $notification)
                                                    <tr class="p-3">
                                                        <td>{{ ucwords($notification->title) }}</td>
                                                        <td>{{ ucwords(Str::limit($notification->message, 30)) }}</td>
                                                        @if ($notification->is_read == 0)
                                                            <td>Unread</td>
                                                        @else
                                                            <td>read</td>
                                                        @endif
    
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                                    data-toggle="dropdown">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item btn btn-primary" data-toggle="modal"
                                                                        data-target="#viewModal"
                                                                        data-id="{{ json_encode($notification->id) }}"
                                                                        data-title="{{ json_encode(ucwords($notification->title)) }}"
                                                                        data-message="{{ json_encode($notification->message) }}"
                                                                        data-date="{{ json_encode($notification->date) }}"
                                                                        data-time="{{ json_encode($notification->time) }}"
                                                                        data-is-read="{{ json_encode($notification->is_read) }}">Read</a>
                                                                    <form method="POST"
                                                                        action="{{ route('superadmin.notification.delete') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $notification->id }}">
                                                                        <button type="submit"
                                                                            class="dropdown-item btn btn-primary">Delete</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-center my-3">
                                        {{ $notifications->links('pagination::bootstrap-4') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-light">
                                    <h3 class="modal-title" id="title"></h3>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <h4 class="text-bold">Date: </h4>
                                        <p class="text-bold" id="date"></p>
                                        <h4 class="text-bold">Time: </h4>
                                        <p class="text-bold" id="time"></p>
                                    </div>
                                    <div class="row">
                                        <h4 class="text-bold">Message: </h4>
                                        <p class="text-bold" id="message"></p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('superadmin.notification.read') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="is_read" id="is_read">
                                            <button type="sumbit" class="btn btn-danger">Back</button>
                                        </form>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ sample-page ] end -->
                </div>
                <!-- [ Main Content ] end -->
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
    @endsection
