@extends('layouts.app')

@section('title', 'Login - Next-Gen Transit')

@push('styles')
<style>
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 80px); /* Subtract navbar height */
        position: relative;
        z-index: 10;
    }
    
    .auth-panel {
        width: 100%;
        max-width: 400px;
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
</style>
@endpush

@section('content')
<!-- Abstract Animated Background (Optional, using Leaflet map div if it's placed in layouts maybe, but here just the form) -->

<div class="auth-container">
    <div class="auth-panel glass-panel" id="loginPanel" style="opacity: 0; transform: translateY(30px);">
        <div class="text-center mb-4">
            <h2 class="mb-1">Welcome Back</h2>
            <p class="text-muted">Enter your details to access <span class="neon-text-primary">AuraRide</span>.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger" style="background: rgba(255,0,0,0.1); border-color: rgba(255,0,0,0.3); color:#ff6b6b;">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3 position-relative">
                <input type="email" name="email" class="form-control input-dark w-100" placeholder="Email Address" required>
            </div>
            
            <div class="mb-4 position-relative">
                <input type="password" name="password" class="form-control input-dark w-100" placeholder="Password" required>
            </div>
            
            <button type="submit" class="btn-glow-cyan w-100 mb-3">Login Securely</button>
        </form>
        
        <div class="text-center mt-3">
            <p class="text-muted small">Don't have an account? <a href="{{ route('register') }}" class="neon-text-secondary text-decoration-none">Sign Up</a></p>
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
            duration: 1,
            ease: "power3.out",
            delay: 0.2
        });
    });
</script>
@endpush
