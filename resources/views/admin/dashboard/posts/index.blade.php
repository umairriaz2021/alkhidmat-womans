@extends('admin.layout')
@section('title','All Posts')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">All Posts</h4>
                        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Add New Post</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> Featured Image </th>
                                    <th> Title </th>
                                    <th> Category </th>
                                    <th> Status </th>
                                    <th> Updated at </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($posts->count() > 0)
                                    @foreach($posts as $post)
                                    <tr>
                                        <td class="py-1">
                                            {{-- Yahan hum image relationship use kar rahe hain --}}
                                            @if(isset($post->profileImage))
                                            <img src="{{asset('storage/'.$post->profileImage->file_path)}}" alt="image" />
                                            @else
                                            <p>No Image</p>
                                            @endif
                                        </td>
                                        <td><strong>{{ $post->title }}</strong></td>
                                        <td>{{ $post->category->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $post->status->slug == 'published' ? 'badge-success' : 'badge-warning' }}">
                                                {{ $post->status->name ?? 'Draft' }}
                                            </span>
                                        </td>
                                        <td>{{ $post->updated_at->format('d M, Y') }}</td>
                                        <td>
                                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-info btn-sm">Edit</a>
                                            <a href="javascript:void(0)" class="btn btn-danger btn-sm deletePost" data-id="{{ $post->id }}">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No posts found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        
                        {{-- Pagination links --}}
                        <div class="mt-4">
                            {{ $posts->links() }}
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

    $(document).on('click', '.deletePost', function(e) {
        e.preventDefault();

        let postId = $(this).data('id');
        // Resource route ke mutabiq URL update kiya gaya hai
        let deleteUrl = "{{ route('posts.destroy', ':id') }}".replace(':id', postId);

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
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
                        Swal.fire('Deleted!', 'Post has been deleted.', 'success').then(() => {
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