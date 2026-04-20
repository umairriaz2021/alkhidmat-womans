@extends('admin.layout')
@section('title','Settings')
@section('content')
<div class="content-wrapper">
            <div class="row">
            
              
              <div class="col-12 grid-margin stretch-card">
                
                
                    <form action="{{ route('admin.settings') }}" method="POST" class="row forms-sample w-100">
    @csrf

    <div class="col-xl-8 col-md-8 col-xs-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Site Settings</h4>

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="stitle">Site Title</label>
                    <input type="text" name="stitle" id="stitle" class="form-control" 
                           placeholder="Site Title" 
                           value="{{ old('stitle', $settings->site_title ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="stag">Site Tagline</label>
                    <input type="text" name="stag" id="stag" class="form-control" 
                           placeholder="Site Tag" 
                           value="{{ old('stag', $settings->site_tag ?? '') }}">
                </div>

                <div class="form-group">
                    {{-- value pass karna zaroori hai media picker ko --}}
                    <x-media-picker name="profile_image_id" label="Site Logo" 
                    :img_id="isset($settings->site_logo) ? $settings->siteLogo->id : null"
                    :preview_path="isset($settings->site_logo) ? $settings->siteLogo->file_path : ''"    
                     />
                </div>

                <div class="form-group">
                    <x-media-picker name="profile_footer_image_id" label="Footer Logo" 
                    :img_id="isset($settings->footer_logo) ? $settings->footerLogo->id : null"
                    :preview_path="isset($settings->footer_logo) ? $settings->footerLogo->file_path : ''"    
                  />
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary me-2">Update Settings</button>
                </div>
            </div>
        </div>
    </div>
</form>

              </div>
            </div>
          </div>
@endsection