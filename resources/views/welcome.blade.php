{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | Hybrid Property System</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <h1>Welcome to Automated Property Management System</h1>
    <a href="{{ route('login') }}">Login</a> | 
    <a href="{{ route('register') }}">Register</a>
</body>
</html>
