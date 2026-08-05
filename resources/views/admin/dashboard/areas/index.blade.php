@extends('admin.layout')
@section('title','All Areas')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">All Areas</h4>
                        <a href="{{ route('area-of-work.create') }}" class="btn btn-primary btn-sm">Add New Post</a>
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
                                @if($areas->count() > 0)
                                    @foreach($areas as $area)
                                    
                                    <tr>
                                        <td class="py-1">
                                            {{-- Yahan hum image relationship use kar rahe hain --}}
                                            @if(isset($area->profileImage) && !empty($area->profileImage))
                                            <img src="{{asset('storage/'.$area->profileImage->file_path)}}" alt="image" />
                                            @else
                                            <p>No Image</p>
                                            @endif
                                        </td>
                                        <td><strong>{{ ucwords(strtolower($area->title)) }}</strong></td>
                                        <td>{{ $area->category?->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $area->status->slug == 'published' ? 'badge-success' : 'badge-warning' }}">
                                                {{ $area->status->name ?? 'Draft' }}
                                            </span>
                                        </td>
                                        <td>{{ $area->updated_at->format('d M, Y') }}</td>
                                        <td>
                                            <a href="{{ route('area-of-work.edit', $area->id) }}" class="btn btn-info btn-sm">Edit</a>
                                            <a href="javascript:void(0)" class="btn btn-danger btn-sm deletePost" data-id="{{ $area->id }}">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No areas found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        
                        {{-- Pagination links --}}
                        <div class="mt-4">
                            {{ $areas->links() }}
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
        let deleteUrl = "{{ route('area-of-work.destroy', ':id') }}".replace(':id', postId);

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