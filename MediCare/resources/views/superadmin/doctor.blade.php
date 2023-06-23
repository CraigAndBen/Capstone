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

                  @if ($errors->any())
                    <div class="row mt-4 mb-3">
                      <div class="col-md-6 bg-primary text-light text-center offset-md-3 p-2 rounded-pill">
                        <h3 class="text-light">Some values are not available</h3>
                      </div>
                    </div>
                  @endif

                  @if (session('status'))
                  <div class="row mt-4 mb-3">
                    <div class="col-md-6 bg-primary text-light text-center offset-md-3 p-2 rounded-pill">
                      <h3 class="text-light">{{session('status')}}</h3>
                    </div>
                  </div>
                  @endif

                    <div class=" d-flex mb-3 justify-content-end">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add Account</button>
                    </div>
                    <table class="table table-bordered">
                      <thead class="bg-primary text-light text-center">
                        <tr>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Email</th>
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
                              <td>{{$user->status}}</td>
                              <td class="text-center">
                                  <div class="dropdown">
                                      <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                        Actions
                                      </button>
                                      <div class="dropdown-menu">
                                        @foreach ($doctors as $doctor)

                                        @if ($user->id === $doctor->account_id)
                                          <a class="dropdown-item btn btn-primary" data-toggle="modal" data-target="#editModal"
                                          {{-- data-toggle="modal" 
                                          data-target="#editModal" 
                                          data-first-name="{{ $user->first_name }}"
                                          data-last-name="{{ $user->last_name }}"
                                          data-specialties="{{ $doctor->specialties }}"
                                          data-address="{{ $doctor->address }}"
                                          data-date="{{ $doctor->birthdate }}"
                                          data-phone="{{ $doctor->phone }}" --}}
                    
                                          >Edit</a>
                                        @endif
                                        @endforeach
                                        <a class="dropdown-item">Deactivate</a>
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

            <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header bg-primary">
                      <h2 class="modal-title text-light" id="myModalLabel">Create Doctor Account</h2>
                      {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button> --}}
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('superadmin.store.doctor')}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 ">
                                        <input type="text" class="form-control ml-2" id="floatingInput first_name" placeholder="First Name" name="first_name" required/>
                                        <label for="floatingInput">First Name</label>    
                                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />     
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 ">
                                        <input type="phone" class="form-control" id="floatingInput last_name" placeholder="Last Name" name="last_name" required/>
                                        <label for="floatingInput">Last Name</label> 
                                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />     

                                    </div>
                                </div>
                              </div>
                            <div class="form-floating mb-3">
                              <input type="specialties" name="specialties" class="form-control" id="floatingInput specialties" placeholder="Email Address" required/>
                              <label for="floatingInput">Specialties</label>
                              <x-input-error :messages="$errors->get('specialties')" class="mt-2" />
                            </div>
                            <div class="form-floating mb-3">
                              <input type="text" name="address" class="form-control" id="floatingInput address" placeholder="Address" required/>
                              <label for="floatingInput">Address</label>
                              <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                  <div class="form-floating mb-3 ">
                                      <input type="date" class="form-control ml-2" id="floatingInput date" placeholder="Date" name="date" required/>
                                      <label for="floatingInput">Date</label> 
                                      <x-input-error :messages="$errors->get('date')" class="mt-2" />     

                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-floating mb-3 ">
                                      <input type="number" class="form-control" id="floatingInput phone" placeholder="Last Name" name="phone" required/>
                                      <label for="floatingInput">Phone</label> 
                                      <x-input-error :messages="$errors->get('phone')" class="mt-2" />    
                                  </div>
                              </div>
                          </div>
                          <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="floatingInput email" placeholder="Email Address" required/>
                            <label for="floatingInput">Email Address</label>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                          </div>
                            <div class="form-floating mb-3">
                              <input type="password" name="password" class="form-control" id="floatingInput password" placeholder="Password" required/>
                              <label for="floatingInput">Password</label>
                              <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div class="form-floating mb-3">
                              <input type="password" name="password_confirmation" class="form-control" id="floatingInput password_confirmation" placeholder="password confirmation" required />
                              <label for="floatingInput">Password Confirmation</label>
                              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
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

            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h4 class="modal-title" id="myModalLabel">User Details</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <h5>User ID: <span id="info"></span></h5>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                  </div>
              </div>
          </div>
            
          <!-- [ sample-page ] end -->
        </div> 
        <!-- [ Main Content ] end -->
      </div>
    </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
@endsection

{{-- @section('scripts')
  <script>
    $(document).ready(function() {
      $('#editModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var info =  JSON.parse(button.data('info'));  // Extract data attributes
        var modal = $(this);
        modal.find('#info').text(info); // Set data in modal
      });
    });
  </script>
@endsection --}}
