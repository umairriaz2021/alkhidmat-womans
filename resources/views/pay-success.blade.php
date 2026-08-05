<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5 text-center">
    <div class="card shadow-sm mx-auto" style="max-width: 500px;">
        <div class="card-body py-5">
            <h1 class="text-success display-4">✓</h1>
            <h2 class="text-success mb-3">Payment Successful!</h2>
            <p class="text-muted">{{ session('success') ?? 'Aapka order kamyabi se process ho chuka hai.' }}</p>
            <a href="{{ route('checkout') }}" class="btn btn-primary mt-3">Go Back to Store</a>
        </div>
    </div>
</div>

</body>
</html>