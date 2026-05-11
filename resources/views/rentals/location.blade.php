@extends('layouts.app')

@section('title', 'Location de véhicules')

@section('content')
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

    .text-primary-gradient {
        background: linear-gradient(90deg, var(--primary), #60A5FA);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
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
    });
</script>
@endpush
