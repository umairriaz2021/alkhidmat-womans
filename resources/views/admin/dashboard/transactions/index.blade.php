@extends('admin.layout')
@section('title','All Transactions')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">All Transactions</h4>
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> Full Name </th>
                                    <th> Email </th>
                                    <th> Phone </th>
                                    <th>Amount</th>
                                    <th> Status </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions['data'] as $transaction)
                                <tr>
                                    <td>{{ $transaction['first_name']." ".$transaction['last_name'] }}</td>
                                    <td>{{ $transaction['email'] }}</td>
                                    <td>{{$transaction['phone']}}</td>
                                    <td>
                                       <span class="badge
                                                @if($transaction['status'] == 'complete')
                                                    bg-success-subtle text-success
                                                @elseif($transaction['status'] == 'pending')
                                                    bg-warning-subtle text-warning-emphasis
                                                @elseif($transaction['status'] == 'processing')
                                                    bg-info-subtle text-info-emphasis
                                                @else
                                                    bg-secondary-subtle text-secondary
                                                @endif
                                            ">
                                                {{ ucwords($transaction['status']) }}
                                            </span>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="transaction-data" data-id="{{$transaction['id']}}">View Details</a> | 
                                        <a href="javascript:void(0)" class="deleteTransaction text-danger" data-id="{{ $transaction['id'] }}">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3"></div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-loader" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <div id="modal-content-data" style="display:none;">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="35%">Order Number</th>
                                <td id="d-order-number"></td>
                            </tr>
                            <tr>
                                <th>Meezan Order Ref</th>
                                <td id="d-meezan-ref"></td>
                            </tr>
                            <tr>
                                <th>Full Name</th>
                                <td id="d-name"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="d-email"></td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td id="d-phone"></td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td id="d-amount"></td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td id="d-address"></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td id="d-status"></td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td id="d-created-at"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function() {
  
    
    $('.transaction-data').on('click', function(e) {
        e.preventDefault();
        
        var transactionId = $(this).data('id');
        var modal = $('#transactionModal');

        // Reset & show loader
        $('#modal-loader').show();
        $('#modal-content-data').hide();
        modal.modal('show');

        $.ajax({
            url: "{{ route('admin.transactions.details', ':id') }}".replace(':id',transactionId),
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    var t = response.data;

                    $('#d-order-number').text(t.order_number || 'N/A');
                    $('#d-meezan-ref').text(t.meezan_order_ref || 'N/A');
                    $('#d-name').text((t.first_name || '') + ' ' + (t.last_name || ''));
                    $('#d-email').text(t.email || 'N/A');
                    $('#d-phone').text(t.phone || 'N/A');
                    $('#d-amount').text((t.currency || 'PKR') + ' ' + t.amount);
                    $('#d-address').text([t.address, t.city, t.postal_code].filter(Boolean).join(', ') || 'N/A');
                    $('#d-status').html('<span class="badge bg-primary">' + (t.status ? t.status.toUpperCase() : 'N/A') + '</span>');
                    $('#d-created-at').text(t.created_at ? new Date(t.created_at).toLocaleString() : 'N/A');

                    $('#modal-loader').hide();
                    $('#modal-content-data').fadeIn();
                }
            },
            error: function(xhr) {
                $('#modal-loader').hide();
                alert(xhr.responseJSON?.message || 'Error fetching transaction details.');
                modal.modal('hide');
            }
        });
    });

    $(document).on('click', '.deleteTransaction', function(e) {
    e.preventDefault();

    var transactionId = $(this).data('id');
    var row = $(this).closest('tr'); // Delete hone par row remove karne ke liye

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            
            // Delete request bhejte waqt loader show karein
            Swal.fire({
                title: 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('admin.transactions.destroy', ':id') }}".replace(':id', transactionId),
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF Token
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Page reload kiye bina table se row ko fade out karein
                        row.fadeOut(500, function() {
                            $(this).remove();
                        });
                    }
                },
                error: function(xhr) {
                    var errorMsg = xhr.responseJSON?.message || 'Failed to delete transaction.';
                    Swal.fire({
                        title: 'Error!',
                        text: errorMsg,
                        icon: 'error'
                    });
                }
            });
        }
    });
});
});
</script>
@endsection