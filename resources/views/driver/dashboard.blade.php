@extends('layouts.app')

@section('title', 'Espace chauffeur — ATLAS AND CO')

@push('styles')
<style>
    .driver-page {
        background: #f8fafc;
        min-height: calc(100vh - 90px);
    }

    .driver-page .active-trip-card {
        background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
        color: #fff;
        border-radius: 24px;
        border: 1px solid rgba(148, 163, 184, 0.25);
        box-shadow: 0 24px 50px rgba(15, 23, 42, 0.15);
    }

    .driver-page .active-trip-card .text-muted-lite {
        color: rgba(255, 255, 255, 0.65) !important;
    }

    .driver-page .map-panel {
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--border-light);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        min-height: 400px;
    }

    .driver-page #driver-tracking-map {
        height: 100%;
        min-height: 420px;
        border-radius: 24px;
    }

    .driver-page .dashboard-accent {
        color: #2563eb;
    }

    .driver-page .ride-offer-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .driver-page .ride-offer-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.1) !important;
    }

    .driver-page .animate-up {
        animation: driverFadeUp 0.45s ease forwards;
    }

    @keyframes driverFadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="driver-page py-4">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success border-0 rounded-4 mb-4 shadow-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
            <h2 class="fw-bold mb-0">Espace <span class="dashboard-accent">Chauffeur</span></h2>
            <div class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill border border-primary border-opacity-25">
                Connecté : {{ Auth::user()->name }}
            </div>
        </div>

        @if (!$isApproved)
            <div class="card border-0 shadow-sm p-5 text-center rounded-4 border-warning border-start border-5">
                <i class="bi bi-hourglass-split text-warning display-4 mb-3 d-block"></i>
                <h5 class="fw-bold">Compte en attente de validation</h5>
                <p class="text-muted mb-0">Un administrateur doit approuver votre profil avant que vous puissiez recevoir et accepter des courses.</p>
            </div>
        @elseif ($activeTrip)
            @php
                $clientPhone = $activeTrip->client?->phone_number;
                $clientTel = $clientPhone ? 'tel:' . preg_replace('/\s+/', '', $clientPhone) : null;
                $statusLabel = match ($activeTrip->status) {
                    'assigned' => 'À accepter',
                    'accepted' => 'Acceptée',
                    'in_progress' => 'En cours',
                    'completed' => 'Terminée',
                    default => ucfirst($activeTrip->status),
                };
            @endphp
            <div class="row mb-4 g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden active-trip-card animate-up">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                                <h4 class="fw-bold mb-0">Course en cours</h4>
                                <span class="badge bg-primary fs-6 rounded-pill px-3">{{ $statusLabel }}</span>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p class="text-muted-lite small mb-1 text-uppercase">Départ</p>
                                    <p class="fw-bold fs-6 mb-0">{{ $activeTrip->pickup_address }}</p>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <p class="text-muted-lite small mb-1 text-uppercase">Destination</p>
                                    <p class="fw-bold fs-6 mb-0">{{ $activeTrip->dropoff_address }}</p>
                                </div>
                            </div>

                            <div class="alert alert-light border-0 rounded-3 mb-4 text-dark">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <span class="d-block text-muted small">Client</span>
                                        <strong>{{ $activeTrip->client?->name ?? '—' }}</strong>
                                    </div>
                                    <div class="text-end">
                                        <span class="d-block text-muted small">Tarif</span>
                                        <strong class="text-success fs-5">{{ number_format($activeTrip->price ?? 0, 2) }} €</strong>
                                    </div>
                                </div>
                                @if ($clientTel)
                                    <hr class="my-3">
                                    <a href="{{ $clientTel }}" class="btn btn-outline-secondary btn-sm w-100">
                                        <i class="bi bi-telephone-fill me-2"></i>Contacter le client
                                    </a>
                                @endif
                            </div>

                            <div class="d-flex flex-column gap-3">
                                @if ($activeTrip->status === 'assigned')
                                    <form action="{{ route('trips.accept', $activeTrip) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-premium btn-lg w-100 py-3 rounded-3 fw-bold">Accepter la course</button>
                                    </form>
                                @elseif ($activeTrip->status === 'accepted')
                                    <form action="{{ route('trips.start', $activeTrip) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-premium btn-lg w-100 py-3 rounded-3 fw-bold">Commencer la course</button>
                                    </form>
                                @elseif ($activeTrip->status === 'in_progress')
                                    <form action="{{ route('trips.complete', $activeTrip) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-lg w-100 py-3 rounded-3 fw-bold">Terminer la course</button>
                                    </form>
                                @elseif ($activeTrip->status === 'completed' && ($activeTrip->payment_status ?? 'pending') !== 'paid')
                                    <div class="alert alert-warning w-100 text-center mb-0 border-0 rounded-3">
                                        <div class="spinner-border spinner-border-sm text-warning me-2" role="status"></div>
                                        <strong>En attente du paiement client</strong>
                                        <p class="mb-2 small mt-2 text-dark">
                                            Montant : {{ number_format($activeTrip->price ?? 0, 2) }} €
                                            ({{ $activeTrip->payment_method === 'cash' ? 'espèces' : 'carte' }}).
                                        </p>
                                        @if ($activeTrip->payment_method === 'cash')
                                            <form action="{{ route('trips.confirm-payment', $activeTrip) }}" method="POST" class="mt-3">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm w-100 py-2 fw-bold">
                                                    <i class="bi bi-cash-stack me-2"></i>Confirmer réception des espèces
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 map-panel">
                        <div id="driver-tracking-map" class="w-100"></div>
                    </div>
                </div>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    <h4 class="fw-bold mb-4">Courses disponibles</h4>
                    <div id="driver-available-trips">
                        @if ($availableTrips->isEmpty())
                            <div class="card border-0 shadow-sm p-5 text-center rounded-4">
                                <i class="bi bi-search text-muted display-4 mb-3 d-block"></i>
                                <h5 class="fw-bold">Aucune course pour le moment</h5>
                                <p class="text-muted mb-0">Les nouvelles missions apparaîtront ici dès qu’un administrateur vous les aura assignées.</p>
                            </div>
                        @else
                            @foreach ($availableTrips as $trip)
                                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden ride-offer-card">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-md-7">
                                                <p class="mb-1 text-muted small">
                                                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>{{ $trip->pickup_address }}
                                                </p>
                                                <p class="mb-0 text-muted small">
                                                    <i class="bi bi-flag-fill text-danger me-2"></i>{{ $trip->dropoff_address }}
                                                </p>
                                            </div>
                                            <div class="col-md-2 text-center mt-3 mt-md-0">
                                                <p class="mb-0 fw-bold fs-5 text-success">{{ number_format($trip->price ?? 0, 2) }} €</p>
                                                @if ($trip->distance)
                                                    <small class="text-muted">{{ number_format($trip->distance, 1) }} km</small>
                                                @endif
                                            </div>
                                            <div class="col-md-3 text-end mt-3 mt-md-0">
                                                <form action="{{ route('trips.accept', $trip) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-premium px-4 py-2 rounded-3 fw-bold w-100 w-md-auto">Accepter</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white mb-4 text-center">
                        <h5 class="fw-bold mb-3">Statistiques du jour</h5>
                        <div class="row">
                            <div class="col-6 border-end">
                                <p class="text-muted small mb-1">Courses</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $completedRidesCount }}</h4>
                            </div>
                            <div class="col-6">
                                <p class="text-muted small mb-1">Gains encaissés</p>
                                <h4 class="fw-bold mb-0 text-success">{{ number_format($totalGains, 2) }} €</h4>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted small">Restez disponible : les courses vous sont attribuées depuis l’espace admin.</p>

                    <div class="card border-0 shadow-sm p-4 rounded-4">
                        <h5 class="fw-bold mb-3">Mon compte</h5>
                        <ul class="nav flex-column gap-2">
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="nav-link text-danger border-0 bg-transparent p-0 w-100 text-start">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if ($isApproved && $activeTrip)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rideStatus = @json($activeTrip->status);
        var paymentStatus = @json($activeTrip->payment_status ?? 'pending');
        var startPos = [{{ (float) $activeTrip->pickup_lat }}, {{ (float) $activeTrip->pickup_lng }}];
        var endPos = [{{ (float) $activeTrip->dropoff_lat }}, {{ (float) $activeTrip->dropoff_lng }}];
        var el = document.getElementById('driver-tracking-map');
        if (!el || typeof L === 'undefined') return;

        var map = L.map('driver-tracking-map', { zoomControl: true }).setView(startPos, 13);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OSM &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20,
        }).addTo(map);

        var pickupIcon = L.divIcon({
            html: '<span style="display:block;width:14px;height:14px;border-radius:50%;background:#2563eb;border:2px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3)"></span>',
            className: 'leaflet-marker-custom',
            iconSize: [14, 14],
            iconAnchor: [7, 7],
        });
        var dropIcon = L.divIcon({
            html: '<span style="display:block;width:14px;height:14px;border-radius:50%;background:#f59e0b;border:2px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3)"></span>',
            className: 'leaflet-marker-custom',
            iconSize: [14, 14],
            iconAnchor: [7, 7],
        });
        L.marker(startPos, { icon: pickupIcon }).addTo(map).bindPopup('Départ');
        L.marker(endPos, { icon: dropIcon }).addTo(map).bindPopup('Arrivée');
        map.fitBounds(L.latLngBounds(startPos, endPos).pad(0.12), { maxZoom: 14 });
        queueMicrotask(function () { map.invalidateSize(); });
        window.addEventListener('resize', function () { map.invalidateSize(); });

        if (rideStatus === 'in_progress') {
            var carIcon = L.divIcon({
                className: 'leaflet-marker-custom',
                html: '<div style="background:#2563eb;width:18px;height:18px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 12px rgba(37,99,235,.6)"></div>',
                iconSize: [18, 18],
                iconAnchor: [9, 9],
            });
            var carMarker = L.marker(startPos, { icon: carIcon }).addTo(map);
            var step = 0;
            var steps = 80;
            function tick() {
                if (step <= steps) {
                    var lat = startPos[0] + (endPos[0] - startPos[0]) * (step / steps);
                    var lng = startPos[1] + (endPos[1] - startPos[1]) * (step / steps);
                    carMarker.setLatLng([lat, lng]);
                    if (step % 12 === 0) map.panTo([lat, lng]);
                    step++;
                    setTimeout(tick, 1800);
                }
            }
            tick();
        }

        if (['assigned', 'accepted', 'in_progress'].indexOf(rideStatus) !== -1) {
            setInterval(function () {
                fetch(window.location.href)
                    .then(function (r) { return r.text(); })
                    .then(function (html) {
                        var doc = new DOMParser().parseFromString(html, 'text/html');
                        if (!doc.querySelector('.active-trip-card')) {
                            window.location.reload();
                            return;
                        }
                        var m = html.match(/var rideStatus = "([^"]+)"/);
                        if (m && m[1] !== rideStatus) window.location.reload();
                    });
            }, 3000);
        }

        if (rideStatus === 'completed' && paymentStatus !== 'paid') {
            setInterval(function () {
                fetch(window.location.href)
                    .then(function (r) { return r.text(); })
                    .then(function (html) {
                        if (!html.includes('En attente du paiement client')) {
                            window.location.reload();
                        }
                    });
            }, 3000);
        }
    });
</script>
@elseif ($isApproved && !$activeTrip)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var container = document.getElementById('driver-available-trips');
        if (!container) return;
        setInterval(function () {
            fetch(window.location.href)
                .then(function (r) { return r.text(); })
                .then(function (html) {
                    var doc = new DOMParser().parseFromString(html, 'text/html');
                    var next = doc.getElementById('driver-available-trips');
                    if (next && container.innerHTML !== next.innerHTML) {
                        window.location.reload();
                    }
                    if (doc.querySelector('.active-trip-card')) {
                        window.location.reload();
                    }
                });
        }, 4000);
    });
</script>
@endif
@endpush
