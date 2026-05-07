@extends('layouts.app')

@section('title', 'Dashboard Client - ATLAS AND CO')

@push('styles')
<style>
    body {
        background: #f3f4f6;
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* Carte */
    .map-container {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        height: 500px;
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
        font-size: 0.9rem;
        outline: none;
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
<div class="dashboard-container">
    <div class="row g-4">
        <!-- Colonne Carte -->
        <div class="col-lg-7">
            <div class="map-container">
                <div id="tripMap"></div>
            </div>
        </div>

        <!-- Colonne Formulaire -->
        <div class="col-lg-5">
            <div class="booking-card">
                <div class="booking-header">
                    <h4 class="fw-bold mb-1">📍 Nouvelle course</h4>
                    <p class="text-muted small mb-0">Chauffeur privé à la demande</p>
                </div>

                <div class="booking-body">
                    <!-- Indicateur d'étapes -->
                    <div class="step-indicator">
                        <div class="step" id="step1Indicator">📍 Trajet</div>
                        <div class="step" id="step2Indicator">🚗 Véhicule</div>
                    </div>

                    <!-- Étape 1 : Trajet -->
                    <div id="stepTrajet">
                        <div class="location-input">
                            <div class="input-row relative">
                                <div class="input-dot pickup"></div>
                                <input type="text" id="pickupInput" placeholder="Départ" autocomplete="off">
                                <div id="pickupResults" class="autocomplete-results" style="display: none;"></div>
                            </div>
                            <div class="input-row relative">
                                <div class="input-dot dropoff"></div>
                                <input type="text" id="dropoffInput" placeholder="Destination" autocomplete="off">
                                <div id="dropoffResults" class="autocomplete-results" style="display: none;"></div>
                            </div>
                        </div>

                        <button class="btn-reserve mt-4" id="continueBtn">Continuer →</button>
                    </div>

                    <!-- Étape 2 : Véhicules (cachée au début) -->
                    <div id="stepVehicules" style="display: none;">
                        <div id="vehiclesList" class="mb-4"></div>

                        <div class="text-center mb-3">
                            <span class="text-muted small">Tarif estimé</span>
                            <div class="price-badge" id="estimatedPrice">0€</div>
                        </div>

                        <button class="btn-reserve" id="confirmBtn">✅ Confirmer la course</button>
                        <button class="btn btn-link w-100 mt-2 text-muted small" id="backBtn">← Retour</button>
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

    // ---------- AFFICHAGE VÉHICULES ----------
    async function displayVehicles() {
        const vehicles = await getEstimation();
        if (!vehicles || !vehicles.length) {
            alert('Aucun véhicule disponible');
            return;
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
                    <div class="text-primary fw-bold">${v.price}€</div>
                </div>
            `;
            div.onclick = () => {
                document.querySelectorAll('.vehicle-option').forEach(opt => opt.classList.remove('selected'));
                div.classList.add('selected');
                selectedVehicle = v;
                document.getElementById('estimatedPrice').innerText = v.price + '€';
            };
            container.appendChild(div);
        });

        selectedVehicle = vehicles[0];
        document.getElementById('estimatedPrice').innerText = vehicles[0].price + '€';
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
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi...';

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
                window.location.href = '/client/trips/' + data.id + '/track';
            } else {
                const err = await res.json();
                alert(err.message || 'Erreur lors de la réservation');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '✅ Confirmer la course';
            }
        } catch {
            alert('Erreur réseau');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '✅ Confirmer la course';
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
