<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $endpoint;
    protected ?string $applicationKey;
    protected ?string $applicationSecret;
    protected ?string $consumerKey;
    protected ?string $serviceName;
    protected string $sender;

    public function __construct()
    {
        $this->endpoint = config('services.ovh_sms.endpoint', 'ovh-eu');
        $this->applicationKey = config('services.ovh_sms.application_key');
        $this->applicationSecret = config('services.ovh_sms.application_secret');
        $this->consumerKey = config('services.ovh_sms.consumer_key');
        $this->serviceName = config('services.ovh_sms.service_name');
        $this->sender = config('services.ovh_sms.sender', 'ATLAS TAXI');
    }

    public function sendSms($to, $message): bool
    {
        if (!$this->isConfigured()) {
            Log::warning("OVH SMS n'est pas configure. Impossible d'envoyer le SMS a {$to}. Message : {$message}");

            return false;
        }

        try {
            $to = $this->normalizeFrenchPhoneNumber($to);
            $path = '/sms/' . rawurlencode($this->serviceName) . '/jobs';
            $url = $this->baseUrl() . $path;
            $body = json_encode([
                'charset' => 'UTF-8',
                'coding' => '7bit',
                'message' => $message,
                'noStopClause' => true,
                'priority' => 'high',
                'receivers' => [$to],
                'sender' => $this->formatSender($this->sender),
                'senderForResponse' => false,
                'validityPeriod' => 2880,
            ], JSON_UNESCAPED_UNICODE);
            $timestamp = $this->timestamp();

            $response = Http::withHeaders([
                'X-Ovh-Application' => $this->applicationKey,
                'X-Ovh-Consumer' => $this->consumerKey,
                'X-Ovh-Timestamp' => (string) $timestamp,
                'X-Ovh-Signature' => $this->signature('POST', $url, $body, $timestamp),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->withBody($body, 'application/json')->post($url);

            if ($response->successful()) {
                Log::info("SMS envoye via OVH a {$to} avec succes.", [
                    'response' => $response->json(),
                ]);

                return true;
            }

            Log::error('Erreur OVH SMS : ' . $response->body(), [
                'status' => $response->status(),
                'to' => $to,
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error("Exception lors de l'envoi du SMS via OVH : " . $e->getMessage());

            return false;
        }
    }

    protected function isConfigured(): bool
    {
        return filled($this->applicationKey)
            && filled($this->applicationSecret)
            && filled($this->consumerKey)
            && filled($this->serviceName);
    }

    protected function normalizeFrenchPhoneNumber(string $phoneNumber): string
    {
        $phoneNumber = preg_replace('/[\s.\-()]/', '', $phoneNumber);

        if (str_starts_with($phoneNumber, '00')) {
            return '+' . substr($phoneNumber, 2);
        }

        if (preg_match('/^0[1-9][0-9]{8}$/', $phoneNumber)) {
            return '+33' . substr($phoneNumber, 1);
        }

        return $phoneNumber;
    }

    protected function formatSender(string $sender): string
    {
        return substr(preg_replace('/[^a-zA-Z0-9 ]/', '', $sender), 0, 11);
    }

    protected function timestamp(): int
    {
        return (int) Http::get($this->baseUrl() . '/auth/time')->body();
    }

    protected function signature(string $method, string $url, string $body, int $timestamp): string
    {
        $data = implode('+', [
            $this->applicationSecret,
            $this->consumerKey,
            $method,
            $url,
            $body,
            $timestamp,
        ]);

        return '$1$' . sha1($data);
    }

    protected function baseUrl(): string
    {
        return match ($this->endpoint) {
            'ovh-ca' => 'https://ca.api.ovh.com/1.0',
            default => 'https://eu.api.ovh.com/1.0',
        };
    }
}
