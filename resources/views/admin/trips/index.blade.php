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
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        border: 1px solid var(--border-light);
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
        <aside>
            <div class="glass-panel p-4 h-100 shadow-sm border-0 bg-white">
                <div class="small text-muted fw-bold mb-3 px-2">MENUS PRINCIPAUX</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ route('admin.users') }}" class="sidebar-link"><i class="bi bi-people"></i> Utilisateurs</a>
                <a href="{{ route('admin.trips') }}" class="sidebar-link active"><i class="bi bi-map"></i> Courses</a>
                <a href="#" class="sidebar-link"><i class="bi bi-credit-card"></i> Paiements</a>
            </div>
        </aside>

        <main>
            @if(session('success'))
                <div class="alert alert-success border-0 bg-success-subtle text-success mb-4 rounded-4">
                    {{ session('success') }}
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
                                                'accepted', 'in_progress' => 'bg-primary-subtle text-primary',
                                                default => 'bg-warning-subtle text-warning',
                                            };
                                        @endphp
                                        <span class="status-pill {{ $badgeClass }}">{{ ucfirst($trip->status) }}</span>
                                    </td>
                                    <td class="px-4 text-end">
                                        @if(!in_array($trip->status, ['completed', 'cancelled']))
                                            <form action="{{ route('admin.trips.cancel', $trip) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Annuler</button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-light disabled rounded-pill px-3">Clôturé</button>
                                        @endif
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
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
