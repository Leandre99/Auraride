@extends('layouts.app')

@section('title', 'Notre Mission')

@section('content')
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h1 class="display-4 mb-4">Notre mission : <span class="text-primary-gradient">élever le transport.</span></h1>
                <p class="lead text-muted mb-4">Fondée en 2024, ATLAS AND CO est née d'une idée simple : la mobilité urbaine doit être bien plus qu'un simple trajet d'un point A à un point B. Ce doit être une expérience.</p>
                <p class="text-muted">Nous croyons au pouvoir de la technologie pour rendre nos villes plus accessibles, nos voyages plus sûrs et notre planète plus propre. C'est pourquoi nous privilégions les véhicules électriques et les standards de service les plus élevés.</p>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=1000" class="w-100 rounded-4 shadow-lg" alt="Équipe">
            </div>
        </div>

        <div class="row g-5 py-5">
            <div class="col-md-4">
                <h3 class="mb-3">Innovation</h3>
                <p class="text-muted">Nous utilisons des algorithmes de pointe et l'IA pour optimiser les trajets et réduire les temps d'attente pour nos utilisateurs.</p>
            </div>
            <div class="col-md-4">
                <h3 class="mb-3">Durabilité</h3>
                <p class="text-muted">Notre engagement envers l'environnement signifie privilégier les véhicules électriques et hybrides dans notre flotte.</p>
            </div>
            <div class="col-md-4">
                <h3 class="mb-3">Excellence</h3>
                <p class="text-muted">Chaque chauffeur est formé pour offrir une expérience 5 étoiles, garantissant votre confort et votre sécurité.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container py-5 text-center">
        <h2 class="mb-5">Notre équipe de direction</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <img src="https://i.pravatar.cc/150?img=12" class="rounded-circle mb-3 shadow-sm" style="width: 120px; height: 120px;" alt="">
                <h5>Sarah Jenkins</h5>
                <p class="text-primary small fw-bold">CEO & Fondatrice</p>
            </div>
            <div class="col-md-3">
                <img src="https://i.pravatar.cc/150?img=13" class="rounded-circle mb-3 shadow-sm" style="width: 120px; height: 120px;" alt="">
                <h5>David Chen</h5>
                <p class="text-primary small fw-bold">CTO</p>
            </div>
            <div class="col-md-3">
                <img src="https://i.pravatar.cc/150?img=14" class="rounded-circle mb-3 shadow-sm" style="width: 120px; height: 120px;" alt="">
                <h5>Elena Rodriguez</h5>
                <p class="text-primary small fw-bold">Directrice des Opérations</p>
            </div>
            <div class="col-md-3">
                <img src="https://i.pravatar.cc/150?img=15" class="rounded-circle mb-3 shadow-sm" style="width: 120px; height: 120px;" alt="">
                <h5>Marcus Thorne</h5>
                <p class="text-primary small fw-bold">Directeur de la Sécurité</p>
            </div>
        </div>
    </div>
</section>
@endsection
