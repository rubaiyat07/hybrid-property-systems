<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HybridEstate - User Dashboard')</title>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <!-- Vite Compiled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner.css') }}">
</head>
<body>
    <!-- Header -->
    @include('partials.owner_header')

    <div class="owner-main-container">
        <!-- Main Content -->
        <main class="owner-content">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Footer -->
    @include('partials.dashboard_footer')

    <!-- Custom JS -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/owner.js') }}"></script>

    @stack('scripts')
</body>
</html>
