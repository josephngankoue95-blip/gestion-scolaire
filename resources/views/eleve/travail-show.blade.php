@extends('layouts.eleve')
@section('title', $travailDirige->titre)

@section('content')
<div class="card">
    <div class="flex justify-between items-start mb-4">
        <div>
            <h3 class="font-semibold text-gray-800 text-lg">{{ $travailDirige->titre }}</h3>
            <p class="text-sm text-gray-500">
                {{ $travailDirige->matiere->nom }} · {{ $travailDirige->enseignant->user->name }}
            </p>
        </div>
        <a href="{{ route('eleve.travaux') }}" class="btn-secondary">← Retour</a>
    </div>

    <div style="background:#fff3cd;border:1px solid #fcd34d;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:12px;">
        <strong>Accès valide jusqu'au :</strong>
        {{ $travailDirige->date_limite_acces->format('d/m/Y à H:i') }}
        @php $restantH = now()->diffInHours($travailDirige->date_limite_acces, false); @endphp
        <span class="badge-amber ml-2" style="font-size:10px;">
            {{ $restantH > 24 ? floor($restantH/24).'j restant(s)' : $restantH.'h restant(es)' }}
        </span>
    </div>

    @if($travailDirige->description)
    <div style="background:#f0f4ff;border-left:3px solid #2563eb;padding:10px 14px;border-radius:0 8px 8px 0;margin-bottom:16px;">
        <p class="text-sm text-gray-700"><strong>Objectifs :</strong> {{ $travailDirige->description }}</p>
    </div>
    @endif

    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px;white-space:pre-wrap;font-size:14px;line-height:1.8;margin-bottom:16px;">{{ $travailDirige->contenu }}</div>

    @if($travailDirige->fichier)
    <a href="{{ asset('storage/'.$travailDirige->fichier) }}" target="_blank" class="btn-primary">
        <i data-lucide="download" class="w-4 h-4"></i> Télécharger le fichier joint
    </a>
    @endif
</div>
@endsection