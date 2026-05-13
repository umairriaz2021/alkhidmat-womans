@extends('admin.layout')
@section('title','Payment Methods')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Payment Methods</h4>
                        <a href="{{ route('payment-methods.create') }}" class="btn btn-primary btn-sm">Add New Method</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> Name </th>
                                    <th> Status </th>
                                    <th> Updated at </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($paymentMethods->count() > 0)
                                    @foreach($paymentMethods as $method)
                                    <tr>
                                    
                                        <td><strong>{{ $method->name }}</strong></td>
                                        
                                        <td>
                                            @php
                                                // Dynamic badge color based on status name
                                                $statusClass = strtolower($method->status->name) == 'active' ? 'badge-success' : 'badge-warning';
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ $method->status->name }}
                                            </span>
                                        </td>
                                        <td>{{ $method->updated_at->format('d M, Y') }}</td>
                                        <td>
                                            <a href="{{ route('payment-methods.edit', $method->id) }}" class="btn btn-sm btn-info text-white">Edit</a>
                                            <button type="button" class="btn btn-sm btn-danger deletePaymentMethod" data-id="{{ $method->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No payment methods found.</td>
                                    </tr>
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

    $(document).on('click', '.deletePaymentMethod', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        // Resource controller standard: DELETE /payment-methods/{id}
        let deleteUrl = "{{ route('payment-methods.destroy', ':id') }}".replace(':id', id);

        Swal.fire({
            title: 'Delete Payment Method?',
            text: "Are you sure? This will remove the configuration!",
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
                        Swal.fire('Error!', 'Could not delete the record.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection