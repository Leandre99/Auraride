<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle demande de location</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>🔔 Nouvelle demande de location</h2>

    <p><strong>Client :</strong> {{ $user->name }}</p>
    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Téléphone :</strong> {{ $user->phone_number ?? 'Non renseigné' }}</p>

    <h3>📋 Détails de la demande</h3>
    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Véhicule</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $vehicleType->name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Dates</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">
                {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }} →
                {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Chauffeur</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $rental->with_driver ? '✅ Oui' : '❌ Non' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Livraison</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $rental->delivery_address ?: 'Retrait en agence' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Montant total</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ number_format($rental->total_price, 2, ',', ' ') }}€</td>
        </tr>
    </table>

    <div style="margin-top: 30px;">
        <a href="{{ url('/admin/rentals/' . $rental->id . '/edit') }}"
           style="background-color: #2563EB; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
           Traiter cette demande
        </a>
    </div>

    <p style="margin-top: 30px;">Cordialement,<br>ATLAS AND CO</p>
</body>
</html>
