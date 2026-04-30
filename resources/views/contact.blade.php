@extends('layouts.app')

@section('title', 'Contactez-nous')

@section('content')
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-5">
                <h1 class="display-5 mb-4">Comment pouvons-nous <span class="text-primary-gradient">vous aider ?</span></h1>
                <p class="text-muted mb-5">Vous avez des questions sur notre service, vous souhaitez devenir chauffeur ou explorer un partenariat ? Envoyez-nous un message.</p>
                
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                            <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.304 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="fw-bold">Siège Social</div>
                        <div class="text-muted small">1204 Broadway, New York, NY 10001</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="fw-bold">Email</div>
                        <div class="text-muted small">support@auraride.com</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                            <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.884.511z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="fw-bold">Centre d'appel</div>
                        <div class="text-muted small">+1 (555) 000-0000</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="glass-panel p-5 border-0 shadow-sm">
                    <form action="#" class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Prénom</label>
                            <input type="text" class="form-control border-0 bg-light p-3 rounded-3" placeholder="Jean">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nom</label>
                            <input type="text" class="form-control border-0 bg-light p-3 rounded-3" placeholder="Dupont">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Adresse Email</label>
                            <input type="email" class="form-control border-0 bg-light p-3 rounded-3" placeholder="jean@example.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Sujet</label>
                            <select class="form-select border-0 bg-light p-3 rounded-3">
                                <option selected>Demande générale</option>
                                <option>Candidature chauffeur</option>
                                <option>Partenariat commercial</option>
                                <option>Support technique</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Message</label>
                            <textarea class="form-control border-0 bg-light p-3 rounded-3" rows="5" placeholder="Comment pouvons-nous vous aider ?"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-premium w-100 py-3">Envoyer le message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
