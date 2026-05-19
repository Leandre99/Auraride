<!-- Footer -->
<footer class="bg-dark text-white py-5 mt-auto">
    <div class="container py-4">
        <div class="row g-5">
            <div class="col-lg-4">
                <h3 class="fw-bold mb-4">ATLAS TAXI / VTC</h3>
                <p class="opacity-50 mb-4">L'excellence du transport privé et de la location de prestige. Redéfinir la
                    mobilité urbaine avec style et sophistication.</p>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-telephone text-primary"></i>
                    <a href="tel:651295339" class="text-white text-decoration-none small">6 51 29 53 39</a>
                </div>
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-geo-alt text-primary"></i>
                    <span class="text-white opacity-75 small">29 Avenue Antonin Trinque, 31410 CAPENS</span>
                </div>
                <div class="d-flex gap-3">
                    <a href="#" class="text-white opacity-50 hover-opacity-100 fs-4"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white opacity-50 hover-opacity-100 fs-4"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white opacity-50 hover-opacity-100 fs-4"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <h6 class="fw-bold mb-4">SERVICES</h6>
                <ul class="list-unstyled opacity-50">
                    <li class="mb-2"><a href="{{ route('dashboard') }}" class="text-white text-decoration-none">VTC Premium</a></li>
                    <li class="mb-2"><a href="{{ route('location') }}" class="text-white text-decoration-none">Location Luxe</a></li>
                    <li class="mb-2"><a href="{{ route('dashboard') }}" class="text-white text-decoration-none">Transferts Aéroport</a></li>
                    <li><a href="{{ route('contact') }}" class="text-white text-decoration-none">Événementiel</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-lg-2">
                <h6 class="fw-bold mb-4">SOCIÉTÉ</h6>
                <ul class="list-unstyled opacity-50">
                    <li class="mb-2"><a href="{{ route('about') }}" class="text-white text-decoration-none">À propos</a>
                    </li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Carrières</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}"
                            class="text-white text-decoration-none">Contact</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Blog</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-lg-4">
                <h6 class="fw-bold mb-4">NEWSLETTER</h6>
                <p class="opacity-50 small mb-4">Inscrivez-vous pour recevoir nos offres exclusives et actualités.</p>
                <form onsubmit="event.preventDefault(); alert('Merci pour votre inscription à notre newsletter !');" class="input-group">
                    <input type="email"
                        class="form-control bg-white bg-opacity-10 border-0 text-white p-3 rounded-start"
                        placeholder="Votre email" required>
                    <button type="submit" class="btn btn-primary px-4 rounded-end">OK</button>
                </form>
            </div>
        </div>
        <hr class="my-5 opacity-10">
        <div class="row align-items-center">
            <div class="col-md-6 opacity-50 small">
                &copy; 2026 ATLAS TAXI / VTC. Tous droits réservés.
            </div>
            <div class="col-md-6 text-md-end opacity-50 small">
                <a href="#" class="text-white text-decoration-none me-3">Confidentialité</a>
                <a href="#" class="text-white text-decoration-none">Mentions Légales</a>
            </div>
        </div>
    </div>
</footer>
