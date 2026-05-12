@extends('layouts.app')

@section('title', 'Mot de passe oublié - ATLAS AND CO')

@push('styles')
<style>
    .auth-fixed-wrapper {
        min-height: calc(100vh - 90px);
        display: flex;
        justify-content: center;
        align-items: center;
        background: #F8FAFC;
        padding: 40px 20px;
    }
    
    .auth-card {
        width: 100%;
        max-width: 440px;
        padding: 40px;
        background: #FFF;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="auth-fixed-wrapper">
    <div class="auth-card" id="forgotPanel" style="opacity: 0; transform: translateY(20px);">
        <div class="text-center mb-5">
            <h2 class="display-6 mb-2">Récupération</h2>
            <p class="text-muted">Entrez votre email pour réinitialiser votre mot de passe.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 bg-success-subtle text-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label class="small fw-bold text-muted mb-1">Adresse Email</label>
                <input type="email" name="email" class="form-control input-premium w-100" placeholder="nom@exemple.com" required value="{{ old('email') }}">
            </div>
            
            <button type="submit" class="btn btn-premium w-100 py-3 mb-4">Envoyer le lien</button>
        </form>
        
        <div class="text-center">
            <p class="text-muted small">Retourner à la <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">connexion</a></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.to("#forgotPanel", {
            y: 0,
            opacity: 1,
            duration: 0.8,
            ease: "power3.out"
        });
    });
</script>
@endpush
