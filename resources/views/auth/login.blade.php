@extends('layouts.app')

@section('title', 'Connexion - ATLAS AND CO')

@push('styles')
<style>
    .auth-fixed-wrapper {
        min-height: calc(100vh - 180px);
        display: flex;
        justify-content: center;
        align-items: center;
        background: radial-gradient(circle at top right, #EBF2FF, transparent);
        padding: 60px 20px;
    }
    
    .auth-card {
        width: 100%;
        max-width: 420px;
        padding: 50px;
        background: #FFF;
        border-radius: 24px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
    }
</style>
@endpush

@section('content')
<div class="auth-fixed-wrapper">
    <div class="auth-card" id="loginPanel" style="opacity: 0; transform: translateY(20px);">
        <div class="text-center mb-5">
            <h2 class="display-6 mb-2">Bon retour</h2>
            <p class="text-muted">L'excellence en mouvement.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="small fw-bold text-muted mb-1">Adresse Email</label>
                <input type="email" name="email" class="form-control input-premium w-100" placeholder="nom@exemple.com" required>
            </div>
            
            <div class="mb-4">
                <label class="small fw-bold text-muted mb-1">Mot de passe</label>
                <input type="password" name="password" class="form-control input-premium w-100" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-premium w-100 py-3 mb-4">Se connecter</button>
        </form>
        
        <div class="text-center">
            <p class="text-muted small">Nouveau ici ? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Créer un compte</a></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.to("#loginPanel", {
            y: 0,
            opacity: 1,
            duration: 0.8,
            ease: "power3.out"
        });
    });
</script>
@endpush
