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
        @elseif ($activeTrip || $activeRental)
            @php
                $mission = $activeTrip ?? $activeRental;
                $isRental = isset($activeRental) && !$activeTrip; // Priorité au Trip si les deux existent (peu probable)
                
                $client = $isRental ? $activeRental->user : $activeTrip->client;
                $clientPhone = $client?->phone_number;
                
                $statusLabel = $isRental ? 'Location Confirmée' : match ($activeTrip->status) {
                    'assigned' => 'À accepter',
                    'accepted' => 'Acceptée',
                    'in_progress' => 'En cours',
                    'completed' => 'Terminée',
                    default => ucfirst($activeTrip->status),
                };
                
                $pickup = $isRental ? ($activeRental->delivery_address ?? 'Retrait en agence') : $activeTrip->pickup_address;
                $dropoff = $isRental ? 'Location (' . $activeRental->total_days . ' jours)' : $activeTrip->dropoff_address;
                $price = $isRental ? $activeRental->total_price : $activeTrip->price;
            @endphp
            <div class="row mb-4 g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden active-trip-card animate-up">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                                <h4 class="fw-bold mb-0">{{ $isRental ? 'Location de véhicule' : 'Course VTC' }}</h4>
                                <span class="badge bg-primary fs-6 rounded-pill px-3">{{ $statusLabel }}</span>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p class="text-muted-lite small mb-1 text-uppercase">{{ $isRental ? 'Lieu de livraison/prise' : 'Départ' }}</p>
                                    <p class="fw-bold fs-6 mb-0">{{ $pickup }}</p>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <p class="text-muted-lite small mb-1 text-uppercase">{{ $isRental ? 'Période' : 'Destination' }}</p>
                                    <p class="fw-bold fs-6 mb-0">
                                        @if($isRental)
                                            Du {{ $activeRental->start_date->format('d/m') }} au {{ $activeRental->end_date->format('d/m/Y') }}
                                        @else
                                            {{ $dropoff }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="card bg-white bg-opacity-10 border-0 rounded-4 mb-4 overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <span class="d-block text-muted-lite small text-uppercase mb-1" style="letter-spacing: 1px;">Passager</span>
                                            <h5 class="fw-bold mb-0"><i class="bi bi-person-circle me-2"></i>{{ $client?->name ?? '—' }}</h5>
                                            @if($clientPhone)
                                                <div class="small text-muted-lite mt-1"><i class="bi bi-phone me-1"></i> {{ $clientPhone }}</div>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <span class="d-block text-muted-lite small text-uppercase mb-1" style="letter-spacing: 1px;">Revenu total</span>
                                            <h4 class="text-success fw-bold mb-0">{{ number_format($price ?? 0, 2) }} €</h4>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12 col-sm-6">
                                            @if($clientPhone)
                                                <a href="tel:{{ preg_replace('/\s+/', '', $clientPhone) }}" class="btn btn-primary w-100 py-3 rounded-3 fw-bold">
                                                    <i class="bi bi-telephone-fill me-2"></i> Appeler le client
                                                </a>
                                            @else
                                                <button class="btn btn-secondary w-100 py-3 rounded-3 fw-bold" disabled>
                                                    <i class="bi bi-telephone-x me-2"></i> Aucun numéro
                                                </button>
                                            @endif
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            @if($clientPhone)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $clientPhone) }}?text={{ urlencode('Bonjour, je suis votre chauffeur Atlas And Co pour votre mission.') }}" 
                                                   target="_blank" 
                                                   class="btn btn-success w-100 py-3 rounded-3 fw-bold">
                                                    <i class="bi bi-whatsapp me-2"></i> Message WhatsApp
                                                </a>
                                            @else
                                                <button class="btn btn-secondary w-100 py-3 rounded-3 fw-bold" disabled>
                                                    <i class="bi bi-whatsapp me-2"></i> WhatsApp indisponible
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-3">
                                @if (!$isRental)
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
                                        <div class="bg-white rounded-3 p-4 text-dark shadow-sm border border-success border-opacity-25" id="payment-options-block">
                                            <div class="text-center mb-3">
                                                <i class="bi bi-cash-stack text-success fs-1 mb-2"></i>
                                                <h5 class="fw-bold">Confirmer le paiement</h5>
                                                <p class="text-muted small">Veuillez sélectionner le mode de paiement reçu du client.</p>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <button type="button" 
                                                            class="btn btn-outline-success w-100 py-3 fw-bold payment-btn" 
                                                            data-method="cash"
                                                            data-url="{{ route('trips.mark-paid', $activeTrip) }}">
                                                        <i class="bi bi-wallet2 d-block fs-4 mb-1"></i> Espèces
                                                    </button>
                                                </div>
                                                <div class="col-6">
                                                    <button type="button" 
                                                            class="btn btn-outline-primary w-100 py-3 fw-bold payment-btn" 
                                                            data-method="card"
                                                            data-url="{{ route('trips.mark-paid', $activeTrip) }}">
                                                        <i class="bi bi-credit-card d-block fs-4 mb-1"></i> TPE / Carte
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="payment-success-badge" class="alert alert-success d-none rounded-4 text-center py-4">
                                            <i class="bi bi-check-circle-fill fs-1 d-block mb-2"></i>
                                            <h5 class="fw-bold mb-0" id="payment-success-text">Paiement validé !</h5>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-info border-0 rounded-3 mb-0">
                                        <i class="bi bi-info-circle me-2"></i> Cette mission de location est confirmée par l'administration.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 map-panel">
                        @if(!$isRental)
                            <div id="driver-tracking-map" class="w-100"></div>
                        @else
                            <div class="p-4 text-center d-flex flex-column justify-content-center h-100">
                                <i class="bi bi-calendar-check text-primary display-1 mb-3"></i>
                                <h5 class="fw-bold">Location programmée</h5>
                                <p class="text-muted small">Utilisez les boutons de contact pour coordonner la remise des clés.</p>
                            </div>
                        @endif
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

        var paymentBtns = document.querySelectorAll('.payment-btn');
        if (paymentBtns.length > 0) {
            paymentBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var method = this.getAttribute('data-method');
                    var url = this.getAttribute('data-url');
                    
                    this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    this.disabled = true;
                    var otherBtns = document.querySelectorAll('.payment-btn');
                    otherBtns.forEach(b => b.disabled = true);
                    
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ payment_method: method })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('payment-options-block').classList.add('d-none');
                            var successBadge = document.getElementById('payment-success-badge');
                            successBadge.classList.remove('d-none');
                            document.getElementById('payment-success-text').innerText = '✓ Payé — ' + (method === 'cash' ? 'Main propre' : 'Terminal');
                            setTimeout(() => window.location.reload(), 2000);
                        } else {
                            alert('Erreur lors du paiement.');
                            window.location.reload();
                        }
                    })
                    .catch(e => {
                        alert('Erreur réseau.');
                        window.location.reload();
                    });
                });
            });
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
