@extends('layouts.app')

@section('title', 'ATLAS AND CO - Centre de Commande')

@push('styles')
    <style>
        body {
            background: #F0F2F5;
            overflow: hidden;
        }

        #map-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Floating Command Center */
        .command-center {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 440px;
            max-height: calc(100vh - 130px);
            z-index: 100;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 32px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .cc-header {
            padding: 30px 30px 20px;
        }

        .cc-content {
            padding: 0 30px 30px;
            overflow-y: auto;
            flex-grow: 1;
        }

        /* Input Styling */
        .ride-search-box {
            background: #FFF;
            border-radius: 20px;
            padding: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .ride-input-group {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #F1F5F9;
        }

        .ride-input-group:last-child {
            border-bottom: none;
        }

        .dot-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .dot-pickup {
            background: #2563EB;
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.4);
        }

        .dot-dropoff {
            background: #F59E0B;
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.4);
        }

        .ride-input {
            border: none;
            width: 100%;
            font-weight: 500;
            font-size: 1.05rem;
            color: #1E293B;
        }

        .ride-input:focus {
            outline: none;
        }

        /* Vehicle Selection */
        .vehicle-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 25px;
        }

        .vehicle-card-v2 {
            background: #FFF;
            border: 2px solid transparent;
            border-radius: 24px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .vehicle-card-v2:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .vehicle-card-v2.active {
            border-color: var(--primary);
            background: #F8FAFF;
        }

        .vehicle-img {
            width: 100%;
            height: 80px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        /* Floating Quick Actions */
        .quick-actions {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 100;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .action-btn {
            width: 55px;
            height: 55px;
            border-radius: 18px;
            background: #FFF;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .action-btn:hover {
            transform: scale(1.1);
            color: var(--primary);
        }

        /* Active Ride Modern Overlay */
        .modern-status-bar {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            background: #1E293B;
            border-radius: 24px;
            padding: 20px 30px;
            display: none;
            z-index: 1000;
            color: #FFF;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
        }

        /* Autocomplete Results */
        .autocomplete-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #FFF;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 250px;
            overflow-y: auto;
            margin-top: 5px;
            display: none;
            border: 1px solid #F1F5F9;
        }

        .result-item {
            padding: 12px 20px;
            cursor: pointer;
            border-bottom: 1px solid #F1F5F9;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        .result-item:hover {
            background: #F8FAFF;
        }

        .result-item:last-child {
            border-bottom: none;
        }
    </style>
@endpush

@section('content')
    <div id="map-container"></div>

    <div class="command-center" id="commandCenter">
        <div class="cc-header">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h2 class="h4 mb-0">Centre de Commande</h2>
                <span class="badge bg-primary-subtle text-primary px-3 rounded-pill">Privé</span>
            </div>
            <p class="text-muted small">Bienvenue chez ATLAS AND CO, {{ auth()->user()->name }}.</p>
        </div>

        <div class="cc-content">
            <div class="ride-search-box">
                <div class="ride-input-group position-relative">
                    <div class="dot-indicator dot-pickup"></div>
                    <input type="text" class="ride-input" id="pickupInput" placeholder="Point de départ" autocomplete="off">
                    <div id="pickupResults" class="autocomplete-results"></div>
                </div>
                <div class="ride-input-group position-relative">
                    <div class="dot-indicator dot-dropoff"></div>
                    <input type="text" class="ride-input" id="dropoffInput" placeholder="Où allez-vous ?"
                        autocomplete="off">
                    <div id="dropoffResults" class="autocomplete-results"></div>
                </div>
            </div>

            <div id="vehicleSection" style="display: none;">
                <div class="vehicle-grid" id="vehicleGrid">
                    <!-- Dynamic Content -->
                </div>

                <div class="mt-4 p-4 rounded-4 bg-light text-center">
                    <div class="small text-muted mb-2"><i class="bi bi-info-circle me-1"></i> Règlement à bord du véhicule
                    </div>
                    <div class="fw-bold small text-primary">Terminal de paiement disponible</div>
                </div>
            </div>

            <button class="btn btn-premium w-100 py-3 mt-4" id="mainActionBtn">Continuer</button>
        </div>
    </div>

    <div class="quick-actions">
        <div class="action-btn" title="Mes Lieux"><i class="bi bi-bookmark-fill"></i></div>
        <div class="action-btn" title="Paramètres"><i class="bi bi-gear-fill"></i></div>
        <div class="action-btn" title="Aide"><i class="bi bi-question-circle-fill"></i></div>
    </div>

    <div class="modern-status-bar" id="rideStatusBar">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="rounded-circle bg-primary p-2"
                    style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-car-front-fill fs-4"></i>
                </div>
            </div>
            <div class="col">
                <div class="small opacity-75">Chauffeur en route</div>
                <div class="fw-bold h5 mb-0" id="driverInfo">Michael • Tesla Model S</div>
            </div>
            <div class="col-auto text-end">
                <div class="text-primary h4 mb-0 fw-bold">3 min</div>
                <div class="small opacity-50">Arrivée estimée</div>
            </div>
        </div>
    </div>

    <!-- Rating & Payment Modal -->
    <div class="command-center" id="ratingModal"
        style="display: none; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="cc-header text-center">
            <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center mb-3"
                style="width: 70px; height: 70px;">
                <i class="bi bi-check2-circle h1 mb-0"></i>
            </div>
            <h2 class="h3">Course Terminée !</h2>
            <p class="text-muted">Comment s'est passé votre trajet ?</p>
        </div>
        <div class="cc-content">
            <div class="text-center mb-4">
                <div class="d-flex justify-content-center gap-2 h2 text-warning" id="starRating">
                    <i class="bi bi-star" data-value="1"></i>
                    <i class="bi bi-star" data-value="2"></i>
                    <i class="bi bi-star" data-value="3"></i>
                    <i class="bi bi-star" data-value="4"></i>
                    <i class="bi bi-star" data-value="5"></i>
                </div>
                <input type="hidden" id="ratingValue" value="5">
            </div>

            <div class="mb-4">
                <label class="small fw-bold text-muted mb-2">Commentaire (optionnel)</label>
                <textarea id="ratingComment" class="form-control border-0 bg-light rounded-4" rows="3"
                    placeholder="Un mot sur Michael..."></textarea>
            </div>

            <div class="mb-4 text-center">
                <p class="small text-muted mb-0">Règlement effectué au chauffeur.</p>
                <div class="fw-bold">Merci de votre confiance !</div>
                <input type="hidden" id="paymentMethod" value="cash">
            </div>

            <button class="btn btn-premium w-100 py-3" id="submitRating">Envoyer mon avis</button>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

            const map = L.map('map-container', { zoomControl: false }).setView([40.7128, -74.0060], 13);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_all/{z}/{x}/{y}.png').addTo(map);

            const mainActionBtn = document.getElementById('mainActionBtn');
            const vehicleGrid = document.getElementById('vehicleGrid');
            const vehicleSection = document.getElementById('vehicleSection');
            const rideStatusBar = document.getElementById('rideStatusBar');
            const pickupInput = document.getElementById('pickupInput');
            const dropoffInput = document.getElementById('dropoffInput');

            let state = 0;
            let selectedVehicle = null;
            let routeData = {
                pickup: null,
                dropoff: null
            };

            // Geocoding Logic
            async function searchAddress(query) {
                if (query.length < 3) return [];
                const response = await axios.get(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=5`);
                return response.data;
            }

            function setupAutocomplete(inputId, resultsId, type) {
                const input = document.getElementById(inputId);
                const results = document.getElementById(resultsId);
                let timer;

                input.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(async () => {
                        const data = await searchAddress(input.value);
                        results.innerHTML = '';
                        if (data.length > 0) {
                            results.style.display = 'block';
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'result-item';
                                div.innerText = item.display_name;
                                div.onclick = () => {
                                    input.value = item.display_name;
                                    results.style.display = 'none';
                                    routeData[type] = {
                                        lat: parseFloat(item.lat),
                                        lng: parseFloat(item.lon),
                                        address: item.display_name
                                    };
                                    map.setView([item.lat, item.lon], 15);
                                    L.marker([item.lat, item.lon]).addTo(map);
                                };
                                results.appendChild(div);
                            });
                        } else {
                            results.style.display = 'none';
                        }
                    }, 500);
                });

                document.addEventListener('click', (e) => {
                    if (!input.contains(e.target) && !results.contains(e.target)) {
                        results.style.display = 'none';
                    }
                });
            }

            setupAutocomplete('pickupInput', 'pickupResults', 'pickup');
            setupAutocomplete('dropoffInput', 'dropoffResults', 'dropoff');

            mainActionBtn.addEventListener('click', async () => {
                if (state === 0) {
                    if (!routeData.pickup || !routeData.dropoff) {
                        alert('Veuillez sélectionner un point de départ et une destination parmi les suggestions.');
                        return;
                    }

                    mainActionBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Calcul...';
                    mainActionBtn.disabled = true;

                    try {
                        const response = await axios.post('/trips/estimate', {
                            pickup_lat: routeData.pickup.lat, pickup_lng: routeData.pickup.lng,
                            dropoff_lat: routeData.dropoff.lat, dropoff_lng: routeData.dropoff.lng
                        });

                        vehicleGrid.innerHTML = '';
                        response.data.forEach((opt, index) => {
                            const card = document.createElement('div');
                            card.className = `vehicle-card-v2 ${index === 0 ? 'active' : ''}`;
                            card.innerHTML = `
                                <div class="rounded-4 overflow-hidden shadow-lg mb-2" style="transform: rotate(2deg);">
                                    <img src="${opt.id === 1 ? '{{ asset('images/berline-standard.jpg') }}' : (opt.id === 2 ? '{{ asset('images/van-luxe.jpg') }}' : 'https://images.unsplash.com/photo-1549416878-b9ca35c2d47b?auto=format&fit=crop&w=300')}" class="w-100 h-100" style="object-fit: cover;" alt="ATLAS AND CO Fleet">
                                </div>
                                <div class="fw-bold">${opt.name}</div>
                                <div class="text-primary fw-bold">${opt.price}€</div>
                            `;
                            card.addEventListener('click', () => {
                                document.querySelectorAll('.vehicle-card-v2').forEach(c => c.classList.remove('active'));
                                card.classList.add('active');
                                selectedVehicle = opt;
                            });
                            vehicleGrid.appendChild(card);
                            if (index === 0) selectedVehicle = opt;
                        });

                        // routeData was already partially filled by autocomplete, we complete it here
                        routeData.distance = response.data[0].distance;
                        routeData.duration = response.data[0].duration;

                        // We prepare the final object for the store request
                        const finalTripData = {
                            vehicle_type_id: selectedVehicle.id,
                            pickup_address: routeData.pickup.address,
                            dropoff_address: routeData.dropoff.address,
                            pickup_lat: routeData.pickup.lat,
                            pickup_lng: routeData.pickup.lng,
                            dropoff_lat: routeData.dropoff.lat,
                            dropoff_lng: routeData.dropoff.lng,
                            price: selectedVehicle.price,
                            distance: routeData.distance,
                            duration: routeData.duration
                        };

                        // Store it for state 1
                        window.currentBookingData = finalTripData;

                        gsap.set(vehicleSection, { display: "block", opacity: 0, y: 20 });
                        gsap.to(vehicleSection, { opacity: 1, y: 0, duration: 0.5 });

                        mainActionBtn.innerHTML = 'Confirmer ATLAS AND CO';
                        mainActionBtn.disabled = false;
                        state = 1;
                    } catch (e) {
                        mainActionBtn.innerHTML = 'Réessayer';
                        mainActionBtn.disabled = false;
                    }
                } else if (state === 1) {
                    mainActionBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Envoi...';
                    mainActionBtn.disabled = true;

                    try {
                        const response = await axios.post('/trips', window.currentBookingData);

                        const trip = response.data;
                        let currentTripId = trip.id;

                        if (window.Echo) {
                            const channel = window.Echo.private(`trip.${trip.id}`);

                            channel.listen('TripAccepted', (e) => {
                                gsap.to("#commandCenter", { x: -500, opacity: 0, duration: 0.8, ease: "power4.in", display: "none" });
                                document.getElementById('driverInfo').innerText = `${e.trip.driver?.name || 'Michael'} • Tesla Model S`;
                                gsap.set(rideStatusBar, { display: "block", opacity: 0, y: 50 });
                                gsap.to(rideStatusBar, { opacity: 1, y: 0, duration: 1 });
                            });

                            channel.listen('TripStarted', (e) => {
                                document.querySelector('.modern-status-bar .opacity-75').innerText = "Course en cours...";
                            });

                            channel.listen('TripCompleted', (e) => {
                                gsap.to(rideStatusBar, { opacity: 0, y: 50, duration: 0.5, display: "none" });
                                gsap.set("#ratingModal", { display: "flex", opacity: 0, scale: 0.9 });
                                gsap.to("#ratingModal", { opacity: 1, scale: 1, duration: 0.8, ease: "power4.out" });
                            });
                        }

                        mainActionBtn.innerHTML = 'Recherche d\'un chauffeur...';
                        mainActionBtn.style.background = '#64748B';

                        // Rating Logic
                        const stars = document.querySelectorAll('#starRating i');
                        stars.forEach(star => {
                            star.addEventListener('click', () => {
                                const val = star.getAttribute('data-value');
                                document.getElementById('ratingValue').value = val;
                                stars.forEach(s => {
                                    s.className = s.getAttribute('data-value') <= val ? 'bi bi-star-fill' : 'bi bi-star';
                                });
                            });
                        });

                        document.querySelectorAll('[data-method]').forEach(btn => {
                            btn.addEventListener('click', () => {
                                document.querySelectorAll('[data-method]').forEach(b => b.classList.remove('active'));
                                btn.classList.add('active');
                                document.getElementById('paymentMethod').value = btn.getAttribute('data-method');
                            });
                        });

                        document.getElementById('submitRating').addEventListener('click', async () => {
                            try {
                                await axios.post(`/trips/${currentTripId}/rate`, {
                                    rating: document.getElementById('ratingValue').value,
                                    comment: document.getElementById('ratingComment').value,
                                    payment_method: document.getElementById('paymentMethod').value
                                });
                                location.reload();
                            } catch (e) {
                                alert('Erreur lors de l\'envoi de votre retour.');
                            }
                        });
                    } catch (e) {
                        mainActionBtn.innerHTML = 'Échec. Réessayez.';
                        mainActionBtn.disabled = false;
                    }
                }
            });

            gsap.from("#commandCenter", { x: -100, opacity: 0, duration: 1, ease: "power3.out" });
        });
    </script>
@endpush
