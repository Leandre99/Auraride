@extends('layouts.app')

@section('title', 'Nouveau mot de passe - ATLAS TAXI / VTC')

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
    <div class="auth-card" id="resetPanel" style="opacity: 0; transform: translateY(20px);">
        <div class="text-center mb-5">
            <h2 class="display-6 mb-2">Réinitialisation</h2>
            <p class="text-muted">Créez votre nouveau mot de passe sécurisé.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="mb-3">
                <label class="small fw-bold text-muted mb-1">Adresse Email</label>
                <input type="email" name="email" class="form-control input-premium w-100" placeholder="nom@exemple.com" required value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label class="small fw-bold text-muted mb-1">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-control input-premium w-100" placeholder="••••••••" required>
            </div>

            <div class="mb-4">
                <label class="small fw-bold text-muted mb-1">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" class="form-control input-premium w-100" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-premium w-100 py-3 mb-4">Réinitialiser le mot de passe</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.to("#resetPanel", {
            y: 0,
            opacity: 1,
            duration: 0.8,
            ease: "power3.out"
        });
    });
</script>
@endpush
