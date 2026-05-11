<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATLAS AND CO - @yield('title', 'Next-Gen Transit')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <!-- Leaflet CSS for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
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

        html, body {
            overflow-x: hidden;
            width: 100%;
            position: relative;
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
            min-height: 90px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        @media (min-width: 992px) {
            .navbar-aura { height: 90px; }
        }

        /* Navbar Mobile Fix */
        /* Admin Layout Responsive */
        /* Sidebar Styling */
        .admin-sidebar .glass-panel {
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: sticky;
            top: 100px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .status-pill {
            padding: 4px 12px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.75rem;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid currentColor;
            background-color: rgba(0,0,0,0.03) !important;
        }

        .sidebar-link i {
            font-size: 1.2rem;
        }

        .sidebar-link:hover {
            background: #f8fafc;
            color: var(--primary);
        }

        .sidebar-link.active {
            background: var(--primary);
            color: #FFF;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        
        .admin-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-top: -40px;
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 100%;
        }
        @media (max-width: 991.98px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
        }
        @media (min-width: 992px) {
            .admin-container {
                grid-template-columns: 280px 1fr;
                gap: 30px;
                margin-top: -100px;
            }
        }

        /* Navbar Mobile Enhancements */
        @media (max-width: 991.98px) {
            .navbar-aura {
                background: #FFFFFF !important;
                height: auto !important;
            }
            .navbar-collapse {
                background: #FFFFFF;
                margin: 0 -12px;
                padding: 20px;
                border-radius: 0 0 24px 24px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                border-top: 1px solid #f1f5f9;
                max-height: 80vh;
                overflow-y: auto;
            }
            .nav-item {
                border-bottom: 1px solid #f8fafc;
                padding: 5px 0;
            }
            .nav-item:last-child { border-bottom: none; }
            
            /* User Menu Mobile */
            .user-menu-mobile {
                margin-top: 15px;
                padding-top: 15px;
                border-top: 2px solid #f1f5f9;
            }

            /* Fix dropdowns on mobile to be full width or list-like */
            .navbar-collapse .dropdown-menu {
                position: static !important;
                float: none !important;
                width: 100% !important;
                margin-top: 10px !important;
                box-shadow: none !important;
                background: #f8fafc !important;
                border-radius: 12px !important;
                border: 1px solid #f1f5f9 !important;
                transform: none !important;
            }
        }

        /* Executive Header Responsive */
        .executive-header {
            padding: 40px 0 80px;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #FFF;
        }
        @media (max-width: 991.98px) {
            .executive-header {
                padding: 20px 0 40px;
                text-align: center;
            }
            .executive-header h1 {
                font-size: 1.75rem !important;
            }
        }

        /* KPI Cards Premium Style */
        .kpi-card {
            background: #FFF;
            border-radius: 16px;
            padding: 1.25rem;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
        }
        
        .kpi-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 991.98px) {
            .kpi-card {
                padding: 1rem !important;
                border-radius: 14px;
            }
            .kpi-card .h3 {
                font-size: 1.1rem !important;
                margin-top: 5px;
            }
            .kpi-card .small {
                font-size: 0.65rem !important;
                letter-spacing: 0.02em;
            }
            .kpi-icon {
                width: 32px !important;
                height: 32px !important;
                font-size: 1rem !important;
                margin-bottom: 8px !important;
                border-radius: 8px !important;
            }
        }
        
        .form-select-sm {
            padding-right: 2rem !important; /* Fix arrow overlap */
        }

        /* Admin Sidebar Mobile */
        @media (max-width: 991.98px) {
            .admin-sidebar .glass-panel {
                display: flex;
                flex-direction: row;
                overflow-x: auto;
                gap: 10px;
                padding: 10px !important;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }
            .admin-sidebar .sidebar-link {
                margin-bottom: 0 !important;
                padding: 8px 15px !important;
                font-size: 0.85rem;
            }
            .admin-sidebar .sidebar-link i {
                margin-right: 8px;
            }
            .admin-sidebar hr, .admin-sidebar .text-muted {
                display: none !important; /* Hide labels and dividers on mobile to save space */
            }
        }
        
        /* Admin Table Mobile Fix */
        .table-premium {
            width: 100%;
            overflow-x: hidden;
        }

        /* Mobile Data Cards Layout */
        .mobile-card-list {
            display: none; /* Hidden on desktop */
        }
        
        .mobile-data-card {
            background: #FFF;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 15px;
            border: 1px solid #eef2f7;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .mobile-data-card .card-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f8fafc;
        }
        .mobile-data-card .data-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        .mobile-data-card .data-label {
            color: #64748b;
            font-weight: 500;
        }
        .mobile-data-card .data-value {
            font-weight: 600;
            color: #1e293b;
        }

        @media (max-width: 991.98px) {
            .table-premium .table-responsive {
                display: none !important;
            }
            .mobile-card-list {
                display: block;
            }
            .table-premium {
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
            }
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

    <div class="main-wrapper d-flex flex-column min-vh-100">
        @yield('content')
        @include('components.footer')
    </div>

    <!-- Footer could go here -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @vite(['resources/js/app.js'])
    
    @stack('scripts')

    <!-- Chatbot Widget -->
    <div id="chatbot-container" style="position:fixed; bottom:28px; right:28px; z-index:9999;">
        <!-- Chat Bubble -->
        <button id="chatbot-bubble" style="background:#2563EB; color:white; border:none; border-radius:50%; width:58px; height:58px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 16px rgba(0,0,0,0.2); transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
            <i class="bi bi-chat-dots-fill" style="font-size: 1.5rem;"></i>
        </button>

        <!-- Chat Panel -->
        <div id="chatbot-panel" style="display:none; width:340px; height:500px; background:white; border-radius:20px; box-shadow:0 15px 50px rgba(0,0,0,0.2); overflow:hidden; flex-direction:column; position:absolute; bottom:80px; right:0; border: 1px solid rgba(0,0,0,0.05);">
            <!-- Header -->
            <div style="background: linear-gradient(135deg, #2563EB 0%, #1E40AF 100%); color:white; padding:20px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div class="fw-bold">Assistant Atlas And Co</div>
                    <div style="font-size: 0.75rem; opacity: 0.8;"><i class="bi bi-circle-fill text-success me-1" style="font-size: 0.5rem;"></i> En ligne</div>
                </div>
                <button id="chatbot-close" style="background:rgba(255,255,255,0.1); border:none; color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer;"><i class="bi bi-x-lg"></i></button>
            </div>
            
            <!-- Messages Area -->
            <div id="chatbot-messages" style="flex-grow:1; padding:20px; overflow-y:auto; background:#F8FAFC; display:flex; flex-direction:column; gap:12px;">
                <div style="background:white; padding:12px 16px; border-radius:15px; border-bottom-left-radius:2px; align-self:start; max-width:85%; font-size:0.9rem; color: #1e293b; shadow: 0 2px 5px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">
                    Bonjour ! Comment puis-je vous aider ? Choisissez une question ci-dessous :
                </div>
            </div>

            <!-- Suggestions Area -->
            <div id="chatbot-suggestions" style="padding:15px; background:white; border-top:1px solid #edf2f7; display:flex; flex-wrap:wrap; gap:8px;">
                <!-- Peuplé par JS -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bubble = document.getElementById('chatbot-bubble');
            const panel = document.getElementById('chatbot-panel');
            const closeBtn = document.getElementById('chatbot-close');
            const messagesContainer = document.getElementById('chatbot-messages');
            const suggestionsContainer = document.getElementById('chatbot-suggestions');

            const qaData = [
                { 
                    q: "Comment réserver ?", 
                    a: "C'est simple ! Cliquez sur 'Réserver une course' sur la page d'accueil, choisissez votre point de départ et d'arrivée, puis sélectionnez votre véhicule." 
                },
                { 
                    q: "Quels sont vos tarifs ?", 
                    a: "Nos tarifs dépendent de la distance et du type de véhicule. La Berline commence à 1.5€/km, le Van à 2.5€/km et le Sprinter à 4€/km." 
                },
                { 
                    q: "Proposez-vous de la location ?", 
                    a: "Oui ! Nous proposons des locations de véhicules de prestige avec ou sans chauffeur pour des durées allant d'une journée à plusieurs semaines." 
                },
                { 
                    q: "Comment contacter un agent ?", 
                    a: "Vous pouvez nous appeler directement au 07 58 27 92 37 ou nous envoyer un email à contact@atlasandco.fr." 
                }
            ];

            bubble.addEventListener('click', () => {
                panel.style.display = panel.style.display === 'none' ? 'flex' : 'none';
                if (panel.style.display === 'flex' && suggestionsContainer.children.length === 0) {
                    showSuggestions();
                }
            });

            closeBtn.addEventListener('click', () => {
                panel.style.display = 'none';
            });

            function showSuggestions() {
                suggestionsContainer.innerHTML = '';
                qaData.forEach((item, index) => {
                    const btn = document.createElement('button');
                    btn.innerText = item.q;
                    btn.style.background = '#F1F5F9';
                    btn.style.border = '1px solid #E2E8F0';
                    btn.style.borderRadius = '20px';
                    btn.style.padding = '6px 12px';
                    btn.style.fontSize = '0.8rem';
                    btn.style.cursor = 'pointer';
                    btn.style.transition = 'all 0.2s';
                    btn.onmouseover = () => { btn.style.background = '#2563EB'; btn.style.color = 'white'; };
                    btn.onmouseout = () => { btn.style.background = '#F1F5F9'; btn.style.color = 'black'; };
                    btn.onclick = () => handleQuestion(index);
                    suggestionsContainer.appendChild(btn);
                });
            }

            function appendMessage(text, role) {
                const msg = document.createElement('div');
                msg.style.padding = '12px 16px';
                msg.style.fontSize = '0.9rem';
                msg.style.maxWidth = '85%';
                msg.style.wordBreak = 'break-word';
                msg.style.boxShadow = '0 2px 5px rgba(0,0,0,0.02)';

                if (role === 'user') {
                    msg.style.background = '#2563EB';
                    msg.style.color = 'white';
                    msg.style.alignSelf = 'end';
                    msg.style.borderRadius = '15px';
                    msg.style.borderBottomRightRadius = '2px';
                } else {
                    msg.style.background = 'white';
                    msg.style.color = '#1e293b';
                    msg.style.alignSelf = 'start';
                    msg.style.borderRadius = '15px';
                    msg.style.borderBottomLeftRadius = '2px';
                    msg.style.border = '1px solid #edf2f7';
                }

                msg.innerText = text;
                messagesContainer.appendChild(msg);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function handleQuestion(index) {
                const item = qaData[index];
                appendMessage(item.q, 'user');
                
                setTimeout(() => {
                    appendMessage(item.a, 'bot');
                }, 500);
            }
        });
    </script>
</body>
</html>

