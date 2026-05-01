@extends('layouts.app')

@section('title', 'Révolutionner le transport urbain')

@section('content')
<!-- Hero Section -->
<section class="py-5 position-relative overflow-hidden" style="background: linear-gradient(180deg, #FFFFFF 0%, var(--bg-light) 100%);">
    <div class="container position-relative z-index-10 py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3">VTC Premium</span>
                <h1 class="display-3 mb-4">Le transport redéfini pour le <span class="text-primary-gradient">Monde Moderne.</span></h1>
                <p class="lead text-muted mb-5 pe-lg-5">AuraRide combine le luxe haut de gamme et la technologie de pointe pour vous offrir une expérience de voyage fluide, sûre et sophistiquée.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('register') }}" class="btn btn-premium px-5 py-3">Commencer</a>
                    <a href="#features" class="btn btn-outline-premium px-5 py-3">Explorer les services</a>
                </div>
                
                <div class="mt-5 d-flex align-items-center gap-4">
                    <div class="d-flex">
                        <img src="https://i.pravatar.cc/150?img=1" class="rounded-circle border border-2 border-white" style="width: 40px; height: 40px; margin-right: -15px;" alt="">
                        <img src="https://i.pravatar.cc/150?img=2" class="rounded-circle border border-2 border-white" style="width: 40px; height: 40px; margin-right: -15px;" alt="">
                        <img src="https://i.pravatar.cc/150?img=3" class="rounded-circle border border-2 border-white" style="width: 40px; height: 40px;" alt="">
                    </div>
                    <div class="small text-muted">
                        Plus de <span class="fw-bold text-dark">500k</span> utilisateurs font confiance à AuraRide
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="position-relative">
                    <div class="rounded-4 overflow-hidden shadow-lg" style="transform: rotate(2deg);">
                        <img src="{{ asset('images/tesla-hero.png') }}" class="w-100" alt="Tesla Model S Premium">
                    </div>
                    <div class="glass-panel p-4 position-absolute" style="bottom: -30px; left: -30px; width: 250px;">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-circle bg-success" style="width: 10px; height: 10px;"></div>
                            <div class="small fw-bold">Chauffeur en route</div>
                        </div>
                        <div class="h5 mb-0">3 Minutes</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="h1 mb-3">Pourquoi choisir AuraRide ?</h2>
            <p class="text-muted">Découvrez le sommet de la mobilité urbaine.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-5 glass-panel h-100 border-0 shadow-sm hover-translate-up transition">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-shield-check" viewBox="0 0 16 16">
                            <path d="M8 14.933a.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067v13.866zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                            <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </div>
                    <h4>Sécurité Maximale</h4>
                    <p class="text-muted mb-0">Chauffeurs rigoureusement sélectionnés et suivi en temps réel de chaque trajet 24/7.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-5 glass-panel h-100 border-0 shadow-sm hover-translate-up transition">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-lightning-charge" viewBox="0 0 16 16">
                            <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09zM4.157 8.5H7a.5.5 0 0 1 .478.647L6.11 13.59l5.733-6.09H9a.5.5 0 0 1-.478-.647L9.89 2.41 4.157 8.5z"/>
                        </svg>
                    </div>
                    <h4>Rapidité d'intervention</h4>
                    <p class="text-muted mb-0">Notre algorithme avancé garantit qu'un chauffeur arrive chez vous en quelques minutes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-5 glass-panel h-100 border-0 shadow-sm hover-translate-up transition">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957-2.939-2.801 4.074-.58L8 1.426l1.797 3.674 4.074.58-2.939 2.801.694 3.957-3.685-1.894z"/>
                        </svg>
                    </div>
                    <h4>Flotte Premium</h4>
                    <p class="text-muted mb-0">Seuls les meilleurs véhicules. Des berlines électriques aux SUVs de luxe.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-primary text-white">
    <div class="container py-5">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="display-5 fw-bold mb-1">10k+</div>
                <div class="small opacity-75">Courses quotidiennes</div>
            </div>
            <div class="col-md-3">
                <div class="display-5 fw-bold mb-1">4.9/5</div>
                <div class="small opacity-75">Note moyenne</div>
            </div>
            <div class="col-md-3">
                <div class="display-5 fw-bold mb-1">15+</div>
                <div class="small opacity-75">Villes couvertes</div>
            </div>
            <div class="col-md-3">
                <div class="display-5 fw-bold mb-1">2k+</div>
                <div class="small opacity-75">Chauffeurs actifs</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="glass-panel p-5 bg-primary position-relative overflow-hidden">
            <div class="row align-items-center position-relative z-index-10">
                <div class="col-lg-8 text-white">
                    <h2 class="display-5 mb-3">Prêt à rejoindre la révolution ?</h2>
                    <p class="lead opacity-75 mb-lg-0">Commencez à voyager ou à gagner avec AuraRide dès aujourd'hui.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('register') }}" class="btn btn-light px-5 py-3 fw-bold rounded-pill">Télécharger l'App</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .hover-translate-up:hover {
        transform: translateY(-10px);
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>
@endpush
