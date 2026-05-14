@extends('layouts.app')

@section('title', 'ATLAS AND CO - Executive Command')

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

    @media (max-width: 767.98px) {
        .executive-header {
            padding: 40px 0;
            padding-bottom: 100px;
        }
        .display-5 { font-size: 1.8rem; }
    }

    .kpi-card {
        background: #FFF;
        border-radius: 20px;
        padding: 20px;
        border: 0;
        box-shadow: 0 8px 30px rgba(15, 23, 42, 0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .kpi-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }

    @media (max-width: 767.98px) {
        .kpi-card {
            padding: 15px;
            border-radius: 16px;
        }
        .kpi-icon {
            width: 35px;
            height: 35px;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        .kpi-card .h3 { font-size: 1.25rem; margin-bottom: 5px !important; }
        .kpi-card .small { font-size: 0.7rem; }
    }

    .table-premium {
        background: #FFF;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
    }

    /* Styles responsifs pour les tableaux */
    @media (max-width: 991.98px) {
        .desktop-table-view {
            display: none;
        }
        .mobile-card-view {
            display: block;
        }
    }

    @media (min-width: 992px) {
        .desktop-table-view {
            display: block;
        }
        .mobile-card-view {
            display: none;
        }
    }

    .mobile-data-card {
        background: #fff;
        border-radius: 16px;
        padding: 12px;
        margin-bottom: 12px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .data-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px dashed #f1f5f9;
    }

    .data-row:last-child {
        border-bottom: none;
    }

    .data-label {
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .data-value {
        font-weight: 600;
        font-size: 0.85rem;
        color: #1e293b;
    }

    .status-pill {
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }

    .btn-action-mobile {
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .admin-container {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .admin-container main {
        flex: 1;
        min-width: 0; /* Évite les débordements */
    }

    @media (max-width: 991.98px) {
        .admin-container {
            flex-direction: column;
            gap: 20px;
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
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="kpi-card shadow-sm border-0 text-center">
                        <div class="kpi-icon bg-primary-subtle text-primary rounded-4 p-3 mx-auto mb-2 mb-md-3"><i class="bi bi-people-fill"></i></div>
                        <div class="text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px; text-transform: uppercase;">Utilisateurs</div>
                        <div class="h3 fw-bold mb-1 mb-md-3 text-dark">{{ $stats['users_count'] }}</div>
                        <div class="pt-2 pt-md-3 border-top d-none d-md-block small text-muted">
                            <i class="bi bi-person-check text-success me-1"></i> Clients & Staff
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="kpi-card shadow-sm border-0 text-center">
                        <div class="kpi-icon bg-success-subtle text-success rounded-4 p-3 mx-auto mb-2 mb-md-3"><i class="bi bi-currency-euro"></i></div>
                        <div class="text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px; text-transform: uppercase;">Revenus</div>
                        <div class="h3 fw-bold mb-1 mb-md-3 text-dark">{{ number_format($stats['total_revenue'], 0) }}€</div>
                        <div class="pt-2 pt-md-3 border-top d-none d-md-block small text-muted">
                            <i class="bi bi-graph-up-arrow text-success me-1"></i> Courses & Locations
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="kpi-card shadow-sm border-0 text-center">
                        <div class="kpi-icon bg-warning-subtle text-warning rounded-4 p-3 mx-auto mb-2 mb-md-3"><i class="bi bi-clock-history"></i></div>
                        <div class="text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px; text-transform: uppercase;">Attente</div>
                        <div class="h3 fw-bold mb-1 mb-md-3 text-dark">{{ $stats['pending_trips_count'] }}</div>
                        <div class="pt-2 pt-md-3 border-top d-none d-md-block small text-muted">
                            <i class="bi bi-hourglass-split text-warning me-1"></i> Courses
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="kpi-card shadow-sm border-0 text-center">
                        <div class="kpi-icon bg-info-subtle text-info rounded-4 p-3 mx-auto mb-2 mb-md-3"><i class="bi bi-car-front-fill"></i></div>
                        <div class="text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px; text-transform: uppercase;">Locations</div>
                        <div class="h3 fw-bold mb-1 mb-md-3 text-dark">{{ $stats['pending_rentals_count'] }}</div>
                        <div class="pt-2 pt-md-3 border-top d-none d-md-block small text-muted">
                            <i class="bi bi-clock text-info me-1"></i> Demandes
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Rentals Section -->
            @if($pendingRentals->count() > 0)
            <div class="table-premium mb-5 border-info border-2">
                <div class="p-4 border-bottom bg-info bg-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-key me-2"></i>Nouvelles demandes de location</h5>
                    <a href="{{ route('admin.rentals') }}" class="btn btn-info btn-sm text-dark fw-bold">Gérer les locations</a>
                </div>

                <!-- Vue Desktop -->
                <div class="desktop-table-view">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3">Client</th>
                                    <th class="py-3">Véhicule</th>
                                    <th class="py-3">Période</th>
                                    <th class="py-3">Prix</th>
                                    <th class="py-3 text-end px-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingRentals as $rental)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-bold">{{ $rental->user->name }}</div>
                                            <div class="small text-muted">{{ $rental->user->email }}</div>
                                         </td>
                                        <td>
                                            <div class="fw-bold">{{ $rental->vehicleType->name }}</div>
                                            @if($rental->with_driver)
                                                <span class="badge bg-success-subtle text-success small">Avec chauffeur</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small fw-bold">Du {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m') }} au {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m') }}</div>
                                            <div class="small text-muted">{{ $rental->total_days }} jour(s)</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-primary">{{ number_format($rental->total_price, 2) }}€</div>
                                        </td>
                                        <td class="px-4 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <form action="{{ route('admin.rentals.confirm', $rental) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm px-3 rounded-pill">Valider</button>
                                                </form>
                                                <form action="{{ route('admin.rentals.reject', $rental) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-pill">Refuser</button>
                                                </form>
                                            </div>
                                        </td>
                                    </table>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Vue Mobile -->
                <div class="mobile-card-view p-3">
                    @foreach($pendingRentals as $rental)
                        <div class="mobile-data-card border-info border-start border-4">
                            <div class="card-header-flex">
                                <div>
                                    <div class="fw-bold text-dark">{{ $rental->user->name }}</div>
                                    <div class="small text-muted">{{ $rental->user->email }}</div>
                                </div>
                                <div class="fw-bold text-primary">{{ number_format($rental->total_price, 2) }}€</div>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Véhicule</span>
                                <span class="data-value">{{ $rental->vehicleType->name }}</span>
                            </div>
                            @if($rental->with_driver)
                                <div class="data-row">
                                    <span class="data-label">Option</span>
                                    <span class="data-value"><span class="badge bg-success-subtle text-success">Avec chauffeur</span></span>
                                </div>
                            @endif
                            <div class="data-row">
                                <span class="data-label">Période</span>
                                <span class="data-value">{{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Durée</span>
                                <span class="data-value">{{ $rental->total_days }} jour(s)</span>
                            </div>
                            <div class="mt-3 d-flex gap-2">
                                <form action="{{ route('admin.rentals.confirm', $rental) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-100 py-2 fw-bold">Valider</button>
                                </form>
                                <form action="{{ route('admin.rentals.reject', $rental) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2 fw-bold">Refuser</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Pending Assignments Section -->
            @if($pendingTrips->count() > 0)
            <div class="table-premium mb-5 border-warning border-2">
                <div class="p-4 border-bottom bg-warning bg-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-clock-history me-2"></i>Demandes en attente d'assignation</h5>
                    <span class="badge bg-warning text-dark">{{ $pendingTrips->count() }} nouvelle(s)</span>
                </div>

                <!-- Vue Desktop -->
                <div class="desktop-table-view">
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
                                            <div class="fw-bold">{{ number_format($trip->price, 2) }}€</div>
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

                <!-- Vue Mobile -->
                <div class="mobile-card-view p-3">
                    @foreach($pendingTrips as $trip)
                        <div class="mobile-data-card border-warning border-start border-4">
                            <div class="card-header-flex">
                                <div>
                                    <div class="fw-bold text-dark">{{ $trip->client->name }}</div>
                                    <div class="small text-muted">{{ $trip->client->email }}</div>
                                </div>
                                <div class="fw-bold text-primary">{{ number_format($trip->price, 2) }}€</div>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Départ</span>
                                <span class="data-value text-truncate" style="max-width: 200px;">{{ $trip->pickup_address }}</span>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Destination</span>
                                <span class="data-value text-truncate" style="max-width: 200px;">{{ $trip->dropoff_address }}</span>
                            </div>
                            <div class="mt-3">
                                @include('admin.partials.trip-assign-button', [
                                    'trip' => $trip,
                                    'buttonLabel' => '<i class="bi bi-person-plus me-1"></i> Assigner chauffeur',
                                    'buttonClass' => 'btn btn-warning btn-sm w-100 py-2 fw-bold'
                                ])
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Activity Section -->
            <div class="table-premium">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">Dernières Courses</h5>
                    <a href="{{ route('admin.trips') }}" class="btn btn-outline-premium btn-sm">Voir tout</a>
                </div>

                <!-- Vue Desktop -->
                <div class="desktop-table-view">
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

                <!-- Vue Mobile -->
                <div class="mobile-card-view p-3">
                    @forelse($recentTrips as $trip)
                        <div class="mobile-data-card shadow-sm border-0">
                            <div class="card-header-flex">
                                <div>
                                    <div class="fw-bold text-primary">{{ $trip->client->name }}</div>
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
                                <span class="data-label">Chauffeur</span>
                                <span class="data-value">{{ $trip->driver->name ?? 'En attente' }}</span>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Montant</span>
                                <span class="data-value fw-bold text-primary">{{ number_format($trip->price, 2) }}€</span>
                            </div>
                            <div class="mt-2 pt-2 border-top small">
                                <div class="mb-1 text-muted"><strong>📍 Départ:</strong> {{ $trip->pickup_address }}</div>
                                <div class="text-muted"><strong>🏁 Arrivée:</strong> {{ $trip->dropoff_address }}</div>
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
