<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        .card { border: 1px solid #ddd; padding: 20px; display: inline-block; border-radius: 10px; }
        h1 { color: #28a745; }
        .session-id { color: #666; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="card">
        <h1>✅ Payment Successful!</h1>
        <p>Thank you for your donation.</p>
        <p class="session-id"><strong>Session ID:</strong> {{ $sessionId }}</p>
        <br>
        <a href="{{url('/')}}">Back to Website</a>
    </div>
</body>
</html>