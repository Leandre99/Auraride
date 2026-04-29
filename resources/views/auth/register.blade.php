@extends('layouts.app')

@section('title', 'Sign Up - Next-Gen Transit')

@push('styles')
<style>
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 80px); /* Subtract navbar height */
        position: relative;
        z-index: 10;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    
    .auth-panel {
        width: 100%;
        max-width: 450px;
        padding: 40px;
    }
    
    .input-dark {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #FFF;
        padding: 16px 20px;
        border-radius: 12px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    
    .input-dark:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--electric-cyan);
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
        color: #FFF;
        outline: none;
    }
    
    .input-dark::placeholder {
        color: var(--text-muted);
    }
    
    /* Role Selection Pills */
    .role-selection {
        display: flex;
        gap: 10px;
    }
    .role-pill {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 12px;
        border-radius: 12px;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
        text-align: center;
    }
    .role-pill:hover, .role-pill.active {
        border-color: var(--neon-pink);
        color: #fff;
        background: rgba(255, 30, 131, 0.1);
        box-shadow: 0 0 15px rgba(255, 30, 131, 0.3);
    }
</style>
@endpush

@section('content')

<div class="auth-container">
    <div class="auth-panel glass-panel" id="registerPanel" style="opacity: 0; transform: translateY(30px);">
        <div class="text-center mb-4">
            <h2 class="mb-1">Join <span class="neon-text-primary">AuraRide</span></h2>
            <p class="text-muted">Create an account to begin your journey.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="background: rgba(255,0,0,0.1); border-color: rgba(255,0,0,0.3); color:#ff6b6b;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            
            <div class="mb-3">
                <input type="text" name="name" class="form-control input-dark w-100" placeholder="Full Name" required value="{{ old('name') }}">
            </div>
            
            <div class="mb-3">
                <input type="email" name="email" class="form-control input-dark w-100" placeholder="Email Address" required value="{{ old('email') }}">
            </div>
            
            <div class="mb-3">
                <input type="password" name="password" class="form-control input-dark w-100" placeholder="Password" required>
            </div>
            
            <div class="mb-4">
                <input type="password" name="password_confirmation" class="form-control input-dark w-100" placeholder="Confirm Password" required>
            </div>
            
            <div class="mb-4">
                <label class="text-muted mb-2 small">I am registering as a:</label>
                <div class="role-selection">
                    <div class="role-pill active" onclick="setRole('client', this)">Client</div>
                    <div class="role-pill" onclick="setRole('driver', this)">Driver</div>
                </div>
                <input type="hidden" name="role" id="roleInput" value="client">
            </div>
            
            <button type="submit" class="btn-glow-pink w-100 mb-3">Create Account</button>
        </form>
        
        <div class="text-center mt-3">
            <p class="text-muted small">Already have an account? <a href="{{ route('login') }}" class="neon-text-primary text-decoration-none">Login</a></p>
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
            duration: 1,
            ease: "power3.out",
            delay: 0.2
        });
    });

    function setRole(role, element) {
        document.getElementById('roleInput').value = role;
        document.querySelectorAll('.role-pill').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }
</script>
@endpush
