@extends('layouts.app')

@section('title', 'Rejoindre ATLAS AND CO')

@push('styles')
<style>
    .auth-fixed-wrapper {
        min-height: calc(100vh - 180px);
        display: flex;
        justify-content: center;
        align-items: center;
        background: radial-gradient(circle at top right, #EBF2FF, transparent);
        padding: 80px 20px;
    }
    
    .auth-card {
        width: 100%;
        max-width: 460px;
        max-height: 85vh;
        overflow-y: auto;
        padding: 40px;
        background: #FFF;
        border-radius: 24px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
    }
    .auth-card::-webkit-scrollbar { width: 5px; }
    .auth-card::-webkit-scrollbar-thumb { background: #EEE; border-radius: 10px; }

    .role-selection {
        display: flex;
        gap: 10px;
    }
    .role-pill {
        flex: 1;
        padding: 10px;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        color: var(--text-muted);
        font-size: 0.9rem;
    }
    .role-pill.active {
        border-color: var(--primary);
        background: rgba(37, 99, 235, 0.05);
        color: var(--primary);
    }
</style>
@endpush

@section('content')
<div class="auth-fixed-wrapper">
    <div class="auth-card" id="registerPanel" style="opacity: 0; transform: translateY(20px);">
        <div class="text-center mb-4">
            <h2 class="h3 mb-2">Rejoindre ATLAS AND CO</h2>
            <p class="text-muted small">Commencez votre voyage avec nous.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-3 p-2">
                <ul class="mb-0 small" style="list-style: none; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-2">
                <label class="small fw-bold text-muted mb-1">Nom complet</label>
                <input type="text" name="name" class="form-control input-premium w-100" placeholder="Jean Dupont" required value="{{ old('name') }}">
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-7">
                    <label class="small fw-bold text-muted mb-1">Adresse Email</label>
                    <input type="email" name="email" class="form-control input-premium w-100" placeholder="nom@exemple.com" required value="{{ old('email') }}">
                </div>
                <div class="col-md-5">
                    <label class="small fw-bold text-muted mb-1">Téléphone</label>
                    <input type="text" name="phone_number" class="form-control input-premium w-100" placeholder="06..." required value="{{ old('phone_number') }}">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="small fw-bold text-muted mb-1">Je m'inscris en tant que :</label>
                <div class="role-selection">
                    <div class="role-pill active" onclick="setRole('client', this)">Passager</div>
                    <div class="role-pill" onclick="setRole('driver', this)">Chauffeur</div>
                </div>
                <input type="hidden" name="role" id="roleInput" value="client">
            </div>

            <!-- Driver Specific Fields -->
            <div id="driverFields" style="display: none;" class="mb-3 bg-light p-3 rounded-4">
                <div class="mb-2">
                    <label class="small fw-bold text-muted mb-1">Curriculum Vitae (CV)</label>
                    <input type="file" name="cv" class="form-control form-control-sm">
                </div>
                <div class="mb-2">
                    <label class="small fw-bold text-muted mb-1">Pièce d'identité (Recto/Verso)</label>
                    <input type="file" name="id_card" class="form-control form-control-sm">
                </div>
                <div class="mb-0">
                    <label class="small fw-bold text-muted mb-1">Photo de profil professionnelle</label>
                    <input type="file" name="photo" class="form-control form-control-sm">
                </div>
            </div>

            <div class="row g-2 mb-4">
                <div class="col-md-6">
                    <label class="small fw-bold text-muted mb-1">Mot de passe</label>
                    <input type="password" name="password" class="form-control input-premium w-100" placeholder="••••••••" required>
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold text-muted mb-1">Confirmation</label>
                    <input type="password" name="password_confirmation" class="form-control input-premium w-100" placeholder="••••••••" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-premium w-100 py-3 mb-3">Créer un compte</button>
        </form>
        
        <div class="text-center">
            <p class="text-muted small mb-0">Déjà un compte ? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Se connecter</a></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.to("#registerPanel", {
            y: 0,
            opacity: 1,
            duration: 0.8,
            ease: "power3.out"
        });
    });

    function setRole(role, element) {
        document.getElementById('roleInput').value = role;
        document.querySelectorAll('.role-pill').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        
        // Toggle driver fields
        const driverFields = document.getElementById('driverFields');
        if (role === 'driver') {
            driverFields.style.display = 'block';
            gsap.from(driverFields, { height: 0, opacity: 0, duration: 0.5, ease: "power2.out" });
        } else {
            driverFields.style.display = 'none';
        }
    }
</script>
@endpush
