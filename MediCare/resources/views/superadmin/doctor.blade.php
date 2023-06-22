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
                  <h5 class="m-b-10">Doctor Account</h5>
                </div>
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('superadmin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{route('superadmin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item" aria-current="page">Doctor Account</li>
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
                <h1>Doctor</h1>
              </div>
              <div class="card-body">
                <div class="container">
                    <div class=" d-flex mb-3 justify-content-end">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add Account</button>
                    </div>
                    <table class="table table-bordered">
                      <thead class="bg-primary text-light text-center">
                        <tr>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Email</th>
                          <th>Specialties</th>
                          <th>Phone</th>
                          <th>Status</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{$user->first_name}}</td>
                                <td>{{$user->last_name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->Specialties}}</td>
                                <td>{{$user->phone}}</td>
                                <td>{{$user->status}}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                          Actions
                                        </button>
                                        <div class="dropdown-menu">
                                          <a class="dropdown-item" href="#">Edit</a>
                                          <a class="dropdown-item" href="#">Deactivate</a>
                                          <a class="dropdown-item" href="#">View</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                      </tbody>
                    </table>
                  </div>
              </div>
            </div>

            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h2 class="modal-title" id="myModalLabel">Create Doctor Account</h2>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('register')}}">
                            @csrf
                            {{-- <x-input-error :messages="$errors->get('name')" class="mt-2" /> --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 ">
                                        <input type="text" class="form-control ml-2" id="floatingInput fname" placeholder="First Name" name="fname" />
                                        <label for="floatingInput">First Name</label> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 ">
                                        <input type="text" class="form-control" id="floatingInput lname" placeholder="Last Name" name="lname" />
                                        <label for="floatingInput">Last Name</label> 
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                              <input type="email" name="email" class="form-control" id="floatingInput email" placeholder="Email Address" />
                              <label for="floatingInput">Email Address</label>
                              <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="form-floating mb-3">
                              <input type="password" name="password" class="form-control" id="floatingInput password" placeholder="Password" />
                              <label for="floatingInput">Password</label>
                              <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    </form>
                  </div>
                </div>
              </div>
            
          <!-- [ sample-page ] end -->
        </div> 
        <!-- [ Main Content ] end -->
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
@endsection
