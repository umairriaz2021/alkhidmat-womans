<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .success-icon {
            font-size: 5rem;
            color: #198754;
            line-height: 1;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center min-vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card border-0 shadow-sm text-center py-5 px-4 rounded-4">
                <div class="card-body">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill success-icon"></i>
                    </div>

                    <!-- Heading & Message -->
                    <h3 class="fw-bold text-dark mb-2">Payment Done Successfully!</h3>
                    <p class="text-muted mb-4 fs-6">
                        Thank you! Your payment has been processed successfully.
                    </p>

                    <!-- Home Redirect Button -->
                    <a href="{{ url('/') }}" class="btn btn-success btn-lg w-100 shadow-sm rounded-3">
                        <i class="bi bi-house-door me-2"></i>Back to Home
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>