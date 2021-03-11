@extends('layout.app')

@section('content')
<div class="right_col admin_list_users" role="main">
  @include('layout/flash')
  <div class="x_panel">
                  <div class="x_title">
                    <h2>User List</h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Email</th>
                          <th>Username</th>
                          <th>Phone</th>
                          <th>Created</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if (count($users)> 0)
                        @foreach ($users as $k=>$v)
                        <tr>
                          <th scope="row">{{$k+1}}</th>
                          <td>{{$v->first_name}}</td>
                          <td>{{$v->last_name}}</td>
                          <td>{{$v->email}}</td>
                          <td>{{$v->username}}</td>
                          <td>{{$v->phone}}</td>
                          <td>{{$v->created}}</td>
                          <td>
                            @if ($v->is_blocked==1)
                            <a href="{{url('/users/status/')}}/{{$v->id}}/deactivate">{{'Active'}}</a>
                            @else
                            <a href="{{url('/users/status/')}}/{{$v->id}}/activate">{{'Blocked'}}</a>
                            @endif
                          </td>
                          <td>
                            <button type="button" class="btn btn-xs btn-success" onclick="location.href='{{url('/users/edit/')}}/{{$v->id}}'">Edit</button>
                            <button type="button" class="btn btn-xs btn-danger delete_user" onclick="deleteUser({{$v->id}})">Delete</button>
                          </td>
                        </tr>
                        @endforeach

                        @else
                        <tr>
                          <td colspan="4">No record found</td>
                        </tr>
                        @endif
                      </tbody>
                    </table>

                  </div>
                </div>
{{ $users->links() }}


              <div class="x_panel">
                <div class="x_title">
                  <h2>Add User</h2>

                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <form class="form-horizontal form-label-left" action="{{ url('/') }}/users/create" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">First name</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input class="form-control" placeholder="First name" type="text" name="first_name" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Last name</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input class="form-control" placeholder="Last name" type="text" name="last_name" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Username</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input class="form-control" placeholder="Username" type="text" name="username" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Phone</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input class="form-control" placeholder="Phone" type="text" name="phone">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input class="form-control" placeholder="Email" type="email" name="email" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" required title="Invalid email address">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Password</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input class="form-control" placeholder="Password" id="password" type="password" name="password" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Confirm Password</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input class="form-control" placeholder="Confirm Password" type="password" name="cpassword" required oninput="checkConfirmPass(this)">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="is_blocked">
                          <option value="0">--Status--</option>
                          <option value="0">Active</option>
                          <option value="1">De-active</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Country</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="select2_single form-control" tabindex="-1" name="country_id" required>
                          @foreach ($country as $k=>$v)
                          <option value="{{$v->id}}">{{$v->name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">

                        <button type="reset" class="btn btn-primary">Reset</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>

                  </form>
                </div>
              </div>
            </div>

            <script>
            function checkConfirmPass(input) {
                if (input.value != document.getElementById('password').value) {
                    input.setCustomValidity('Password Must be Matching.');
                } else {
                    // input is valid -- reset the error message
                    input.setCustomValidity('');
                }
            }
            </script>
@endsection
