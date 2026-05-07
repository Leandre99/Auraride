<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de votre course</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Bonjour {{ $user->name }},</h2>

    <p>Votre course a bien été réservée !</p>

    <h3>📋 Récapitulatif</h3>
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
    </table>

    <p style="margin-top: 30px;">Un chauffeur va vous être assigné sous peu.</p>
    <p>Cordialement,<br><strong>ATLAS AND CO</strong></p>
</body>
</html>
