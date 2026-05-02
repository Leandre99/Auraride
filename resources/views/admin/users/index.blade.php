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
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        border: 1px solid var(--border-light);
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
                <h1 class="display-5 fw-bold mb-1">Utilisateurs</h1>
                <p class="opacity-75 mb-0">Gestion des comptes clients et chauffeurs.</p>
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
                <a href="{{ route('admin.users') }}" class="sidebar-link active"><i class="bi bi-people"></i> Utilisateurs</a>
                <a href="{{ route('admin.trips') }}" class="sidebar-link"><i class="bi bi-map"></i> Courses</a>
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
                        <thead>
                            <tr>
                                <th class="px-4 py-3">Nom / Email</th>
                                <th class="py-3">Rôle</th>
                                <th class="py-3">Statut Appr.</th>
                                <th class="py-3">Compte</th>
                                <th class="py-3 text-end px-4">Actions</th>
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
                                            @if($user->role == 'driver' && !$user->is_approved)
                                                <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">Approuver</button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-primary' }} rounded-pill px-3">
                                                    {{ $user->is_active ? 'Suspendre' : 'Réactiver' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
