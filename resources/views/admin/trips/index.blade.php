@extends('layouts.app')

@section('title', 'Gestion Courses - ATLAS AND CO')

@push('styles')
<style>
    body { background: #F8FAFC; }

    .executive-header {
        padding: 40px 0;
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        color: #FFF;
        margin-bottom: -100px;
        padding-bottom: 140px;
    }

    .table-premium {
        background: #FFF;
        border-radius: 24px;
        overflow: visible;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        border: 1px solid var(--border-light);
    }
    .table-premium .table-responsive {
        overflow-x: auto;
        overflow-y: visible;
    }
    
    .status-pill {
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: var(--text-main);
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        margin-bottom: 5px;
        font-weight: 500;
    }
    .sidebar-link i { margin-right: 15px; font-size: 1.1rem; }
    .sidebar-link:hover, .sidebar-link.active {
        background: var(--primary);
        color: #FFF;
    }

    .admin-container {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
        margin-top: -100px;
        position: relative;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="executive-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-5 fw-bold mb-1">Courses</h1>
                <p class="opacity-75 mb-0">Historique et suivi des trajets en temps réel.</p>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="admin-container">
        @include('admin.partials.sidebar')

        <main>
            @if(session('success'))
                <div class="alert alert-success border-0 bg-success-subtle text-success mb-4 rounded-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger border-0 rounded-4 mb-4">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-4">
                    <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="table-premium">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">ID / Date</th>
                                <th class="py-3">Clients / Chauffeur</th>
                                <th class="py-3">Trajet</th>
                                <th class="py-3">Prix</th>
                                <th class="py-3">Statut</th>
                                <th class="py-3 text-end px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trips as $trip)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold">#{{ $trip->id }}</div>
                                        <div class="small text-muted">{{ $trip->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $trip->client->name }}</div>
                                        <div class="small text-primary"><i class="bi bi-person-badge"></i> {{ $trip->driver->name ?? 'Non assigné' }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold">DE: {{ $trip->pickup_address }}</div>
                                        <div class="small text-muted">À: {{ $trip->dropoff_address }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ number_format($trip->price, 2) }}€</div>
                                        <div class="small text-muted">{{ $trip->payment_status == 'paid' ? 'Payé' : 'À régler' }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($trip->status) {
                                                'completed' => 'bg-success-subtle text-success',
                                                'cancelled' => 'bg-danger-subtle text-danger',
                                                'assigned', 'accepted', 'in_progress' => 'bg-primary-subtle text-primary',
                                                default => 'bg-warning-subtle text-warning',
                                            };
                                        @endphp
                                        <span class="status-pill {{ $badgeClass }}">{{ ucfirst($trip->status) }}</span>
                                    </td>
                                    <td class="px-4 text-end">
                                        <div class="d-flex justify-content-end flex-wrap gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 show-trip-details"
                                                data-id="{{ $trip->id }}"
                                                data-pickup="{{ $trip->pickup_address }}"
                                                data-dropoff="{{ $trip->dropoff_address }}"
                                                data-date="{{ $trip->created_at->format('d/m/Y H:i') }}"
                                                data-client="{{ $trip->client->name }}"
                                                data-driver="{{ $trip->driver->name ?? 'Non assigné' }}"
                                                data-price="{{ number_format($trip->price, 2) }}€"
                                                data-payment-status="{{ $trip->payment_status == 'paid' ? 'Payé' : 'À régler' }}"
                                                data-rating="{{ $trip->rating ?? 0 }}"
                                                data-comment="{{ $trip->comment }}"
                                                data-review-date="{{ $trip->updated_at->format('d/m/Y') }}">
                                                Voir détails
                                            </button>

                                            @if($trip->status === 'pending')
                                                @include('admin.partials.trip-assign-button', [
                                                    'trip' => $trip,
                                                    'buttonLabel' => 'Assigner',
                                                    'buttonClass' => 'btn btn-primary btn-sm rounded-pill px-3',
                                                ])
                                            @endif
                                            @if(!in_array($trip->status, ['completed', 'cancelled']))
                                                <form action="{{ route('admin.trips.cancel', $trip) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Annuler</button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-light disabled rounded-pill px-3">Clôturé</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-top">
                    {{ $trips->links() }}
                </div>
            </div>
        </main>
    </div>
</div>

@include('admin.partials.assign-trip-modal-singleton', ['drivers' => $drivers])

<!-- Modale de Détails de la Course -->
<div class="modal fade" id="tripDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Détails de la course #<span id="modalTripId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="mb-4">
                    <div class="small text-muted text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Itinéraire</div>
                    <div class="p-3 bg-light rounded-4 border border-light">
                        <div class="d-flex gap-2 mb-2">
                            <i class="bi bi-geo-alt-fill text-primary"></i>
                            <div class="small"><strong>Départ :</strong> <span id="modalPickup"></span></div>
                        </div>
                        <div class="d-flex gap-2">
                            <i class="bi bi-flag-fill text-danger"></i>
                            <div class="small"><strong>Arrivée :</strong> <span id="modalDropoff"></span></div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Date & Heure</div>
                        <div class="small fw-bold" id="modalDate"></div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Prix & Statut</div>
                        <div class="small fw-bold text-primary" id="modalPrice"></div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Client</div>
                        <div class="small fw-bold" id="modalClient"></div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Chauffeur</div>
                        <div class="small fw-bold" id="modalDriver"></div>
                    </div>
                </div>

                <hr class="my-4 opacity-5">

                <div class="review-section">
                    <div class="small text-muted text-uppercase fw-bold mb-3" style="font-size: 0.7rem; letter-spacing: 0.5px;">Avis client</div>
                    <div id="modalReviewContent" class="p-3 bg-white border rounded-4">
                        <!-- Peuplé par JS -->
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 mt-3">
                <button type="button" class="btn btn-light w-100 rounded-pill fw-bold" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('tripDetailsModal');
    if (!modalEl) return;
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    
    document.querySelectorAll('.show-trip-details').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;
            document.getElementById('modalTripId').innerText = data.id;
            document.getElementById('modalPickup').innerText = data.pickup;
            document.getElementById('modalDropoff').innerText = data.dropoff;
            document.getElementById('modalDate').innerText = data.date;
            document.getElementById('modalClient').innerText = data.client;
            document.getElementById('modalDriver').innerText = data.driver;
            document.getElementById('modalPrice').innerText = data.price + ' (' + data.paymentStatus + ')';
            
            const reviewContent = document.getElementById('modalReviewContent');
            const rating = parseInt(data.rating);
            
            if (rating > 0) {
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    stars += `<i class="bi bi-star${i <= rating ? '-fill text-warning' : ''} fs-5"></i> `;
                }
                
                let commentHtml = '';
                if (data.comment && data.comment !== 'null' && data.comment.trim() !== '') {
                    commentHtml = `
                        <div class="mt-3 p-3 bg-light rounded-3 border-start border-4 border-primary shadow-sm" style="font-style: italic;">
                            "${data.comment}"
                        </div>
                    `;
                }
                
                reviewContent.innerHTML = `
                    <div class="d-flex align-items-center justify-content-between">
                        <div>${stars}</div>
                        <span class="badge bg-warning text-dark rounded-pill">${rating}/5</span>
                    </div>
                    ${commentHtml}
                    <div class="small text-muted mt-3 text-end">— ${data.client}, le ${data.reviewDate}</div>
                `;
            } else {
                reviewContent.innerHTML = `<div class="text-center py-2 text-muted small italic"><i class="bi bi-chat-left-dots me-2"></i>Aucun avis laissé par le client</div>`;
                reviewContent.classList.add('bg-light');
            }
            
            modal.show();
        });
    });
});
</script>
@endpush
