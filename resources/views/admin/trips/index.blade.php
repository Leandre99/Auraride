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
        border-radius: 20px;
        overflow: hidden; /* Fix double borders */
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        border: 1px solid var(--border-light);
    }
    .table-premium .table-responsive {
        overflow-x: auto;
    }
    
    .status-pill {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        background: #FFF;
        color: #64748b;
    }
    .btn-action:hover {
        background: var(--primary);
        color: #FFF;
        border-color: var(--primary);
        transform: translateY(-2px);
    }
    .btn-action-danger:hover {
        background: #ef4444;
        color: #FFF;
        border-color: #ef4444;
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
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-muted small fw-bold">ID / DATE</th>
                                <th class="py-3 text-muted small fw-bold">CLIENTS / CHAUFFEUR</th>
                                <th class="py-3 text-muted small fw-bold">TRAJET</th>
                                <th class="py-3 text-muted small fw-bold">PRIX</th>
                                <th class="py-3 text-muted small fw-bold">STATUT</th>
                                <th class="py-3 text-end px-4 text-muted small fw-bold">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trips as $trip)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-dark">#{{ $trip->id }}</div>
                                        <div class="small text-muted">{{ $trip->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $trip->client->name }}</div>
                                        <div class="small text-primary fw-medium"><i class="bi bi-person-badge me-1"></i>{{ $trip->driver->name ?? 'Non assigné' }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-dark">DE: {{ Str::limit($trip->pickup_address, 30) }}</div>
                                        <div class="small text-muted">À: {{ Str::limit($trip->dropoff_address, 30) }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ number_format($trip->price, 2) }}€</div>
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
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn-action show-trip-details" title="Voir détails"
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
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            @if($trip->status === 'pending')
                                                @include('admin.partials.trip-assign-button', [
                                                    'trip' => $trip,
                                                    'buttonLabel' => '<i class="bi bi-person-plus"></i>',
                                                    'buttonClass' => 'btn-action',
                                                ])
                                            @endif

                                            @if(!in_array($trip->status, ['completed', 'cancelled']))
                                                <form action="{{ route('admin.trips.cancel', $trip) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-action btn-action-danger" title="Annuler" onclick="return confirm('Annuler cette course ?')">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-top bg-light bg-opacity-50">
                    {{ $trips->links() }}
                </div>
            </div>
        </main>
    </div>
</div>

@include('admin.partials.assign-trip-modal-singleton', ['drivers' => $drivers])

<!-- Modale de Détails de la Course -->
<div class="modal fade" id="tripDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-4 border-0">
                <h5 class="modal-title fw-bold">Récapitulatif Course #<span id="modalTripId"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-7">
                        <div class="p-4 bg-light rounded-4 h-100 border border-light">
                            <h6 class="text-uppercase small fw-bold text-primary mb-4">Itinéraire & Statut</h6>
                            <div class="d-flex gap-3 mb-4">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="rounded-circle bg-primary" style="width: 12px; height: 12px;"></div>
                                    <div style="width: 2px; flex-grow: 1; background: #e2e8f0; margin: 4px 0;"></div>
                                    <div class="rounded-circle bg-danger" style="width: 12px; height: 12px;"></div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="mb-4">
                                        <div class="small text-muted mb-1">DÉPART</div>
                                        <div class="fw-bold" id="modalPickup"></div>
                                    </div>
                                    <div>
                                        <div class="small text-muted mb-1">ARRIVÉE</div>
                                        <div class="fw-bold" id="modalDropoff"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="p-4 border rounded-4 h-100 shadow-sm">
                            <h6 class="text-uppercase small fw-bold text-primary mb-4">Informations Clés</h6>
                            <div class="mb-3 d-flex justify-content-between">
                                <span class="text-muted small">Date :</span>
                                <span class="fw-bold small" id="modalDate"></span>
                            </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <span class="text-muted small">Montant :</span>
                                <span class="fw-bold text-primary" id="modalPrice"></span>
                            </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <span class="text-muted small">Client :</span>
                                <span class="fw-bold small" id="modalClient"></span>
                            </div>
                            <div class="mb-0 d-flex justify-content-between">
                                <span class="text-muted small">Chauffeur :</span>
                                <span class="fw-bold small" id="modalDriver"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4" id="modalReviewSection">
                    <div class="p-4 bg-white border rounded-4 shadow-sm">
                        <h6 class="text-uppercase small fw-bold text-primary mb-3">Avis du client</h6>
                        <div id="modalReviewContent">
                            <!-- Peuplé par JS -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-4 border-0 pt-0">
                <button type="button" class="btn btn-dark w-100 py-3 rounded-3 fw-bold" data-bs-dismiss="modal">Fermer les détails</button>
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
