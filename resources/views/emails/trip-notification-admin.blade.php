<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle course réservée</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>🔔 Nouvelle course réservée</h2>

    <p><strong>Client :</strong> {{ $user->name }}</p>
    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Téléphone :</strong> {{ $user->phone_number ?? 'Non renseigné' }}</p>

    <h3>📋 Détails de la course</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Départ</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $trip->pickup_address }}</td>
        </tr>
        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Arrivée</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $trip->dropoff_address }}</td>
        </tr>
        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Véhicule</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $vehicleType->name }}</td>
        </tr>
        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Prix</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ number_format($trip->price, 2) }}€</td>
        </tr>
        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Distance</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $trip->distance }} km</td>
        </tr>
    </table>

    <p style="margin-top: 30px;">
        <a href="{{ route('admin.trips') }}" style="background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Voir dans l'admin
        </a>
    </p>
</body>
</html>
