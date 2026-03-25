@extends('admin.layout')
@if(isset($slider))
@section('title','Update Slider Page')
@else
@section('title','Create Page')
@endif
@section('content')
<div class="content-wrapper">
            <div class="row">
            
              
              <div class="col-12 grid-margin stretch-card">
                
                
                    <form action="{{ isset($slider) ? route('admin.update.slider', $slider['id']) : route('admin.add.slider') }}" 
      method="POST" class="row forms-sample w-100">
    
    @csrf
    @if(isset($slider))
        @method('PATCH')
    @endif

    <div class="col-xl-8 col-md-8 col-xs-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ isset($slider) ? 'Edit Slider' : 'Add Slider' }}</h4>

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
                    <label for="tagline">Tag line text</label>
                    <input type="text" name="tagline" id="tagline" class="form-control" placeholder="Tagline"
                        value="{{ old('tagline', $slider['tagline'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="main_heading">Main Heading</label>
                    <input type="text" name="main_heading" id="main_heading" class="form-control" placeholder="Main Heading"
                        value="{{ old('main_heading', $slider['main_heading'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="content">Main Content</label>
                    <textarea name="content" id="content" class="form-control h-100" cols="50" rows="5" placeholder="Content">{{ old('content', $slider['content'] ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="cta_text">Button Text</label>
                    <input type="text" name="cta_text" id="cta_text" class="form-control" placeholder="Button Text"
                        value="{{ old('cta_text', $slider['cta_text'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="cta_url">Button URL</label>
                    <input type="url" name="cta_url" id="cta_url" class="form-control" placeholder="Button URL"
                        value="{{ old('cta_url', $slider['cta_url'] ?? '') }}">
                </div>

                <div class="form-group" data-id-img="{{isset($slider['profile_image']) ? $slider['profile_image']['id'] : null}}">
                    <x-media-picker 
                        name="profile_image_id" 
                        label="Slider Image" 
                        :img_id="isset($slider['profile_image']) ? $slider['profile_image']['id'] : null"
                        :preview_path="isset($slider['profile_image']) ? $slider['profile_image']['file_path'] : ''"
                    />
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Post Status</div>
                <select name="post_status" class="form-select mb-3">
                    @if(!empty($statuses))
                        @foreach($statuses as $status)
                            <option value="{{ $status['id'] }}" 
                                {{ (isset($slider) && $slider['status_id'] == $status['id']) ? 'selected' : '' }}>
                                {{ ucfirst($status['name']) }}
                            </option>
                        @endforeach
                    @endif
                </select>

                <button type="submit" class="btn btn-primary me-2">Submit</button>
            </div>
        </div>
    </div>

</form>

              </div>
            </div>
          </div>
@endsection