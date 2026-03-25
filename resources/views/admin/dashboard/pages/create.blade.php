@extends('admin.layout')
@section('title', isset($page) ? 'Edit Page' : 'Create Page')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <form action="{{ isset($page) ? route('admin.update.pages', $page['id']) : route('admin.add.pages') }}" 
                  id="createPageForm" class="row forms-sample w-100" method="POST">
                
                @csrf
                @if(isset($page))
                    @method('PATCH')
                @endif

                <input type="hidden" name="slug" id="slugField" value="{{ $page['slug'] ?? '' }}">

                <div class="col-xl-8 col-md-8 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ isset($page) ? 'Edit Page' : 'Add Page' }}</h4>
                            <div id="ajax-alert-container"></div>
                            
                            <div class="form-group mb-0">
                                <label for="slug_gen">Page Title</label>
                                <input type="text" class="form-control" name="p_title" id="slug_gen" 
                                       placeholder="Title" value="{{ $page['title'] ?? '' }}">
                            </div>
                            <p style="font-size:12px;" class="pt-1" id="urlPreview">
                                URL: <span>{{ env('APP_BASE_URL') }}/{{ $page['slug'] ?? '' }}</span>
                            </p>

                            <div class="form-group">
                                <label for="summernote">Performance Text</label>
                                <textarea name="p_content" class="editor" id="summernote">{{ $page['content'] ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Hero Banner Slides</label>
                                <div class="filter-bar">
                                    <div class="dropdown">
                                        <button type="button" class="dropdown-btn">
                                            Select Slides <span class="arrow">▾</span>
                                        </button>
                                        <div class="dropdown-menu" id="dropdownMenu" style="display: none;">
                                            @if(!empty($sliders))
                                                @foreach($sliders as $slider) 
                                                <div class="dropdown-filters">
                                                    <label class="checkbox-item" for="slider_{{$slider['id']}}">
                                                        <input type="checkbox" name="bcat[]" id="slider_{{$slider['id']}}" 
                                                            value="{{$slider['id']}}"
                                                            {{-- Check if ID exists in slider_id array --}}
                                                            @if(isset($page['slider_id']) && in_array($slider['id'], json_decode($page['slider_id'],true))) checked @endif>
                                                        <span>{{$slider['main_heading']}}</span>
                                                    </label>
                                                </div>
                                                @endforeach
                                            @endif
                                            <div class="dropdown-actions">
                                                <button type="button" class="clear">CLEAR</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" class="form-control" name="mtitle" id="meta_title" 
                                       placeholder="Meta Title" value="{{ $page['meta_title'] ?? '' }}">
                            </div>
                            <div class="form-group mb-0">
                                <label for="meta_desc">Meta Description</label>
                                <textarea class="form-control h-100" name="meta_desc" id="meta_desc" cols="50" rows="5">{{ $page['meta_description'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4 col-xs-12">
                    <div class="card">
                        <div class="card-body"> 
                            <div class="form-group">
                                {{-- Media Picker with existing image --}}
                                <x-media-picker name="profile_image_id" label="Featured Image" 
                                               :img_id="$page['image_id'] ?? ''" :preview_path="$page['profile_image']['file_path'] ?? ''" />
                            </div>
            
                       
                            <div class="card-title mb-2">Post Status</div>
                            <select class="form-select mb-3" name="post_status">
                                @foreach($statuses as $status) 
                                    <option value="{{$status['id']}}" 
                                        {{ (isset($page['status_id']) && $page['status_id'] == $status['id']) ? 'selected' : '' }}>
                                        {{ucfirst($status['name'])}}
                                    </option>
                                @endforeach
                            </select>

                            <div class="card-title mb-2">Templates</div>
                            <select class="form-select mb-3" name="template_id">
                                @foreach($templates as $template) 
                                    <option value="{{$template['id']}}"
                                        {{ (isset($page['template_id']) && $page['template_id'] == $template['id']) ? 'selected' : '' }}>
                                        {{ucfirst($template['display_name'])}}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-primary me-2 apply">
                                {{ isset($page) ? 'Update Page' : 'Create Page' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
      @section('script')
      <script>
      $(document).ready(function(){
          $('#createPageForm').on('submit',function(e){
               e.preventDefault();

          let selectedValues = $("input[name='bcat[]']:checked").map(function() {
    return $(this).val();
}).get();
let formData = new FormData(this);
formData.delete('bcat[]'); // Safety check
selectedValues.forEach(function(value) {
    formData.append('bcat[]', value);
});
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.apply').prop('disabled', true).text('Processing...');
            },
            success: function(response) {
                if(response.success) {
                      $('#ajax-alert-container').html(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> ${response.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
                    window.location.href = "{{route('admin.all.pages')}}"; // List page par redirect
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
        let errors = xhr.responseJSON.errors;
        let errorList = '';
        
        $.each(errors, function(key, value) {
            errorList += `<li>${value[0]}</li>`;
        });

        $('#ajax-alert-container').html(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">${errorList}</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
    } else {
        alert("Something went wrong!");
    }
            }
        });
              
          })
      })

      </script>
      @endsection
        