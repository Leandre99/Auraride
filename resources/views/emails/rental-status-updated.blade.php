<!DOCTYPE html>
<html>
<head>
    <title>Mise à jour de votre demande de location</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Bonjour {{ $rental->user->name }},</h2>

    <p>Le statut de votre demande de location a été mis à jour.</p>

    <h3>📋 Récapitulatif</h3>
    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Véhicule</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $rental->vehicleType->name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Dates</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">
                {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }} →
                {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Nouveau statut</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">
                @if($rental->status == 'confirmed')
                    ✅ Confirmée
                @elseif($rental->status == 'rejected')
                    ❌ Refusée
                @elseif($rental->status == 'cancelled')
                    🔴 Annulée
                @else
                    ⏳ En attente
                @endif
            </td>
        </tr>
    </table>

    @if($rental->admin_notes)
    <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px;">
        <p><strong>📝 Message de l'administrateur :</strong></p>
        <p>{{ $rental->admin_notes }}</p>
    </div>
    @endif

    <p style="margin-top: 30px;">
        Cordialement,<br>
        <strong>ATLAS AND CO</strong>
    </p>
</body>
</html>
