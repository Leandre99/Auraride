<nav class="navbar navbar-expand-lg navbar-light navbar-aura sticky-top">
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
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item mx-2"><a class="nav-link fw-bold {{ Request::routeIs('home') ? 'text-primary' : '' }}" href="{{ route('home') }}">Accueil</a></li>
                <li class="nav-item mx-2"><a class="nav-link fw-bold {{ Request::routeIs('prices') ? 'text-primary' : '' }}" href="{{ route('prices') }}">Tarifs</a></li>
                <li class="nav-item mx-2"><a class="nav-link fw-bold {{ Request::routeIs('about') ? 'text-primary' : '' }}" href="{{ route('about') }}">À Propos</a></li>
                <li class="nav-item mx-2"><a class="nav-link fw-bold {{ Request::routeIs('contact') ? 'text-primary' : '' }}" href="{{ route('contact') }}">Contact</a></li>
            </ul>

            {{-- Menu utilisateur --}}
            <div class="d-flex align-items-center gap-3 flex-shrink-0 ms-lg-auto pb-3 pb-lg-0">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none dropdown-toggle p-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background: none; border: none;">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <span class="fw-bold small">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                            <div class="text-start d-none d-md-block">
                                <span class="d-block fw-semibold small text-dark">{{ Auth::user()->name }}</span>
                                <span class="d-block text-muted small" style="font-size: 0.7rem;">
                                    @if(Auth::user()->role === 'client')
                                        👤 Client
                                    @elseif(Auth::user()->role === 'driver')
                                        🚗 Chauffeur
                                    @elseif(Auth::user()->role === 'admin')
                                        👑 Admin
                                    @endif
                                </span>
                            </div>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width: 220px;">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-circle"></i> Mon profil
                                </a>
                            </li>

                            @if(Auth::user()->role === 'client')
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('my.rentals') }}">
                                    <i class="bi bi-car-front"></i> Mon historique
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('client.dashboard') }}">
                                    <i class="bi bi-clock-history"></i> Mes courses
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->role === 'driver')
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('driver.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Tableau de bord
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->role === 'admin')
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-shield-lock-fill"></i> Administration
                                </a>
                            </li>
                            @endif

                            <li><hr class="dropdown-divider my-1"></li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="dropdown-item p-0 m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Se déconnecter
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-muted text-decoration-none fw-medium">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-premium px-4 shadow-sm text-nowrap">S'inscrire</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
