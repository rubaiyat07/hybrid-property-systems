{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- @include('partials.header') -->
    <!-- @include('partials.sidebar') -->

    <div class="content">
        @yield('content')
    </div>

    <!-- @include('partials.footer') -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
