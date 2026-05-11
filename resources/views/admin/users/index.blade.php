@extends('layouts.app')

@section('title', 'Gestion Utilisateurs - ATLAS AND CO')

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

</style>
@endpush

@section('content')
<div class="executive-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-5 fw-bold mb-1">Utilisateurs</h1>
                <p class="opacity-75 mb-0">Gestion des comptes clients et chauffeurs.</p>
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

            <div class="table-premium">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                    <form action="{{ route('admin.users') }}" method="GET" class="d-flex gap-2">
                        <select name="role" class="form-select form-select-sm border-0 bg-light rounded-pill px-3" onchange="this.form.submit()">
                            <option value="">Tous les rôles</option>
                            <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Passagers</option>
                            <option value="driver" {{ request('role') == 'driver' ? 'selected' : '' }}>Chauffeurs</option>
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-muted small fw-bold">NOM / EMAIL</th>
                                <th class="py-3 text-muted small fw-bold">RÔLE</th>
                                <th class="py-3 text-muted small fw-bold">TÉLÉPHONE</th>
                                <th class="py-3 text-muted small fw-bold">APPROBATION</th>
                                <th class="py-3 text-muted small fw-bold">STATUT</th>
                                <th class="py-3 text-end px-4 text-muted small fw-bold">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <div class="small text-muted">{{ $user->email }}</div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $user->role == 'driver' ? 'bg-primary-subtle text-primary' : 'bg-light text-dark' }} px-3 rounded-pill">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="small fw-bold text-muted">
                                        {{ $user->phone ?? '-' }}
                                    </td>
                                    <td>
                                        @if($user->role == 'driver')
                                            @if($user->is_approved)
                                                <span class="text-success small fw-bold"><i class="bi bi-patch-check-fill"></i> Approuvé</span>
                                            @else
                                                <span class="text-warning small fw-bold"><i class="bi bi-hourglass-split"></i> En attente</span>
                                            @endif
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-pill {{ $user->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                            {{ $user->is_active ? 'Actif' : 'Suspendu' }}
                                        </span>
                                    </td>
                                    <td class="px-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($user->role === 'driver' && !$user->is_approved)
                                                <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-action" title="Approuver le chauffeur">
                                                        <i class="bi bi-check-circle text-success"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-action {{ $user->is_active ? 'btn-action-danger' : '' }}" title="{{ $user->is_active ? 'Suspendre' : 'Activer' }}">
                                                    <i class="bi bi-{{ $user->is_active ? 'person-x' : 'person-check' }}"></i>
                                                </button>
                                            </form>

                                            @if($user->role === 'driver' && $user->cv_path)
                                                <a href="{{ asset('storage/' . $user->cv_path) }}" target="_blank" class="btn-action" title="Voir CV">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
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
                    @foreach($users as $user)
                        <div class="mobile-data-card shadow-sm border-0">
                            <div class="card-header-flex">
                                <div>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <div class="small text-muted">{{ $user->email }}</div>
                                </div>
                                <span class="badge {{ $user->role == 'driver' ? 'bg-primary-subtle text-primary' : 'bg-light text-dark' }} px-2 rounded-pill small">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Téléphone</span>
                                <span class="data-value">{{ $user->phone ?? '-' }}</span>
                            </div>
                            <div class="data-row">
                                <span class="data-label">Statut</span>
                                <span class="data-value">
                                    <span class="status-pill {{ $user->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} small">
                                        {{ $user->is_active ? 'Actif' : 'Suspendu' }}
                                    </span>
                                </span>
                            </div>
                            @if($user->role === 'driver')
                            <div class="data-row">
                                <span class="data-label">Approbation</span>
                                <span class="data-value">
                                    {!! $user->is_approved ? '<span class="text-success small fw-bold">Approuvé</span>' : '<span class="text-warning small fw-bold">En attente</span>' !!}
                                </span>
                            </div>
                            @endif
                            <div class="mt-3 d-flex flex-column gap-2">
                                @if($user->role === 'driver' && !$user->is_approved)
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="w-100">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100 py-2">
                                            <i class="bi bi-check-circle me-1"></i> Approuver le chauffeur
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-{{ $user->is_active ? 'danger' : 'success' }} btn-sm w-100 py-2">
                                        <i class="bi bi-{{ $user->is_active ? 'person-x' : 'person-check' }} me-1"></i>
                                        {{ $user->is_active ? 'Suspendre le compte' : 'Réactiver le compte' }}
                                    </button>
                                </form>

                                @if($user->role === 'driver' && $user->cv_path)
                                    <a href="{{ asset('storage/' . $user->cv_path) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 py-2 text-center">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Voir le CV
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-top">
                    {{ $users->links() }}
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
