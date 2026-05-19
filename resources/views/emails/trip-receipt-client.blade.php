<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de votre course</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f3f4f6; color: #1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f3f4f6; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #2563eb; padding: 20px; text-align: left; color: #ffffff;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td valign="middle" width="50">
                                        <div style="background-color: #ffffff; color: #2563eb; width: 40px; height: 40px; border-radius: 50%; text-align: center; line-height: 40px; font-size: 24px; font-weight: bold; margin-right: 15px;">A</div>
                                    </td>
                                    <td valign="middle">
                                        <h1 style="margin: 0; font-size: 24px; font-weight: bold;">Atlas Taxi / VTC</h1>
                                    </td>
                                    <td valign="middle" align="right">
                                        <span style="font-size: 14px; opacity: 0.9;">Reçu #{{ str_pad($trip->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Client and Date info -->
                    <tr>
                        <td style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="left" style="font-size: 16px; font-weight: bold; color: #374151;">
                                        Client : {{ $trip->client->name ?? $client->name ?? 'Client' }}
                                    </td>
                                    <td align="right" style="font-size: 14px; color: #6b7280;">
                                        {{ $trip->updated_at ? $trip->updated_at->format('d/m/Y à H:i') : $trip->created_at->format('d/m/Y à H:i') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Confirmation Banner -->
                    <tr>
                        <td style="padding: 20px 20px 0 20px;">
                            <div style="background-color: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; text-align: center; font-weight: bold; font-size: 16px;">
                                Paiement confirmé par votre chauffeur
                            </div>
                        </td>
                    </tr>

                    <!-- Route Block -->
                    <tr>
                        <td style="padding: 20px;">
                            <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td width="30" valign="top" style="padding-top: 4px;">
                                            <div style="width: 12px; height: 12px; border-radius: 50%; background-color: #3b82f6; margin: 0 auto;"></div>
                                        </td>
                                        <td style="padding-bottom: 20px; font-size: 15px; color: #374151;">
                                            <strong>Départ</strong><br>
                                            {{ $trip->pickup_address }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="30" valign="middle">
                                            <div style="width: 2px; height: 30px; background-color: transparent; border-left: 2px dashed #9ca3af; margin: 0 auto; margin-top: -15px; margin-bottom: 5px;"></div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td width="30" valign="top" style="padding-top: 4px;">
                                            <div style="width: 12px; height: 12px; border-radius: 50%; background-color: #f59e0b; margin: 0 auto;"></div>
                                        </td>
                                        <td style="font-size: 15px; color: #374151;">
                                            <strong>Destination</strong><br>
                                            {{ $trip->dropoff_address }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>

                    <!-- Details Table -->
                    <tr>
                        <td style="padding: 0 20px 20px 20px;">
                            <table width="100%" cellpadding="12" cellspacing="0" border="0" style="border-collapse: collapse;">
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="color: #6b7280; font-size: 14px; width: 40%;">Chauffeur</td>
                                    <td style="color: #1f2937; font-size: 15px; text-align: right; font-weight: bold;">
                                        {{ $trip->driver->name ?? 'Non assigné' }}
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="color: #6b7280; font-size: 14px;">Véhicule</td>
                                    <td style="color: #1f2937; font-size: 15px; text-align: right; font-weight: bold;">
                                        {{ $trip->vehicle->model ?? $trip->vehicleType->name ?? 'Standard' }}
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="color: #6b7280; font-size: 14px;">Méthode de paiement</td>
                                    <td style="color: #1f2937; font-size: 15px; text-align: right; font-weight: bold;">
                                        {{ $trip->payment_method === 'cash' ? 'En main propre' : ($trip->payment_method === 'card' ? 'Par terminal' : ucfirst($trip->payment_method)) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #1f2937; font-size: 18px; font-weight: bold; padding-top: 20px;">Total payé</td>
                                    <td style="color: #2563eb; font-size: 24px; text-align: right; font-weight: bold; padding-top: 20px;">
                                        {{ number_format($trip->price, 2, ',', ' ') }} €
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer Details -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0; font-size: 14px; color: #6b7280;">
                                Vous avez une question ? Contactez-nous :<br>
                                <a href="mailto:contact@atlasandco.com" style="color: #2563eb; text-decoration: none;">contact@atlasandco.com</a> | +33 1 23 45 67 89
                            </p>
                        </td>
                    </tr>
                    <!-- Bottom Bar -->
                    <tr>
                        <td style="background-color: #e5e7eb; padding: 12px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #6b7280;">
                                Ce reçu est généré automatiquement. Conservez-le comme preuve de paiement.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
