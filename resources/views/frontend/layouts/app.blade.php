<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $generalSettings->site_name ?? 'Investment Platform')</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }

        .bg-dark-emerald {
            background-color: #034833 !important;
        }

        .text-dark-emerald {
            color: #034833 !important;
        }

        .border-dark-emerald {
            border-color: #034833 !important;
        }

        .card-emerald {
            background-color: #034833 !important;
            color: #ffffff !important;
            border-radius: 1.25rem;
            border: none;
        }

        .card-emerald-item {
            background-color: #034833 !important;
            color: #ffffff !important;
            border-radius: 1rem;
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-emerald,
        .btn-dark-emerald {
            background-color: #034833 !important;
            color: #ffffff !important;
            border: none !important;
        }

        .btn-emerald:hover,
        .btn-dark-emerald:hover {
            background-color: #023828 !important;
            color: #ffffff !important;
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Frontend Mobile App Header -->
    @include('frontend.layouts.header')

    <!-- Main App Content -->
    <main>
        @yield('content')
    </main>

    <!-- Frontend Mobile Bottom Navigation -->
    @include('frontend.layouts.footer')

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>