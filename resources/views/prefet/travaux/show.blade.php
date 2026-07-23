@extends('layouts.prefet')
@section('title', $travailDirige->titre)

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold text-gray-800">{{ $travailDirige->titre }}</h3>
            <p class="text-sm text-gray-500">{{ $travailDirige->matiere->nom }} — {{ $travailDirige->classe->nom }} · {{ $travailDirige->enseignant->user->name }}</p>
        </div>
        <a href="{{ route('prefet.travaux.imprimer', $travailDirige) }}" target="_blank" class="btn-primary">
            <i data-lucide="printer" class="w-4 h-4"></i> Imprimer
        </a>
    </div>

    @if($travailDirige->description)
    <div style="background:#f0f4ff;padding:12px 16px;border-radius:8px;margin-bottom:16px;">{{ $travailDirige->description }}</div>
    @endif

    <div style="background:#f9fafb;padding:16px;border-radius:8px;white-space:pre-wrap;">{{ $travailDirige->contenu }}</div>

    @if($travailDirige->fichier)
    <a href="{{ asset('storage/'.$travailDirige->fichier) }}" target="_blank" class="btn-outline mt-4">
        <i data-lucide="download" class="w-4 h-4"></i> Télécharger le fichier joint
    </a>
    @endif
</div>
@endsection