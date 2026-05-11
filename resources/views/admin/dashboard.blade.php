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
        border: 0;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }
    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.12);
    }

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

    /* Mobile Dashboard Adjustments */
    .mobile-card-list {
        display: none;
    }

    @media (max-width: 991.98px) {
        .table-premium .table-responsive {
            display: none;
        }
        .mobile-card-list {
            display: block;
        }
        .mobile-data-card {
            background: #fff;
            border-radius: 16px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .card-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .data-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        .data-label {
            color: #64748b;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .data-value {
            font-weight: 700;
            font-size: 0.9rem;
            color: #1e293b;
        }
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
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        @include('admin.partials.sidebar')

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
            <div class="row g-3 g-md-4 mb-4">
                <div class="col-12 col-md-6 col-lg-3 animate__animated animate__zoomIn">
                    <div class="kpi-card shadow-sm border-0 text-center">
                        <div class="kpi-icon bg-primary-subtle text-primary rounded-4 p-3 mx-auto mb-3" style="width: 60px; height: 60px;"><i class="bi bi-people-fill fs-3"></i></div>
                        <div class="text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.8px; text-transform: uppercase;">Utilisateurs</div>
                        <div class="h3 fw-bold mb-3 text-dark">{{ $stats['users_count'] }}</div>
                        <div class="pt-3 border-top small text-muted">
                            <i class="bi bi-person-check text-success me-1"></i> Clients & Staff
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 animate__animated animate__zoomIn animate__delay-1s">
                    <div class="kpi-card shadow-sm border-0 text-center">
                        <div class="kpi-icon bg-success-subtle text-success rounded-4 p-3 mx-auto mb-3" style="width: 60px; height: 60px;"><i class="bi bi-currency-euro fs-3"></i></div>
                        <div class="text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.8px; text-transform: uppercase;">Revenus</div>
                        <div class="h3 fw-bold mb-3 text-dark">{{ number_format($stats['total_revenue'], 2) }}€</div>
                        <div class="pt-3 border-top small text-muted">
                            <i class="bi bi-graph-up-arrow text-success me-1"></i> Chiffre d'affaires
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 animate__animated animate__zoomIn animate__delay-2s">
                    <div class="kpi-card shadow-sm border-0 text-center">
                        <div class="kpi-icon bg-warning-subtle text-warning rounded-4 p-3 mx-auto mb-3" style="width: 60px; height: 60px;"><i class="bi bi-geo-alt-fill fs-3"></i></div>
                        <div class="text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.8px; text-transform: uppercase;">Actives</div>
                        <div class="h3 fw-bold mb-3 text-dark">{{ $stats['active_trips'] }}</div>
                        <div class="pt-3 border-top small text-muted">
                            <i class="bi bi-clock-history text-warning me-1"></i> Courses en cours
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 animate__animated animate__zoomIn animate__delay-3s">
                    <div class="kpi-card shadow-sm border-0 text-center">
                        <div class="kpi-icon bg-danger-subtle text-danger rounded-4 p-3 mx-auto mb-3" style="width: 60px; height: 60px;"><i class="bi bi-lightning-fill fs-3"></i></div>
                        <div class="text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.8px; text-transform: uppercase;">Jour (24h)</div>
                        <div class="h3 fw-bold mb-3 text-dark">{{ $stats['trips_today'] }}</div>
                        <div class="pt-3 border-top small text-muted">
                            <i class="bi bi-lightning-charge text-danger me-1"></i> Demandes du jour
                        </div>
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

                <!-- Mobile View for Pending Trips -->
                <div class="mobile-card-list p-3">
                    @foreach($pendingTrips as $trip)
                        <div class="mobile-data-card border-warning border-start border-4">
                            <div class="card-header-flex">
                                <div class="fw-bold text-dark">{{ $trip->client->name }}</div>
                                <div class="fw-bold text-primary">{{ number_format($trip->price, 2) }}€</div>
                            </div>
                            <div class="small text-muted mb-3">
                                <div class="text-truncate">📍 {{ $trip->pickup_address }}</div>
                                <div class="text-truncate">🏁 {{ $trip->dropoff_address }}</div>
                            </div>
                            @include('admin.partials.trip-assign-button', [
                                'trip' => $trip,
                                'buttonLabel' => '<i class="bi bi-person-plus me-1"></i> Assigner maintenant',
                                'buttonClass' => 'btn btn-warning btn-sm w-100 py-2 fw-bold'
                            ])
                        </div>
                    @endforeach
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

                <!-- Mobile Card List (Visible only on mobile) -->
                <div class="mobile-card-list p-3">
                    @forelse($recentTrips as $trip)
                        <div class="mobile-data-card shadow-sm border-0">
                            <div class="card-header-flex">
                                <div>
                                    <div class="fw-bold text-primary">#{{ $trip->id }}</div>
                                    <div class="small text-muted">{{ $trip->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                @php
                                    $badgeClass = match($trip->status) {
                                        'completed' => 'bg-success-subtle text-success',
                                        'cancelled' => 'bg-danger-subtle text-danger',
                                        'assigned', 'accepted', 'in_progress' => 'bg-primary-subtle text-primary',
                                        default => 'bg-warning-subtle text-warning',
                                    };
                                @endphp
                                <span class="status-pill {{ $badgeClass }} px-2 py-1 small">{{ ucfirst($trip->status) }}</span>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Client</span>
                                <span class="data-value">{{ $trip->client->name }}</span>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Chauffeur</span>
                                <span class="data-value">{{ $trip->driver->name ?? 'En attente' }}</span>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Montant</span>
                                <span class="data-value fw-bold text-primary">{{ number_format($trip->price, 2) }}€</span>
                            </div>
                            <div class="mt-2 pt-2 border-top small">
                                <div class="text-truncate text-muted">📍 <strong>De:</strong> {{ $trip->pickup_address }}</div>
                                <div class="text-truncate text-muted">🏁 <strong>À:</strong> {{ $trip->dropoff_address }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Aucune course enregistrée.</div>
                    @endforelse
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
