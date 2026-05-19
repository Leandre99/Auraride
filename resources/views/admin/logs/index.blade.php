@extends('layouts.app')

@section('title', 'Historique de Traçabilité - ATLAS TAXI / VTC')

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
    .log-card {
        background: #FFF;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        border: 1px solid var(--border-light);
    }
    .timeline-item {
        padding: 20px;
        border-left: 3px solid #E2E8F0;
        position: relative;
        margin-left: 20px;
        transition: all 0.2s;
    }
    .timeline-item:hover {
        background: #F8FAFC;
        border-left-color: var(--primary);
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 24px;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        background: #CBD5E1;
        border: 3px solid #FFF;
    }
    .timeline-item.active::before {
        background: var(--primary);
    }
    .log-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
</style>
@endpush

@section('content')
<div class="executive-header">
    <div class="container">
        <h1 class="display-5 fw-bold mb-1">Traçabilité</h1>
        <p class="opacity-75 mb-0">Historique complet des actions effectuées sur la plateforme.</p>
    </div>
</div>

<div class="container pb-5">
    <div class="admin-container">
        @include('admin.partials.sidebar')

        <main>
            <div class="log-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                    <h5 class="fw-bold mb-0">Journal d'activité</h5>
                    <span class="badge bg-light text-dark px-3 rounded-pill">{{ $logs->total() }} événements</span>
                </div>

                @if($logs->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x display-1 text-muted opacity-25"></i>
                        <p class="mt-3 text-muted">Aucune activité enregistrée pour le moment.</p>
                    </div>
                @else
                    <div class="timeline">
                        @foreach($logs as $log)
                            <div class="timeline-item">
                                <div class="d-flex gap-3 align-items-start">
                                    <div class="log-icon 
                                        @if(str_contains($log->action, 'approved') || str_contains($log->action, 'confirm') || str_contains($log->action, 'completed')) bg-success-subtle text-success
                                        @elseif(str_contains($log->action, 'cancelled') || str_contains($log->action, 'rejected') || str_contains($log->action, 'suspend')) bg-danger-subtle text-danger
                                        @elseif(str_contains($log->action, 'assigned')) bg-primary-subtle text-primary
                                        @else bg-light text-muted @endif">
                                        <i class="bi 
                                            @if(str_contains($log->action, 'user')) bi-person
                                            @elseif(str_contains($log->action, 'trip')) bi-map
                                            @elseif(str_contains($log->action, 'rental')) bi-car-front
                                            @else bi-lightning @endif"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="fw-bold mb-1">{{ $log->description }}</h6>
                                            <span class="small text-muted">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                                        </div>
                                        <div class="small d-flex gap-3 text-muted">
                                            <span><i class="bi bi-person-circle me-1"></i> {{ $log->user->name ?? 'Système' }}</span>
                                            <span><i class="bi bi-pc-display me-1"></i> {{ $log->ip_address }}</span>
                                            <span class="badge bg-light text-dark text-uppercase" style="font-size: 0.65rem;">{{ $log->action }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        @include('partials.pagination', ['items' => $logs])
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection
