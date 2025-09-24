<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow text-center">
            <h1 class="text-2xl font-bold text-green-600 mb-4">✅ Payment Successful!</h1>
            <p class="mb-4">Thank you, your order has been placed successfully.</p>
            <a href="{{ route('home') }}" class="px-4 py-2 bg-black text-white rounded">Go to Home</a>
        </div>
    </div>
</body>
</html>
