@extends('admin.layout')
@section('title','Settings')
@section('content')
<div class="content-wrapper">
            <div class="row">
            
              
              <div class="col-12 grid-margin stretch-card">
                
                
                    <form action="" 
      method="POST" class="row forms-sample w-100">
    
    @csrf
   

    <div class="col-xl-8 col-md-8 col-xs-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Site Settings</h4>

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Error Messages --}}
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

                {{-- Form Fields --}}
                <div class="form-group">
                    <label for="stitle">Site Title</label>
                    <input type="text" name="stitle" id="stitle" class="form-control" placeholder="Site Title"
                        value="">
                </div>

                <div class="form-group">
                    <label for="stag">Site Tagline</label>
                    <input type="text" name="stag" id="stag" class="form-control" placeholder="Site Tag"
                        value="">
                </div>
               

                
                <div class="form-group">
                    <x-media-picker name="profile_image_id" label="Site Logo" />
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                </div>
            </div>
        </div>
    </div>

    

</form>

              </div>
            </div>
          </div>
@endsection