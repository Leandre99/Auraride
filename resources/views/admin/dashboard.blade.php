@extends('layouts.app')

@section('title', 'ATLAS AND CO - Executive Command')

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

    .kpi-card {
        background: #FFF;
        border-radius: 24px;
        padding: 30px;
        border: 1px solid var(--border-light);
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        transition: transform 0.3s ease;
        height: 100%;
    }
    .kpi-card:hover { transform: translateY(-5px); }

    .kpi-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 1.2rem;
    }

    /* overflow: visible évite les bugs de clic / backdrop avec boutons qui ouvrent des modales liées au tableau */
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
        border-radius: inherit;
    }
    .table-premium thead {
        background: #F8FAFC;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
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
                <h1 class="display-5 fw-bold mb-1">Centre de Gestion</h1>
                <p class="opacity-75 mb-0">Contrôle global d'ATLAS AND CO.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-inline-flex align-items-center gap-3 bg-white bg-opacity-10 p-2 rounded-4">
                    <div class="px-3">
                        <div class="small opacity-50">Statut Réseau</div>
                        <div class="small fw-bold text-success"><i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i> OPTIMAL</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <aside>
            <div class="glass-panel p-4 h-100 shadow-sm border-0 bg-white">
                <div class="small text-muted fw-bold mb-3 px-2">MENUS PRINCIPAUX</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link active"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ route('admin.users') }}" class="sidebar-link"><i class="bi bi-people"></i> Utilisateurs</a>
                <a href="{{ route('admin.trips') }}" class="sidebar-link"><i class="bi bi-map"></i> Courses</a>
                <a href="#" class="sidebar-link"><i class="bi bi-credit-card"></i> Paiements</a>
                
                <hr class="my-4 opacity-10">
                
                <div class="small text-muted fw-bold mb-3 px-2">SYSTÈME</div>
                <a href="#" class="sidebar-link"><i class="bi bi-gear"></i> Configuration</a>
                <a href="#" class="sidebar-link"><i class="bi bi-shield-check"></i> Sécurité</a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main>
            @if (session('success'))
                <div class="alert alert-success border-0 rounded-4 mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger border-0 rounded-4 mb-4">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-4">
                    <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <!-- KPI Row -->
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="kpi-card">
                        <div class="kpi-icon bg-primary-subtle text-primary"><i class="bi bi-people-fill"></i></div>
                        <div class="text-muted small fw-bold">TOTAL UTILISATEURS</div>
                        <div class="h3 fw-bold mb-0">{{ $stats['users_count'] }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="kpi-card">
                        <div class="kpi-icon bg-success-subtle text-success"><i class="bi bi-currency-euro"></i></div>
                        <div class="text-muted small fw-bold">REVENUS TOTAUX</div>
                        <div class="h3 fw-bold mb-0">{{ number_format($stats['total_revenue'], 2) }}€</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="kpi-card">
                        <div class="kpi-icon bg-warning-subtle text-warning"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="text-muted small fw-bold">COURSES ACTIVES</div>
                        <div class="h3 fw-bold mb-0">{{ $stats['active_trips'] }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="kpi-card">
                        <div class="kpi-icon bg-danger-subtle text-danger"><i class="bi bi-lightning-fill"></i></div>
                        <div class="text-muted small fw-bold">COURSES (24H)</div>
                        <div class="h3 fw-bold mb-0">{{ $stats['trips_today'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Pending Assignments -->
            @if($pendingTrips->count() > 0)
            <div class="table-premium mb-5 border-warning border-2">
                <div class="p-4 border-bottom bg-warning bg-opacity-10 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-clock-history me-2"></i>Demandes en attente d'assignation</h5>
                    <span class="badge bg-warning text-dark">{{ $pendingTrips->count() }} nouvelle(s)</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">Client</th>
                                <th class="py-3">Trajet / Prix</th>
                                <th class="py-3 text-end px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingTrips as $trip)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold">{{ $trip->client->name }}</div>
                                        <div class="small text-muted">{{ $trip->client->email }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold">{{ number_format($trip->price, 2) }}€</div>
                                        <div class="small text-muted">{{ $trip->pickup_address }} <i class="bi bi-arrow-right"></i> {{ $trip->dropoff_address }}</div>
                                    </td>
                                    <td class="px-4 text-end">
                                        @include('admin.partials.trip-assign-button', ['trip' => $trip, 'buttonLabel' => 'Assigner chauffeur'])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="table-premium">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Dernières Courses</h5>
                    <a href="{{ route('admin.trips') }}" class="btn btn-outline-premium btn-sm">Voir tout</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">Client</th>
                                <th class="py-3">Chauffeur</th>
                                <th class="py-3">Trajet</th>
                                <th class="py-3">Montant</th>
                                <th class="py-3 text-end px-4">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTrips as $trip)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold">{{ $trip->client->name }}</div>
                                        <div class="small text-muted">{{ $trip->client->email }}</div>
                                    </td>
                                    <td>{{ $trip->driver->name ?? 'En attente' }}</td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;" title="{{ $trip->pickup_address }}">De: {{ $trip->pickup_address }}</div>
                                        <div class="text-truncate" style="max-width: 150px;" title="{{ $trip->dropoff_address }}">À: {{ $trip->dropoff_address }}</div>
                                    </td>
                                    <td class="fw-bold">{{ number_format($trip->price, 2) }}€</td>
                                    <td class="px-4 text-end">
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Aucune course enregistrée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

@include('admin.partials.assign-trip-modal-singleton', ['drivers' => $drivers])
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
