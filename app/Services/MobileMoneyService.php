<?php
// app/Services/MobileMoneyService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MobileMoneyService
{
    /**
     * Initie une demande de paiement Mobile Money.
     * ── EMPLACEMENT RÉSERVÉ POUR L'INTÉGRATION API ──
     * MTN MoMo : Collection API (https://momodeveloper.mtn.com)
     * Orange Money : Orange Money Web Payment API
     *
     * Pour l'instant, cette méthode simule la demande et retourne
     * une référence en attente. À remplacer par un vrai appel HTTP
     * dès que les identifiants API (clé, secret, subscription key) seront fournis.
     */
    public function initierPaiement(string $operateur, string $telephone, float $montant, string $reference): array
    {
        // ── STUB : remplacer par l'appel réel ──
        if ($operateur === 'mtn_momo') {
            return $this->stubMtnMomo($telephone, $montant, $reference);
        }

        if ($operateur === 'orange_money') {
            return $this->stubOrangeMoney($telephone, $montant, $reference);
        }

        return ['success' => false, 'message' => 'Opérateur non supporté.'];
    }

    protected function stubMtnMomo(string $telephone, float $montant, string $reference): array
    {
        // TODO : appel réel vers config('services.mtn_momo.base_url') . '/collection/v1_0/requesttopay'
        Log::info('MTN MoMo — demande de paiement simulée', compact('telephone','montant','reference'));

        return [
            'success' => true,
            'reference_transaction' => 'MOMO-' . strtoupper(uniqid()),
            'message' => 'Demande de paiement envoyée sur le téléphone du client (simulation).',
        ];
    }

    protected function stubOrangeMoney(string $telephone, float $montant, string $reference): array
    {
        // TODO : appel réel vers l'API Orange Money Web Payment
        Log::info('Orange Money — demande de paiement simulée', compact('telephone','montant','reference'));

        return [
            'success' => true,
            'reference_transaction' => 'OM-' . strtoupper(uniqid()),
            'message' => 'Demande de paiement envoyée sur le téléphone du client (simulation).',
        ];
    }

    /** Vérifie le statut d'une transaction — à connecter à l'API réelle plus tard */
    public function verifierStatut(string $operateur, string $referenceTransaction): string
    {
        // TODO : appel réel de vérification de statut
        return 'en_attente';
    }
}