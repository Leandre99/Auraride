{{--
  Une seule modale pour tout le tableau : les modales dans <td> + overflow:hidden cassent Bootstrap (backdrop sans interaction).
--}}
<div class="modal fade" id="assignTripModal" tabindex="-1" aria-labelledby="assignTripModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="assignTripModalTitle">Assigner un chauffeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body pt-3">
                @if($drivers->isEmpty())
                    <p class="text-danger mb-0">Aucun chauffeur actif et approuvé. Passez par <strong>Utilisateurs</strong> pour approuver un chauffeur.</p>
                @else
                    <form id="assignTripForm" method="POST" action="#">
                        @csrf
                        <p class="small text-muted mb-3" id="assignTripModalHint">Choisissez le chauffeur pour cette course.</p>
                        <div class="mb-3">
                            <label for="assignDriverSelect" class="form-label small fw-semibold text-secondary">Chauffeur</label>
                            <select name="driver_id" id="assignDriverSelect" class="form-select form-select-lg rounded-3" required>
                                <option value="">— Choisir —</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}">
                                        {{ $driver->name }} — {{ $driver->vehicle?->model ?? 'Sans véhicule' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold">Confirmer l’assignation</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modalEl = document.getElementById('assignTripModal');
                var form = document.getElementById('assignTripForm');
                if (!modalEl) return;
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl, { backdrop: true, keyboard: true });
                document.querySelectorAll('[data-assign-trip-url]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var url = btn.getAttribute('data-assign-trip-url');
                        if (form && url) form.setAttribute('action', url);
                        modal.show();
                    });
                });
            });
        </script>
    @endpush
@endonce
