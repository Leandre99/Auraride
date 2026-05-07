@extends('layouts.app')

@section('title', 'Révolutionner le transport urbain')

@section('content')
<!-- Hero Section (id pour cibler les animations sans toucher à la navbar) -->
<section id="hero" class="py-5 position-relative overflow-hidden" style="background: linear-gradient(180deg, #FFFFFF 0%, var(--bg-light) 100%);">
    <div class="container position-relative z-index-10 py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3" style="font-size: 1.1rem; letter-spacing: 1px;">VTC Premium</span>
                <h1 class="display-3 mb-4">Le transport redéfini pour le <span class="text-primary-gradient">Monde Moderne.</span></h1>
                <p class="lead text-muted mb-5 pe-lg-5">ATLAS AND CO combine le luxe haut de gamme et la technologie de pointe pour vous offrir une expérience de voyage fluide, sûre et sophistiquée.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('register') }}" class="btn btn-premium px-5 py-3">Commencer</a>
                    <a href="#features" class="btn btn-outline-premium px-5 py-3">Explorer les services</a>
                </div>

                <div class="mt-5 d-flex align-items-center gap-4">
                    <div class="d-flex">
                        <img src="https://i.pravatar.cc/150?img=1" class="rounded-circle border border-2 border-white" style="width: 40px; height: 40px; margin-right: -15px;" alt="">
                        <img src="https://i.pravatar.cc/150?img=2" class="rounded-circle border border-2 border-white" style="width: 40px; height: 40px; margin-right: -15px;" alt="">
                        <img src="https://i.pravatar.cc/150?img=3" class="rounded-circle border border-2 border-white" style="width: 40px; height: 40px;" alt="">
                    </div>
                    <div class="small text-muted">
                        Plus de <span class="fw-bold text-dark">500k</span> utilisateurs font confiance à ATLAS AND CO
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="position-relative">
                    <div class="rounded-4 overflow-hidden shadow-lg" style="transform: rotate(2deg);">
                        <img src="{{ asset('images/tesla-hero.png') }}" class="w-100" alt="ATLAS AND CO Premium">
                    </div>
                    <div class="glass-panel p-4 position-absolute" style="bottom: -30px; left: -30px; width: 250px;">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-circle bg-success" style="width: 10px; height: 10px;"></div>
                            <div class="small fw-bold">Chauffeur en route</div>
                        </div>
                        <div class="h5 mb-0">3 Minutes</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="h1 mb-3">Pourquoi choisir ATLAS AND CO ?</h2>
            <p class="text-muted">Découvrez le sommet de la mobilité urbaine.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-5 glass-panel h-100 border-0 shadow-sm hover-translate-up transition">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-shield-check" viewBox="0 0 16 16">
                            <path d="M8 14.933a.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067v13.866zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                            <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </div>
                    <h4>Sécurité Maximale</h4>
                    <p class="text-muted mb-0">Chauffeurs rigoureusement sélectionnés et suivi en temps réel de chaque trajet 24/7.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-5 glass-panel h-100 border-0 shadow-sm hover-translate-up transition">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-lightning-charge" viewBox="0 0 16 16">
                            <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09zM4.157 8.5H7a.5.5 0 0 1 .478.647L6.11 13.59l5.733-6.09H9a.5.5 0 0 1-.478-.647L9.89 2.41 4.157 8.5z"/>
                        </svg>
                    </div>
                    <h4>Rapidité d'intervention</h4>
                    <p class="text-muted mb-0">Notre algorithme avancé garantit qu'un chauffeur arrive chez vous en quelques minutes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-5 glass-panel h-100 border-0 shadow-sm hover-translate-up transition">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957-2.939-2.801 4.074-.58L8 1.426l1.797 3.674 4.074.58-2.939 2.801.694 3.957-3.685-1.894z"/>
                        </svg>
                    </div>
                    <h4>Flotte Premium</h4>
                    <p class="text-muted mb-0">Seuls les meilleurs véhicules. Des berlines électriques aux SUVs de luxe.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-primary text-white">
    <div class="container py-5">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="display-5 fw-bold mb-1">10k+</div>
                <div class="small opacity-75">Courses quotidiennes</div>
            </div>
            <div class="col-md-3">
                <div class="display-5 fw-bold mb-1">4.9/5</div>
                <div class="small opacity-75">Note moyenne</div>
            </div>
            <div class="col-md-3">
                <div class="display-5 fw-bold mb-1">15+</div>
                <div class="small opacity-75">Villes couvertes</div>
            </div>
            <div class="col-md-3">
                <div class="display-5 fw-bold mb-1">2k+</div>
                <div class="small opacity-75">Chauffeurs actifs</div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-2">NOS SERVICES</span>
            <h2 class="display-5 fw-bold">Solutions de Mobilité <span class="text-primary-gradient">Sur Mesure</span></h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="glass-panel p-5 h-100 border-0 shadow-sm bg-white hover-up transition">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                        <i class="bi bi-car-front fs-1"></i>
                    </div>
                    <h3 class="fw-bold mb-3">VTC Premium</h3>
                    <p class="text-muted mb-4">Commandez un chauffeur privé en quelques secondes. Voyagez dans des véhicules de luxe avec un service irréprochable.</p>
                    <div class="small text-muted mb-3 p-3 rounded-3 bg-light"><strong>VTC / taxi premium</strong> : vous êtes conduit par un chauffeur sur un trajet ponctuel (départ → arrivée).</div>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> Toujours <strong>avec chauffeur</strong></li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> Chauffeurs professionnels &amp; véhicules haut de gamme</li>
                        <li><i class="bi bi-check2-circle text-primary me-2"></i> Disponibilité 24/7</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-outline-premium w-100 py-3">Réserver une course avec chauffeur</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass-panel p-5 h-100 border-0 shadow-sm bg-white hover-up transition">
                    <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                        <i class="bi bi-key fs-1"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Location de véhicules</h3>
                    <div class="small text-muted mb-3 p-3 rounded-3 bg-light"><strong>Location</strong> : vous réservez un véhicule pour une <strong>période</strong> (dates de début / fin). Vous pouvez choisir <strong>avec ou sans chauffeur</strong>, selon offre.</div>
                    <p class="text-muted mb-4">Louez nos véhicules d’exception pour vos déplacements personnels ou pros — ce n’est pas une course ponctuelle type taxi.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-warning me-2"></i> Flotte de prestige</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-warning me-2"></i> Durée flexible (jour/semaine)</li>
                        <li><i class="bi bi-check2-circle text-warning me-2"></i> Assurance premium incluse</li>
                    </ul>
                    <a href="#rental" class="btn btn-outline-premium w-100 py-3 border-warning text-warning" style="--primary: #F59E0B;">Louer un véhicule</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Car Rental Booking Section -->
<section id="rental" class="py-5 bg-white">
    <div class="container py-5">
        <div class="glass-panel p-0 overflow-hidden border-0 shadow-lg">
            <div class="row g-0">
                <div class="col-lg-5 bg-dark p-5 text-white d-flex flex-column justify-content-center">
                    <h2 class="display-6 fw-bold mb-4">Louez votre <span class="text-primary-gradient">Liberté</span></h2>
                    <p class="opacity-75 mb-5">Sélectionnez votre modèle et vos dates. La location couvre une <strong>période</strong> ; précisez à l'agent si vous souhaitez le véhicule <strong>avec chauffeur</strong> ou en <strong>conduite autonome</strong>.</p>

                    <div class="d-flex gap-4 mb-4">
                        <div class="text-center vehicle-type-btn active" data-type="1">
                            <i class="bi bi-car-front fs-2 d-block mb-1"></i>
                            <span class="small fw-bold">Berline Standard</span>
                        </div>
                        <div class="text-center vehicle-type-btn" data-type="2">
                            <i class="bi bi-people fs-2 d-block mb-1"></i>
                            <span class="small fw-bold">Van Luxe</span>
                        </div>
                        <div class="text-center vehicle-type-btn" data-type="3">
                            <i class="bi bi-bus-front fs-2 d-block mb-1"></i>
                            <span class="small fw-bold">Sprinter</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 p-5">
                    <form id="rentalForm" action="{{ route('rentals.store') }}" method="POST" class="row g-4">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">DATE DE DÉBUT</label>
                            <input type="date" name="start_date" id="startDate" class="form-control p-3 border-light rounded-3 bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">DATE DE FIN</label>
                            <input type="date" name="end_date" id="endDate" class="form-control p-3 border-light rounded-3 bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">HEURE DE PRISE EN CHARGE</label>
                            <input type="time" name="pickup_time" class="form-control p-3 border-light rounded-3 bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">TYPE DE VÉHICULE</label>
                            <select class="form-select p-3 border-light rounded-3 bg-light" name="vehicle_type_id" id="vehicleSelect" required>
                                <option value="1">Berline Standard (Tesla / Toyota)</option>
                                <option value="2">Van Luxe (Mercedes V-Class)</option>
                                <option value="3">Sprinter Mercedes (9 places)</option>
                            </select>
                        </div>

                        <!-- Adresse de livraison (optionnel) -->
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">ADRESSE DE LIVRAISON (optionnel)</label>
                            <input type="text" name="delivery_address" id="deliveryAddress" class="form-control p-3 border-light rounded-3 bg-light"
                                   placeholder="Laissez vide pour un retrait en agence">
                            <small class="text-muted">La livraison à domicile peut entraîner un supplément.</small>
                        </div>

                        <!-- Option chauffeur -->
                        <div class="col-12">
                            <div class="form-check p-4 rounded-3 bg-light border border-light mt-3">
                                <input class="form-check-input mt-2" type="checkbox" value="1" name="with_driver" id="rentalWithDriver">
                                <label class="form-check-label small mb-0" for="rentalWithDriver">
                                    <strong>Inclure un chauffeur</strong> (+150,00€/jour) pour toute la période de location
                                </label>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 mb-0">Estimation :</span>
                                <div class="text-end">
                                    <span class="h6 text-muted mb-0 d-block" id="rentalDetails">(1 jour)</span>
                                    <span class="h4 mb-0 text-primary fw-bold" id="rentalPrice">100,00€</span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-premium w-100 py-3" id="rentalSubmitBtn">
                                Louer ce véhicule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-5 mt-5">
    <div class="container py-4">
        <div class="row g-5">
            <div class="col-lg-4">
                <h3 class="fw-bold mb-4">ATLAS AND CO</h3>
                <p class="opacity-50 mb-4">L'excellence du transport privé et de la location de prestige. Redéfinir la mobilité urbaine avec style et sophistication.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-white opacity-50 hover-opacity-100 fs-4"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white opacity-50 hover-opacity-100 fs-4"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white opacity-50 hover-opacity-100 fs-4"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <h6 class="fw-bold mb-4">SERVICES</h6>
                <ul class="list-unstyled opacity-50">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">VTC Premium</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Location Luxe</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Transferts Aéroport</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Événementiel</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-lg-2">
                <h6 class="fw-bold mb-4">SOCIÉTÉ</h6>
                <ul class="list-unstyled opacity-50">
                    <li class="mb-2"><a href="{{ route('about') }}" class="text-white text-decoration-none">À propos</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Carrières</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}" class="text-white text-decoration-none">Contact</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Blog</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-lg-4">
                <h6 class="fw-bold mb-4">NEWSLETTER</h6>
                <p class="opacity-50 small mb-4">Inscrivez-vous pour recevoir nos offres exclusives et actualités.</p>
                <div class="input-group">
                    <input type="email" class="form-control bg-white bg-opacity-10 border-0 text-white p-3 rounded-start" placeholder="Votre email">
                    <button class="btn btn-primary px-4 rounded-end">OK</button>
                </div>
            </div>
        </div>
        <hr class="my-5 opacity-10">
        <div class="row align-items-center">
            <div class="col-md-6 opacity-50 small">
                &copy; 2026 ATLAS AND CO. Tous droits réservés.
            </div>
            <div class="col-md-6 text-md-end opacity-50 small">
                <a href="#" class="text-white text-decoration-none me-3">Confidentialité</a>
                <a href="#" class="text-white text-decoration-none">Mentions Légales</a>
            </div>
        </div>
    </div>
</footer>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .vehicle-type-btn {
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 15px;
        border-radius: 20px;
        border: 2px solid transparent;
        background: rgba(255, 255, 255, 0.05);
        flex: 1;
    }

    .vehicle-type-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .vehicle-type-btn.active {
        border-color: var(--primary);
        background: rgba(37, 99, 235, 0.1);
    }

    .transition {
        transition: all 0.3s ease;
    }

    .hover-up:hover {
        transform: translateY(-10px);
    }

    .text-primary-gradient {
        background: linear-gradient(90deg, var(--primary), #60A5FA);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hover-translate-up:hover {
        transform: translateY(-10px);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Éléments DOM
        const vehicleBtns = document.querySelectorAll('.vehicle-type-btn');
        const rentalPriceEl = document.getElementById('rentalPrice');
        const rentalDetailsEl = document.getElementById('rentalDetails');
        const vehicleSelect = document.getElementById('vehicleSelect');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const withDriverCheckbox = document.getElementById('rentalWithDriver');

        // Prix journaliers par véhicule (en euros)
        const dailyPrices = {
            '1': 100,  // Berline
            '2': 300,  // Van
            '3': 350   // Sprinter
        };

        // Supplément chauffeur par jour
        const DRIVER_FEE_PER_DAY = 150;

        // Véhicule actuellement sélectionné
        let currentVehicleType = '1';

        // Fonction pour calculer le nombre de jours entre deux dates
        function calculateDays(startDate, endDate) {
            if (!startDate || !endDate) return 1;

            const start = new Date(startDate);
            const end = new Date(endDate);

            if (isNaN(start.getTime()) || isNaN(end.getTime())) return 1;
            if (end < start) return 0;

            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 pour inclure le dernier jour
            return diffDays;
        }

        // Fonction pour mettre à jour le prix affiché
        function updatePrice() {
            const dailyPrice = dailyPrices[currentVehicleType];
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            const withDriver = withDriverCheckbox.checked;

            let days = calculateDays(startDate, endDate);

            // Validation : si date de fin < date de début
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                if (end < start) {
                    rentalPriceEl.innerText = '0,00€';
                    rentalDetailsEl.innerHTML = '(Dates invalides)';
                    return;
                }
            }

            // Calcul du prix total
            let totalPrice = dailyPrice * days;
            let driverText = '';

            if (withDriver) {
                totalPrice += DRIVER_FEE_PER_DAY * days;
                driverText = ` + ${DRIVER_FEE_PER_DAY}€/jour chauffeur`;
            }

            // Formatage du prix
            const formattedPrice = totalPrice.toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            rentalPriceEl.innerText = `${formattedPrice}€`;

            // Affichage des détails
            const dayText = days === 1 ? '1 jour' : `${days} jours`;
            rentalDetailsEl.innerHTML = `(${dayText} × ${dailyPrice}€/jour${driverText})`;

            // Animation GSAP
            gsap.from(rentalPriceEl, { scale: 1.2, duration: 0.3, ease: "back.out" });
        }

        // Mise à jour lors du changement de véhicule (boutons)
        vehicleBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                vehicleBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentVehicleType = this.getAttribute('data-type');
                vehicleSelect.value = currentVehicleType;
                updatePrice();
                gsap.from(rentalPriceEl, { scale: 1.2, duration: 0.3, ease: "back.out" });
            });
        });

        // Mise à jour lors du changement de véhicule (select)
        vehicleSelect.addEventListener('change', function() {
            currentVehicleType = this.value;
            vehicleBtns.forEach(b => {
                if(b.getAttribute('data-type') === currentVehicleType) b.classList.add('active');
                else b.classList.remove('active');
            });
            updatePrice();
        });

        // Mise à jour lors du changement de dates
        startDateInput.addEventListener('change', updatePrice);
        endDateInput.addEventListener('change', updatePrice);

        // Mise à jour lors du changement de l'option chauffeur
        withDriverCheckbox.addEventListener('change', updatePrice);

        // Validation des dates côté client
        function validateDates() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);

                if (end < start) {
                    endDateInput.setCustomValidity("La date de fin ne peut pas être antérieure à la date de début");
                    return false;
                } else {
                    endDateInput.setCustomValidity("");
                    return true;
                }
            }
            return true;
        }

        startDateInput.addEventListener('change', validateDates);
        endDateInput.addEventListener('change', () => {
            validateDates();
            updatePrice();
        });

        // Initialisation du prix au chargement
        updatePrice();

        // Sélectionner la date du jour comme minimum pour start_date
        const today = new Date().toISOString().split('T')[0];
        startDateInput.min = today;
        endDateInput.min = today;

        // Mise à jour du min de end_date quand start_date change
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
        });

        // Rental Form Submission
        const rentalForm = document.getElementById('rentalForm');
        if(rentalForm) {
            rentalForm.addEventListener('submit', async (e) => {
                // Validation finale des dates
                if (!validateDates()) {
                    e.preventDefault();
                    alert('Veuillez corriger les dates : la date de fin ne peut pas être antérieure à la date de début.');
                    return;
                }

                e.preventDefault();
                @if(!auth()->check())
                    window.location.href = "{{ route('login') }}";
                    return;
                @endif

                const submitBtn = document.getElementById('rentalSubmitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi en cours...';

                try {
                    const fd = new FormData(rentalForm);
                    if (!document.getElementById('rentalWithDriver').checked) {
                        fd.delete('with_driver');
                    }
                    const res = await fetch(rentalForm.action, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: fd,
                    });
                    const data = await res.json().catch(() => ({}));
                    if (res.ok && data.success) {
                        alert('Votre demande de location a bien été envoyée ! Un agent ATLAS AND CO vous contactera pour confirmer la période et l’option chauffeur éventuelle.');
                        rentalForm.reset();
                        // Réinitialiser les valeurs par défaut
                        startDateInput.min = today;
                        endDateInput.min = today;
                        currentVehicleType = '1';
                        vehicleSelect.value = '1';
                        vehicleBtns.forEach(b => {
                            if(b.getAttribute('data-type') === '1') b.classList.add('active');
                            else b.classList.remove('active');
                        });
                        withDriverCheckbox.checked = false;
                        updatePrice();
                    } else {
                        const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Erreur ' + res.status);
                        alert('Une erreur est survenue : ' + msg);
                    }
                } catch (error) {
                    alert('Une erreur est survenue. Réessayez plus tard.');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Réserver la Location';
                }
            });
        }

        // Hero animations
        gsap.from("#hero .display-3", { y: 50, opacity: 0, duration: 1, delay: 0.2 });
        gsap.from("#hero .lead", { y: 30, opacity: 0, duration: 1, delay: 0.4 });
        gsap.from("#hero .btn-premium, #hero .btn-outline-premium", { y: 20, opacity: 0, duration: 1, delay: 0.6, stagger: 0.2 });
    });
</script>
@endpush
