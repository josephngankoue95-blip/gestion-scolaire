<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NexahNotificationService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $senderId;

    public function __construct()
    {
        $this->baseUrl = config('services.nexah.base_url', 'https://api.nexah.net');
        $this->apiKey = config('services.nexah.api_key', '');
        $this->senderId = config('services.nexah.sender_id', 'ECOLE');
    }

    /**
     * Envoie un SMS via l'API Nexah.
     * STUB — à activer dès que les identifiants API sont fournis.
     */
    public function envoyerSms(string $telephone, string $message): bool
    {
        if (empty($this->apiKey)) {
            Log::info("[NEXAH SMS - STUB] À: {$telephone} | Message: {$message}");
            return true; // simulation en environnement de développement
        }

        try {
            $response = Http::post("{$this->baseUrl}/sms/send", [
                'api_key' => $this->apiKey,
                'sender_id' => $this->senderId,
                'to' => $this->formaterTelephone($telephone),
                'message' => $message,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Erreur envoi SMS Nexah: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoie un message WhatsApp via l'API Nexah.
     * STUB — à activer dès que les identifiants API sont fournis.
     */
    public function envoyerWhatsApp(string $telephone, string $message): bool
    {
        if (empty($this->apiKey)) {
            Log::info("[NEXAH WHATSAPP - STUB] À: {$telephone} | Message: {$message}");
            return true;
        }

        try {
            $response = Http::post("{$this->baseUrl}/whatsapp/send", [
                'api_key' => $this->apiKey,
                'to' => $this->formaterTelephone($telephone),
                'message' => $message,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Erreur envoi WhatsApp Nexah: " . $e->getMessage());
            return false;
        }
    }

    /** Envoie sur les deux canaux (fallback SMS si WhatsApp échoue) */
    public function notifier(string $telephone, string $message): bool
    {
        $whatsapp = $this->envoyerWhatsApp($telephone, $message);
        $sms = $this->envoyerSms($telephone, $message);

        return $whatsapp || $sms;
    }

    protected function formaterTelephone(string $telephone): string
    {
        $telephone = preg_replace('/\s+/', '', $telephone);
        if (!str_starts_with($telephone, '+')) {
            $telephone = str_starts_with($telephone, '237') ? "+{$telephone}" : "+237{$telephone}";
        }
        return $telephone;
    }
}