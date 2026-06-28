@extends('layouts.app')

@section('title', 'Reçu de course & Finalisation de compte')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <!-- Carte principale -->
            <div class="card glass-panel p-4 p-md-5 border-0 shadow-lg animate__animated animate__fadeInUp">
                
                <!-- Titre & Entête -->
                <div class="text-center mb-4">
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-2">
                        <i class="bi bi-check-circle-fill me-1"></i>Course Payée
                    </span>
                    <h2 class="h3 text-primary-gradient mb-1">Votre Reçu & Finalisation</h2>
                    <p class="small text-muted">Consultez votre reçu ci-dessous et finalisez votre compte en 10 secondes pour recevoir votre facture PDF.</p>
                </div>

                <!-- Détails du Reçu -->
                <div class="bg-light rounded-4 p-4 mb-4 border border-light">
                    <h4 class="h6 fw-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.5px; font-size: 0.8rem;">Détails de la course #{{ $trip->id }}</h4>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Date</span>
                        <span class="fw-semibold small">{{ $trip->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Départ</span>
                        <span class="fw-semibold small text-end text-truncate" style="max-width: 250px;" title="{{ $trip->pickup_address }}">{{ $trip->pickup_address }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Destination</span>
                        <span class="fw-semibold small text-end text-truncate" style="max-width: 250px;" title="{{ $trip->dropoff_address }}">{{ $trip->dropoff_address }}</span>
                    </div>

                    @if($trip->driver)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Chauffeur</span>
                        <span class="fw-semibold small">{{ $trip->driver->name }}</span>
                    </div>
                    @endif

                    <hr class="my-3 border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark">Montant Réglé</span>
                        <span class="h4 fw-bold text-primary mb-0">{{ number_format($trip->price, 2) }} €</span>
                    </div>
                </div>

                <!-- Formulaire de Finalisation -->
                <div class="border-top pt-4">
                    <h4 class="h5 fw-bold mb-3 text-dark"><i class="bi bi-person-plus-fill text-primary me-2"></i>Enregistrer votre compte</h4>
                    
                    <!-- Affichage des erreurs de validation -->
                    @if($errors->any())
                        <div class="alert alert-danger border-0 rounded-4 small p-3 mb-4 animate__animated animate__shakeX" style="background-color: #FEF2F2; color: #991B1B;">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('express.complete.post', $trip->id) }}" method="POST">
                        @csrf
                        
                        <!-- Nom complet -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Votre Nom complet</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="name" id="name" class="form-control border-start-0 ps-0" value="{{ old('name', $trip->client->name) }}" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Adresse E-mail (pour la facture)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email" name="email" id="email" class="form-control border-start-0 ps-0" placeholder="exemple@mail.com" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Mot de Passe</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password" id="password" class="form-control border-start-0 ps-0" placeholder="Minimum 8 caractères" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Confirmation</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-shield-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-start-0 ps-0" placeholder="Confirmer" required>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton de validation -->
                        <button type="submit" class="btn btn-primary btn-premium w-100 py-3 d-flex align-items-center justify-content-center gap-2">
                            <span>Valider mon compte & recevoir ma facture</span>
                            <i class="bi bi-arrow-right-short fs-5"></i>
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
