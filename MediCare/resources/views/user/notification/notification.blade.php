@extends('layouts.inner_home')

@section('content')
    <section class="breadcrumbs">
        <div class="container" style="margin-top: 85px">

            <div class="d-flex justify-content-between align-items-center">
                <h2><b>Notification</b></h2>
                <ol>
                    <li><a href="user/dashboard">Home</a></li>
                    <li>Notification</li>
                </ol>
            </div>

        </div>
    </section><!-- End Breadcrumbs Section -->

    <section class="inner-page">
        <div class="container">
            <div class="auth-main">
                <div class="auth-wrapper v3">
                    <div class="auth-form">
                        <div class="card my-2 shadow">
                            <div class="row m-4">
                                <h2>Notification List</h2>
                                <hr>
                            </div>
                            <div class="card-body">
                                <div class="m-2">
                                    <div class="d-flex justify-content-end">
                                        <div class="m-1">
                                            <form action="{{ route('user.notification.delete.all') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Delete All</button>
                                            </form>
                                        </div>
                                    </div>
                                    <hr>
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <strong>Whoops!</strong> There were some problems with your input. Please fix
                                            the
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
                                            {{ session('info') }}
                                        </div>
                                    @endif

                                    @if ($notifications->isEmpty())
                                        <div class="alert alert-info">
                                            No Notification Yet.
                                        </div>
                                    @else
                                        <table class="table table-bordered">
                                            <thead class="bg-primary text-light text-center">
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
                                                                <button class="btn btn-primary dropdown-toggle"
                                                                    type="button" data-toggle="dropdown">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    @if ($notification->is_read == 0)
                                                                        <a class="dropdown-item btn btn-primary"
                                                                            data-toggle="modal" data-target="#viewModal"
                                                                            data-id="{{ json_encode($notification->id) }}"
                                                                            data-title="{{ json_encode(ucwords($notification->title)) }}"
                                                                            data-message="{{ json_encode($notification->message) }}"
                                                                            data-date="{{ json_encode($notification->date) }}"
                                                                            data-time="{{ json_encode($notification->time) }}"
                                                                            data-is-read="{{ json_encode($notification->is_read) }}">Read</a>
                                                                    @else
                                                                        <a class="dropdown-item btn btn-primary"
                                                                            data-toggle="modal" data-target="#viewModal"
                                                                            data-id="{{ json_encode($notification->id) }}"
                                                                            data-title="{{ json_encode(ucwords($notification->title)) }}"
                                                                            data-message="{{ json_encode($notification->message) }}"
                                                                            data-date="{{ json_encode($notification->date) }}"
                                                                            data-time="{{ json_encode($notification->time) }}"
                                                                            data-is-read="{{ json_encode($notification->is_read) }}">Read</a>
                                                                    @endif
                                                                    <form method="POST"
                                                                        action="{{ route('user.notification.delete') }}">
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
                                        <div class="d-flex justify-content-center my-3">
                                            {{ $notifications->links('pagination::bootstrap-4') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
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
                        <form action="{{ route('user.notification.read') }}" method="post">
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

    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
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
                    <hr>
                    <div class="row">
                        <h4 class="text-bold">Do you really want to delete this? </h4>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('user.notification.delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="id">
                            <button type="sumbit" class="btn btn-danger">Delete</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                    </form>
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

            $('#deleteModal').on('show.bs.modal', function(event) {
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
