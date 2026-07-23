@extends('layouts.secretaire')
@section('title', 'Tableau de bord')

@section('content')
<div style="background:linear-gradient(135deg,#1a3a6b,#2563eb);color:#fff;border-radius:12px;padding:16px 20px;margin-bottom:20px;">
    <p style="font-size:12px;opacity:0.8;">Année scolaire active</p>
    <p style="font-size:20px;font-weight:bold;">{{ $annee?->libelle }}</p>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="stat-card" style="border-left:4px solid #c0392b;">
        <p class="stat-label">Total dû</p>
        <p class="stat-value" style="font-size:16px;color:#c0392b;">{{ number_format($totalDu, 0, ',', ' ') }}</p>
    </div>
    <div class="stat-card" style="border-left:4px solid #1a7a1a;">
        <p class="stat-label">Encaissé</p>
        <p class="stat-value" style="font-size:16px;color:#1a7a1a;">{{ number_format($totalPaye, 0, ',', ' ') }}</p>
    </div>
    <div class="stat-card" style="border-left:4px solid #2563eb;">
        <p class="stat-label">Taux recouvrement</p>
        <p class="stat-value">{{ $tauxRecouv }}%</p>
    </div>
    <div class="stat-card" style="border-left:4px solid #9333ea;">
        <p class="stat-label">Requêtes en attente</p>
        <p class="stat-value" style="color:#9333ea;">{{ $requetesEnAttente }}</p>
    </div>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-4">Soldés / Avec dette</h4>
        <div class="flex gap-4">
            <div style="flex:1;background:#f0fdf4;border-radius:8px;padding:16px;text-align:center;">
                <p style="font-size:24px;font-weight:bold;color:#1a7a1a;">{{ $nbSoldes }}</p>
                <p style="font-size:12px;color:#555;">Dossiers soldés</p>
            </div>
            <div style="flex:1;background:#fef2f2;border-radius:8px;padding:16px;text-align:center;">
                <p style="font-size:24px;font-weight:bold;color:#c0392b;">{{ $nbDettes }}</p>
                <p style="font-size:12px;color:#555;">Dossiers avec dette</p>
            </div>
        </div>
        <a href="{{ route('secretaire.scolarite') }}" class="btn-primary w-full mt-4">
            <i data-lucide="wallet" class="w-4 h-4"></i> Gérer la scolarité
        </a>
    </div>

    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-4">Paiements enregistrés aujourd'hui</h4>
        @forelse ($paiementsAujourdhui as $p)
        <div class="flex justify-between items-center py-2 border-t text-sm">
            <span>{{ $p->scolarite->eleve->nomComplet() }}</span>
            <span style="color:#1a7a1a;font-weight:bold;">{{ number_format($p->montant, 0, ',', ' ') }} F</span>
        </div>
        @empty
        <p class="text-gray-400 text-center py-6">Aucun paiement aujourd'hui.</p>
        @endforelse
    </div>
</div>
@endsection