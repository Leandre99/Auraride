@extends('layouts.app')

@section('title', 'Gestion des locations - ATLAS AND CO')

@push('styles')
<style>
    body { background: #F8FAFC; }

    .executive-header {
        padding: 80px 0;
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        color: #FFF;
        margin-bottom: -40px;
        padding-bottom: 140px;
    }

    .table-premium {
        background: #FFF;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        border: 1px solid var(--border-light);
    }
    .table-premium .table-responsive {
        overflow-x: auto;
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
</style>
@endpush

@section('content')
<div class="executive-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-5 fw-bold mb-1">Locations</h1>
                <p class="opacity-75 mb-0">Gestion des demandes de location de véhicules.</p>
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

            <div class="table-premium">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-muted small fw-bold">ID / DATE</th>
                                <th class="py-3 text-muted small fw-bold">CLIENT</th>
                                <th class="py-3 text-muted small fw-bold">VÉHICULE</th>
                                <th class="py-3 text-muted small fw-bold">PÉRIODE</th>
                                <th class="py-3 text-muted small fw-bold">CHAUFFEUR</th>
                                <th class="py-3 text-muted small fw-bold">PRIX TOTAL</th>
                                <th class="py-3 text-muted small fw-bold">STATUT</th>
                                <th class="py-3 text-end px-4 text-muted small fw-bold">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentals as $rental)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold">#{{ $rental->id }}</div>
                                        <div class="small text-muted">{{ $rental->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $rental->user->name }}</div>
                                        <div class="small text-muted">{{ $rental->user->email }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $rental->vehicleType->name }}</div>
                                        <div class="small text-muted">
                                            @if($rental->daily_price)
                                                {{ number_format($rental->daily_price, 2) }}€/jour
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div><i class="bi bi-calendar-check"></i> {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}</div>
                                            <div><i class="bi bi-calendar-x"></i> {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}</div>
                                            <div class="text-muted"><i class="bi bi-clock"></i> {{ $rental->pickup_time }}</div>
                                            <div class="text-muted"><strong>{{ $rental->total_days }} jour(s)</strong></div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($rental->with_driver)
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                <i class="bi bi-person-badge"></i> Inclus
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                                <i class="bi bi-person"></i> Sans chauffeur
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ number_format($rental->total_price, 2) }}€</div>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($rental->status) {
                                                'confirmed' => 'bg-success-subtle text-success',
                                                'rejected' => 'bg-danger-subtle text-danger',
                                                'cancelled' => 'bg-danger-subtle text-danger',
                                                'pending' => 'bg-warning-subtle text-warning',
                                                default => 'bg-secondary-subtle text-secondary',
                                            };
                                            $statusText = match($rental->status) {
                                                'confirmed' => 'Confirmée',
                                                'rejected' => 'Refusée',
                                                'cancelled' => 'Annulée',
                                                'pending' => 'En attente',
                                                default => $rental->status,
                                            };
                                        @endphp
                                        <span class="status-pill {{ $badgeClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td class="px-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.rentals.edit', $rental) }}" class="btn-action" title="Traiter la demande">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($rental->status === 'pending')
                                                <form action="{{ route('admin.rentals.update-status', $rental) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn-action btn-action-danger" title="Annuler" onclick="return confirm('Annuler cette demande ?')">
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

                <!-- Mobile Card List -->
                <div class="mobile-card-list p-3">
                    @foreach($rentals as $rental)
                        <div class="mobile-data-card shadow-sm border-0">
                            <div class="card-header-flex">
                                <div>
                                    <div class="fw-bold text-primary">#{{ $rental->id }}</div>
                                    <div class="small text-muted">{{ $rental->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                @php
                                    $badgeClass = match($rental->status) {
                                        'confirmed' => 'bg-success-subtle text-success',
                                        'rejected', 'cancelled' => 'bg-danger-subtle text-danger',
                                        'pending' => 'bg-warning-subtle text-warning',
                                        default => 'bg-secondary-subtle text-secondary',
                                    };
                                    $statusText = match($rental->status) {
                                        'confirmed' => 'Confirmée',
                                        'rejected' => 'Refusée',
                                        'cancelled' => 'Annulée',
                                        'pending' => 'En attente',
                                        default => $rental->status,
                                    };
                                @endphp
                                <span class="status-pill {{ $badgeClass }} px-2 py-1 small">{{ $statusText }}</span>
                            </div>
                            
                            <div class="data-row">
                                <span class="data-label">Client</span>
                                <span class="data-value">{{ $rental->user->name }}</span>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Véhicule</span>
                                <span class="data-value">{{ $rental->vehicleType->name }}</span>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Chauffeur</span>
                                <span class="data-value">{{ $rental->with_driver ? 'Inclus' : 'Sans' }}</span>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Montant</span>
                                <span class="data-value fw-bold text-primary">{{ number_format($rental->total_price, 2) }}€</span>
                            </div>

                            <div class="mt-2 pt-2 border-top">
                                <div class="small text-muted mb-1"><i class="bi bi-calendar-event me-1"></i> Du {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}</div>
                                <div class="small text-muted"><i class="bi bi-clock me-1"></i> Prise en charge: {{ $rental->pickup_time }}</div>
                            </div>

                            @if($rental->status === 'pending')
                            <div class="mt-3 d-flex gap-2">
                                <form action="{{ route('admin.rentals.confirm', $rental->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-100 py-2">Confirmer</button>
                                </form>
                                <form action="{{ route('admin.rentals.reject', $rental->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2">Rejeter</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-top">
                    {{ $rentals->links() }}
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
