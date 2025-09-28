{{-- resources/views/layouts/admin.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HybridEstate - Admin Dashboard')</title>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <!-- Vite Compiled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <div class="admin-wrapper">
        <!-- Header -->
        @include('partials.dashboard_header')

        <div class="admin-main-container">
            <!-- Sidebar -->
            @include('partials.sidebar')

            <!-- Main Content -->
            <main class="admin-content">
                <!-- Page Content -->
                <div class="content-wrapper">
                    @yield('content')
                </div>

                <!-- Footer -->
                @include('partials.dashboard_footer')
            </main>
        </div>
    </div>

    <!-- Custom JS -->
    <script src="{{ asset('js/welcome.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>

    <!-- Stack Scripts -->
    @stack('scripts')
</body>
</html>
