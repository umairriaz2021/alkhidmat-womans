@extends('admin.layout')
@section('title','All Menus')
@section('content')

   <div class="content-wrapper">
            <div class="row">
              
             
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">All Menus</h4>

                    </p>
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th> Menu Name </th>
                            <th> Submenu</th>
                            <th>Updated at</th> 
                            <th> Action </th>
                          </tr>
                        </thead>
                        <tbody>
                          @if(!empty($menus))
                          @foreach($menus as $menu)
                          <tr>
                            
                            <td>{{$menu['title']}} </td>
                            <td>
                              {{($menu['parent_id']) ? $menu['parent_id'] : "Parent"}}
                            </td>
                            <td>{{$menu['updated_at']}}</td>
                            <td><a href="{{route('admin.edit.menu',$menu['id'])}}">Edit</a> | <a href="javascript:void(0)" id="deleteMenu" data-id="{{$menu['id']}}">Delete</a></td>
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

   $(document).on('click', '#deleteMenu', function(e) {
    e.preventDefault();

    let menuId = $(this).data('id');
    let deleteUrl = "{{ route('admin.delete.menu', ':id') }}".replace(':id', menuId);

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
