@extends('layouts.app')

@section('title', 'Mon historique')

@section('content')
<div class="container py-5" style="min-height: 80vh;">
    <h2 class="fw-bold mb-4">Mon historique</h2>
    
    <div class="glass-panel p-4 bg-white shadow-sm border-0">
        @if($items->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                <p class="text-muted mb-0">Aucun historique trouvé pour le moment.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Détails</th>
                            <th>Statut</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($item->item_type === 'trip')
                                        <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="bi bi-car-front me-1"></i> Course VTC</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-key me-1"></i> Location</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->item_type === 'trip')
                                        <strong>De :</strong> {{ \Illuminate\Support\Str::limit($item->pickup_address, 30) }}<br>
                                        <strong>À :</strong> {{ \Illuminate\Support\Str::limit($item->dropoff_address, 30) }}
                                    @else
                                        <strong>Véhicule :</strong> {{ optional($item->vehicleType)->name ?? 'N/A' }}<br>
                                        <strong>Période :</strong> {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($item->status) {
                                            'completed' => 'bg-success-subtle text-success border-success',
                                            'confirmed', 'accepted' => 'bg-info-subtle text-info border-info',
                                            'assigned', 'in_progress' => 'bg-primary-subtle text-primary border-primary',
                                            'cancelled', 'rejected' => 'bg-danger-subtle text-danger border-danger',
                                            'pending' => 'bg-warning-subtle text-warning border-warning',
                                            default => 'bg-secondary-subtle text-secondary border-secondary',
                                        };
                                        $statusText = match($item->status) {
                                            'completed' => 'Terminé',
                                            'confirmed' => 'Confirmé',
                                            'accepted' => 'Accepté',
                                            'assigned' => 'Chauffeur assigné',
                                            'in_progress' => 'En cours',
                                            'cancelled' => 'Annulé',
                                            'rejected' => 'Refusé',
                                            'pending' => 'En attente',
                                            default => ucfirst($item->status),
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} border">{{ $statusText }}</span>
                                </td>
                                <td>
                                    @if($item->item_type === 'trip')
                                        <span class="fw-bold">{{ number_format($item->price, 2, ',', ' ') }} €</span>
                                    @else
                                        <span class="fw-bold">{{ number_format($item->total_price ?? $item->daily_price, 2, ',', ' ') }} €</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 d-flex justify-content-center">
                {{ $items->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
