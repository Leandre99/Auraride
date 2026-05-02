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
                <li class="nav-item mx-2"><a class="nav-link fw-bold {{ Request::routeIs('home') ? 'text-primary' : '' }}" href="{{ route('home') }}">Accueil</a></li>
                <li class="nav-item mx-2"><a class="nav-link fw-bold {{ Request::routeIs('prices') ? 'text-primary' : '' }}" href="{{ route('prices') }}">Tarifs</a></li>
                <li class="nav-item mx-2"><a class="nav-link fw-bold {{ Request::routeIs('about') ? 'text-primary' : '' }}" href="{{ route('about') }}">À Propos</a></li>
                <li class="nav-item mx-2"><a class="nav-link fw-bold {{ Request::routeIs('contact') ? 'text-primary' : '' }}" href="{{ route('contact') }}">Contact</a></li>
            </ul>
            
            <div class="d-flex align-items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-premium">Tableau de bord</a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted text-decoration-none p-0">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-muted text-decoration-none fw-medium me-2">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-premium px-4 shadow-sm">S'inscrire</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
