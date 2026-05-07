@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Demandes de location</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Véhicule</th>
                <th>Dates</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rentals as $rental)
            <tr>
                <td>{{ $rental->id }}</td>
                <td>{{ $rental->user->name }}</td>
                <td>{{ $rental->vehicleType->name }}</td>
                <td>{{ $rental->start_date->format('d/m/Y') }} → {{ $rental->end_date->format('d/m/Y') }}</td>
                <td>{{ number_format($rental->total_price, 2) }}€</td>
                <td>
                    <span class="badge bg-{{ $rental->status == 'pending' ? 'warning' : ($rental->status == 'confirmed' ? 'success' : 'danger') }}">
                        {{ $rental->status }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.rentals.edit', $rental->id) }}" class="btn btn-sm btn-primary">Traiter</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    \]

    {{ $rentals->links() }}
</div>
@endsection
