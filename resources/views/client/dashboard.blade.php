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

                        <div class="text-center mb-4 p-3 bg-primary-subtle rounded-4">
                            <span class="text-muted small text-uppercase fw-bold" style="letter-spacing: 1px;">Prix Total TTC</span>
                            <div class="price-badge mt-1" style="color: #2563eb; font-weight: 800; font-size: 2rem;"><span id="estimatedPriceTTC">0.00</span>€</div>
                        </div>

                        <button class="btn btn-primary w-100 py-3 rounded-pill fw-bold mb-3 shadow" id="confirmBtn">
                            Commander mon chauffeur
                        </button>

                        <button class="btn btn-link w-100 text-muted small text-decoration-none" id="backBtn">
                            <i class="bi bi-chevron-left me-1"></i> Modifier le trajet
                        </button>
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
    // ---------- CARTE ----------
    const map = L.map('tripMap').setView([48.8566, 2.3522], 13);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>'
    }).addTo(map);

    let pickupMarker = null, dropoffMarker = null;
    let pickupLat = null, pickupLng = null, dropoffLat = null, dropoffLng = null;
    let pickupAddress = '', dropoffAddress = '';

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

    // ---------- AFFICHAGE VÉHICULES ----------
    async function displayVehicles() {
        const vehicles = await getEstimation();
        if (!vehicles || !vehicles.length) {
            alert('Aucun véhicule disponible');
            return;
        }

        // --- Appel OSRM pour la distance réelle de route et le tracé ---
        try {
            // On demande le trajet complet avec la géométrie (geojson)
            const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${pickupLng},${pickupLat};${dropoffLng},${dropoffLat}?overview=full&geometries=geojson`;
            const osrmRes = await fetch(osrmUrl);
            const osrmData = await osrmRes.json();

            if (osrmData.routes && osrmData.routes.length > 0) {
                const route = osrmData.routes[0];
                const realDistanceKm = (route.distance / 1000).toFixed(2);
                const realDurationMin = Math.round(route.duration / 60);

                // Tracé de l'itinéraire sur la carte
                if (routeLine) map.removeLayer(routeLine);
                routeLine = L.geoJSON(route.geometry, {
                    style: { color: '#2563eb', weight: 5, opacity: 0.8 }
                }).addTo(map);
                map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });

                vehicles.forEach(v => {
                    v.distance = parseFloat(realDistanceKm);
                    v.duration = realDurationMin;

                    // Tarification demandée : 1.50 / 2.50 / 4.00
                    let rate = 1.50; // Par défaut (Berline Standard)
                    if (v.name.toLowerCase().includes('van') || v.name.toLowerCase().includes('affaires')) rate = 2.50;
                    if (v.name.toLowerCase().includes('sprinter') || v.name.toLowerCase().includes('premium')) rate = 4.00;

                    v.price_ht = (v.distance * rate).toFixed(2);
                    v.price_ttc = (v.price_ht * 1.10).toFixed(2);
                    v.price = v.price_ttc; // Pour la soumission au backend
                });
            }
        } catch (error) {
            console.error("Erreur OSRM:", error);
        }

        vehiclesData = vehicles;
        const container = document.getElementById('vehiclesList');
        container.innerHTML = '';

        vehicles.forEach((v, idx) => {
            const div = document.createElement('div');
            div.className = `vehicle-option mb-2 ${idx === 0 ? 'selected' : ''}`;
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold">${v.name}</div>
                        <div class="small text-muted">Chauffeur inclus</div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">HT : ${v.price_ht}€</div>
                        <div class="fw-bold text-primary" style="font-size: 1.1rem;">TTC : ${v.price_ttc}€</div>
                    </div>
                </div>
            `;
            div.onclick = () => {
                document.querySelectorAll('.vehicle-option').forEach(opt => opt.classList.remove('selected'));
                div.classList.add('selected');
                selectedVehicle = v;
                updatePriceSummary(v);
            };
            container.appendChild(div);
        });

        const v = vehicles[0];
        selectedVehicle = v;
        updatePriceSummary(v);

        // Mise à jour des détails (Distance, Durée, CO2) avec les valeurs réelles
        document.getElementById('detailDistance').innerText = v.distance + ' km';
        document.getElementById('detailDuration').innerText = v.duration + ' min';
        document.getElementById('detailCO2').innerText = (v.distance * 0.104).toFixed(2) + ' kg';
        document.getElementById('tripDetails').classList.remove('d-none');

        // Configuration du lien Mappy
        const mappyUrl = `https://fr.mappy.com/itineraire#from=${encodeURIComponent(pickupAddress)}&to=${encodeURIComponent(dropoffAddress)}`;
        document.getElementById('mappyLink').href = mappyUrl;
        document.getElementById('mappySection').classList.remove('d-none');
    }

    function updatePriceSummary(v) {
        document.getElementById('estimatedPriceHT').innerText = v.price_ht;
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
        await displayVehicles();
        currentStep = 2;
        stepTrajet.style.display = 'none';
        stepVehicules.style.display = 'block';
        step1Indicator.classList.remove('active');
        step2Indicator.classList.add('active');
        continueBtn.disabled = false;
        continueBtn.innerHTML = 'Continuer →';
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
