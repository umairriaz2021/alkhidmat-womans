@extends('admin.layout')
@section('title', isset($post) ? 'Edit Post' : 'Create Post')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            
            <form action="{{ isset($post) ? route('posts.update', $post->id) : route('posts.store') }}" 
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
                            <h4 class="card-title">{{ isset($post) ? 'Edit Post' : 'Add New Post' }}</h4>
                            <div id="ajax-alert-container"></div>
                            
                            {{-- Post Title --}}
                            <div class="form-group mb-0">
                                <label for="slug_gen">Post Title</label>
                                <input type="text" class="form-control" name="title" id="slug_gen" 
                                       placeholder="Enter Post Title" value="{{ $post->title ?? '' }}">
                            </div>
                            <p style="font-size:12px;" class="pt-1" id="urlPreview">
                                URL: <span>{{ env('APP_BASE_URL') }}{{ $post->slug ?? '' }}</span>
                            </p>

                            {{-- Post Content --}}
                            <div class="form-group">
                                <label for="summernote">Post Content</label>
                                <textarea name="content" class="editor" id="summernote">{{ $post->content ?? '' }}</textarea>
                            </div>

                            {{-- Tags Multi-Select (Bcat dropdown ko Tags mein convert kiya hai) --}}
                            <div class="form-group">
                                <label>Select Tags</label>
                                <div class="filter-bar">
                                    <div class="dropdown">
                                        <button type="button" class="dropdown-btn">
                                            Choose Tags <span class="arrow">▾</span>
                                        </button>
                                        <div class="dropdown-menu" id="dropdownMenu" style="display: none;">
                                            @if(!empty($tags))
                                                @foreach($tags as $tag) 
                                                <div class="dropdown-filters">
                                                    <label class="checkbox-item" for="tag_{{$tag->id}}">
                                                        <input type="checkbox" name="tags[]" id="tag_{{$tag->id}}" 
                                                            value="{{$tag->id}}"
                                                            @if(isset($post) && $post->tags->contains($tag->id)) checked @endif>
                                                        <span>{{$tag->name}}</span>
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
                            {{-- Post Content --}}
                            <div class="form-group">
                                <label for="ex">Post Excerpt</label>
                                <textarea name="excerpt" class="form-control">{{ $post->excerpt ?? '' }}</textarea>
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
                                {{ isset($post) ? 'Update Post' : 'Publish Post' }}
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
        let slug = title.toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        $('#slugField').val(slug);
        $('#urlPreview span').text("{{ env('APP_BASE_URL') }}/" + slug);
    });

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
                if(response.success) {
                    $('#ajax-alert-container').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    setTimeout(function() {
                        window.location.href = "{{ route('posts.index') }}";
                    }, 1000);
                }
            },
            error: function(xhr) {
                $('.apply').prop('disabled', false).text("{{ isset($post) ? 'Update Post' : 'Publish Post' }}");
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

    // Clear Tags logic
    $('.clear').on('click', function() {
        $("input[name='tags[]']").prop('checked', false);
    });
});
</script>
@endsection