@extends('layouts.app')

@section('title', 'AuraRide - Cockpit Chauffeur')

@push('styles')
<style>
    body { background: #0F172A; overflow: hidden; }

    #map-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        opacity: 0.8;
    }

    /* Driver HUD */
    .driver-hud {
        position: absolute;
        bottom: 30px;
        left: 30px;
        right: 30px;
        z-index: 100;
        display: grid;
        grid-template-columns: 350px 1fr 350px;
        gap: 20px;
        pointer-events: none;
    }

    .hud-panel {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 32px;
        padding: 25px;
        pointer-events: auto;
        color: #FFF;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }

    /* Status Toggle HUD */
    .status-hud {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .hud-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(255,255,255,0.05);
        padding: 15px 20px;
        border-radius: 20px;
        margin-top: auto;
    }

    /* Stats HUD */
    .stat-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .stat-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid #1E293B;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .stat-circle.active { border-color: var(--primary); }

    /* New Ride Request HUD (Floating Center) */
    .request-hud-center {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        width: 480px;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .request-hud-center.active {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, -50%) scale(1);
    }

    .pulse-glow {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        border-radius: inherit;
        box-shadow: 0 0 20px rgba(37, 99, 235, 0.4);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.4; }
        50% { transform: scale(1.05); opacity: 0.1; }
        100% { transform: scale(1); opacity: 0.4; }
    }

    .hud-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        opacity: 0.6;
        font-weight: 700;
    }

    .waiting-approval {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 1000;
        color: #FFF;
        width: 400px;
    }
</style>
@endpush

@section('content')
<div id="map-container"></div>

@if(auth()->user()->is_approved)
<div class="driver-hud">
    <!-- Left: Status & Profile -->
    <div class="hud-panel status-hud">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="rounded-circle" style="width: 60px; height: 60px; background: url('https://i.pravatar.cc/150?img=11') center/cover;"></div>
            <div>
                <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                <div class="small text-primary fw-bold">Partenaire Gold</div>
            </div>
        </div>

        <div class="mt-4">
            <div class="hud-label mb-2">Performance du jour</div>
            <div class="stat-row">
                <div class="stat-circle active">
                    <div class="fw-bold h5 mb-0">12</div>
                    <div class="small opacity-50" style="font-size: 0.6rem;">Courses</div>
                </div>
                <div class="stat-circle">
                    <div class="fw-bold h5 mb-0">4.9</div>
                    <div class="small opacity-50" style="font-size: 0.6rem;">Note</div>
                </div>
                <div class="stat-circle">
                    <div class="fw-bold h5 mb-0">98%</div>
                    <div class="small opacity-50" style="font-size: 0.6rem;">Accept.</div>
                </div>
            </div>
        </div>

        <div class="hud-toggle">
            <span class="fw-bold" id="statusText">HORS LIGNE</span>
            <div class="form-check form-switch p-0 m-0">
                <input class="form-check-input ms-0" type="checkbox" id="onlineToggle" style="width: 50px; height: 26px; cursor: pointer;">
            </div>
        </div>
    </div>

    <!-- Middle: Navigation Hint (Optional) -->
    <div></div>

    <!-- Right: Earnings HUD -->
    <div class="hud-panel">
        <div class="hud-label mb-3">Revenus Session</div>
        <div class="display-4 fw-bold text-primary-gradient mb-1">142,50€</div>
        <div class="small opacity-50 mb-4">+12% par rapport à hier</div>

        <div class="hud-label mb-2">Prochaine Prime</div>
        <div class="progress bg-secondary bg-opacity-25 mb-2" style="height: 8px; border-radius: 10px;">
            <div class="progress-bar bg-primary" style="width: 75%; border-radius: 10px;"></div>
        </div>
        <div class="d-flex justify-content-between small opacity-75">
            <span>3/4 courses pour 20€</span>
            <span>75%</span>
        </div>
    </div>
</div>

<!-- Request Modal HUD -->
<div class="hud-panel request-hud-center p-0 overflow-hidden" id="requestAlert">
    <div class="pulse-glow"></div>
    <div class="bg-primary p-4 text-center">
        <div class="hud-label text-white opacity-75">Nouvelle Offre</div>
        <div class="display-4 fw-bold text-white"><span id="reqPrice">0.00</span>€</div>
    </div>
    <div class="p-4">
        <div class="row mb-4">
            <div class="col-6">
                <div class="hud-label mb-1">Départ</div>
                <div class="fw-bold text-truncate" id="reqPickup">--</div>
            </div>
            <div class="col-6">
                <div class="hud-label mb-1">Distance</div>
                <div class="fw-bold">2.4 km</div>
            </div>
        </div>
        <div class="d-flex gap-3 mt-4">
            <button class="btn btn-premium flex-grow-1 py-3" id="acceptBtn">ACCEPTER</button>
            <button class="btn btn-outline-light border-secondary flex-grow-1 py-3" id="declineBtn">IGNORER</button>
        </div>
    </div>
</div>
@else
<div class="waiting-approval hud-panel">
    <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
        <i class="bi bi-clock-history h2 mb-0"></i>
    </div>
    <h3 class="mb-3">Compte en attente</h3>
    <p class="opacity-75 mb-0">Votre compte chauffeur doit être validé par un administrateur avant de pouvoir accepter des courses.</p>
</div>
@endif
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

        const map = L.map('map-container', { zoomControl: false }).setView([40.7128, -74.0060], 13);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png').addTo(map);

        const onlineToggle = document.getElementById('onlineToggle');
        const statusText = document.getElementById('statusText');
        const requestAlert = document.getElementById('requestAlert');
        const acceptBtn = document.getElementById('acceptBtn');

        if (onlineToggle) {
            onlineToggle.addEventListener('change', function() {
                if(this.checked) {
                    statusText.innerText = 'EN LIGNE';
                    statusText.className = 'text-success fw-bold';
                    
                    if (window.Echo) {
                        window.Echo.private('drivers').listen('TripRequested', (e) => showRequest(e.trip));
                    }
                } else {
                    statusText.innerText = 'HORS LIGNE';
                    statusText.className = 'text-white fw-bold';
                    if (window.Echo) window.Echo.leave('drivers');
                }
            });
        }

        let currentTrip = null;

        function showRequest(trip) {
            currentTrip = trip;
            document.getElementById('reqPrice').innerText = trip.price;
            document.getElementById('reqPickup').innerText = trip.pickup_address;
            requestAlert.classList.add('active');
        }

        if (acceptBtn) {
            acceptBtn.addEventListener('click', async () => {
                if (!currentTrip) return;
                try {
                    await axios.post(`/driver/trips/${currentTrip.id}/accept`);
                    alert('Course acceptée ! Navigation vers le point de départ...');
                    requestAlert.classList.remove('active');
                    currentTrip = null;
                } catch (e) {
                    alert('Erreur lors de l\'acceptation : ' + (e.response?.data?.error || 'Inconnu'));
                }
            });

            document.getElementById('declineBtn').addEventListener('click', () => {
                requestAlert.classList.remove('active');
                currentTrip = null;
            });
        }

        // Intro animation
        gsap.from(".hud-panel", { y: 100, opacity: 0, duration: 1, stagger: 0.2, ease: "power4.out" });
    });
</script>
@endpush
