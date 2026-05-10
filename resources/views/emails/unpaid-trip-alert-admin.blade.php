<!DOCTYPE html>
<html>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
        <h2 style="color: #ea580c; border-bottom: 2px solid #ea580c; padding-bottom: 10px;">⚠️ Course non payée depuis +24h</h2>
        <p>Une course est terminée depuis plus de 24 heures et le paiement n'a toujours pas été validé par le chauffeur dans l'application.</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <ul style="list-style-type: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 10px;"><strong>ID Course :</strong> #{{ $trip->id }}</li>
                <li style="margin-bottom: 10px;"><strong>Date de fin :</strong> {{ $trip->updated_at->format('d/m/Y à H:i') }}</li>
                <li style="margin-bottom: 10px;"><strong>Client :</strong> {{ optional($trip->client)->name ?? 'N/A' }}</li>
                <li style="margin-bottom: 10px;"><strong>Chauffeur :</strong> {{ optional($trip->driver)->name ?? 'N/A' }}</li>
                <li style="margin-bottom: 10px;"><strong>Montant :</strong> {{ number_format($trip->price, 2) }} €</li>
                <li><strong>Temps écoulé :</strong> {{ $hours }} heures</li>
            </ul>
        </div>
        
        <p>Veuillez contacter le chauffeur pour vérifier la situation.</p>
    </div>
</body>
</html>
