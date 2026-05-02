<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATLAS AND CO - @yield('title', 'Next-Gen Transit')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <!-- Leaflet CSS for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    
    <style>
        :root {
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --primary: #2563EB;
            --primary-dark: #1E40AF;
            --accent: #F59E0B;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --border-light: #E2E8F0;
            --glass-bg: rgba(255, 255, 255, 0.85);
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
        }

        /* Premium Components */
        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-light);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        }

        .text-primary-gradient {
            background: linear-gradient(135deg, var(--primary), #6366F1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Premium Buttons */
        .btn-premium {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #FFF !important;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 14px 28px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
            filter: brightness(1.1);
        }

        .btn-outline-premium {
            background: transparent;
            color: var(--primary) !important;
            font-weight: 600;
            border: 2px solid var(--primary);
            border-radius: 12px;
            padding: 12px 26px;
            transition: all 0.3s ease;
        }
        .btn-outline-premium:hover {
            background: var(--primary);
            color: #FFF !important;
        }

        /* Layout */
        .navbar-aura {
            height: 90px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .main-wrapper {
            min-height: calc(100vh - 90px);
            position: relative;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    @include('components.navbar')

    <div class="main-wrapper">
        @yield('content')
    </div>

    <!-- Footer could go here -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.css"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @vite(['resources/js/app.js'])
    
    @stack('scripts')
</body>
</html>
