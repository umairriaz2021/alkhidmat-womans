@extends('admin.layout')
@section('title','All Categories')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">All Categories</h4>
                        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">Add Category</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> Name </th>
                                    <th> Slug </th>
                                    <th> Status </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $cat)
                                <tr>
                                    <td>{{ $cat->name }}</td>
                                    <td>{{ $cat->slug }}</td>
                                    <td>
                                        <span class="badge {{ $cat->status->slug == 'active' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $cat->status->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('categories.edit', $cat->id) }}">Edit</a> | 
                                        <a href="javascript:void(0)" class="deleteCategory text-danger" data-id="{{ $cat->id }}">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">{{ $categories->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).on('click', '.deleteCategory', function() {
    let id = $(this).data('id');
    let url = "{{ route('categories.destroy', ':id') }}".replace(':id', id);
    
    Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function(res) {
                    location.reload();
                }
            });
        }
    });
});
</script>
@endsection