@extends('admin.layout')
@section('title','All Users')
@section('content')
   <div class="content-wrapper">
            <div class="row">
              
             
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">All Users</h4>

                    </p>
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th> User </th>
                            <th> Username </th>
                            <th> Email</th>
                            <th> Role</th>
                            <th> Action </th>
                          </tr>
                        </thead>
                        <tbody>
                          @if(!empty($users))
                          @foreach($users as $user)
                          <tr>
                            <td class="py-1">
                              @if(!empty($user['image_path']))
                              <img src="{{asset('storage/'.$user['image_path'])}}" alt="image" />
                              @else
                              <img src="{{asset('assets/images/faces/profile/profile.jpg')}}" alt="image" />
                              @endif
                            </td>
                            <td>{{$user['name']}} </td>
                            <td>
                              {{$user['email']}}
                            </td>
                            <td>{{(isset($user['role_name'])) ? ucfirst(str_replace('_',' ',$user['role_name'])):"N/A"}}</td>
                            <td><a href="{{route('admin.edit.user',$user['id'])}}">Edit</a> | <a href="javascript:void(0)" id="deleteUser" data-id="{{$user['id']}}">Delete</a></td>
                          </tr>
                          @endforeach
                          @endif
                       
                            
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection