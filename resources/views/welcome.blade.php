<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuraRide - Next-Gen Transit</title>
    
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

        /* Map Container */
        #map-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            filter: contrast(1.1) saturate(1.2) brightness(0.8) hue-rotate(210deg) invert(0.9);
            /* A quick hack to give Leaflet a dark outrun-ish vibe */
        }

        /* Sidebar Panels */
        .ride-panel {
            position: relative;
            z-index: 10;
            width: 450px;
            margin: 20px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            transform: translateX(-50px);
            opacity: 0;
        }

        .input-dark {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #FFF;
            padding: 16px 20px;
            border-radius: 12px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .input-dark:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--electric-cyan);
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
            color: #FFF;
            outline: none;
        }
        .input-dark::placeholder {
            color: var(--text-muted);
        }

        /* Vehicle Options */
        .vehicle-option {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .vehicle-option.selected {
            border-color: var(--electric-cyan);
            background: rgba(0, 229, 255, 0.05);
            box-shadow: inset 0 0 20px rgba(0, 229, 255, 0.1);
        }
        .vehicle-option:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: scale(1.02);
        }

        .vehicle-icon {
            font-size: 2rem;
            margin-right: 15px;
        }
        
        .price-tag {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--electric-cyan);
        }
        
        .payment-methods {
            display: flex;
            gap: 10px;
        }
        .payment-pill {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-pill:hover, .payment-pill.active {
            border-color: var(--neon-pink);
            color: var(--neon-pink);
            background: rgba(255, 30, 131, 0.05);
            box-shadow: 0 0 10px rgba(255, 30, 131, 0.2);
        }

        /* Customizing leaflet attribution for aesthetics */
        .leaflet-control-container .leaflet-bottom,
        .leaflet-control-container .leaflet-top {
            display: none !important;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <!-- Navbar -->
        <nav class="navbar-custom">
            <div class="navbar-brand-aura">AuraRide</div>
            <div class="d-flex align-items-center gap-4">
                <a href="#" class="text-decoration-none text-white opacity-75 hover-opacity-100">Driver Mode</a>
                <a href="#" class="text-decoration-none text-white opacity-75 hover-opacity-100">My Trips</a>
                <div class="dropdown">
                    <button class="btn btn-dark rounded-circle" type="button" style="width:45px; height:45px; display:flex; align-items:center; justify-content:center; border: 1px solid var(--electric-cyan);">
                        JD
                    </button>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Background Map -->
            <div id="map-container"></div>

            <!-- Ride Booking Panel -->
            <div class="ride-panel glass-panel" id="bookingPanel">
                <h2 class="mb-1">Where to, <span class="neon-text-primary">John?</span></h2>
                <p class="text-muted mb-4">Book your premium transit instantly.</p>

                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="position-relative">
                        <input type="text" class="form-control input-dark w-100" placeholder="Current Location" value="1204 Broadway, New York" id="pickupInput">
                        <div class="position-absolute" style="top: 20px; left: -25px; width: 10px; height: 10px; background:var(--electric-cyan); border-radius: 50%; box-shadow: 0 0 10px var(--electric-cyan);"></div>
                        <div class="position-absolute" style="top: 35px; left: -21px; width: 2px; height: 35px; background:rgba(255,255,255,0.2);"></div>
                    </div>
                    <div class="position-relative">
                        <input type="text" class="form-control input-dark w-100" placeholder="Destination" id="dropoffInput">
                        <div class="position-absolute" style="top: 20px; left: -25px; width: 10px; height: 10px; background:var(--neon-pink); border-radius: 50%; box-shadow: 0 0 10px var(--neon-pink);"></div>
                    </div>
                </div>

                <!-- Vehicles List (Hidden until destination entered) -->
                <div id="vehiclesList" style="display:none;" class="d-flex flex-column gap-3 mb-4">
                    <div class="vehicle-option selected d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="vehicle-icon">⚡</div>
                            <div>
                                <h5 class="mb-0">Aura Volt</h5>
                                <small class="text-muted">Electric • 3 mins away</small>
                            </div>
                        </div>
                        <div class="price-tag">$18.50</div>
                    </div>
                    
                    <div class="vehicle-option d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="vehicle-icon">👑</div>
                            <div>
                                <h5 class="mb-0">Aura Black</h5>
                                <small class="text-muted">Luxury SUV • 5 mins away</small>
                            </div>
                        </div>
                        <div class="price-tag neon-text-secondary">$34.00</div>
                    </div>
                </div>

                <!-- Payment Methods (Hidden initially) -->
                <div id="paymentSection" style="display:none;" class="mb-4">
                    <label class="text-muted mb-2 small">Payment Method</label>
                    <div class="payment-methods">
                        <div class="payment-pill active">Apple Pay</div>
                        <div class="payment-pill">•••• 4242</div>
                        <div class="payment-pill">Cash</div>
                    </div>
                </div>

                <button class="btn-glow-cyan w-100 mt-auto" id="mainActionBtn">Calculate Route</button>
            </div>
            
            <!-- Ride Active Panel (Hidden initially) -->
            <div class="ride-panel glass-panel" id="activeRidePanel" style="display:none; position:absolute; top:20px; right:20px; transform: translateX(50px);">
                <h4 class="mb-3">Driver Arriving</h4>
                <div class="d-flex align-items-center mb-4">
                    <div style="width:60px; height:60px; background:#fff; border-radius:50%; margin-right:15px; overflow:hidden;">
                        <img src="https://i.pravatar.cc/150?img=11" alt="Driver" class="w-100 h-100" style="object-fit:cover;">
                    </div>
                    <div>
                        <h5 class="mb-0" id="driverName">Michael T.</h5>
                        <div class="text-warning">★ 4.9 <span class="text-muted">(1,240 trips)</span></div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center glass-panel p-3 mb-4" style="background:rgba(255,255,255,0.02)">
                    <div>
                        <div class="text-muted small">Tesla Model S</div>
                        <div class="h5 mb-0" style="letter-spacing: 2px;">NXT GEN</div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small">Arriving in</div>
                        <div class="h4 mb-0 neon-text-primary">3 min</div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-outline-light w-50" style="border-radius:12px;">Contact</button>
                    <button class="btn btn-outline-danger w-50" style="border-radius:12px;">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.css"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            // Animate Panel In
            gsap.to("#bookingPanel", {
                x: 0,
                opacity: 1,
                duration: 1,
                ease: "power3.out",
                delay: 0.2
            });

            // Init Leaflet Map
            const map = L.map('map-container').setView([40.7128, -74.0060], 13); // NY Coordinates
            
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);

            // Flow Logic variables
            const mainActionBtn = document.getElementById('mainActionBtn');
            const dropoffInput = document.getElementById('dropoffInput');
            const vehiclesList = document.getElementById('vehiclesList');
            const paymentSection = document.getElementById('paymentSection');
            const activeRidePanel = document.getElementById('activeRidePanel');
            let state = 0; // 0: enter dest, 1: choose ride, 2: looking, 3: active

            // Dummy route drawing
            let routeLine = null;

            mainActionBtn.addEventListener('click', () => {
                if(state === 0) {
                    if(dropoffInput.value.length < 3) {
                        dropoffInput.value = "Central Park, NY";
                    }
                    
                    // Simulate route calculation
                    mainActionBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Calculating...';
                    
                    setTimeout(() => {
                        // Show vehicles list and payments
                        gsap.set([vehiclesList, paymentSection], { display: "flex", opacity: 0, y: 20 });
                        gsap.to([vehiclesList, paymentSection], { opacity: 1, y: 0, duration: 0.5, stagger: 0.2 });
                        mainActionBtn.innerHTML = 'Confirm AuraRide';
                        state = 1;

                        // Draw a glowing "laser" line on map
                        const latlngs = [
                            [40.7128, -74.0060],
                            [40.7200, -73.9950],
                            [40.7300, -73.9900],
                            [40.7812, -73.9665] // Central Park
                        ];
                        routeLine = L.polyline(latlngs, {color: '#00E5FF', weight: 4, className: 'neon-path'}).addTo(map);
                        map.fitBounds(routeLine.getBounds(), {padding: [50, 50]});
                        
                        // Add marker CSS glowing effect
                        L.circleMarker([40.7812, -73.9665], {color: '#FF1E83', fillColor: '#FF1E83', fillOpacity: 1, radius: 6}).addTo(map);

                    }, 1000);

                } else if(state === 1) {
                    // Confirming ride
                    mainActionBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Requesting Driver...';
                    mainActionBtn.classList.replace('btn-glow-cyan', 'btn-glow-pink');
                    
                    setTimeout(() => {
                        // Collapse booking panel slightly or hide it, and bring in Active Ride Panel
                        gsap.to("#bookingPanel", { x: -50, opacity: 0, duration: 0.5, display: "none" });
                        
                        gsap.set(activeRidePanel, { display: "block" });
                        gsap.to(activeRidePanel, { x: 0, opacity: 1, duration: 0.8, ease: "power3.out", left: 20 });

                        // Add driver marker
                        L.circleMarker([40.7250, -73.9980], {color: '#ffffff', fillColor: '#fff', fillOpacity: 1, radius: 8}).addTo(map);

                    }, 2000);
                }
            });

            // Vehicle Selection logic
            document.querySelectorAll('.vehicle-option').forEach(el => {
                el.addEventListener('click', function() {
                    document.querySelectorAll('.vehicle-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });

            // Payment Pill Selection
            document.querySelectorAll('.payment-pill').forEach(el => {
                el.addEventListener('click', function() {
                    document.querySelectorAll('.payment-pill').forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>
