@extends('admin.layout')
@section('title','Edit User')
@section('content')
<div class="content-wrapper">
            <div class="row">
            
              
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Edit User</h4>
                    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
                    <form action="{{route('admin.user.update',$user['id'])}}" class="forms-sample" method="POST">
                     @method('PATCH')
                      @csrf
                      <div class="form-group">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" value="{{$user['name']}}" class="form-control" name="n" id="exampleInputName1" placeholder="Name">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">Email address</label>
                        <input type="email" value="{{$user['email']}}" name="e" class="form-control" id="exampleInputEmail3" placeholder="Email">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword4">Password</label>
                        <input type="password" name="password" value="{{$user['password']}}" class="form-control" id="exampleInputPassword4" placeholder="Password">
                      </div>
                      <div class="form-group">
                        <label for="exampleSelectGender">Roles</label>
                        <select class="form-select" name="role_id" id="exampleSelectGender" @if($user['role_name'] === 'super_admin') readonly @endif>
                          @if(!empty($roles))
                          <option value="">Select Role</option>  
                          @foreach($roles as $role)
                          <option value="{{$role['id']}}"   @if($user['role_id'] == $role['id']) selected @endif>{{ucfirst(str_replace('_',' ',$role['name']))}}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                      <div class="form-group">
                      
                       <x-media-picker name="profile_image_id" label="User Profile Picture" :img_id="$user['image_id']" :preview_path="$user['image_path'] ?? ''" />
                       
                 
                      </div>
                    
                     
                      <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form> 
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection