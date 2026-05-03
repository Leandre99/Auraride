@php
    $btnClass = $buttonClass ?? 'btn btn-primary btn-sm rounded-pill px-3';
    $btnLabel = $buttonLabel ?? 'Assigner chauffeur';
@endphp
<button type="button" class="{{ $btnClass }}" data-assign-trip-url="{{ route('trips.assign', $trip) }}">{{ $btnLabel }}</button>
