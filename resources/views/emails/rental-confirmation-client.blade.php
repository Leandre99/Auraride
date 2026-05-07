<!DOCTYPE html>
<html>
<head>
    <title>Confirmation location</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Bonjour {{ $user->name }},</h2>

    <p>Nous accusons réception de votre demande de location.</p>

    <h3>📋 Récapitulatif</h3>
    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Véhicule</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $vehicleType->name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Date de début</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Date de fin</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Heure</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $rental->pickup_time }}</td>
        </tr>
        @if($rental->with_driver)
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Option chauffeur</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">✅ Incluse (+150€/jour)</td>
        </tr>
        @endif
        @if($rental->delivery_address)
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Adresse de livraison</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $rental->delivery_address }}</td>
        </tr>
        @endif
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Nombre de jours</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $rental->total_days }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Montant total estimé</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd; font-size: 18px;">
                <strong>{{ number_format($rental->total_price, 2, ',', ' ') }}€</strong>
            </td>
        </tr>
    </table>

    <div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 8px;">
        <p><strong>🔍 Prochaines étapes :</strong></p>
        <ul>
            <li>Nos équipes vérifient la disponibilité du véhicule</li>
            <li>Vous recevrez un appel ou un email sous 24h</li>
            <li>Documents nécessaires : permis de conduire, carte d'identité</li>
        </ul>
    </div>

    <p>Cordialement,<br><strong>ATLAS AND CO</strong></p>
</body>
</html>
