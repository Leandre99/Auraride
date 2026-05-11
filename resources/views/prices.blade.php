@extends('layouts.app')

@section('title', 'Nos Tarifs - ATLAS AND CO')

@section('content')
    <!-- Executive Header -->
    <header class="executive-header">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 animate__animated animate__fadeInDown" style="letter-spacing: 1px;">TRANSPARENCE</span>
                    <h1 class="display-3 fw-bold mb-2 animate__animated animate__fadeInLeft">Nos <span class="text-primary-gradient">Tarifs</span></h1>
                    <p class="lead opacity-75 pe-lg-5 animate__animated animate__fadeIn animate__delay-1s">Une tarification transparente et compétitive, sans frais cachés, pour une mobilité d'exception à travers toute la région.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Tableau des Tarifs -->
    <section class="py-5 bg-light" style="margin-top: -40px; position: relative; z-index: 20;">
        <div class="container py-5">
            <div class="row g-4">
                <!-- Berline Standard -->
                <div class="col-lg-4 animate__animated animate__fadeInUp">
                    <div class="glass-panel p-0 h-100 border-0 shadow-sm bg-white text-center overflow-hidden">
                        <div class="p-0 overflow-hidden" style="height: 240px;">
                            <img src="{{ asset('images/berline-standard.jpg') }}" class="w-100 h-100" style="object-fit: cover;" alt="Berline Standard">
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
                                <span class="fw-bold text-primary">0.00 €</span>
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

                <div class="col-lg-4 animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="glass-panel p-0 h-100 border-0 shadow-lg bg-white text-center overflow-hidden position-relative"
                        style="border: 2px solid var(--primary) !important; transform: scale(1.05); z-index: 10;">
                        <div class="p-0 overflow-hidden" style="height: 240px;">
                            <img src="{{ asset('images/van-luxe.jpg') }}" class="w-100 h-100" style="object-fit: cover;" alt="Van Luxe">
                        </div>
                        <div class="p-4">
                            <i class="bi bi-people fs-1 text-primary"></i>
                            <h3 class="fw-bold mt-2 mb-0">Van Luxe</h3>
                            <p class="text-muted small">Mercedes V-Class (7-8 places)</p>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Prix / km</span>
                                <span class="fw-bold">2.50 €</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Prise en charge</span>
                                <span class="fw-bold text-primary">0.00 €</span>
                            </div>
                            <div class="d-flex justify-content-between mb-0">
                                <span class="text-muted">Attente / min</span>
                                <span class="fw-bold">0.20 €</span>
                            </div>
                        </div>
                        <div class="p-4 bg-primary-subtle">
                            <a href="{{ route('register') }}" class="btn btn-premium w-100 shadow">Réserver</a>
                        </div>
                    </div>
                </div>

                <!-- Sprinter Mercedes -->
                <div class="col-lg-4 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="glass-panel p-0 h-100 border-0 shadow-sm bg-white text-center overflow-hidden">
                        <div class="p-0 overflow-hidden" style="height: 240px;">
                            <img src="{{ asset('images/sprinter-mercedes.jpg') }}" class="w-100 h-100" style="object-fit: cover;" alt="Sprinter Mercedes">
                        </div>
                        <div class="p-4">
                            <i class="bi bi-bus-front fs-1 text-primary"></i>
                            <h3 class="fw-bold mt-2 mb-0">Sprinter Mercedes</h3>
                            <p class="text-muted small">9 places - Confort First Class</p>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Prix / km</span>
                                <span class="fw-bold">4.00 €</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Prise en charge</span>
                                <span class="fw-bold text-primary">0.00 €</span>
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
                    <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 80px; height: 80px;">
                        <i class="bi bi-airplane-engines fs-1 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Forfaits Aéroport</h4>
                    <p class="text-muted">Aéroport → Centre-ville à partir de <span class="fw-bold text-dark">35 €</span> tout compris.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check fs-1 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Siège Enfant</h4>
                    <p class="text-muted">Disponible sur demande <span class="fw-bold text-primary">gratuitement</span> pour la sécurité de vos petits.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 80px; height: 80px;">
                        <i class="bi bi-luggage-fill fs-1 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Bagages Volumineux</h4>
                    <p class="text-muted">Nos Vans sont parfaitement adaptés pour les groupes et les bagages encombrants.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
