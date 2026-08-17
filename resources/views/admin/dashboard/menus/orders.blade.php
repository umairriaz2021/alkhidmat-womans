@extends('admin.layout')
@section('title', 'All Parent Menus')

@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">All Parent Menus (Drag & Drop to Reorder)</h4>

          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th width="80px">Sort</th>
                  <th width="100px">Order</th>
                  <th>Menu Name</th>
                </tr>
              </thead>
              <tbody id="sortable-menu">
                @forelse($menus as $menu)
                <tr class="sortable-row" data-id="{{ $menu->id }}">
                  <td style="cursor: move;">
                    <i class="mdi mdi-drag-vertical fs-4 text-muted">☰</i>
                  </td>
                  <td class="order-number">{{ $menu->order ?? 0 }}</td>
                  <td>{{ $menu->title }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="3" class="text-center">No parent menus found.</td>
                </tr>
                @endforelse
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
<!-- jQuery UI CDN for Drag & Drop Sorting -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
  $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // Make table rows sortable
    $('#sortable-menu').sortable({
      handle: 'td',
      cursor: 'move',
      placeholder: 'ui-state-highlight',
      update: function(event, ui) {
        let order = [];

        // Loop through each row and get updated index
        $('#sortable-menu tr.sortable-row').each(function(index) {
          let position = index + 1;
          $(this).find('.order-number').text(position); // Update UI order number

          order.push({
            id: $(this).data('id'),
            position: position
          });
        });

        // Send AJAX request to update database
        $.ajax({
          url: "{{ route('admin.menus.update.order') }}",
          type: "POST",
          data: {
            order: order
          },
          success: function(response) {
            Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: response.message,
              showConfirmButton: false,
              timer: 2000
            });
          },
          error: function(xhr) {
            Swal.fire('Error!', 'Failed to update menu order.', 'error');
          }
        });
      }
    });
  });
</script>
@endsection