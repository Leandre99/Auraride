@extends('layouts.app')

@section('title', 'Réserver votre course VTC')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #tripMap {
        height: 550px;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
    }
    .booking-card {
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }
    .step-indicator {
        display: flex;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }
    .step-indicator .step {
        flex: 1;
        text-align: center;
        font-weight: 700;
        font-size: 0.8rem;
        color: #64748b;
        border-bottom: 3px solid transparent;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .step-indicator .step.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
    .location-input input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 0.95rem;
        font-weight: 500;
        color: #0f172a;
    }
    .autocomplete-results {
        position: absolute;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        width: 100%;
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
        margin-top: 10px;
    }
    .autocomplete-results .result-item {
        padding: 12px;
        cursor: pointer;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .autocomplete-results .result-item:hover {
        background: #f8fafc;
        color: #0f172a;
    }
    .vehicle-card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
    }
    .vehicle-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }
    .vehicle-card.selected {
        border-color: var(--primary);
        background: rgba(37, 99, 235, 0.02);
    }
    .input-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 12px;
    }
    .input-dot.pickup { background: #22c55e; }
    .input-dot.dropoff { background: #ef4444; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Colonne gauche : Carte Leaflet -->
        <div class="col-lg-7">
            <div class="map-container sticky-top" style="top: 100px; z-index: 1;">
                <div id="tripMap"></div>
            </div>
        </div>

        <!-- Colonne droite : Formulaire de réservation -->
        <div class="col-lg-5">
            <div class="card booking-card border-0">
                <div class="booking-header bg-primary text-white p-4">
                    <h4 class="fw-bold mb-1"><i class="bi bi-geo-alt-fill me-2"></i>Réserver votre course</h4>
                    <p class="mb-0 small text-white-50">Estimez votre trajet et réservez en quelques clics.</p>
                </div>

                <!-- Indicateur d'étapes -->
                <div class="step-indicator">
                    <div class="step active py-3" id="step1Indicator">1. Trajet</div>
                    <div class="step py-3" id="step2Indicator">2. Véhicule</div>
                    <div class="step py-3" id="step3Indicator">3. Contact</div>
                </div>

                <div class="booking-body p-4">
                    <!-- Étape 1 : Trajet -->
                    <div id="stepTrajet">
                        <div class="location-input bg-light border-0 p-3 rounded-4 mb-3">
                            <!-- Départ -->
                            <div class="input-row relative border-bottom pb-3 mb-3 d-flex align-items-center">
                                <div class="input-dot pickup"></div>
                                <div class="w-100 position-relative">
                                    <input type="text" id="pickupInput" class="w-100" placeholder="Saisir l'adresse de départ..." autocomplete="off" value="{{ request('pickup') }}">
                                    <div id="pickupResults" class="autocomplete-results shadow" style="display: none;"></div>
                                </div>
                            </div>
                            <!-- Destination -->
                            <div class="input-row relative pt-2 d-flex align-items-center">
                                <div class="input-dot dropoff"></div>
                                <div class="w-100 position-relative">
                                    <input type="text" id="dropoffInput" class="w-100" placeholder="Saisir la destination..." autocomplete="off" value="{{ request('dropoff') }}">
                                    <div id="dropoffResults" class="autocomplete-results shadow" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Date et Heure de planification -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">Planification (Optionnel)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-clock-fill"></i></span>
                                <input type="datetime-local" id="scheduledAtInput" class="form-control bg-light border-0 p-3 rounded-end-3" value="{{ request('date') }}" placeholder="Départ immédiat">
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Laissez vide pour un départ immédiat.</small>
                        </div>

                        <button class="btn btn-primary btn-premium w-100 py-3 rounded-pill fw-bold" id="continueBtn">
                            Estimer le trajet <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>

                    <!-- Étape 2 : Véhicules -->
                    <div id="stepVehicules" style="display: none;">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-car-front-fill text-primary me-2"></i>Choisissez votre véhicule</h5>
                        <div id="vehiclesList" class="mb-4 d-flex flex-column gap-3"></div>

                        <!-- Résumé trajet -->
                        <div id="tripDetails" class="mb-4 p-3 bg-light rounded-4 border shadow-sm">
                            <div class="row g-2 text-center small">
                                <div class="col-4 border-end">
                                    <div class="text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Distance</div>
                                    <div class="fw-bold text-dark" id="detailDistance">-</div>
                                </div>
                                <div class="col-4 border-end">
                                    <div class="text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Durée</div>
                                    <div class="fw-bold text-dark" id="detailDuration">-</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Prix TTC</div>
                                    <div class="fw-bold text-primary" id="detailPrice">-</div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-premium w-100 py-3 rounded-pill fw-bold mb-3" id="selectVehicleBtn">
                            Sélectionner et continuer <i class="bi bi-arrow-right ms-2"></i>
                        </button>

                        <button class="btn btn-link w-100 text-muted small text-decoration-none text-center" id="backToTrajetBtn">
                            <i class="bi bi-chevron-left me-1"></i> Modifier le trajet
                        </button>
                    </div>

                    <!-- Étape 3 : Contact & Confirmation -->
                    <div id="stepContact" style="display: none;">
                        @guest
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-fill text-primary me-2"></i>Vos Coordonnées</h5>
                            <p class="small text-muted mb-4">Entrez vos coordonnées pour recevoir votre confirmation et facture par mail.</p>
                            
                            <!-- Nom complet -->
                            <div class="mb-3">
                                <label for="guest_name" class="form-label fw-bold small text-uppercase text-muted" style="font-size: 0.7rem;">Nom Complet</label>
                                <input type="text" id="guest_name" class="form-control p-3 border-light rounded-3 bg-light" placeholder="Jean Dupont" required>
                            </div>

                            <!-- E-mail -->
                            <div class="mb-3">
                                <label for="guest_email" class="form-label fw-bold small text-uppercase text-muted" style="font-size: 0.7rem;">Adresse E-mail</label>
                                <input type="email" id="guest_email" class="form-control p-3 border-light rounded-3 bg-light" placeholder="jean.dupont@exemple.com" required>
                            </div>

                            <!-- Téléphone -->
                            <div class="mb-4">
                                <label for="guest_phone" class="form-label fw-bold small text-uppercase text-muted" style="font-size: 0.7rem;">Numéro de Téléphone</label>
                                <input type="tel" id="guest_phone" class="form-control p-3 border-light rounded-3 bg-light" placeholder="06 12 34 56 78" required>
                            </div>
                        @else
                            <div class="text-center py-4 mb-4 bg-light rounded-4 border border-light">
                                <i class="bi bi-person-check-fill text-success fs-1 mb-2 d-block"></i>
                                <h6 class="fw-bold mb-1">Réservation avec votre compte</h6>
                                <p class="small text-muted mb-0">Connecté en tant que <strong>{{ auth()->user()->name }}</strong></p>
                            </div>
                        @endguest

                        <div class="alert alert-info border-0 rounded-4 small p-3 mb-4 d-flex align-items-start gap-2" style="background-color: #EFF6FF; color: #1E40AF;">
                            <i class="bi bi-cash-coin fs-5 mt-1"></i>
                            <div>
                                <span class="fw-bold d-block">Paiement à bord</span>
                                Vous règlerez directement le chauffeur à la fin de votre course (espèces ou carte bancaire).
                            </div>
                        </div>

                        <button class="btn btn-success w-100 py-3 rounded-pill fw-bold mb-3 shadow-sm d-flex align-items-center justify-content-center gap-2" id="confirmBtn">
                            <span>Confirmer la commande</span>
                            <i class="bi bi-check-circle-fill"></i>
                        </button>

                        <button class="btn btn-link w-100 text-muted small text-decoration-none text-center" id="backToVehiculesBtn">
                            <i class="bi bi-chevron-left me-1"></i> Modifier le véhicule
                        </button>
                    </div>

                    <!-- Loader de traitement -->
                    <div id="bookingLoader" class="text-center py-5 d-none animate__animated animate__fadeIn">
                        <div class="spinner-border text-primary fs-3 mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                        <h5 class="fw-bold text-dark mb-1">Réservation en cours...</h5>
                        <p class="text-muted small">Nous préparons votre course, veuillez patienter.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---------- INITIALISATION DE LA CARTE ----------
    const map = L.map('tripMap').setView([48.8566, 2.3522], 13);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>'
    }).addTo(map);

    let pickupMarker = null, dropoffMarker = null, routePolyline = null;
    let pickupLat = null, pickupLng = null, dropoffLat = null, dropoffLng = null;
    let pickupAddress = '', dropoffAddress = '';
    let selectedVehicle = null;

    // Boutons & Étapes
    const stepTrajet = document.getElementById('stepTrajet');
    const stepVehicules = document.getElementById('stepVehicules');
    const stepContact = document.getElementById('stepContact');
    const bookingLoader = document.getElementById('bookingLoader');

    const step1Indicator = document.getElementById('step1Indicator');
    const step2Indicator = document.getElementById('step2Indicator');
    const step3Indicator = document.getElementById('step3Indicator');

    const continueBtn = document.getElementById('continueBtn');
    const selectVehicleBtn = document.getElementById('selectVehicleBtn');
    const confirmBtn = document.getElementById('confirmBtn');

    // Autocomplete inputs
    const pickupInput = document.getElementById('pickupInput');
    const dropoffInput = document.getElementById('dropoffInput');
    const pickupResults = document.getElementById('pickupResults');
    const dropoffResults = document.getElementById('dropoffResults');

    // ---------- AUTOCOMPLETE LOGIC ----------
    async function searchAddress(query) {
        if (!query || query.length < 3) return [];
        try {
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`;
            const r = await fetch(url);
            return await r.json();
        } catch (e) {
            console.error('Autocomplete error:', e);
            return [];
        }
    }

    function setupAutocomplete(input, resultsContainer, setCoordsCallback) {
        let debounceTimer;
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = input.value.trim();
            if (query.length < 3) {
                resultsContainer.style.display = 'none';
                return;
            }
            debounceTimer = setTimeout(async () => {
                const results = await searchAddress(query);
                resultsContainer.innerHTML = '';
                if (results.length > 0) {
                    resultsContainer.style.display = 'block';
                    results.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'result-item';
                        div.innerText = item.display_name;
                        div.addEventListener('click', () => {
                            input.value = item.display_name;
                            resultsContainer.style.display = 'none';
                            setCoordsCallback(parseFloat(item.lat), parseFloat(item.lon), item.display_name);
                        });
                        resultsContainer.appendChild(div);
                    });
                } else {
                    resultsContainer.style.display = 'none';
                }
            }, 300);
        });

        // Fermer les résultats au clic extérieur
        document.addEventListener('click', function(e) {
            if (e.target !== input) resultsContainer.style.display = 'none';
        });
    }

    setupAutocomplete(pickupInput, pickupResults, (lat, lng, address) => {
        pickupLat = lat; pickupLng = lng; pickupAddress = address;
        updateMarkers();
    });

    setupAutocomplete(dropoffInput, dropoffResults, (lat, lng, address) => {
        dropoffLat = lat; dropoffLng = lng; dropoffAddress = address;
        updateMarkers();
    });

    // ---------- UPDATE MAP MARKERS ----------
    function updateMarkers() {
        if (pickupLat && pickupLng) {
            if (pickupMarker) map.removeLayer(pickupMarker);
            pickupMarker = L.marker([pickupLat, pickupLng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41]
                })
            }).addTo(map).bindPopup("Lieu de départ").openPopup();
        }

        if (dropoffLat && dropoffLng) {
            if (dropoffMarker) map.removeLayer(dropoffMarker);
            dropoffMarker = L.marker([dropoffLat, dropoffLng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41]
                })
            }).addTo(map).bindPopup("Destination");
        }

        if (pickupLat && pickupLng && dropoffLat && dropoffLng) {
            const group = new L.featureGroup([pickupMarker, dropoffMarker]);
            map.fitBounds(group.getBounds().pad(0.15));
            drawRoute();
        }
    }

    // Tracer la route entre départ et arrivée
    async function drawRoute() {
        if (!pickupLat || !pickupLng || !dropoffLat || !dropoffLng) return;
        try {
            const r = await fetch(`https://router.project-osrm.org/route/v1/driving/${pickupLng},${pickupLat};${dropoffLng},${dropoffLat}?overview=full&geometries=geojson`);
            const data = await r.json();
            if (data.routes && data.routes[0]) {
                if (routePolyline) map.removeLayer(routePolyline);
                routePolyline = L.geoJSON(data.routes[0].geometry, {
                    style: { color: '#2563eb', weight: 5, opacity: 0.75 }
                }).addTo(map);
            }
        } catch (e) {
            console.error('OSRM route error:', e);
        }
    }

    // ---------- GEOLOCALISATION AUTO AU CHARGEMENT (si vide) ----------
    const urlParams = new URLSearchParams(window.location.search);
    const initialPickup = urlParams.get('pickup');
    const initialDropoff = urlParams.get('dropoff');

    if (!initialPickup) {
        map.locate({setView: true, maxZoom: 15});
        map.on('locationfound', function(e) {
            pickupLat = e.latlng.lat;
            pickupLng = e.latlng.lng;
            updateMarkers();
            
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${pickupLat}&lon=${pickupLng}`)
                .then(r => r.json())
                .then(data => {
                    if (data.display_name) {
                        pickupInput.value = data.display_name;
                        pickupAddress = data.display_name;
                    }
                });
        });
    } else {
        // Résoudre les adresses passées en paramètre
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(initialPickup)}&limit=1`)
            .then(r => r.json())
            .then(data => {
                if(data.length > 0) {
                    pickupLat = parseFloat(data[0].lat);
                    pickupLng = parseFloat(data[0].lon);
                    pickupAddress = initialPickup;
                    updateMarkers();
                }
            });
    }

    if (initialDropoff) {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(initialDropoff)}&limit=1`)
            .then(r => r.json())
            .then(data => {
                if(data.length > 0) {
                    dropoffLat = parseFloat(data[0].lat);
                    dropoffLng = parseFloat(data[0].lon);
                    dropoffAddress = initialDropoff;
                    updateMarkers();
                }
            });
    }

    // ---------- ÉTAPE 1 -> ÉTAPE 2 : ESTIMATION ----------
    continueBtn.addEventListener('click', async function() {
        if (!pickupLat || !dropoffLat) {
            alert('Veuillez renseigner un départ et une destination valides via la liste de suggestions.');
            return;
        }

        continueBtn.disabled = true;
        continueBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Calcul de l\'itinéraire...';

        try {
            const res = await fetch('/trips/estimate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    pickup_lat: pickupLat,
                    pickup_lng: pickupLng,
                    dropoff_lat: dropoffLat,
                    dropoff_lng: dropoffLng
                })
            });

            if (!res.ok) throw new Error('Erreur estimation');
            
            const vehicles = await res.json();
            const container = document.getElementById('vehiclesList');
            container.innerHTML = '';

            vehicles.forEach((v, index) => {
                const card = document.createElement('div');
                card.className = `vehicle-card d-flex justify-content-between align-items-center ${index === 0 ? 'selected' : ''}`;
                if(index === 0) selectedVehicle = v;

                card.innerHTML = `
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-car-front fs-2 text-primary"></i>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">${v.name}</h6>
                            <span class="text-muted small">${v.description || 'Chauffeur VTC Premium'}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary h5 mb-0">${v.price_ttc} €</div>
                        <span class="text-muted small" style="font-size: 0.65rem;">${v.duration_txt}</span>
                    </div>
                `;

                card.addEventListener('click', () => {
                    document.querySelectorAll('.vehicle-card').forEach(c => c.classList.remove('selected'));
                    card.classList.add('selected');
                    selectedVehicle = v;
                    updateSummary();
                });

                container.appendChild(card);
            });

            updateSummary();

            // Changement d'étape
            stepTrajet.style.display = 'none';
            stepVehicules.style.display = 'block';
            step1Indicator.classList.remove('active');
            step2Indicator.classList.add('active');

        } catch (e) {
            alert('Impossible de charger les estimations de tarifs. Veuillez réessayer.');
        } finally {
            continueBtn.disabled = false;
            continueBtn.innerHTML = 'Estimer le trajet <i class="bi bi-arrow-right ms-2"></i>';
        }
    });

    function updateSummary() {
        if (!selectedVehicle) return;
        document.getElementById('detailDistance').innerText = selectedVehicle.distance + ' km';
        document.getElementById('detailDuration').innerText = selectedVehicle.duration_txt;
        document.getElementById('detailPrice').innerText = selectedVehicle.price_ttc + ' €';
    }

    // ---------- ÉTAPE 2 -> ÉTAPE 3 : CONTACT ----------
    selectVehicleBtn.addEventListener('click', function() {
        if(!selectedVehicle) {
            alert('Veuillez sélectionner un véhicule.');
            return;
        }
        stepVehicules.style.display = 'none';
        stepContact.style.display = 'block';
        step2Indicator.classList.remove('active');
        step3Indicator.classList.add('active');
    });

    // Retour en arrière
    document.getElementById('backToTrajetBtn').addEventListener('click', function() {
        stepVehicules.style.display = 'none';
        stepTrajet.style.display = 'block';
        step2Indicator.classList.remove('active');
        step1Indicator.classList.add('active');
    });

    document.getElementById('backToVehiculesBtn').addEventListener('click', function() {
        stepContact.style.display = 'none';
        stepVehicules.style.display = 'block';
        step3Indicator.classList.remove('active');
        step2Indicator.classList.add('active');
    });

    // ---------- ÉTAPE 3 : CONFIRMATION FINALE ----------
    confirmBtn.addEventListener('click', async function() {
        // Collecte des infos de contact pour les invités (guests)
        const isGuest = document.getElementById('guest_name') !== null;
        let payload = {
            vehicle_type_id: selectedVehicle.id,
            pickup_address: pickupAddress,
            dropoff_address: dropoffAddress,
            pickup_lat: pickupLat,
            pickup_lng: pickupLng,
            dropoff_lat: dropoffLat,
            dropoff_lng: dropoffLng,
            price: parseFloat(selectedVehicle.price_ttc),
            distance: selectedVehicle.distance,
            scheduled_at: document.getElementById('scheduledAtInput').value || null
        };

        if (isGuest) {
            const guestName = document.getElementById('guest_name').value.trim();
            const guestEmail = document.getElementById('guest_email').value.trim();
            const guestPhone = document.getElementById('guest_phone').value.trim();

            if (!guestName || !guestEmail || !guestPhone) {
                alert('Veuillez remplir toutes les informations de contact.');
                return;
            }

            payload.guest_name = guestName;
            payload.guest_email = guestEmail;
            payload.guest_phone = guestPhone;
        }

        // Masquer le formulaire et afficher le loader
        stepContact.style.display = 'none';
        bookingLoader.classList.remove('d-none');

        try {
            const res = await fetch('/trips', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                bookingLoader.innerHTML = `
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="fw-bold mb-2 text-dark">Réservation Réussie !</h4>
                    <p class="text-muted small px-3">Votre course a été enregistrée avec succès. Vous allez être redirigé vers votre espace personnel dans un instant...</p>
                `;

                // Rediriger vers le dashboard (puisque l'utilisateur a été connecté automatiquement)
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 3000);
            } else {
                const err = await res.json();
                alert(err.message || 'Erreur lors de la réservation.');
                // Réinitialiser la vue
                bookingLoader.classList.add('d-none');
                stepContact.style.display = 'block';
            }
        } catch (e) {
            alert('Erreur réseau. Impossible de finaliser la réservation.');
            bookingLoader.classList.add('d-none');
            stepContact.style.display = 'block';
        }
    });
});
</script>
@endpush
