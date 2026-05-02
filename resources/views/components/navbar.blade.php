<nav class="navbar navbar-expand-lg navbar-aura sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                <span class="text-white fw-bold">A</span>
            </div>
            <span class="fs-3 fw-bold text-primary-gradient" style="letter-spacing: -1px;">ATLAS AND CO</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('prices') }}">Tarifs</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">À Propos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            </ul>
            
            <div class="d-flex align-items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-premium">Tableau de bord</a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted text-decoration-none p-0">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-muted text-decoration-none fw-medium">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-premium">S'inscrire</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
