@extends('admin.layout')
@section('title', isset($post) ? 'Edit Area' : 'Create Area')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            
            <form action="{{ isset($post) ? route('area-of-work.update', $post->id) : route('area-of-work.store') }}" 
                  id="createPostForm" class="row forms-sample w-100" method="POST">
                
                @csrf
                @if(isset($post))
                    @method('PATCH')
                @endif

                {{-- Hidden Slug Field --}}
                <input type="hidden" name="slug" id="slugField" value="{{ $post->slug ?? '' }}">

                <div class="col-xl-8 col-md-8 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ isset($post) ? 'Edit Post' : 'Add New Area' }}</h4>
                            <div id="ajax-alert-container"></div>
                            
                            {{-- Post Title --}}
                            <div class="form-group mb-0">
                                <label for="slug_gen">Area Title</label>
                                <input type="text" class="form-control" name="title" id="slug_gen" 
                                       placeholder="Enter Area Title" value="{{ $post->title ?? '' }}">
                            </div>
                            
                           @php $baseUrl = Request::root(); @endphp
                            <a href="{{ $baseUrl }}/area-of-work/{{ $post->slug ?? '' }}" style="font-size:12px;" class="pt-1" id="urlPreview">
                                @if(isset($post->slug))
                                {{ $baseUrl }}{{ $post->slug ?? '' }}
                                @else
                                {{ $baseUrl }}/area-of-work/
                               @endif 
                            </a>

                            {{-- Post Content --}}
                            <div class="form-group">
                                <label for="summernote">Area Content</label>
                                <textarea name="content" class="editor" id="summernote">{{ $post->content ?? '' }}</textarea>
                            </div>
                            {{-- NEW FIELDS: Area Heading & Area Content --}}
                            <div class="card my-4 border">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Area Section Details</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label for="area_heading">Area Heading</label>
                                        <input type="text" class="form-control" name="area_heading" id="area_heading" 
                                               placeholder="Enter Area Heading" value="{{ $post->area_heading ?? '' }}">
                                    </div>

                                    <div class="form-group mb-0">
                                        <label for="area_content">Area Content</label>
                                         <textarea name="area_content" class="editor" id="summernote">{{ $post->area_content ?? '' }}</textarea>
                                        
                                    </div>
                                </div>
                            </div>

                            {{-- NEW FIELDS: Quote Section --}}
                            <div class="card my-4 border">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Quote & CTA Settings</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label for="area_quote">Area Quote</label>
                                        <textarea class="editor" name="area_quote" id="summernote">{{ $post->area_quote ?? '' }}</textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group mb-0">
                                            <label for="quote_button_text">Quote Button Text</label>
                                            <input type="text" class="form-control" name="quote_button_text" id="quote_button_text" 
                                                   placeholder="e.g. Read More / Apply Now" value="{{ $post->quote_button_text ?? '' }}">
                                        </div>
                                        <div class="col-md-6 form-group mb-0">
                                            <label for="quote_url">Quote URL</label>
                                            <input type="text" class="form-control" name="quote_url" id="quote_url" 
                                                   placeholder="e.g. https://example.com" value="{{ $post->quote_url ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Dynamic Repeater: Our Services --}}
                            <div class="card my-4 border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">Our Services</h5>
                                        <button type="button" class="btn btn-sm btn-success" id="add-service-btn">+ Add Service</button>
                                    </div>
                                    <div id="services-wrapper">
                                        @php
                                            $services = isset($post->our_services) ? (is_array($post->our_services) ? $post->our_services : json_decode($post->our_services, true)) : [];
                                        @endphp
                                        @if(!empty($services))
                                            @foreach($services as $index => $service)
                                                <div class="row align-items-center service-row mb-2">
                                                    <div class="col-md-5">
                                                        <input type="text" name="our_services[{{$index}}][service_title]" class="form-control" placeholder="Service Title" value="{{ $service['service_title'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" name="our_services[{{$index}}][service_cost]" class="form-control" placeholder="Service Cost" value="{{ $service['service_cost'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger btn-sm remove-row-btn">Remove</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- SEO Fields --}}
                            <div class="form-group mb-0">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" class="form-control" name="meta_title" id="meta_title" 
                                       placeholder="Meta Title" value="{{ $post->meta_title ?? '' }}">
                            </div>
                            <div class="form-group mb-0">
                                <label for="meta_desc">Meta Description</label>
                                <textarea class="form-control h-100" name="meta_description" id="meta_desc" cols="50" rows="4">{{ $post->meta_description ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4 col-xs-12">
                    <div class="card">
                        <div class="card-body"> 
                            {{-- Featured Image (Media Picker) --}}
                            <div class="form-group">
                                <x-media-picker name="image_id" label="Featured Image" 
                                               :img_id="$post->image_id ?? ''" :preview_path="$post->profileImage->file_path ?? ''" />
                            </div>

                            {{-- Dynamic Repeater: Gallery --}}
                            <div class="card my-3 border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="card-title mb-0">Gallery Images</h6>
                                    </div>
                                    <div id="gallery-wrapper">
                            @php
                                $gallery = isset($post->gallery) ? (is_array($post->gallery) ? $post->gallery : json_decode($post->gallery, true)) : [];
                            @endphp
                            
                            @if(!empty($gallery))
                            <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
                                @foreach($gallery as $item)
                                    @php $imgPath = is_array($item) ? ($item['gallery_img'] ?? '') : $item; @endphp
                                    @if(!empty($imgPath))
                                        <div class="position-relative existing-img-card">
                                            <img src="{{ asset($imgPath) }}" style="width: 60px; height: 60px; object-fit: cover;" class="rounded border">
                                            
                                            {{-- Retention Hidden Field --}}
                                            <input type="hidden" name="gallery_existing[]" value="{{ $imgPath }}">
                                            
                                            {{-- Existing Image Delete Button --}}
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-existing-img" 
                                                    style="padding: 0px 4px; font-size: 10px; line-height: 1;">✕</button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                            {{-- Multiple File Upload Input --}}
                    <div class="form-group mb-0">
                        <label for="gallery_files" class="text-muted style-sm mb-1">Upload New Images (Multiple)</label>
                        <input type="file" name="gallery[]" id="gallery_files" class="form-control form-control-sm" multiple accept="image/*">
                    </div>
                        </div>
                                </div>
                            </div>
                
                            {{-- Category Selection --}}
                            <div class="card-title mb-2">Category</div>
                            <select class="form-select mb-3" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category) 
                                    <option value="{{$category->id}}" 
                                        {{ (isset($post) && $post->category_id == $category->id) ? 'selected' : '' }}>
                                        {{$category->name}}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Post Status --}}
                            <div class="card-title mb-2">Post Status</div>
                            <select class="form-select mb-3" name="status_id">
                                @foreach($statuses as $status) 
                                    <option value="{{$status->id}}" 
                                        {{ (isset($post) && $post->status_id == $status->id) ? 'selected' : '' }}>
                                        {{ucfirst($status->name)}}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-primary w-100 apply">
                                {{ isset($post) ? 'Update Area' : 'Publish Area' }}
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
    // Dropdown toggle logic
    $('.dropdown-btn').on('click', function() {
        $('#dropdownMenu').toggle();
    });

    // Slug generation from title
    $('#slug_gen').on('input', function() {
        let title = $(this).val();
        let slug = title.toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/-+$/, '');
        $('#slugField').val('/area-of-work/'+slug);
        $('#urlPreview').text("{{ $baseUrl }}/area-of-work/" + slug);
    });

    // ---------------- Repeaters JS ----------------
    let serviceIndex = $('#services-wrapper .service-row').length;
    $('#add-service-btn').on('click', function() {
        let html = `
            <div class="row align-items-center service-row mb-2">
                <div class="col-md-5">
                    <input type="text" name="our_services[${serviceIndex}][service_title]" class="form-control" placeholder="Service Title">
                </div>
                <div class="col-md-5">
                    <input type="text" name="our_services[${serviceIndex}][service_cost]" class="form-control" placeholder="Service Cost">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-row-btn">Remove</button>
                </div>
            </div>`;
        $('#services-wrapper').append(html);
        serviceIndex++;
    });


   
    // Remove row event delegate
    $(document).on('click', '.remove-row-btn', function() {
        $(this).closest('.row').remove();
    });
    
    $(document).on('click', '.remove-existing-img', function() {
    $(this).closest('.existing-img-card').fadeOut(200, function() {
        $(this).remove();
    });
});
    // ---------------- AJAX Form Submit ----------------
    $('#createPostForm').on('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);

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
               console.log(response);   
            if(response.success) {
                    $('#ajax-alert-container').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    setTimeout(function() {
                        window.location.href = "{{ route('area-of-work.index') }}";
                    }, 1000);
                }
            },
            error: function(xhr) {
                $('.apply').prop('disabled', false).text("{{ isset($post) ? 'Update Area' : 'Publish Area' }}");
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
    });

   
});
</script>
@endsection