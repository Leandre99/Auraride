@extends('layouts.app')

@section('title', 'Nos Tarifs - ATLAS AND CO')

@section('content')
<!-- Header Tarifs -->
<section class="py-5 bg-dark text-white text-center">
    <div class="container py-5">
        <span class="badge bg-primary px-3 py-2 rounded-pill mb-3">TRANSPARENCE</span>
        <h1 class="display-4 fw-bold">Nos Tarifs</h1>
        <p class="lead opacity-75">Une tarification transparente et compétitive, sans frais cachés.</p>
    </div>
</section>

<!-- Tableau des Tarifs -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="row g-4">
            <!-- Berline Standard -->
            <div class="col-lg-4">
                <div class="glass-panel p-0 h-100 border-0 shadow-sm bg-white text-center overflow-hidden">
                    <div class="bg-primary-subtle p-0 overflow-hidden">
                        <img src="{{ asset('images/tesla-hero.png') }}" class="w-100" style="height: 200px; object-fit: cover;" alt="Berline Standard">
                    </div>
                    <div class="p-4">
                        <i class="bi bi-car-front fs-1 text-primary"></i>
                        <h3 class="fw-bold mt-2 mb-0">Berline Standard</h3>
                        <p class="text-muted small">Tesla Model S, Toyota Camry</p>
                    </div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Prix / km</span>
                            <span class="fw-bold">1.50 €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Prise en charge</span>
                            <span class="fw-bold text-success">0.00 €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="text-muted">Attente / min</span>
                            <span class="fw-bold">0.20 €</span>
                        </div>
                    </div>
                    <div class="p-4 bg-light">
                        <a href="{{ route('register') }}" class="btn btn-premium w-100">Réserver</a>
                    </div>
                </div>
            </div>

            <!-- Van Luxe -->
            <div class="col-lg-4">
                <div class="glass-panel p-0 h-100 border-0 shadow-sm bg-white text-center overflow-hidden border-primary" style="border-top: 5px solid var(--primary) !important;">
                    <div class="bg-primary p-0 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=600" class="w-100" style="height: 200px; object-fit: cover;" alt="Van Luxe">
                    </div>
                    <div class="p-4 text-white bg-primary">
                        <i class="bi bi-people fs-1"></i>
                        <h3 class="fw-bold mt-2 mb-0">Van Luxe</h3>
                        <p class="opacity-75 small">Mercedes V-Class (7-8 places)</p>
                    </div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Prix / km</span>
                            <span class="fw-bold">2.50 €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Prise en charge</span>
                            <span class="fw-bold text-success">0.00 €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="text-muted">Attente / min</span>
                            <span class="fw-bold">0.20 €</span>
                        </div>
                    </div>
                    <div class="p-4 bg-light">
                        <a href="{{ route('register') }}" class="btn btn-premium w-100">Réserver</a>
                    </div>
                </div>
            </div>

            <!-- Sprinter Mercedes -->
            <div class="col-lg-4">
                <div class="glass-panel p-0 h-100 border-0 shadow-sm bg-white text-center overflow-hidden">
                    <div class="bg-dark p-0 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1618519764620-7403abdb0921?auto=format&fit=crop&w=600" class="w-100" style="height: 200px; object-fit: cover;" alt="Sprinter Mercedes">
                    </div>
                    <div class="p-4 text-white bg-dark">
                        <i class="bi bi-bus-front fs-1 text-primary"></i>
                        <h3 class="fw-bold mt-2 mb-0">Sprinter Mercedes</h3>
                        <p class="opacity-75 small">9 places - Confort First Class</p>
                    </div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Prix / km</span>
                            <span class="fw-bold">4.00 €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Prise en charge</span>
                            <span class="fw-bold text-success">0.00 €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="text-muted">Attente / min</span>
                            <span class="fw-bold">0.20 €</span>
                        </div>
                    </div>
                    <div class="p-4 bg-light">
                        <a href="{{ route('register') }}" class="btn btn-premium w-100">Réserver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Informations Complémentaires -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-4 text-center">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                    <i class="bi bi-airplane fs-2 text-primary"></i>
                </div>
                <h4 class="fw-bold">Forfaits Aéroport</h4>
                <p class="text-muted">Aéroport → Centre-ville à partir de <span class="fw-bold text-dark">35 €</span> tout compris.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                    <i class="bi bi-universal-access fs-2 text-primary"></i>
                </div>
                <h4 class="fw-bold">Siège Enfant</h4>
                <p class="text-muted">Disponible sur demande <span class="fw-bold text-success">gratuitement</span> pour la sécurité de vos petits.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                    <i class="bi bi-luggage fs-2 text-primary"></i>
                </div>
                <h4 class="fw-bold">Bagages Volumineux</h4>
                <p class="text-muted">Nos Vans sont parfaitement adaptés pour les groupes et les bagages encombrants.</p>
            </div>
        </div>
    </div>
</section>
@endsection
