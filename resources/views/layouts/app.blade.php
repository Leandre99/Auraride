<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuraRide - @yield('title', 'Next-Gen Transit')</title>
    
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
            --bg-dark: #0A0914;
            --bg-card: rgba(18, 17, 32, 0.6);
            --border-glass: rgba(255, 255, 255, 0.08);
            --neon-pink: #FF1E83;
            --electric-cyan: #00E5FF;
            --text-main: #FFFFFF;
            --text-muted: #A09EAD;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* Glassmorphism Classes */
        .glass-panel {
            background: var(--bg-card);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.4);
        }

        .neon-text-primary {
            color: var(--electric-cyan);
            text-shadow: 0 0 10px rgba(0, 229, 255, 0.5);
        }
        .neon-text-secondary {
            color: var(--neon-pink);
            text-shadow: 0 0 10px rgba(255, 30, 131, 0.5);
        }

        /* Glow Buttons */
        .btn-glow-cyan {
            background: linear-gradient(135deg, #00E5FF, #0088FF);
            color: #000 !important;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        }
        .btn-glow-cyan:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(0, 229, 255, 0.7);
        }

        .btn-glow-pink {
            background: linear-gradient(135deg, #FF1E83, #B0005C);
            color: #FFF !important;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(255, 30, 131, 0.4);
        }
        .btn-glow-pink:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(255, 30, 131, 0.7);
        }

        /* Global Layout layout */
        .dashboard-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            height: 80px;
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-glass);
            background: rgba(10, 9, 20, 0.8);
            backdrop-filter: blur(20px);
            z-index: 1000;
            position: relative;
        }
        .navbar-brand-aura {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--electric-cyan), var(--neon-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }

        .main-content {
            flex-grow: 1;
            position: relative;
            display: flex;
            overflow: hidden;
        }
        
        @stack('styles')
    </style>
</head>
<body>

    <div class="dashboard-container">
        <!-- Navbar Component -->
        @include('components.navbar')

        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.css"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    @stack('scripts')
</body>
</html>
