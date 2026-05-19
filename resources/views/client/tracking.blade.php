@extends('layouts.app')

@section('title', 'Suivi de votre course - ATLAS TAXI / VTC')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="tracking-status animate-up shadow-lg bg-dark text-white rounded-4 p-4" id="status-card">
                    @if($trip->status === 'pending')
                        <h4 class="mb-3"><i class="bi bi-search me-2"></i>Recherche de chauffeur...</h4>
                        <p class="mb-0 opacity-75">Nous envoyons votre demande aux chauffeurs les plus proches.</p>
                        <div class="d-flex align-items-center mt-3">
                            <div class="spinner-border text-light spinner-border-sm me-2" role="status"></div>
                            <span>Veuillez patienter...</span>
                        </div>
                    @elseif($trip->status === 'assigned')
                        <h4 class="mb-3"><i class="bi bi-person-check me-2"></i>Chauffeur désigné</h4>
                        <p class="mb-0 opacity-75">Un chauffeur vous a été assigné. Il va bientôt confirmer.</p>
                        @if($trip->driver)
                            <p class="mb-0 mt-3"><strong>{{ $trip->driver->name }}</strong></p>
                        @endif
                        <div class="d-flex align-items-center mt-3">
                            <div class="spinner-border text-light spinner-border-sm me-2" role="status"></div>
                            <span>En attente de confirmation...</span>
                        </div>
                    @elseif($trip->status === 'accepted')
                        <h4 class="mb-3"><i class="bi bi-check-circle-fill me-2"></i>Chauffeur trouvé !</h4>
                        <p class="mb-0"><strong>{{ $trip->driver?->name ?? 'Votre chauffeur' }}</strong> arrive vers vous.</p>
                        @if($trip->vehicle)
                            <p class="mb-0 small opacity-75 mt-1">{{ $trip->vehicle->model ?? '' }} - {{ $trip->vehicle->plate_number ?? '' }}</p>
                        @endif
                        <hr class="border-secondary opacity-25">
                        <div class="d-flex align-items-center mb-3">
                            <div class="spinner-grow text-light spinner-grow-sm me-2" role="status"></div>
                            <span>Arrivée prévue dans <strong id="eta">5</strong> min</span>
                        </div>
                    @elseif($trip->status === 'in_progress')
                        <h4 class="mb-3"><i class="bi bi-geo-alt-fill me-2"></i>Course en cours</h4>
                        <p class="mb-0 opacity-75">Vous êtes en route vers votre destination.</p>
                        <hr class="border-secondary opacity-25">
                        <p class="mb-0">Destination: <strong>{{ $trip->dropoff_address }}</strong></p>
                    @elseif($trip->status === 'completed')
                        <h4 class="mb-3"><i class="bi bi-flag-fill me-2"></i>Course terminée !</h4>
                        <p class="mb-3 opacity-75">Merci d'avoir voyagé avec <strong>ATLAS TAXI / VTC</strong>.</p>
                        <div class="bg-white text-dark p-4 rounded-4 shadow-sm mb-3">
                            <h5 class="fw-bold text-center mb-3">Votre avis nous intéresse</h5>
                            @if(!$trip->rating)
                            <form id="payment-form" action="{{ route('trips.rate', $trip) }}" method="POST">
                                @csrf
                                <div class="star-rating mb-3 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <input type="radio" id="star5" name="rating" value="5" /><label for="star5">★</label>
                                        <input type="radio" id="star4" name="rating" value="4" /><label for="star4">★</label>
                                        <input type="radio" id="star3" name="rating" value="3" /><label for="star3">★</label>
                                        <input type="radio" id="star2" name="rating" value="2" /><label for="star2">★</label>
                                        <input type="radio" id="star1" name="rating" value="1" /><label for="star1">★</label>
                                    </div>
                                </div>
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block mb-2">Mode de paiement</label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="payment_method" id="pay_card" value="card" checked>
                                        <label class="btn btn-outline-primary" for="pay_card"><i class="bi bi-credit-card me-2"></i>Carte</label>

                                        <input type="radio" class="btn-check" name="payment_method" id="pay_cash" value="cash">
                                        <label class="btn btn-outline-primary" for="pay_cash"><i class="bi bi-cash-stack me-2"></i>Espèces</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <textarea name="comment" class="form-control border-0 bg-light" rows="3" placeholder="Un commentaire sur votre trajet ? (Optionnel)"></textarea>
                                </div>
                                <button type="submit" id="submit-btn" class="btn btn-premium w-100 py-3 fw-bold">
                                    <i class="bi bi-check-circle me-2"></i>VALIDER & NOTER
                                </button>
                            </form>
                            @else
                            <div class="text-center">
                                <i class="bi bi-check-circle-fill text-success fs-1 mb-3 d-block"></i>
                                <h6 class="fw-bold">Merci pour votre avis !</h6>
                                <p class="text-muted small">Note : <strong>{{ $trip->rating }}/5</strong></p>
                            </div>
                            @endif
                        </div>
                    @elseif($trip->status === 'cancelled')
                        <h4 class="mb-3 text-danger"><i class="bi bi-x-circle-fill me-2"></i>Course annulée</h4>
                        <p class="mb-0 opacity-75">La course a été annulée.</p>
                        <a href="{{ route('client.dashboard') }}" class="btn btn-light mt-3">Nouvelle course</a>
                    @endif

                    @if(in_array($trip->status, ['pending', 'assigned', 'accepted']))
                    <hr class="border-secondary opacity-25">
                    <form action="{{ route('trips.cancel', $trip) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler cette course ?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-light w-100">Annuler la course</button>
                    </form>
                    @endif
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
                    <h5 class="fw-bold mb-4">Détails du trajet</h5>
                    <div class="mb-4">
                        <p class="text-muted small mb-1 text-uppercase fw-bold" style="letter-spacing: 1px;">DÉPART</p>
                        <p class="fw-bold mb-0 text-primary"><i class="bi bi-geo-alt-fill me-2"></i>{{ $trip->pickup_address }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-muted small mb-1 text-uppercase fw-bold" style="letter-spacing: 1px;">ARRIVÉE</p>
                        <p class="fw-bold mb-0 text-danger"><i class="bi bi-flag-fill me-2"></i>{{ $trip->dropoff_address }}</p>
                    </div>
                    <hr class="my-4 opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Tarif</span>
                        <span class="fw-bold text-success fs-5">{{ number_format($trip->price, 2) }} €</span>
                    </div>
                </div>

                @if($trip->driver)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 60px; height: 60px;">
                                <i class="bi bi-person-fill text-primary fs-3"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="d-block text-muted small text-uppercase fw-bold" style="letter-spacing: 1px;">Votre Chauffeur</span>
                                <h5 class="fw-bold mb-0">{{ $trip->driver->name }}</h5>
                                @if($trip->vehicle)
                                    <div class="small text-primary mt-1 fw-medium">
                                        <i class="bi bi-car-front-fill me-1"></i>{{ $trip->vehicle->model ?? '' }} • <span class="badge bg-light text-dark border">{{ $trip->vehicle->plate_number ?? '' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if(in_array($trip->status, ['assigned', 'accepted', 'in_progress']))
                            <div class="row g-2">
                                <div class="col-6">
                                    @if($trip->driver?->phone_number)
                                        <a href="tel:{{ preg_replace('/\s+/', '', $trip->driver->phone_number) }}" class="btn btn-primary w-100 py-3 rounded-3 fw-bold">
                                            <i class="bi bi-telephone-fill me-2"></i>Appeler
                                        </a>
                                    @else
                                        <button class="btn btn-light w-100 py-3 rounded-3 text-muted" disabled>
                                            <i class="bi bi-telephone-x me-2"></i>Indisponible
                                        </button>
                                    @endif
                                </div>
                                <div class="col-6">
                                    @if($trip->driver?->phone_number)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $trip->driver->phone_number) }}?text={{ urlencode('Bonjour, je suis votre client pour la course de ' . $trip->pickup_address) }}" 
                                           target="_blank" 
                                           class="btn btn-success w-100 py-3 rounded-3 fw-bold">
                                            <i class="bi bi-whatsapp me-2"></i>WhatsApp
                                        </a>
                                    @else
                                        <button class="btn btn-light w-100 py-3 rounded-3 text-muted" disabled>
                                            <i class="bi bi-whatsapp me-2"></i>Indisponible
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-8">
                <div id="tripMap" style="height: 500px; border-radius: 20px;" class="shadow"></div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .tracking-status {
        background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
    }
    .star-rating .d-flex {
        flex-direction: row-reverse;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        cursor: pointer;
        color: #cbd5e1;
        transition: color 0.2s;
        font-size: 2rem;
        padding: 0 2px;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #f59e0b;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var rideStatus = "{{ $trip->status }}";
    var startPos = [{{ $trip->pickup_lat ?? 48.8566 }}, {{ $trip->pickup_lng ?? 2.3522 }}];
    var endPos = [{{ $trip->dropoff_lat ?? 48.8766 }}, {{ $trip->dropoff_lng ?? 2.3722 }}];

    var map = L.map('tripMap').setView(startPos, 13);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>'
    }).addTo(map);

    // Marqueurs
    L.marker(startPos, { icon: L.icon({ iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png', iconSize: [25, 41] }) }).addTo(map).bindPopup('Départ');
    L.marker(endPos, { icon: L.icon({ iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png', iconSize: [25, 41] }) }).addTo(map).bindPopup('Arrivée');

    // Auto-refresh toutes les 3 secondes pour les statuts actifs
    if (['pending', 'assigned', 'accepted', 'in_progress'].includes(rideStatus)) {
        setInterval(function() {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newStatusCard = doc.getElementById('status-card');
                    const oldStatusCard = document.getElementById('status-card');

                    if (newStatusCard && oldStatusCard && newStatusCard.innerHTML !== oldStatusCard.innerHTML) {
                        window.location.reload();
                    }
                })
                .catch(console.error);
        }, 3000);
    }

    // Formulaire de paiement / avis
    var paymentForm = document.getElementById('payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = document.getElementById('submit-btn');
            var originalHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Validation en cours...';
            btn.disabled = true;

            var formData = new FormData(paymentForm);
            
            fetch(paymentForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var statusCard = document.getElementById('status-card');
                    if (statusCard) {
                        statusCard.innerHTML = `
                            <div class="text-center py-5 px-4 animate__animated animate__fadeIn bg-white text-dark rounded-4">
                                <div class="mb-4">
                                    <i class="bi bi-heart-fill text-danger" style="font-size: 4.5rem; display: inline-block;"></i>
                                </div>
                                <h4 class="fw-bold mb-3 text-dark">Merci pour votre avis !</h4>
                                <p class="text-muted mb-4 small" style="line-height: 1.6;">Votre avis a bien été enregistré. Merci d\'avoir choisi ATLAS TAXI / VTC ! Votre facture a été envoyée par email.</p>
                                <div class="d-flex align-items-center justify-content-center gap-2 text-muted">
                                    <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                                    <span style="font-size: 0.85rem;">Redirection vers votre historique...</span>
                                </div>
                            </div>
                        `;
                    }
                    setTimeout(function() {
                        window.location.href = "{{ route('my.rentals') }}";
                    }, 10000);
                } else {
                    alert('Erreur lors de la validation : ' + (data.message || 'veuillez réessayer.'));
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error(error);
                alert('Erreur réseau.');
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
        });
    }
});
</script>
@endpush
