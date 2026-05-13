@extends('admin.layout')
@section('title', isset($paymentMethod) ? 'Edit Payment Method' : 'Create Payment Method')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ isset($paymentMethod) ? 'Edit Payment Method' : 'Add Payment Method' }}</h4>
                    <div id="ajax-alert-container"></div>
                    
                    <form id="paymentMethodForm" action="{{ isset($paymentMethod) ? route('payment-methods.update', $paymentMethod->id) : route('payment-methods.store') }}" method="POST">
                        @csrf
                        @if(isset($paymentMethod)) @method('PATCH') @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Method Name (e.g. Stripe, EasyPaisa)</label>
                                    <input type="text" name="name" class="form-control" id="name_input" value="{{ $paymentMethod->name ?? '' }}" placeholder="Enter name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status_id" class="form-select">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ (isset($paymentMethod) && $paymentMethod->status_id == $status->id) ? 'selected' : '' }}>
                                                {{ ucfirst($status->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">

                             <x-media-picker name="image_id" label="Logo Image" 
                                               :img_id="$paymentMethod->image_id ?? ''" :preview_path="$paymentMethod->image->file_path ?? ''" />
                        </div>

                       <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Public Key / Merchant ID</label>
                                <input type="text" 
                                    name="general[public_key]" 
                                    class="form-control" 
                                    value="{{ $paymentMethod->general['public_key'] ?? '' }}" 
                                    placeholder="Enter Public Key">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Secret Key / Password</label>
                                <input type="password" 
                                    name="general[secret_key]" 
                                    class="form-control" 
                                    value="{{ $paymentMethod->general['secret_key'] ?? '' }}" 
                                    placeholder="Enter Secret Key">
                            </div>
                        </div>
                    </div>

                        <button type="submit" class="btn btn-primary apply">Save Payment Method</button>
                        <a href="{{ route('payment-methods.index') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // AJAX Form Submission
    $('#paymentMethodForm').on('submit', function(e) {
    e.preventDefault(); // Sab se pehle form submit hone se rokein
    
    // Purane errors ko saaf karein
    $('#ajax-alert-container').html('');
    
    let form = $(this);
    let formData = new FormData(this);
    let submitBtn = $('.apply');

    $.ajax({
        url: form.attr('action'),
        type: 'POST', 
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() { 
            submitBtn.prop('disabled', true).text('Processing...'); 
        },
        success: function(response) {
            // Check karein ke response waqai success hai
            if(response.success === true || response.status === 'success') {
                window.location.href = "{{ route('payment-methods.index') }}";
            } else {
                // Agar 200 OK aya lekin success false hai (Logical Error)
                submitBtn.prop('disabled', false).text('Save Payment Method');
                let errorMsg = response.message ? response.message : 'Something went wrong.';
                $('#ajax-alert-container').html('<div class="alert alert-danger">' + errorMsg + '</div>');
            }
        },
        error: function(xhr) {
            // Redirect rokne ke liye button wapis enable karein
            submitBtn.prop('disabled', false).text('Save Payment Method');

            let errorHtml = '<div class="alert alert-danger"><ul>';
            
            if (xhr.status === 422) { // Laravel Validation Error
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(k, v) { 
                    errorHtml += '<li>' + v[0] + '</li>'; 
                });
            } else { // 500 ya koi aur error
                errorHtml += '<li>Internal Server Error or Unexpected response.</li>';
            }

            errorHtml += '</ul></div>';
            $('#ajax-alert-container').html(errorHtml);
            
            // Error dekhne ke liye top par scroll karein
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        }
    });
});

  
});
</script>
@endsection