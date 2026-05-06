@extends('admin.layout')
@section('title','All Tags')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">All Tags</h4>
                        <a href="{{ route('tags.create') }}" class="btn btn-primary btn-sm">Add New Tag</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th> Name </th>
                                    <th> Slug </th>
                                    <th> Created at </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($tags->count() > 0)
                                    @foreach($tags as $tag)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $tag->name }}</strong></td>
                                        <td>{{ $tag->slug }}</td>
                                        <td>{{ $tag->created_at->format('d M, Y') }}</td>
                                        <td>
                                            <a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-info btn-sm">Edit</a>
                                            <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteTag" data-id="{{ $tag->id }}">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No tags found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        
                        {{-- Pagination links --}}
                        <div class="mt-4">
                            {{ $tags->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.deleteTag', function(e) {
        e.preventDefault();

        let tagId = $(this).data('id');
        let deleteUrl = "{{ route('tags.destroy', ':id') }}".replace(':id', tagId);

        Swal.fire({
            title: 'Are you sure?',
            text: "This will remove the tag from all associated posts!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire('Deleted!', 'Tag has been deleted.', 'success').then(() => {
                            location.reload(); 
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection