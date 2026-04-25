@extends('admin.layout')
@section('title','All Pages')
@section('content')

   <div class="content-wrapper">
            <div class="row">
              
             
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">All Pages</h4>

                    </p>
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th> Featured Image </th>
                            <th> Title </th>
                            <th> Slug</th>
                            <th>Updated at</th> 
                            <th> Action </th>
                          </tr>
                        </thead>
                        <tbody>
                          @if(!empty($pages))
                          @foreach($pages as $page)
                          <tr>
                            <td class="py-1">
                              @if(isset($page['profile_image']))
                              <img src="{{asset('storage/'.$page['profile_image']['file_path'])}}" alt="image" />
                              @else
                              <p>No Image</p>
                              @endif
                            </td>
                            <td>{{$page['title']}} </td>
                            <td>
                              {{$page['slug']}}
                            </td>
                            <td>{{$page['updated_at']}}</td>
                            <td><a href="{{route('admin.edit.pages',$page['id'])}}">Edit</a> | <a href="javascript:void(0)" id="deletePage" data-id="{{$page['id']}}">Delete</a></td>
                          </tr>
                          @endforeach
                          @endif
                       
                            
                        </tbody>
                      </table>
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

   $(document).on('click', '#deletePage', function(e) {
    e.preventDefault();

    let pageId = $(this).data('id');
    let deleteUrl = "{{ route('admin.delete.pages', ':id') }}".replace(':id', pageId);

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
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
                    if (response.success) {
                        Swal.fire('Deleted!', response.message, 'success').then(() => {
                            location.reload(); 
                        });
                    }
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