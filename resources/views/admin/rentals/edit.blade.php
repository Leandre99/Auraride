@extends('layouts.app')

@section('title', 'Traiter la location #' . $rental->id . ' - ATLAS AND CO')

@section('content')
<div class="py-5 bg-light min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('admin.rentals') }}" class="btn btn-outline-secondary rounded-circle me-3">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h2 class="fw-bold mb-0">Traiter la location <span class="text-primary">#{{ $rental->id }}</span></h2>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-dark text-white p-4">
                        <h5 class="mb-0">Détails de la demande</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Client</label>
                                <p class="fw-bold mb-0">{{ $rental->user->name }}</p>
                                <p class="text-muted small">{{ $rental->user->email }}</p>
                                <p class="text-muted small">📞 {{ $rental->user->phone_number ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Véhicule demandé</label>
                                <p class="fw-bold mb-0 text-primary">{{ $rental->vehicleType->name }}</p>
                                <p class="text-muted small">Tarif base: {{ number_format($rental->daily_price, 2) }}€/jour</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Période</label>
                                <p class="fw-bold mb-0">Du {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}</p>
                                <p class="text-muted small">{{ $rental->total_days }} jour(s) - Prise en charge à {{ $rental->pickup_time }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Options & Livraison</label>
                                <p class="mb-0">
                                    @if($rental->with_driver)
                                        <span class="badge bg-success-subtle text-success">Avec chauffeur</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Sans chauffeur</span>
                                    @endif
                                </p>
                                @if($rental->delivery_address)
                                    <p class="text-muted small mt-1">📍 {{ $rental->delivery_address }}</p>
                                @else
                                    <p class="text-muted small mt-1">Retrait en agence</p>
                                @endif
                            </div>
                            <div class="col-12 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0 fw-bold">Montant Total :</span>
                                    <span class="h4 mb-0 fw-bold text-success">{{ number_format($rental->total_price, 2) }} €</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Actions de l'administrateur</h5>
                        <form action="{{ route('admin.rentals.update-status', $rental) }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Statut de la demande</label>
                                    <select name="status" class="form-select p-3 rounded-3 bg-light border-0">
                                        <option value="pending" {{ $rental->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="confirmed" {{ $rental->status == 'confirmed' ? 'selected' : '' }}>Confirmer / Valider</option>
                                        <option value="rejected" {{ $rental->status == 'rejected' ? 'selected' : '' }}>Rejeter / Refuser</option>
                                        <option value="completed" {{ $rental->status == 'completed' ? 'selected' : '' }}>Terminée</option>
                                        <option value="cancelled" {{ $rental->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Assigner un chauffeur (si applicable)</label>
                                    <select name="driver_id" class="form-select p-3 rounded-3 bg-light border-0">
                                        <option value="">-- Aucun chauffeur --</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ $rental->driver_id == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->name }} ({{ $driver->phone_number ?? 'Sans tel' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Notes administratives / Message au client</label>
                                    <textarea name="admin_notes" class="form-control p-3 rounded-3 bg-light border-0" rows="4" placeholder="Ce message sera inclus dans l'email envoyé au client...">{{ $rental->admin_notes }}</textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-premium w-100 py-3 fw-bold rounded-3 shadow-sm">
                                        <i class="bi bi-check-circle me-2"></i> ENREGISTRER & ENVOYER L'EMAIL
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
