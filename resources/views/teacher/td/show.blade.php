@extends('layouts.admin')
@section('title', $travailDirige->titre)

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold text-gray-800 text-lg">{{ $travailDirige->titre }}</h3>
            <p class="text-sm text-gray-500">
                {{ $travailDirige->matiere->nom }} — {{ $travailDirige->classe->nom }}
            </p>
        </div>
        <div class="flex gap-2">
            @php $statut = $travailDirige->statut(); @endphp
            <span class="{{ match($statut) { 'actif' => 'badge-green', 'brouillon' => 'badge-gray', 'programme' => 'badge-blue', 'expire' => 'badge-red', default => 'badge-gray' } }}">
                {{ ucfirst($statut) }}
            </span>
            <a href="{{ route('teacher.td.edit', $travailDirige) }}" class="btn-outline">Modifier</a>
            <a href="{{ route('teacher.td.index') }}" class="btn-secondary">← Retour</a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="stat-card">
            <p class="stat-label">Publication</p>
            <p style="font-size:13px;font-weight:bold;">{{ $travailDirige->date_publication->format('d/m/Y H:i') }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Limite accès</p>
            <p style="font-size:13px;font-weight:bold;color:{{ now() > $travailDirige->date_limite_acces ? '#c0392b' : '#1a7a1a' }};">
                {{ $travailDirige->date_limite_acces->format('d/m/Y H:i') }}
            </p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Durée d'accès</p>
            @php
                $dureeH = $travailDirige->date_publication->diffInHours($travailDirige->date_limite_acces);
                $dureeJ = floor($dureeH / 24);
                $dureeR = $dureeH % 24;
            @endphp
            <p style="font-size:13px;font-weight:bold;">
                {{ $dureeJ > 0 ? $dureeJ.'j ' : '' }}{{ $dureeR }}h
            </p>
        </div>
    </div>

    @if($travailDirige->description)
    <div style="background:#f0f4ff;border:1px solid #dce6f5;border-radius:8px;padding:12px 16px;margin-bottom:16px;">
        <p class="text-sm text-gray-600">{{ $travailDirige->description }}</p>
    </div>
    @endif

    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px;margin-bottom:16px;white-space:pre-wrap;font-size:14px;line-height:1.7;">
        {{ $travailDirige->contenu }}
    </div>

    @if($travailDirige->fichier)
    <a href="{{ asset('storage/'.$travailDirige->fichier) }}" target="_blank" class="btn-outline">
        <i data-lucide="download" class="w-4 h-4"></i> Télécharger le fichier joint
    </a>
    @endif
</div>
@endsection