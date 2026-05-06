@extends('admin.layout')
@section('title', isset($category) ? 'Edit Category' : 'Create Category')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ isset($category) ? 'Edit Category' : 'Add Category' }}</h4>
                    <div id="ajax-alert-container"></div>
                    
                    <form id="categoryForm" action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
                        @csrf
                        @if(isset($category)) @method('PATCH') @endif
                        <input type="hidden" name="slug" id="slug_input" value="{{ $category->slug ?? ''}}">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" name="name" class="form-control" id="name_input" value="{{ $category->name ?? '' }}">
                        </div>
                         <p style="font-size:12px;" class="pt-1" id="urlPreview">
                                URL: <span>{{ env('APP_BASE_URL') }}{{ $category->slug ?? '' }}</span>
                            </p>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="summernote" class="form-control editor" rows="4">{{ $category->description ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status_id" class="form-select">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ (isset($category) && $category->status_id == $status->id) ? 'selected' : '' }}>
                                        {{ ucfirst($status->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary apply">Save Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Auto-slug generation
    const baseURL = window.location.origin + "/categories/";
    $('#name_input').on('input', function() {
        let title = $(this).val();
        
        // Slug conversion logic
        let slug = title.toLowerCase()
                        .trim()
                        .replace(/[^\w\s-]/g, '') 
                        .replace(/[\s_-]+/g, '-') 
                        .replace(/^-+|-+$/g, '');

        let fullURL = baseURL + slug;

        // Update UI
        $('#urlPreview').text("URL: " + fullURL);
        $('#slugField').val(slug);
    });

    $('#name_input').on('input', function() {
        let slug = $(this).val().toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-');
        $('#slug_input').val(slug);
    });

    $('#categoryForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() { $('.apply').prop('disabled', true).text('Saving...'); },
            success: function(response) {
              console.log(response);
                window.location.href = "{{ route('categories.index') }}";
            },
            error: function(xhr) {
                $('.apply').prop('disabled', false).text('Save Category');
                let errors = xhr.responseJSON.errors;
                let errorHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors, function(k, v) { errorHtml += '<li>'+v[0]+'</li>'; });
                errorHtml += '</ul></div>';
                $('#ajax-alert-container').html(errorHtml);
            }
        });
    });
});
</script>
@endsection