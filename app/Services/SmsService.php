<?php
// app/Services/SmsService.php
namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected ?Client $client = null;

    public function __construct()
    {
        if (config('services.twilio.sid')) {
            $this->client = new Client(config('services.twilio.sid'), config('services.twilio.auth_token'));
        }
    }

    public function envoyer(string $telephone, string $message): bool
    {
        if (!$this->client) {
            Log::warning('SMS non envoyé : Twilio non configuré.', ['telephone' => $telephone]);
            return false;
        }

        $telephone = $this->normaliserNumero($telephone);

        try {
            $this->client->messages->create($telephone, [
                'from' => config('services.twilio.from_number'),
                'body' => $message,
            ]);
            Log::info('SMS envoyé avec succès.', ['telephone' => $telephone]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi SMS Twilio: ' . $e->getMessage());
            return false;
        }
    }

    protected function normaliserNumero(string $numero): string
    {
        $numero = preg_replace('/\s+/', '', $numero);
        $numero = ltrim($numero, '+');
        if (!str_starts_with($numero, '237')) {
            $numero = '237' . ltrim($numero, '0');
        }
        return '+' . $numero;
    }

    public function messageIdentifiants(string $email, string $motDePasse): string
    {
        return "Vos identifiants de connexion : Email: {$email} / Mot de passe: {$motDePasse}. Connectez-vous sur notre plateforme.";
    }
}