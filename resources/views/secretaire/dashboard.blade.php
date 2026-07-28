@extends('layouts.secretaire')
@section('title', 'Tableau de bord')
@section('content')

<div style="background:linear-gradient(135deg,#1a3a6b,#2563eb);color:#fff;border-radius:14px;padding:18px 22px;margin-bottom:22px;">
    <p style="font-size:12px;opacity:0.8;">Année scolaire active</p>
    <p style="font-size:21px;font-weight:800;">{{ $annee?->libelle }}</p>
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

<div class="grid grid-cols-2 gap-6 mb-6">
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
        <div class="flex gap-2 mt-4">
            <a href="{{ route('secretaire.scolarite') }}" class="btn-primary w-full">
                <i data-lucide="wallet" class="w-4 h-4"></i> Gérer la scolarité
            </a>
            <a href="{{ route('secretaire.inscription.create') }}" class="btn-outline w-full">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Inscrire un élève
            </a>
        </div>
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
        @if($paiementsAujourdhui->isNotEmpty())
        <div class="flex justify-between pt-3 mt-2 border-t font-bold">
            <span>Total cette semaine</span>
            <span style="color:#1a3a6b;">{{ number_format($paiementsSemaine, 0, ',', ' ') }} F</span>
        </div>
        @endif
    </div>
</div>

@if($dettesParClasse->isNotEmpty())
<div class="card">
    <h4 class="font-semibold text-gray-800 mb-4">Top 5 classes avec le plus de dettes</h4>
    @foreach ($dettesParClasse as $classe => $montant)
    <div class="flex justify-between items-center py-2 border-t text-sm">
        <span class="font-medium">{{ $classe }}</span>
        <span style="color:#c0392b;font-weight:bold;">{{ number_format($montant, 0, ',', ' ') }} FCFA</span>
    </div>
    @endforeach
</div>
@endif

@endsection