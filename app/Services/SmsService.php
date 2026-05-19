<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $apiKey;
    protected $sender;

    public function __construct()
    {
        $this->apiKey = config('services.brevo.key');
        $this->sender = config('services.brevo.sms_sender', 'ATLAS VTC'); // 11 caractères max, pas d'espaces spéciaux
    }

    public function sendSms($to, $message)
    {
        if (!$this->apiKey) {
            Log::warning("La clé API Brevo n'est pas configurée. Impossible d'envoyer le SMS à {$to}. Message : {$message}");
            return false;
        }

        try {
            // S'assurer que le numéro commence par le format international sans le "+" ou avec
            // L'API Brevo demande un format international complet, par ex: +33612345678
            if (!str_starts_with($to, '+')) {
                if (preg_match('/^0[1-9][0-9]{8}$/', $to)) {
                    $to = '+33' . substr($to, 1);
                }
            }

            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ])->post('https://api.brevo.com/v3/transactionalSMS/sms', [
                'sender' => substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->sender), 0, 11), // Strictement <= 11 caractères alphanumériques
                'recipient' => $to,
                'content' => $message,
            ]);

            if ($response->successful()) {
                Log::info("SMS envoyé via Brevo à {$to} avec succès.");
                return true;
            } else {
                Log::error("Erreur Brevo SMS : " . $response->body());
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Exception lors de l'envoi du SMS via Brevo : " . $e->getMessage());
            return false;
        }
    }
}
