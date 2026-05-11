@extends('layouts.app')

@section('title', 'Contactez-nous')

@section('content')
    <!-- Executive Header -->
    <header class="executive-header">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 animate__animated animate__fadeInDown" style="letter-spacing: 1px;">SUPPORT 24/7</span>
                    <h1 class="display-3 fw-bold mb-2 animate__animated animate__fadeInLeft">Contactez-<span class="text-primary-gradient">nous</span></h1>
                    <p class="lead opacity-75 pe-lg-5 animate__animated animate__fadeIn animate__delay-1s">Une question ? Un besoin spécifique ? Notre équipe est à votre disposition pour vous accompagner dans tous vos déplacements.</p>
                </div>
            </div>
        </div>
    </header>

    <section class="py-5 bg-white" style="margin-top: -40px; position: relative; z-index: 20;">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-5 animate__animated animate__fadeInLeft">
                    <h2 class="display-5 mb-4 fw-bold">Comment pouvons-nous <span class="text-primary">vous aider ?</span></h2>
                    <p class="text-muted mb-5">Vous avez des questions sur notre service, vous souhaitez devenir chauffeur ou explorer un partenariat ? Envoyez-nous un message.</p>
                    
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                            <i class="bi bi-geo-alt fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Siège Social</div>
                            <div class="text-muted small">29 Avenue Antonin Trinque, 31410 CAPENS</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                            <i class="bi bi-envelope fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Email</div>
                            <a href="mailto:support@atlasandco.com" class="text-primary text-decoration-none small">support@atlasandco.com</a>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                            <i class="bi bi-telephone fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Centre d'appel</div>
                            <a href="tel:651295339" class="text-primary text-decoration-none small">6 51 29 53 39</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-7 animate__animated animate__fadeInRight animate__delay-1s">
                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 animate__animated animate__fadeIn">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 animate__animated animate__fadeIn">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="glass-panel p-5 border-0 shadow-lg bg-white rounded-4">
                        <form action="{{ route('contact.send') }}" method="POST" class="row g-4">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Prénom</label>
                                <input type="text" name="first_name" class="form-control border-0 bg-light p-3 rounded-3" placeholder="Jean" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Nom</label>
                                <input type="text" name="last_name" class="form-control border-0 bg-light p-3 rounded-3" placeholder="Dupont" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small">Adresse Email</label>
                                <input type="email" name="email" class="form-control border-0 bg-light p-3 rounded-3" placeholder="jean@example.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small">Sujet</label>
                                <select name="subject" class="form-select border-0 bg-light p-3 rounded-3" required>
                                    <option value="" selected disabled>Choisissez un sujet</option>
                                    <option value="Demande générale">Demande générale</option>
                                    <option value="Candidature chauffeur">Candidature chauffeur</option>
                                    <option value="Partenariat commercial">Partenariat commercial</option>
                                    <option value="Support technique">Support technique</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small">Message</label>
                                <textarea name="message" class="form-control border-0 bg-light p-3 rounded-3" rows="5" placeholder="Comment pouvons-nous vous aider ?" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-premium w-100 py-3 shadow">Envoyer le message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
