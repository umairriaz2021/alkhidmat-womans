@extends('admin.layout')
@section('title','All Templates')
@section('content')

   <div class="content-wrapper">
            <div class="row">
              
             
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">All Templates</h4>

                    </p>
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Updated at</th> 
                            <th> Action </th>
                          </tr>
                        </thead>
                        <tbody>
                          @if(!empty($templates))
                          @foreach($templates as $template)
                          <tr>
                            
                            <td>{{$template['display_name']}} </td>
                            
                            
                            <td>{{ucfirst($template['statuses']['name'])}}</td>
                            <td>{{$template['updated_at']}}</td>
                            <td><a href="{{route('admin.edit.template',$template['id'])}}">Edit</a> | <a href="javascript:void(0)" id="deleteSlider" data-id="">Delete</a></td>
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
    // Slider Delete Click Event
    $(document).on('click', '#deleteSlider', function(e) {
        e.preventDefault();

        let sliderId = $(this).data('id');
        let deleteUrl = "{{ route('admin.delete.slider', ':id') }}";
        deleteUrl = deleteUrl.replace(':id', sliderId);

        Swal.fire({
            title: 'Delete Slide ?',
            text: "Are you sure, you want to delete this slide",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'yes, delete this slide!',
            cancelButtonText: 'No, back to page'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            ).then(() => {
                                // Row ko smooth tareeqay se remove karne ke liye:
                                $(`a[data-id="${sliderId}"]`).closest('tr').fadeOut(500, function() {
                                    $(this).remove();
                                });
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong, try again',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
  </script>
@endsection