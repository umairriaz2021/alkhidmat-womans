@extends('admin.layout')
@section('title', isset($tag) ? 'Edit Tag' : 'Create Tag')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ isset($tag) ? 'Edit Tag' : 'Add Tag' }}</h4>
                    <div id="ajax-alert-container"></div>
                    
                    <form id="tagForm" action="{{ isset($tag) ? route('tags.update', $tag->id) : route('tags.store') }}" method="POST">
                        @csrf
                        @if(isset($tag)) @method('PATCH') @endif
                        
                        <input type="hidden" name="slug" id="slug_input" value="{{ $tag->slug ?? ''}}">
                        
                        <div class="form-group">
                            <label>Tag Name</label>
                            <input type="text" name="name" class="form-control" id="name_input" value="{{ $tag->name ?? '' }}" placeholder="Enter tag name">
                        </div>

                        <p style="font-size:12px;" class="pt-1" id="urlPreview">
                            URL: <span>{{ url('/') }}/tag/{{ $tag->slug ?? '' }}</span>
                        </p>

                        <button type="submit" class="btn btn-primary apply">Save Tag</button>
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
    const baseURL = window.location.origin + "/tag/";

    $('#name_input').on('input', function() {
        let title = $(this).val();
        
        // Slug generation logic
        let slug = title.toLowerCase()
                        .trim()
                        .replace(/[^\w\s-]/g, '') 
                        .replace(/[\s_-]+/g, '-') 
                        .replace(/^-+|-+$/g, '');

        // Update UI
        $('#urlPreview span').text(slug);
        $('#slug_input').val(slug);
    });

    $('#tagForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() { 
                $('.apply').prop('disabled', true).text('Saving...'); 
            },
            success: function(response) {
                // Success handle
                window.location.href = "{{ route('tags.index') }}";
            },
            error: function(xhr) {
                $('.apply').prop('disabled', false).text('Save Tag');
                let errors = xhr.responseJSON.errors;
                let errorHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors, function(k, v) { 
                    errorHtml += '<li>'+v[0]+'</li>'; 
                });
                errorHtml += '</ul></div>';
                $('#ajax-alert-container').html(errorHtml);
            }
        });
    });
});
</script>
@endsection