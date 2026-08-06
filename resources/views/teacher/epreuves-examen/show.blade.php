@extends('layouts.admin')
@section('title', $epreuveExamen->titre)
@section('content')
<div class="card" style="max-width:640px;">
    <div class="flex justify-between items-start mb-4">
        <div>
            <h3 class="font-semibold text-gray-800 text-lg">{{ $epreuveExamen->titre }}</h3>
            <p class="text-sm text-gray-500 mt-1">Épreuve d'examen — archive {{ $epreuveExamen->annee_examen }}</p>
        </div>
        <x-retour-button fallback-route="teacher.epreuves-examen.index" label="← Retour" />
    </div>

    <dl class="space-y-2 text-sm">
        <div class="flex gap-3 py-2 border-t"><dt style="width:150px;color:#6b7280;">Matière</dt><dd class="font-medium">{{ $epreuveExamen->matiere?->nom ?? '—' }}</dd></div>
        <div class="flex gap-3 py-2 border-t"><dt style="width:150px;color:#6b7280;">Niveau</dt><dd class="font-medium">{{ $epreuveExamen->niveau?->nom ?? '—' }}</dd></div>
        <div class="flex gap-3 py-2 border-t"><dt style="width:150px;color:#6b7280;">Année</dt><dd><span class="badge-amber">{{ $epreuveExamen->annee_examen }}</span></dd></div>
        <div class="flex gap-3 py-2 border-t"><dt style="width:150px;color:#6b7280;">Inséré par</dt><dd class="font-medium">{{ $epreuveExamen->inserePar?->name ?? '—' }}</dd></div>
    </dl>

    @php
        $fichierOk = $epreuveExamen->fichier && \Illuminate\Support\Facades\Storage::disk('public')->exists($epreuveExamen->fichier);
        $corrigeOk = $epreuveExamen->fichier_corrige && \Illuminate\Support\Facades\Storage::disk('public')->exists($epreuveExamen->fichier_corrige);
    @endphp

    <div class="flex gap-3 mt-6">
        @if($fichierOk)
        <a href="{{ asset('storage/'.$epreuveExamen->fichier) }}" target="_blank" class="btn-primary w-full"><i data-lucide="download" class="w-4 h-4"></i> Télécharger</a>
        @else
        <span class="btn-outline w-full" style="opacity:.5;text-align:center;">Fichier introuvable</span>
        @endif
        @if($corrigeOk)
        <a href="{{ asset('storage/'.$epreuveExamen->fichier_corrige) }}" target="_blank" class="btn-outline w-full"><i data-lucide="check-circle" class="w-4 h-4"></i> Corrigé</a>
        @endif
    </div>

    <div class="mt-4">
        <form method="POST" action="{{ route('teacher.epreuves-examen.destroy', $epreuveExamen) }}"
              data-confirm-delete data-confirm-message="Supprimer définitivement cette épreuve ?">
            @csrf @method('DELETE')
            <input type="hidden" name="redirect_to" value="{{ route('teacher.epreuves-examen.index') }}">
            <button type="button" class="text-red-500 text-sm" onclick="this.closest('form').requestSubmit()"><i data-lucide="trash-2" class="w-4 h-4 inline"></i> Supprimer</button>
        </form>
    </div>
</div>
@endsection