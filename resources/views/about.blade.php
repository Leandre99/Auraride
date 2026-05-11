@extends('layouts.app')

@section('title', 'Notre Mission')

@section('content')
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row align-items-center mb-5 pb-5">
            <div class="col-lg-6">
                <h1 class="display-4 mb-4">Notre mission : <span class="text-primary-gradient">élever le transport.</span></h1>
                <p class="lead text-muted mb-4">Fondée en 2024, ATLAS AND CO est née d'une idée simple : la mobilité urbaine doit être bien plus qu'un simple trajet d'un point A à un point B. Ce doit être une expérience.</p>
                <p class="text-muted">Nous croyons au pouvoir de la technologie pour rendre nos villes plus accessibles, nos voyages plus sûrs et notre planète plus propre. C'est pourquoi nous privilégions les véhicules électriques et les standards de service les plus élevés.</p>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=1000" class="w-100 rounded-4 shadow-lg" alt="Équipe">
            </div>
        </div>

        <div class="row g-4 pt-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="kpi-icon bg-primary text-white mb-3 shadow-sm"><i class="bi bi-cpu-fill"></i></div>
                    <h4 class="mb-3">Innovation</h4>
                    <p class="text-muted mb-0">Nous utilisons des algorithmes de pointe et l'IA pour optimiser les trajets et réduire les temps d'attente pour nos utilisateurs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="kpi-icon bg-success text-white mb-3 shadow-sm"><i class="bi bi-leaf-fill"></i></div>
                    <h4 class="mb-3">Durabilité</h4>
                    <p class="text-muted mb-0">Notre engagement envers l'environnement signifie privilégier les véhicules électriques et hybrides dans notre flotte.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="kpi-icon bg-warning text-dark mb-3 shadow-sm"><i class="bi bi-star-fill"></i></div>
                    <h4 class="mb-3">Excellence</h4>
                    <p class="text-muted mb-0">Chaque chauffeur est formé pour offrir une expérience 5 étoiles, garantissant votre confort et votre sécurité.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light border-top">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Notre équipe de direction</h2>
            <div class="text-primary fw-bold">Les visages derrière ATLAS AND CO</div>
        </div>
        
        <!-- Le BOSS -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-4 text-center">
                <div class="p-4 bg-white rounded-4 shadow-sm border">
                    <img src="https://i.pravatar.cc/150?img=12" class="rounded-circle mb-3 shadow" style="width: 140px; height: 140px; object-fit: cover; border: 4px solid var(--primary);" alt="CEO">
                    <h4 class="mb-1">Sarah Jenkins</h4>
                    <p class="text-primary fw-bold text-uppercase small letter-spacing-1">Fondatrice & Directrice Générale</p>
                    <p class="text-muted small mb-0">Visionnaire à l'origine de la révolution ATLAS, Sarah pilote la stratégie globale avec 15 ans d'expérience dans la Tech.</p>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-3 text-center">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <img src="https://i.pravatar.cc/150?img=13" class="rounded-circle mb-3 shadow-sm mx-auto" style="width: 80px; height: 80px; object-fit: cover;" alt="">
                    <h6 class="mb-1 fw-bold">David Chen</h6>
                    <p class="text-primary x-small fw-bold mb-0">CTO</p>
                </div>
            </div>
            <div class="col-6 col-md-3 text-center">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <img src="https://i.pravatar.cc/150?img=14" class="rounded-circle mb-3 shadow-sm mx-auto" style="width: 80px; height: 80px; object-fit: cover;" alt="">
                    <h6 class="mb-1 fw-bold">Elena Rodriguez</h6>
                    <p class="text-primary x-small fw-bold mb-0">Dir. Opérations</p>
                </div>
            </div>
            <div class="col-6 col-md-3 text-center">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <img src="https://i.pravatar.cc/150?img=15" class="rounded-circle mb-3 shadow-sm mx-auto" style="width: 80px; height: 80px; object-fit: cover;" alt="">
                    <h6 class="mb-1 fw-bold">Marcus Thorne</h6>
                    <p class="text-primary x-small fw-bold mb-0">Dir. Sécurité</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
