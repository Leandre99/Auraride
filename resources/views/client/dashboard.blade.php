@extends('layouts.app')

@section('title', 'Courses avec chauffeur — ATLAS AND CO')

@push('styles')
    @unless(isset($trackingTrip) && $trackingTrip)
    <style>
        body {
            background: #0f172a;
            overflow: hidden;
        }

        #map-container {
            position: fixed;
            top: 90px;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }

        /* Lisibilité carte : léger voile sous le panneau */
        #map-container::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 500;
            background: linear-gradient(105deg, rgba(15, 23, 42, 0.35) 0%, transparent 45%, transparent 100%);
        }

        .leaflet-pane,
        .leaflet-top,
        .leaflet-bottom {
            z-index: 2 !important;
        }

        #map-container.leaflet-container {
            font-family: inherit;
        }

        .leaflet-marker-custom {
            background: transparent !important;
            border: none !important;
        }

        /* Panneau réservation — carte posée sur la carte */
        .command-center {
            position: fixed;
            top: 104px;
            left: max(16px, env(safe-area-inset-left));
            width: min(420px, calc(100vw - 32px));
            max-height: calc(100vh - 118px - env(safe-area-inset-bottom));
            z-index: 520;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow:
                0 1px 0 rgba(255, 255, 255, 0.85) inset,
                0 24px 50px rgba(15, 23, 42, 0.18),
                0 0 0 1px rgba(15, 23, 42, 0.04);
        }

        .cc-accent {
            height: 4px;
            flex-shrink: 0;
            background: linear-gradient(90deg, #2563eb, #6366f1);
        }

        .cc-header {
            padding: 1.125rem 1.25rem 0.875rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .cc-content {
            padding: 1rem 1.25rem 1.125rem;
            overflow-y: auto;
            flex-grow: 1;
            -webkit-overflow-scrolling: touch;
        }

        .booking-stepper {
            display: flex;
            gap: 8px;
            margin-bottom: 0.875rem;
        }

        .booking-step {
            flex: 1;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            text-align: center;
            padding: 0.4rem 0.35rem;
            border-radius: 10px;
            background: #f1f5f9;
            color: #64748b;
            transition: color 0.2s, background 0.2s, box-shadow 0.2s;
        }

        .booking-step--current {
            background: linear-gradient(135deg, #eff6ff, #eef2ff);
            color: #1d4ed8;
            box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.2);
        }

        .cc-title-row h2 {
            font-size: 1.15rem;
            letter-spacing: -0.02em;
        }

        .rental-hint details {
            font-size: 0.8rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 0.5rem 0.75rem;
        }

        .rental-hint summary {
            cursor: pointer;
            font-weight: 600;
            color: #475569;
            list-style: none;
        }

        .rental-hint summary::-webkit-details-marker {
            display: none;
        }

        .ride-search-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 4px;
            border: 1px solid #e2e8f0;
        }

        .ride-input-group {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
        }

        .ride-input-group:first-child {
            border-radius: 12px 12px 0 0;
        }

        .ride-input-group:last-child {
            border-bottom: none;
            border-radius: 0 0 12px 12px;
        }

        .dot-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .dot-pickup {
            background: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .dot-dropoff {
            background: #ea580c;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15);
        }

        .ride-input {
            border: none;
            width: 100%;
            font-weight: 500;
            font-size: 0.95rem;
            color: #0f172a;
            background: transparent;
        }

        .ride-input::placeholder {
            color: #94a3b8;
        }

        .ride-input:focus {
            outline: none;
        }

        .ride-input-group:focus-within {
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25);
        }

        .section-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin: 1rem 0 0.5rem;
        }

        .vehicle-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 0.5rem;
        }

        @media (max-width: 380px) {
            .vehicle-grid {
                grid-template-columns: 1fr;
            }
        }

        .vehicle-card-v2 {
            background: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
        }

        .vehicle-card-v2:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }

        .vehicle-card-v2.active {
            border-color: #2563eb;
            background: #f8fafc;
            box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.15);
        }

        .vehicle-card-v2 .veh-thumb {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        #mainActionBtn {
            border-radius: 14px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .quick-actions {
            position: fixed;
            top: 104px;
            right: max(16px, env(safe-area-inset-right));
            z-index: 520;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .action-btn {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
            cursor: pointer;
            transition: transform 0.15s, color 0.15s;
            border: 1px solid #e2e8f0;
            color: #64748b;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            color: var(--primary);
        }

        .modern-status-bar {
            position: fixed;
            bottom: max(24px, env(safe-area-inset-bottom));
            left: 50%;
            transform: translateX(-50%);
            width: min(560px, calc(100vw - 32px));
            background: #0f172a;
            border-radius: 18px;
            padding: 16px 22px;
            display: none;
            z-index: 530;
            color: #fff;
            border: 1px solid rgba(148, 163, 184, 0.25);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.35);
        }

        .autocomplete-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
            z-index: 1060;
            max-height: 220px;
            overflow-y: auto;
            margin-top: 6px;
            display: none;
            border: 1px solid #e2e8f0;
        }

        .result-item {
            padding: 10px 14px;
            cursor: pointer;
            font-size: 0.82rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
        }

        .result-item:hover {
            background: #f8fafc;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        /* Modale notation : même style carte, au-dessus de tout */
        #ratingModal.command-center {
            position: fixed;
            z-index: 2000;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: min(400px, calc(100vw - 24px));
            max-height: min(90vh, 640px);
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.35);
        }

        #ratingModal .cc-accent {
            display: none;
        }
    </style>
    @else
    <style>
        body { background: #f8fafc; }
        .client-tracking .tracking-status {
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            border-radius: 24px;
            padding: 1.75rem;
            border: 1px solid rgba(148, 163, 184, 0.25);
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.2);
        }
        .client-tracking .tracking-status .text-light-muted { color: rgba(255, 255, 255, 0.65); }
        .client-tracking .map-panel {
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--border-light);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
            min-height: 480px;
        }
        .client-tracking #tracking-map { height: 500px; border-radius: 24px; }
        .client-tracking .dashboard-accent { color: #2563eb; }
        .client-tracking .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 0.35rem;
            margin-bottom: 1rem;
        }
        .client-tracking .star-rating input { display: none; }
        .client-tracking .star-rating label {
            cursor: pointer;
            font-size: 1.5rem;
            color: #cbd5e1;
            margin: 0;
            line-height: 1;
        }
        .client-tracking .star-rating label:hover,
        .client-tracking .star-rating label:hover ~ label,
        .client-tracking .star-rating input:checked ~ label { color: #f59e0b; }
        .client-tracking .animate-up { animation: auraFadeUp 0.5s ease forwards; }
        @keyframes auraFadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @endunless
@endpush

@section('content')
    @if(isset($trackingTrip) && $trackingTrip)
    @php
        $driverPhone = $trackingTrip->driver?->phone_number;
        $telHref = $driverPhone ? 'tel:' . preg_replace('/\s+/', '', $driverPhone) : '#';
        $veh = $trackingTrip->vehicle;
        $typeName = $veh?->vehicleType?->name ?? 'Véhicule';
        $vehDesc = $veh
            ? trim(($veh->model ?: $typeName) . ($veh->color ? ' (' . $veh->color . ')' : ''))
            : $typeName;
        $plate = $veh?->plate_number ?? '—';
    @endphp
    <section class="client-tracking py-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success border-0 rounded-4 mb-4 shadow-sm">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm">
                    <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
                <h2 class="fw-bold mb-0">Suivi de votre <span class="dashboard-accent">course</span></h2>
                <div class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill border border-primary border-opacity-25">
                    Connecté : {{ Auth::user()->name }}
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="tracking-status animate-up shadow-lg" id="status-card" data-trip-status="{{ $trackingTrip->status }}">
                        @if($trackingTrip->status === 'pending')
                            <h4 class="mb-3"><i class="bi bi-search me-2"></i>Recherche de chauffeur…</h4>
                            <p class="mb-0 text-light-muted">Votre demande est envoyée aux chauffeurs disponibles.</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="spinner-border text-light spinner-border-sm me-2" role="status"></div>
                                <span class="text-light-muted">Veuillez patienter…</span>
                            </div>
                        @elseif($trackingTrip->status === 'assigned')
                            <h4 class="mb-3"><i class="bi bi-person-check me-2"></i>Chauffeur désigné</h4>
                            <p class="mb-0 text-light-muted">Un chauffeur vous a été assigné. Il peut accepter la course sous peu.</p>
                            @if($trackingTrip->driver)
                                <p class="mb-0 mt-3"><strong>{{ $trackingTrip->driver->name }}</strong></p>
                            @endif
                            <div class="d-flex align-items-center mt-3">
                                <div class="spinner-border text-light spinner-border-sm me-2" role="status"></div>
                                <span class="text-light-muted">En attente de confirmation…</span>
                            </div>
                        @elseif($trackingTrip->status === 'accepted')
                            <h4 class="mb-3"><i class="bi bi-check-circle-fill me-2"></i>Chauffeur trouvé !</h4>
                            <p class="mb-0"><strong>{{ $trackingTrip->driver?->name ?? 'Votre chauffeur' }}</strong> se dirige vers vous.</p>
                            <p class="mb-0 small text-light-muted mt-2">{{ $vehDesc }} — {{ $plate }}</p>
                            <hr class="border-secondary opacity-25">
                            <div class="d-flex align-items-center mb-0">
                                <div class="spinner-grow text-light spinner-grow-sm me-2" role="status"></div>
                                <span>Prise en charge sous peu</span>
                            </div>
                        @elseif($trackingTrip->status === 'in_progress')
                            <h4 class="mb-3"><i class="bi bi-geo-alt-fill me-2"></i>Course en cours</h4>
                            <p class="mb-0 text-light-muted">Vous êtes en route vers votre destination.</p>
                            <hr class="border-secondary opacity-25">
                            <p class="mb-0">Destination : <strong>{{ $trackingTrip->dropoff_address }}</strong></p>
                        @elseif($trackingTrip->status === 'completed')
                            <h4 class="mb-3"><i class="bi bi-flag-fill me-2"></i>Course terminée</h4>
                            <p class="mb-3 text-light-muted">Merci d'avoir voyagé avec nous.</p>
                            <div class="bg-white text-dark p-4 rounded-4 shadow-sm mb-0">
                                @if(!$trackingTrip->rating)
                                    <h5 class="fw-bold text-center mb-3">Votre avis</h5>
                                    <form id="payment-form" action="{{ route('trips.rate', $trackingTrip) }}" method="POST">
                                        @csrf
                                        <div class="star-rating mb-3">
                                            <input type="radio" id="star5" name="rating" value="5" required />
                                            <label for="star5" title="Excellent"><i class="bi bi-star-fill"></i></label>
                                            <input type="radio" id="star4" name="rating" value="4" />
                                            <label for="star4" title="Très bien"><i class="bi bi-star-fill"></i></label>
                                            <input type="radio" id="star3" name="rating" value="3" />
                                            <label for="star3" title="Bien"><i class="bi bi-star-fill"></i></label>
                                            <input type="radio" id="star2" name="rating" value="2" />
                                            <label for="star2" title="Passable"><i class="bi bi-star-fill"></i></label>
                                            <input type="radio" id="star1" name="rating" value="1" />
                                            <label for="star1" title="Médiocre"><i class="bi bi-star-fill"></i></label>
                                        </div>
                                        <div class="mb-3 text-center">
                                            <label class="form-label d-block mb-2 small fw-semibold">Mode de paiement</label>
                                            <div class="btn-group w-100" role="group">
                                                <input type="radio" class="btn-check" name="payment_method" id="pay_card" value="card" checked autocomplete="off">
                                                <label class="btn btn-outline-primary" for="pay_card"><i class="bi bi-credit-card me-2"></i>Carte</label>
                                                <input type="radio" class="btn-check" name="payment_method" id="pay_cash" value="cash" autocomplete="off">
                                                <label class="btn btn-outline-primary" for="pay_cash"><i class="bi bi-cash-stack me-2"></i>Espèces</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <textarea name="comment" class="form-control border bg-light" rows="3" placeholder="Commentaire (optionnel)"></textarea>
                                        </div>
                                        <button type="submit" id="submit-btn" class="btn btn-premium w-100 py-3 fw-bold">
                                            <i class="bi bi-check-circle me-2"></i>Valider & noter
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center">
                                        @if($trackingTrip->payment_status === 'paid')
                                            <i class="bi bi-check-circle-fill text-success fs-1 mb-3 d-block"></i>
                                            <h6 class="fw-bold">Paiement effectué</h6>
                                            <p class="text-muted small mb-0">Règlement par {{ $trackingTrip->payment_method === 'card' ? 'carte' : 'espèces' }}.</p>
                                            @if($trackingTrip->rating)
                                                <p class="mb-0 mt-2">Note : <strong>{{ $trackingTrip->rating }}/5</strong> <i class="bi bi-star-fill text-warning"></i></p>
                                            @endif
                                        @else
                                            <i class="bi bi-hourglass-split text-warning fs-1 mb-3 d-block"></i>
                                            <h6 class="fw-bold">Paiement en attente</h6>
                                            @if($trackingTrip->payment_method === 'card')
                                                <p class="text-muted small">Réglez <strong>{{ number_format($trackingTrip->price, 2) }} €</strong> par carte.</p>
                                                <form action="{{ route('trips.pay', $trackingTrip) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-premium w-100 fw-bold">Payer maintenant</button>
                                                </form>
                                            @else
                                                <p class="text-muted small">Remettez <strong>{{ number_format($trackingTrip->price, 2) }} €</strong> en espèces au chauffeur. Il confirmera la réception.</p>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if(in_array($trackingTrip->status, ['pending', 'assigned', 'accepted']))
                            <hr class="border-secondary opacity-25">
                            <form action="{{ route('trips.cancel', $trackingTrip) }}" method="POST" onsubmit="return confirm('Annuler cette course ?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-light w-100">Annuler la course</button>
                            </form>
                        @endif
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
                        <h5 class="fw-bold mb-3">Détails du trajet</h5>
                        <p class="text-muted small mb-1 text-uppercase">Départ</p>
                        <p class="fw-bold mb-3">{{ $trackingTrip->pickup_address }}</p>
                        <p class="text-muted small mb-1 text-uppercase">Arrivée</p>
                        <p class="fw-bold mb-3">{{ $trackingTrip->dropoff_address }}</p>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Tarif estimé</span>
                            <span class="fw-bold text-success fs-5">{{ number_format($trackingTrip->price ?? 0, 2) }} €</span>
                        </div>
                    </div>

                    @if($trackingTrip->driver)
                        <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
                            <h5 class="fw-bold mb-3">Chauffeur & véhicule</h5>
                            <div class="d-flex align-items-center mt-2">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 50px; height: 50px;">
                                    <i class="bi bi-person-fill text-primary fs-3"></i>
                                </div>
                                <div>
                                    <p class="fw-bold mb-0">{{ $trackingTrip->driver->name }}</p>
                                    <p class="text-muted small mb-0">{{ $vehDesc }} · {{ $plate }}</p>
                                </div>
                            </div>
                            @if(in_array($trackingTrip->status, ['accepted', 'in_progress']) && $driverPhone)
                                <hr>
                                <a href="{{ $telHref }}" class="btn btn-outline-premium w-100"><i class="bi bi-telephone-fill me-2"></i>Appeler le chauffeur</a>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-lg-8">
                    <div id="tracking-map" class="map-panel shadow"></div>
                </div>
            </div>
        </div>
    </section>
    @else
    <div id="map-container"></div>

    <div class="command-center" id="commandCenter">
        <div class="cc-accent" aria-hidden="true"></div>
        <div class="cc-header">
            <div class="booking-stepper" role="presentation">
                <span class="booking-step booking-step--current" id="bookingStepTrip">Trajet</span>
                <span class="booking-step" id="bookingStepVehicle">Véhicule</span>
            </div>
            <div class="cc-title-row d-flex justify-content-between align-items-start gap-2 mb-2">
                <div>
                    <h2 class="fw-bold mb-1 text-dark">Course avec chauffeur</h2>
                    <p class="small text-muted mb-0 lh-sm">Une prise en charge <strong>taxi&nbsp;/&nbsp;VTC</strong> : départ → arrivée, chauffeur dédié.</p>
                </div>
                <span class="badge rounded-pill bg-primary text-white flex-shrink-0 align-self-start px-2 py-2" style="font-size: .65rem;">live</span>
            </div>
            <p class="small text-secondary mb-2">Bonjour <strong>{{ auth()->user()->name }}</strong>.</p>
            <div class="rental-hint mb-0">
                <details>
                    <summary>Location plusieurs jours (avec ou sans chauffeur)&nbsp;?</summary>
                    <p class="text-muted mb-0 mt-2 small">Ce n’est pas la même chose qu’ici. Les locations se font depuis l’accueil&nbsp;:</p>
                    <a href="{{ route('home') }}#rental" class="d-inline-block mt-1 small fw-semibold">Ouvrir la section location →</a>
                </details>
            </div>
        </div>

        <div class="cc-content">
            <p class="section-label mb-0" id="sectionRouteLabel">Où allez-vous&nbsp;?</p>
            <div class="ride-search-box mt-2">
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
                <p class="section-label mb-0" id="sectionVehicleLabel">Catégorie &amp; tarif estimé</p>
                <div class="vehicle-grid mt-2" id="vehicleGrid">
                    <!-- Dynamic Content -->
                </div>

                <div class="mt-3 p-3 rounded-3 border text-center" style="border-color: #e2e8f0 !important; background: #fafafa;">
                    <div class="small text-muted mb-1"><i class="bi bi-person-badge me-1 text-primary"></i> Tarif tout compris avec <strong>chauffeur</strong></div>
                    <div class="small text-muted">À régler après le trajet à bord (TPE).</div>
                </div>
            </div>

            <button type="button" class="btn btn-premium w-100 py-3 mt-4" id="mainActionBtn">Continuer</button>
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

            <button type="button" class="btn btn-premium w-100 py-3" id="submitRating">Envoyer mon avis</button>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
    @if(isset($trackingTrip) && $trackingTrip)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            var rideStatus = @json($trackingTrip->status);
            var startPos = [{{ (float) $trackingTrip->pickup_lat }}, {{ (float) $trackingTrip->pickup_lng }}];
            var endPos = [{{ (float) $trackingTrip->dropoff_lat }}, {{ (float) $trackingTrip->dropoff_lng }}];
            var mapEl = document.getElementById('tracking-map');
            if (!mapEl || typeof L === 'undefined') return;
            var map = L.map('tracking-map', { zoomControl: true }).setView(startPos, 13);
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
            queueMicrotask(() => map.invalidateSize());
            window.addEventListener('resize', () => map.invalidateSize());

            if (rideStatus === 'in_progress') {
                var carIcon = L.divIcon({
                    className: 'leaflet-marker-custom',
                    html: '<div style="background:#2563eb;width:18px;height:18px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 12px rgba(37,99,235,.6)"></div>',
                    iconSize: [18, 18],
                    iconAnchor: [9, 9],
                });
                var carMarker = L.marker(startPos, { icon: carIcon }).addTo(map);
                var currentStep = 0;
                var steps = 80;
                function animateTrip() {
                    if (currentStep <= steps) {
                        var lat = startPos[0] + (endPos[0] - startPos[0]) * (currentStep / steps);
                        var lng = startPos[1] + (endPos[1] - startPos[1]) * (currentStep / steps);
                        carMarker.setLatLng([lat, lng]);
                        if (currentStep % 12 === 0) map.panTo([lat, lng]);
                        currentStep++;
                        setTimeout(animateTrip, 1800);
                    }
                }
                animateTrip();
            }

            if (['pending', 'assigned', 'accepted', 'in_progress'].indexOf(rideStatus) !== -1) {
                setInterval(function () {
                    fetch(window.location.href)
                        .then(function (r) { return r.text(); })
                        .then(function (html) {
                            var doc = new DOMParser().parseFromString(html, 'text/html');
                            var el = doc.querySelector('#status-card');
                            var newStatus = el && el.getAttribute('data-trip-status');
                            if (newStatus && newStatus !== rideStatus) window.location.reload();
                        });
                }, 3000);
            }

            if (rideStatus === 'completed') {
                setInterval(function () {
                    fetch(window.location.href)
                        .then(function (r) { return r.text(); })
                        .then(function (html) {
                            var doc = new DOMParser().parseFromString(html, 'text/html');
                            if (!doc.querySelector('#status-card')) {
                                window.location.reload();
                                return;
                            }
                            var el = doc.querySelector('#status-card');
                            var newStatus = el && el.getAttribute('data-trip-status');
                            if (newStatus && newStatus !== rideStatus) window.location.reload();
                            var oldCard = document.getElementById('status-card');
                            var newCard = doc.getElementById('status-card');
                            if (oldCard && newCard && newCard.innerHTML !== oldCard.innerHTML) window.location.reload();
                        });
                }, 3000);
            }

            var payForm = document.getElementById('payment-form');
            if (payForm) {
                payForm.addEventListener('submit', function (e) {
                    var card = document.getElementById('pay_card');
                    if (card && card.checked) {
                        e.preventDefault();
                        var btn = document.getElementById('submit-btn');
                        if (btn) {
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Traitement…';
                            btn.disabled = true;
                        }
                        setTimeout(function () { payForm.submit(); }, 1500);
                    }
                });
            }
        });
    </script>
    @else
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

            const jsonHeaders = () => ({
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            });

            async function parseResponseBody(res) {
                const text = await res.text();
                try { return text ? JSON.parse(text) : {}; } catch { return { message: text || 'Réponse invalide' }; }
            }

            // URLs relatives pour éviter les erreurs CORS quand APP_URL ≠ l'hôte du navigateur.
            const estimateUrl = '/client/trips/estimate';
            const storeUrl = '/client/trips';

            async function postJson(url, payload) {
                let res;
                try {
                    res = await fetch(url, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: jsonHeaders(),
                        body: JSON.stringify(payload),
                    });
                } catch (e) {
                    const err = new Error(
                        'Connexion au serveur impossible. Vérifiez que vous utilisez la même adresse que dans votre navigateur (évitez de mélanger localhost et 127.0.0.1), puis rafraîchissez la page.'
                    );
                    err.cause = e;
                    throw err;
                }
                const data = await parseResponseBody(res);
                if (!res.ok) {
                    const fromValidation = data.errors ? Object.values(data.errors).flat().join('\n') : null;
                    const err = new Error(fromValidation || data.message || ('Erreur ' + res.status));
                    err.data = data;
                    err.status = res.status;
                    throw err;
                }
                return data;
            }

            let map;
            try {
                map = L.map('map-container', { zoomControl: true }).setView([48.8566, 2.3522], 12);
            } catch (mapErr) {
                console.error(mapErr);
                alert('La carte ne peut pas s\'afficher sur cette page.');
                return;
            }
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; CARTO',
                subdomains: 'abcd',
                maxZoom: 20,
            }).addTo(map);

            let pickupMarker = null;
            let dropoffMarker = null;

            function setRouteMarker(type, lat, lng, options) {
                const iconHtml = `<span style="display:block;width:16px;height:16px;border-radius:50%;background:${options.color};border:2px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.35);"></span>`;
                const ic = L.divIcon({
                    html: iconHtml,
                    className: 'leaflet-marker-custom',
                    iconSize: [16, 16],
                    iconAnchor: [8, 8],
                });
                const m = L.marker([lat, lng], { icon: ic });
                if (type === 'pickup') {
                    if (pickupMarker) map.removeLayer(pickupMarker);
                    pickupMarker = m.addTo(map);
                } else {
                    if (dropoffMarker) map.removeLayer(dropoffMarker);
                    dropoffMarker = m.addTo(map);
                }
            }

            queueMicrotask(() => map.invalidateSize());
            window.addEventListener('resize', () => map.invalidateSize());

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

            // Geocoding Logic (Nominatim — usage modeste conforme à leur policy)
            async function searchAddress(query) {
                if (!query || query.trim().length < 3) return [];
                try {
                    const q = encodeURIComponent(query.trim());
                    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${q}&limit=5`, {
                        headers: { Accept: 'application/json' },
                        credentials: 'omit',
                    });
                    const data = await parseResponseBody(res);
                    return Array.isArray(data) ? data : [];
                } catch (_) {
                    return [];
                }
            }

            /** Si l'utilisateur a tapé du texte sans cliquer une suggestion, on prend la 1re proposition Nominatim. */
            async function ensureRouteSlotsFromInputs() {
                let ok = true;
                const pickText = pickupInput?.value?.trim() ?? '';
                const dropText = dropoffInput?.value?.trim() ?? '';

                if (!routeData.pickup && pickText.length >= 3) {
                    const hits = await searchAddress(pickText);
                    const item = hits[0];
                    if (item) {
                        routeData.pickup = {
                            lat: parseFloat(item.lat),
                            lng: parseFloat(item.lon),
                            address: item.display_name,
                        };
                        pickupInput.value = item.display_name;
                        setRouteMarker('pickup', routeData.pickup.lat, routeData.pickup.lng, { color: '#2563eb' });
                    } else ok = false;
                }
                if (!routeData.dropoff && dropText.length >= 3) {
                    const hits = await searchAddress(dropText);
                    const item = hits[0];
                    if (item) {
                        routeData.dropoff = {
                            lat: parseFloat(item.lat),
                            lng: parseFloat(item.lon),
                            address: item.display_name,
                        };
                        dropoffInput.value = item.display_name;
                        setRouteMarker('dropoff', routeData.dropoff.lat, routeData.dropoff.lng, { color: '#f59e0b' });
                    } else ok = false;
                }
                return ok;
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
                                    map.setView([routeData[type].lat, routeData[type].lng], 15);
                                    setRouteMarker(type, routeData[type].lat, routeData[type].lng, {
                                        color: type === 'pickup' ? '#2563eb' : '#f59e0b',
                                    });
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

            function setBookingStep(phase) {
                const stepTrip = document.getElementById('bookingStepTrip');
                const stepVeh = document.getElementById('bookingStepVehicle');
                if (!stepTrip || !stepVeh) return;
                stepTrip.classList.toggle('booking-step--current', phase === 'trip');
                stepVeh.classList.toggle('booking-step--current', phase === 'vehicle');
            }
            setBookingStep('trip');

            mainActionBtn.addEventListener('click', async () => {
                if (state === 0) {
                    if (!routeData.pickup || !routeData.dropoff) {
                        mainActionBtn.disabled = true;
                        mainActionBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Adresses...';
                        const resolved = await ensureRouteSlotsFromInputs();
                        mainActionBtn.disabled = false;
                        mainActionBtn.innerHTML = 'Continuer';
                        if (!resolved || !routeData.pickup || !routeData.dropoff) {
                            alert('Indiquez un départ et une arrivée (au moins 3 caractères). Choisissez une suggestion ou laissez-nous géocoder au clic sur Continuer.');
                            return;
                        }
                    }

                        mainActionBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Calcul...';
                    mainActionBtn.disabled = true;

                    try {
                        const estimateRows = await postJson(estimateUrl, {
                            pickup_lat: routeData.pickup.lat, pickup_lng: routeData.pickup.lng,
                            dropoff_lat: routeData.dropoff.lat, dropoff_lng: routeData.dropoff.lng,
                        });

                        if (!estimateRows || !estimateRows.length) {
                            alert('Aucun type de véhicule disponible. Exécutez les seeders : php artisan db:seed.');
                            mainActionBtn.innerHTML = 'Continuer';
                            mainActionBtn.disabled = false;
                            return;
                        }

                        vehicleGrid.innerHTML = '';
                        estimateRows.forEach((opt, index) => {
                            const card = document.createElement('div');
                            card.className = `vehicle-card-v2 ${index === 0 ? 'active' : ''}`;
                            card.innerHTML = `
                                <div class="veh-thumb shadow-sm"><img src="${opt.id === 1 ? '{{ asset('images/berline-standard.jpg') }}' : (opt.id === 2 ? '{{ asset('images/van-luxe.jpg') }}' : '{{ asset('images/sprinter-mercedes.jpg') }}')}" class="w-100" style="height:72px;object-fit:cover;" alt=""></div>
                                <div class="fw-bold small">${opt.name}</div>
                                <div class="text-primary fw-bold small mt-1">${opt.price}€</div>`;
                            card.addEventListener('click', () => {
                                document.querySelectorAll('.vehicle-card-v2').forEach(c => c.classList.remove('active'));
                                card.classList.add('active');
                                selectedVehicle = opt;
                            });
                            vehicleGrid.appendChild(card);
                            if (index === 0) selectedVehicle = opt;
                        });

                        // routeData was already partially filled by autocomplete, we complete it here
                        routeData.distance = estimateRows[0].distance;
                        routeData.duration = estimateRows[0].duration;

                        const bounds = L.latLngBounds(
                            [routeData.pickup.lat, routeData.pickup.lng],
                            [routeData.dropoff.lat, routeData.dropoff.lng],
                        );
                        map.fitBounds(bounds.pad(0.15), { maxZoom: 15 });

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

                        mainActionBtn.innerHTML = 'Confirmer la course';
                        mainActionBtn.disabled = false;
                        state = 1;
                        setBookingStep('vehicle');
                    } catch (e) {
                        const err = e.data;
                        const msg = err?.message
                            ?? (err?.errors ? Object.values(err.errors).flat().join('\n') : null)
                            ?? e.message;
                        alert('Impossible de calculer le trajet :\n' + msg);
                        mainActionBtn.innerHTML = 'Continuer';
                        mainActionBtn.disabled = false;
                        setBookingStep('trip');
                    }
                } else if (state === 1) {
                    mainActionBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Envoi...';
                    mainActionBtn.disabled = true;

                    try {
                        await postJson(storeUrl, window.currentBookingData);
                        window.location.reload();
                    } catch (e) {
                        const err = e.data;
                        const msg = err?.message
                            ?? (err?.errors ? Object.values(err.errors).flat().join('\n') : null)
                            ?? e.message;
                        alert('La commande a échoué :\n' + msg);
                        mainActionBtn.innerHTML = 'Confirmer la course';
                        mainActionBtn.disabled = false;
                    }
                }
            });

            if (typeof gsap !== 'undefined') {
                gsap.from('#commandCenter', { x: -28, opacity: 0, duration: 0.55, ease: 'power2.out' });
            }
        });
    </script>
    @endif
@endpush
