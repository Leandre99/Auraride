@extends('layouts.app')

@section('title', 'Dashboard Client - ATLAS AND CO')

@push('styles')
<style>
    body {
        background: #f3f4f6;
    }

    .dashboard-container-fluid {
        width: 100%;
        padding: 20px;
        background: #f8fafc;
    }
    @media (min-width: 992px) {
        .dashboard-container {
            padding: 0;
        }
    }

    .map-container {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        height: 300px;
    }

    @media (min-width: 992px) {
        .map-container {
            height: 600px; /* Desktop height */
        }
    }

    #tripMap {
        height: 100%;
        width: 100%;
    }

    /* Panneau de réservation */
    .booking-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .booking-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        background: #ffffff;
    }

    .booking-body {
        padding: 1.5rem;
    }

    .step-indicator {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    .step {
        flex: 1;
        text-align: center;
        padding: 0.5rem;
        border-radius: 40px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        background: #f3f4f6;
        color: #6b7280;
    }

    .step.active {
        background: #2563eb;
        color: white;
    }

    .location-input {
        background: #f9fafb;
        border-radius: 16px;
        padding: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .input-row {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .input-row:last-child {
        border-bottom: none;
    }

    .input-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 12px;
    }

    .input-dot.pickup { background: #2563eb; }
    .input-dot.dropoff { background: #ea580c; }

    .input-row input {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 1rem;
        outline: none;
        padding: 12px 0;
    }

    .booking-body {
        padding: 2rem !important;
    }

    .vehicle-option {
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .vehicle-option:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .vehicle-option.selected {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .btn-reserve {
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 40px;
        padding: 0.875rem;
        font-weight: 600;
        width: 100%;
        transition: all 0.2s;
    }

    .btn-reserve:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .btn-reserve:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }

    .price-badge {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2563eb;
    }

    .autocomplete-results {
        position: absolute;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
        width: calc(100% - 2rem);
    }

    .result-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .result-item:hover {
        background: #f3f4f6;
    }

    .relative {
        position: relative;
    }
</style>
@endpush

@section('content')
<div class="dashboard-container-fluid">
    <div class="row g-0">
        <!-- Colonne Carte (Plus grande sur Desktop) -->
        <div class="col-lg-7 order-2 order-lg-1 animate__animated animate__fadeIn">
            <div class="map-container shadow-lg border-0" style="height: 700px;">
                <div id="tripMap"></div>
            </div>
        </div>

        <!-- Colonne Formulaire (Plus compacte et centrée sur Desktop) -->
        <div class="col-lg-5 order-1 order-lg-2 animate__animated animate__slideInRight px-lg-4">
            <div class="booking-card shadow-lg border-0 sticky-top" style="top: 20px;">
                @if(isset($trackingTrip))
                <!-- Affichage de la course active -->
                <div class="booking-header bg-dark text-white p-4 rounded-top-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1">🚖 Course en cours</h4>
                            <p class="opacity-75 small mb-0">Suivi en temps réel</p>
                        </div>
                        <span class="badge bg-primary px-3 py-2 rounded-pill">
                            @switch($trackingTrip->status)
                                @case('pending') Recherche... @break
                                @case('assigned') Chauffeur désigné @break
                                @case('accepted') Chauffeur en route @break
                                @case('in_progress') Course en cours @break
                                @case('completed') Terminée @break
                                @default {{ $trackingTrip->status }}
                            @endswitch
                        </span>
                    </div>
                </div>

                <div class="booking-body p-4">
                    <div class="card border-0 bg-light rounded-4 p-3 mb-4">
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Itinéraire</label>
                            <div class="d-flex align-items-start gap-2 mt-2">
                                <div class="input-dot pickup mt-1"></div>
                                <div class="small fw-bold">{{ $trackingTrip->pickup_address }}</div>
                            </div>
                            <div class="d-flex align-items-start gap-2 mt-2">
                                <div class="input-dot dropoff mt-1"></div>
                                <div class="small fw-bold">{{ $trackingTrip->dropoff_address }}</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <span class="text-muted">Prix Total TTC</span>
                            <span class="h4 mb-0 fw-bold text-primary">{{ number_format($trackingTrip->price, 2) }} €</span>
                        </div>
                    </div>

                    @if($trackingTrip->driver)
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-2"></i>Votre Chauffeur</h6>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 50px; height: 50px;">
                                <i class="bi bi-person-fill text-primary fs-4"></i>
                            </div>
                            <div>
                                <p class="fw-bold mb-0">{{ $trackingTrip->driver->name }}</p>
                                @if($trackingTrip->vehicle)
                                <p class="text-muted small mb-0">{{ $trackingTrip->vehicle->model ?? '' }} • {{ $trackingTrip->vehicle->plate_number ?? '' }}</p>
                                @endif
                            </div>
                        </div>

                        @if(in_array($trackingTrip->status, ['assigned', 'accepted', 'in_progress']))
                        <div class="row g-2 mt-3">
                            <div class="col-6">
                                @if($trackingTrip->driver?->phone_number)
                                    <a href="tel:{{ $trackingTrip->driver->phone_number }}" class="btn btn-primary w-100 py-2 rounded-pill small">
                                        <i class="bi bi-telephone-fill me-1"></i> Appeler
                                    </a>
                                @else
                                    <button class="btn btn-secondary w-100 py-2 rounded-pill small" disabled title="Numéro non renseigné">
                                        <i class="bi bi-telephone-fill me-1"></i> Appeler
                                    </button>
                                @endif
                            </div>
                            <div class="col-6">
                                @if($trackingTrip->driver?->phone_number)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $trackingTrip->driver->phone_number) }}?text={{ urlencode('Bonjour, je suis votre client pour la course de ' . $trackingTrip->pickup_address) }}" target="_blank" class="btn btn-success w-100 py-2 rounded-pill small">
                                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                    </a>
                                @else
                                    <button class="btn btn-secondary w-100 py-2 rounded-pill small" disabled>
                                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <a href="{{ route('client.trips.track', $trackingTrip->id) }}" class="btn btn-outline-primary w-100 py-3 rounded-pill fw-bold">
                        Ouvrir la page de suivi complète <i class="bi bi-arrow-up-right-circle ms-2"></i>
                    </a>
                </div>
                @else
                <!-- Formulaire de réservation classique -->
                <div class="booking-header bg-primary text-white p-4 rounded-top-4">
                    <h4 class="fw-bold mb-1">📍 Réserver une course</h4>
                    <p class="opacity-75 small mb-0">Chauffeur privé à la demande</p>
                </div>

                <div class="booking-body p-4">
                    <!-- Indicateur d'étapes -->
                    <div class="step-indicator mb-4">
                        <div class="step active py-2" id="step1Indicator">1. Trajet</div>
                        <div class="step py-2" id="step2Indicator">2. Véhicule</div>
                    </div>

                    <!-- Étape 1 : Trajet -->
                    <div id="stepTrajet">
                        <div class="location-input bg-light border-0 p-3 rounded-4">
                            <div class="input-row relative border-bottom pb-2 mb-2">
                                <div class="input-dot pickup"></div>
                                <input type="text" id="pickupInput" class="w-100" placeholder="Lieu de départ..." autocomplete="off">
                                <div id="pickupResults" class="autocomplete-results shadow" style="display: none;"></div>
                            </div>
                            <div class="input-row relative pt-2">
                                <div class="input-dot dropoff"></div>
                                <input type="text" id="dropoffInput" class="w-100" placeholder="Destination..." autocomplete="off">
                                <div id="dropoffResults" class="autocomplete-results shadow" style="display: none;"></div>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 py-3 rounded-pill fw-bold mt-4 shadow-sm" id="continueBtn">
                            Estimer le trajet <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>

                    <!-- Étape 2 : Véhicules -->
                    <div id="stepVehicules" style="display: none;">
                        <div id="vehiclesList" class="mb-4"></div>

                        <!-- Informations principales -->
                        <div id="tripDetails" class="mb-4 p-3 bg-light rounded-4 d-none border shadow-sm">
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
                                    <div class="text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Émissions</div>
                                    <div class="fw-bold text-success" id="detailCO2">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- Notice subtile affichée si OSRM n'a pas encore répondu -->
                        <div id="distanceFallbackNotice" class="text-center mb-2 small text-muted" style="display: none;">
                            <i class="bi bi-info-circle me-1"></i> Distance estimée — l'itinéraire précis s'affiche dans quelques instants.
                        </div>

                        <div class="text-center mb-4 p-3 bg-primary-subtle rounded-4">
                            <span class="text-muted small text-uppercase fw-bold" style="letter-spacing: 1px;">Prix Total TTC</span>
                            <div class="price-badge mt-1" style="color: #2563eb; font-weight: 800; font-size: 2rem;"><span id="estimatedPriceTTC">0.00</span>€</div>
                        </div>

                        <button class="btn btn-primary w-100 py-3 rounded-pill fw-bold mb-3 shadow" id="confirmBtn">
                            Commander mon chauffeur
                        </button>

                        <!-- Lien Mappy -->
                        <div id="mappyLinkContainer" class="text-center mb-3" style="display: none;">
                            <p class="text-muted small mb-1">Besoin d'une contre-expertise ?</p>
                            <a id="mappyLink" href="#" target="_blank" rel="noopener noreferrer" class="text-muted small text-decoration-none opacity-75 hover-opacity-100 transition">
                                <i class="bi bi-map me-1"></i> Vérifier l'itinéraire et estimer le coût sur Mappy →
                            </a>
                        </div>

                        <button class="btn btn-link w-100 text-muted small text-decoration-none" id="backBtn">
                            <i class="bi bi-chevron-left me-1"></i> Modifier le trajet
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@if(isset($trackingTrip))
<script>
    window.activeTrip = @json($trackingTrip);
</script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---------- CARTE ----------
    const map = L.map('tripMap').setView([48.8566, 2.3522], 13);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>'
    }).addTo(map);

    // Géolocalisation automatique
    if (!window.activeTrip) {
        map.locate({setView: true, maxZoom: 15});
        map.on('locationfound', function(e) {
            if (pickupMarker) map.removeLayer(pickupMarker);
            pickupMarker = L.marker(e.latlng, {
                icon: L.icon({
                    iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41]
                })
            }).addTo(map).bindPopup("Vous êtes ici").openPopup();
            
            pickupLat = e.latlng.lat;
            pickupLng = e.latlng.lng;
            
            // Inversion de géocodage pour l'adresse de départ
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${pickupLat}&lon=${pickupLng}`)
                .then(r => r.json())
                .then(data => {
                    if (data.display_name) {
                        document.getElementById('pickupInput').value = data.display_name;
                        pickupAddress = data.display_name;
                    }
                });
        });
    }

    let pickupMarker = null, dropoffMarker = null;
    let pickupLat = null, pickupLng = null, dropoffLat = null, dropoffLng = null;
    let pickupAddress = '', dropoffAddress = '';

    // Si une course est active, on initialise les variables avec ses données
    if (window.activeTrip) {
        pickupLat = parseFloat(window.activeTrip.pickup_lat);
        pickupLng = parseFloat(window.activeTrip.pickup_lng);
        dropoffLat = parseFloat(window.activeTrip.dropoff_lat);
        dropoffLng = parseFloat(window.activeTrip.dropoff_lng);
        pickupAddress = window.activeTrip.pickup_address;
        dropoffAddress = window.activeTrip.dropoff_address;
    }

    // Variables globales
    let currentStep = 1;
    let selectedVehicle = null;
    let vehiclesData = [];

    const stepTrajet = document.getElementById('stepTrajet');
    const stepVehicules = document.getElementById('stepVehicules');
    const step1Indicator = document.getElementById('step1Indicator');
    const step2Indicator = document.getElementById('step2Indicator');
    const continueBtn = document.getElementById('continueBtn');
    const confirmBtn = document.getElementById('confirmBtn');
    const backBtn = document.getElementById('backBtn');

    // ---------- AUTOCOMPLETE ----------
    async function searchAddress(query) {
        if (!query || query.length < 3) return [];
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`);
            return await res.json();
        } catch {
            return [];
        }
    }

    function setupAutocomplete(inputId, resultsId, type) {
        const input = document.getElementById(inputId);
        const resultsDiv = document.getElementById(resultsId);
        let timer;

        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(async () => {
                const data = await searchAddress(input.value);
                resultsDiv.innerHTML = '';
                if (data.length) {
                    resultsDiv.style.display = 'block';
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'result-item';
                        div.textContent = item.display_name;
                        div.onclick = () => {
                            input.value = item.display_name;
                            resultsDiv.style.display = 'none';
                            if (type === 'pickup') {
                                pickupLat = parseFloat(item.lat);
                                pickupLng = parseFloat(item.lon);
                                pickupAddress = item.display_name;
                                if (pickupMarker) map.removeLayer(pickupMarker);
                                pickupMarker = L.marker([pickupLat, pickupLng], { icon: L.icon({ iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png', iconSize: [25, 41] }) }).addTo(map);
                                map.setView([pickupLat, pickupLng], 14);
                            } else {
                                dropoffLat = parseFloat(item.lat);
                                dropoffLng = parseFloat(item.lon);
                                dropoffAddress = item.display_name;
                                if (dropoffMarker) map.removeLayer(dropoffMarker);
                                dropoffMarker = L.marker([dropoffLat, dropoffLng], { icon: L.icon({ iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png', iconSize: [25, 41] }) }).addTo(map);
                            }
                        };
                        resultsDiv.appendChild(div);
                    });
                } else {
                    resultsDiv.style.display = 'none';
                }
            }, 500);
        });

        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !resultsDiv.contains(e.target)) {
                resultsDiv.style.display = 'none';
            }
        });
    }

    setupAutocomplete('pickupInput', 'pickupResults', 'pickup');
    setupAutocomplete('dropoffInput', 'dropoffResults', 'dropoff');

    // Si une course est active, on dessine l'itinéraire sur la carte
    if (window.activeTrip && pickupLat && dropoffLat) {
        pickupMarker = L.marker([pickupLat, pickupLng], { icon: L.icon({ iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png', iconSize: [25, 41] }) }).addTo(map).bindPopup('Départ');
        dropoffMarker = L.marker([dropoffLat, dropoffLng], { icon: L.icon({ iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png', iconSize: [25, 41] }) }).addTo(map).bindPopup('Arrivée');
        
        const routeLine = L.polyline([[pickupLat, pickupLng], [dropoffLat, dropoffLng]], {
            color: '#2563eb',
            weight: 5,
            opacity: 0.7,
            dashArray: '10, 10'
        }).addTo(map);
        
        map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });
    }

    // ---------- ESTIMATION ----------
    async function getEstimation() {
        if (!pickupLat || !dropoffLat) return null;
        try {
            const res = await fetch('/client/trips/estimate', {
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
            return await res.json();
        } catch {
            return null;
        }
    }

    let routeLine = null;

    // ---------- HELPERS TARIFICATION ----------
    function computePricing(v) {
        // Le backend renvoie déjà les prix HT, TTC et TVA
        // On s'assure juste que les propriétés sont bien présentes
        v.price_ht  = v.price_ht || 0;
        v.price_ttc = v.price_ttc || v.price || 0;
        v.price     = v.price_ttc;
    }

    function renderVehicleCard(v, idx) {
        const div = document.createElement('div');
        div.className = `vehicle-option mb-2 ${idx === 0 ? 'selected' : ''}`;
        div.dataset.vehicleId = v.id;
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold">${v.name}</div>
                    <div class="small text-muted">Chauffeur inclus</div>
                </div>
                <div class="text-end">
                    <div class="small text-muted">HT : <span class="price-ht">${v.price_ht}</span>€</div>
                    <div class="fw-bold text-primary" style="font-size: 1.1rem;">TTC : <span class="price-ttc">${v.price_ttc}</span>€</div>
                </div>
            </div>
        `;
        div.onclick = () => {
            document.querySelectorAll('.vehicle-option').forEach(opt => opt.classList.remove('selected'));
            div.classList.add('selected');
            selectedVehicle = v;
            updatePriceSummary(v);
        };
        return div;
    }

    function refreshVehicleCards(vehicles) {
        vehicles.forEach(v => {
            const card = document.querySelector(`.vehicle-option[data-vehicle-id="${v.id}"]`);
            if (!card) return;
            card.querySelector('.price-ht').textContent  = v.price_ht;
            card.querySelector('.price-ttc').textContent = v.price_ttc;
        });
    }

    function updateTripDetails(v) {
        document.getElementById('detailDistance').innerText = v.distance + ' km';
        document.getElementById('detailDuration').innerText = v.duration + ' min';
        document.getElementById('detailCO2').innerText = (v.distance * 0.104).toFixed(2) + ' kg';
        document.getElementById('tripDetails').classList.remove('d-none');
    }

    function updateMappyLink() {
        const linkContainer = document.getElementById('mappyLinkContainer');
        const mappyLink = document.getElementById('mappyLink');
        if (pickupAddress && dropoffAddress) {
            const url = `https://fr.mappy.com/itineraire#from=${encodeURIComponent(pickupAddress)}&to=${encodeURIComponent(dropoffAddress)}`;
            mappyLink.href = url;
            linkContainer.style.display = 'block';
        } else {
            linkContainer.style.display = 'none';
        }
    }

    // ---------- AFFICHAGE VÉHICULES ----------
    async function displayVehicles() {
        // Update Mappy Link
        updateMappyLink();

        // 1. Fetch backend estimate (OSRM with Haversine fallback — always fast)
        const vehicles = await getEstimation();
        if (!vehicles || !vehicles.length) {
            alert('Aucun véhicule disponible');
            return;
        }

        // 2. Compute pricing from backend distance and render vehicles immediately
        vehicles.forEach(v => computePricing(v));
        vehiclesData = vehicles;

        const container = document.getElementById('vehiclesList');
        container.innerHTML = '';
        vehicles.forEach((v, idx) => container.appendChild(renderVehicleCard(v, idx)));

        const firstVehicle = vehicles[0];
        selectedVehicle = firstVehicle;
        updatePriceSummary(firstVehicle);
        updateTripDetails(firstVehicle);

        // Show a subtle fallback notice that will be hidden if OSRM succeeds
        const fallbackNotice = document.getElementById('distanceFallbackNotice');
        if (fallbackNotice) fallbackNotice.style.display = 'block';

        // 3. Fire OSRM in background with a 5-second timeout for map polyline.
        //    We do NOT await this — vehicles are already displayed above.
        const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${pickupLng},${pickupLat};${dropoffLng},${dropoffLat}?overview=full&geometries=geojson`;

        const osrmTimeout = new Promise((_, reject) =>
            setTimeout(() => reject(new Error('OSRM timeout')), 5000)
        );

        Promise.race([fetch(osrmUrl), osrmTimeout])
            .then(res => res.json())
            .then(osrmData => {
                if (!osrmData.routes || !osrmData.routes.length) return;

                const route = osrmData.routes[0];

                // Draw polyline on map
                if (routeLine) map.removeLayer(routeLine);
                routeLine = L.geoJSON(route.geometry, {
                    style: { color: '#2563eb', weight: 5, opacity: 0.8 }
                }).addTo(map);
                map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });

                // Update vehicles with OSRM distance so map and prices stay in sync
                const osrmDistanceKm = parseFloat((route.distance / 1000).toFixed(2));
                const osrmDurationMin = Math.round(route.duration / 60);

                vehicles.forEach(v => {
                    v.distance = osrmDistanceKm;
                    v.duration = osrmDurationMin;
                    computePricing(v);
                });

                // Refresh cards and summary with OSRM values
                refreshVehicleCards(vehicles);
                updateTripDetails(vehicles[0]);
                if (selectedVehicle) {
                    const updated = vehicles.find(v => v.id === selectedVehicle.id);
                    if (updated) {
                        selectedVehicle = updated;
                        updatePriceSummary(updated);
                    }
                }
                
                // Update Mappy Link again
                updateMappyLink();

                // Hide fallback notice — we have real road data
                if (fallbackNotice) fallbackNotice.style.display = 'none';
            })
            .catch(err => {
                console.warn('OSRM not available for map polyline:', err.message);
                if (pickupMarker && dropoffMarker && !routeLine) {
                    routeLine = L.polyline(
                        [[pickupLat, pickupLng], [dropoffLat, dropoffLng]],
                        { color: '#2563eb', weight: 3, opacity: 0.5, dashArray: '8, 8' }
                    ).addTo(map);
                    map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });
                }
            });
    }

    function updatePriceSummary(v) {
        document.getElementById('estimatedPriceTTC').innerText = v.price_ttc;
    }

    // ---------- CHANGEMENT D'ÉTAPE ----------
    continueBtn.onclick = async () => {
        if (!pickupLat || !dropoffLat) {
            alert('Veuillez choisir un départ et une destination');
            return;
        }
        continueBtn.disabled = true;
        continueBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Chargement...';
        try {
            await displayVehicles();
            currentStep = 2;
            stepTrajet.style.display = 'none';
            stepVehicules.style.display = 'block';
            step1Indicator.classList.remove('active');
            step2Indicator.classList.add('active');
        } catch (err) {
            console.error('Erreur lors du chargement des véhicules:', err);
            alert('Une erreur est survenue. Veuillez réessayer.');
        } finally {
            continueBtn.disabled = false;
            continueBtn.innerHTML = 'Estimer le trajet <i class="bi bi-arrow-right ms-2"></i>';
        }
    };

    backBtn.onclick = () => {
        currentStep = 1;
        stepTrajet.style.display = 'block';
        stepVehicules.style.display = 'none';
        step1Indicator.classList.add('active');
        step2Indicator.classList.remove('active');
    };


    // ---------- CONFIRMATION ----------
    confirmBtn.onclick = async () => {
        if (!selectedVehicle) {
            alert('Choisissez un véhicule');
            return;
        }

        // Bloquer le bouton immédiatement pour éviter les doubles clics
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi en cours...';

        try {
            const res = await fetch('/client/trips', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    vehicle_type_id: selectedVehicle.id,
                    pickup_address: pickupAddress,
                    dropoff_address: dropoffAddress,
                    pickup_lat: pickupLat,
                    pickup_lng: pickupLng,
                    dropoff_lat: dropoffLat,
                    dropoff_lng: dropoffLng,
                    price: selectedVehicle.price,
                    distance: selectedVehicle.distance
                })
            });

            if (res.ok) {
                const data = await res.json();
                confirmBtn.classList.remove('btn-primary');
                confirmBtn.classList.add('btn-success');
                confirmBtn.innerHTML = '✅ Course confirmée !';

                // Petite pause pour laisser l'utilisateur voir le succès
                setTimeout(() => {
                    window.location.href = '/client/trips/' + data.id + '/track';
                }, 1000);
            } else {
                const err = await res.json();
                alert(err.message || 'Erreur lors de la réservation');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = 'Commander mon chauffeur';
            }
        } catch {
            alert('Erreur réseau');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = 'Commander mon chauffeur';
        }
    };

    // Animation d'entrée
    if (typeof gsap !== 'undefined') {
        gsap.from('.booking-card', { opacity: 0, y: 30, duration: 0.5 });
        gsap.from('.map-container', { opacity: 0, x: -30, duration: 0.5 });
    }
});
</script>
@endpush
