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
        <div id="chatbot-panel" style="display:none; width:320px; height:450px; background:white; border-radius:16px; box-shadow:0 10px 40px rgba(0,0,0,0.15); overflow:hidden; flex-direction:column; position:absolute; bottom:70px; right:0; border: 1px solid #e5e7eb;">
            <!-- Header -->
            <div style="background:#2563EB; color:white; padding:15px; display:flex; justify-content:space-between; align-items:center;">
                <span class="fw-bold">Assistant Atlas And Co</span>
                <button id="chatbot-close" style="background:transparent; border:none; color:white; font-size:1.2rem; cursor:pointer;"><i class="bi bi-x-lg"></i></button>
            </div>
            <!-- Messages Area -->
            <div id="chatbot-messages" style="flex-grow:1; padding:15px; overflow-y:auto; background:#f8fafc; display:flex; flex-direction:column; gap:10px;">
                <div style="background:#e5e7eb; padding:10px 15px; border-radius:12px; align-self:start; max-width:85%; font-size:0.9rem; color: #1e293b;">
                    Bonjour ! Je suis l'assistant virtuel d'ATLAS AND CO. Comment puis-je vous aider aujourd'hui ?
                </div>
            </div>
            <!-- Input Area -->
            <div style="padding:15px; border-top:1px solid #e5e7eb; display:flex; gap:10px;">
                <input type="text" id="chatbot-input" placeholder="Écrivez votre message..." style="flex-grow:1; border:1px solid #e5e7eb; border-radius:8px; padding:8px 12px; font-size:0.9rem; outline:none;">
                <button id="chatbot-send" style="background:#2563EB; color:white; border:none; border-radius:8px; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bubble = document.getElementById('chatbot-bubble');
            const panel = document.getElementById('chatbot-panel');
            const closeBtn = document.getElementById('chatbot-close');
            const input = document.getElementById('chatbot-input');
            const sendBtn = document.getElementById('chatbot-send');
            const messagesContainer = document.getElementById('chatbot-messages');

            bubble.addEventListener('click', () => {
                panel.style.display = panel.style.display === 'none' ? 'flex' : 'none';
                if (panel.style.display === 'flex') {
                    input.focus();
                }
            });

            closeBtn.addEventListener('click', () => {
                panel.style.display = 'none';
            });

            function appendMessage(text, role) {
                const msg = document.createElement('div');
                msg.style.padding = '10px 15px';
                msg.style.borderRadius = '12px';
                msg.style.maxWidth = '85%';
                msg.style.fontSize = '0.9rem';
                msg.style.wordBreak = 'break-word';

                if (role === 'user') {
                    msg.style.background = '#2563EB';
                    msg.style.color = 'white';
                    msg.style.alignSelf = 'end';
                } else {
                    msg.style.background = '#e5e7eb';
                    msg.style.color = '#1e293b';
                    msg.style.alignSelf = 'start';
                }

                msg.innerText = text;
                messagesContainer.appendChild(msg);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            async function sendMessage() {
                const text = input.value.trim();
                if (!text) return;

                input.value = '';
                appendMessage(text, 'user');

                // Typing indicator
                const typing = document.createElement('div');
                typing.id = 'chatbot-typing';
                typing.style.background = '#e5e7eb';
                typing.style.padding = '10px 15px';
                typing.style.borderRadius = '12px';
                typing.style.alignSelf = 'start';
                typing.style.fontSize = '0.9rem';
                typing.style.color = '#1e293b';
                typing.innerText = '...';
                messagesContainer.appendChild(typing);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                try {
                    const response = await fetch('{{ route("chatbot.message") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ message: text })
                    });
                    const data = await response.json();
                    
                    const typingElem = document.getElementById('chatbot-typing');
                    if (typingElem) typingElem.remove();
                    
                    appendMessage(data.reply, 'bot');
                } catch (error) {
                    const typingElem = document.getElementById('chatbot-typing');
                    if (typingElem) typingElem.remove();
                    appendMessage('Désolé, je rencontre un problème. Contactez-nous au 0758279237.', 'bot');
                }
            }

            sendBtn.addEventListener('click', sendMessage);
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') sendMessage();
            });
        });
    </script>
</body>
</html>
