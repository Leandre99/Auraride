@extends('layouts.app')

@section('title', 'Gestion des locations - ATLAS AND CO')

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
                <h1 class="display-5 fw-bold mb-1">Locations</h1>
                <p class="opacity-75 mb-0">Gestion des demandes de location de véhicules.</p>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="admin-container">
        <aside>
            <div class="glass-panel p-4 h-100 shadow-sm border-0 bg-white">
                <div class="small text-muted fw-bold mb-3 px-2">MENUS PRINCIPAUX</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ route('admin.users') }}" class="sidebar-link"><i class="bi bi-people"></i> Utilisateurs</a>
                <a href="{{ route('admin.trips') }}" class="sidebar-link"><i class="bi bi-map"></i> Courses</a>
                <a href="{{ route('admin.rentals') }}" class="sidebar-link active"><i class="bi bi-car-front"></i> Locations</a>
            </div>
        </aside>

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
                        <thead>
                            <tr>
                                <th class="px-4 py-3">ID / Date</th>
                                <th class="py-3">Client</th>
                                <th class="py-3">Véhicule</th>
                                <th class="py-3">Période</th>
                                <th class="py-3">Chauffeur</th>
                                <th class="py-3">Prix total</th>
                                <th class="py-3">Statut</th>
                                <th class="py-3 text-end px-4">Actions</th>
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
                                        <div class="d-flex justify-content-end flex-wrap gap-2">
                                            <a href="{{ route('admin.rentals.edit', $rental) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                                <i class="bi bi-pencil"></i> Traiter
                                            </a>
                                            @if($rental->status === 'pending')
                                                <form action="{{ route('admin.rentals.update-status', $rental) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Annuler cette demande ?')">
                                                        Annuler
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
