<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de votre course</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; padding: 20px;">
    <p>Bonjour {{ $clientName ?? ($client->name ?? 'Client') }},</p>
    
    <p>Merci d'avoir voyagé avec <strong>Atlas Taxi / VTC</strong> !</p>
    
    <p>Votre paiement a bien été confirmé. Veuillez trouver ci-joint la facture détaillée correspondant à votre course au format PDF.</p>
    
    <p>Vous pouvez également la télécharger à tout moment depuis l'historique de vos réservations sur votre espace client.</p>

    <p>À très bientôt,<br>L'équipe Atlas Taxi / VTC</p>
</body>
</html>
