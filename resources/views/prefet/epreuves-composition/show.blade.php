@extends('layouts.prefet')
@section('title', $epreuveComposition->titre)
@section('content')
<div class="card" style="max-width:640px;">
    <div class="flex justify-between items-start mb-4">
        <div>
            <h3 class="font-semibold text-gray-800 text-lg">{{ $epreuveComposition->titre }}</h3>
            <p class="text-sm text-gray-500 mt-1">Épreuve de composition — consultation uniquement</p>
        </div>
        <x-retour-button fallback-route="prefet.epreuves-composition.index" label="← Retour" />
    </div>

    <dl class="space-y-2 text-sm">
        <div class="flex gap-3 py-2 border-t"><dt style="width:150px;color:#6b7280;">Matière</dt><dd class="font-medium">{{ $epreuveComposition->matiere?->nom ?? '-' }}</dd></div>
        <div class="flex gap-3 py-2 border-t"><dt style="width:150px;color:#6b7280;">Classe</dt><dd class="font-medium">{{ $epreuveComposition->classe?->nom ?? '-' }}</dd></div>
        <div class="flex gap-3 py-2 border-t"><dt style="width:150px;color:#6b7280;">Séquence</dt><dd class="font-medium">{{ $epreuveComposition->sequence?->nom ?? '-' }}</dd></div>
        <div class="flex gap-3 py-2 border-t"><dt style="width:150px;color:#6b7280;">Enseignant</dt><dd class="font-medium">{{ $epreuveComposition->enseignant?->user?->name ?? '-' }}</dd></div>
        <div class="flex gap-3 py-2 border-t"><dt style="width:150px;color:#6b7280;">Envoyée le</dt><dd class="font-medium">{{ $epreuveComposition->created_at->format('d/m/Y à H:i') }}</dd></div>
    </dl>

    <div class="flex gap-3 mt-6">
        @if($epreuveComposition->fichier && \Illuminate\Support\Facades\Storage::disk('public')->exists($epreuveComposition->fichier))
        <a href="{{ asset('storage/'.$epreuveComposition->fichier) }}" target="_blank" class="btn-primary w-full">
            <i data-lucide="download" class="w-4 h-4"></i> Télécharger l'épreuve
        </a>
        @else
        <span class="btn-outline w-full" style="opacity:.5;cursor:not-allowed;text-align:center;">Fichier introuvable</span>
        @endif

        @if($epreuveComposition->fichier_corrige && \Illuminate\Support\Facades\Storage::disk('public')->exists($epreuveComposition->fichier_corrige))
        <a href="{{ asset('storage/'.$epreuveComposition->fichier_corrige) }}" target="_blank" class="btn-outline w-full">
            <i data-lucide="check-circle" class="w-4 h-4"></i> Télécharger le corrigé
        </a>
        @endif
    </div>
</div>
@endsection