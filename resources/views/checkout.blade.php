<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            @if(session('error'))
                <div class="alert alert-danger mb-3">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Order Summary</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Demo Product Name</span>
                        <strong>Rs. 1,000.00</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4 fs-5">
                        <span>Total Amount:</span>
                        <strong class="text-success">Rs. 1,000.00</strong>
                    </div>

                    <!-- Payment Trigger Form -->
                    <form action="{{ route('meezan.pay') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            Pay Rs. 1,000 via Meezan Bank
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>