@extends('layouts.eleve')
@section('title', 'Mon tableau de bord')

@section('content')

<div class="card mb-6" style="background:linear-gradient(135deg,#1a3a6b,#2563eb);color:#fff;padding:20px;">
    <div class="flex items-center gap-4">
        @if($eleve->photo)
            <img src="{{ asset('storage/'.$eleve->photo) }}" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.4);">
        @else
            <div style="width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:bold;">
                {{ strtoupper(substr($eleve->prenom,0,1)) }}
            </div>
        @endif
        <div>
            <h3 style="font-size:18px;font-weight:bold;">{{ $eleve->nomComplet() }}</h3>
            <p style="font-size:12px;opacity:0.8;">Matricule : {{ $eleve->matricule }}</p>
            @if($classe)
                <span style="background:rgba(255,255,255,0.2);padding:2px 10px;border-radius:20px;font-size:11px;">
                    {{ $classe->nom }} — {{ $classe->section->nom }}
                </span>
            @else
                <span style="background:rgba(255,255,255,0.2);padding:2px 10px;border-radius:20px;font-size:11px;">
                    Non inscrit cette année
                </span>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="stat-card" style="border-left:4px solid #2563eb;">
        <p class="stat-label">Moyenne {{ $derniereSeq?->nom ?? '' }}</p>
        <p class="stat-value">
            {{ $bulletinSeq && $bulletinSeq['moyenne_generale'] !== null ? number_format($bulletinSeq['moyenne_generale'],2) : '-' }}
            <span style="font-size:13px;color:#9ca3af;">/20</span>
        </p>
    </div>
    <div class="stat-card" style="border-left:4px solid #1a7a1a;">
        <p class="stat-label">Rang</p>
        <p class="stat-value">{{ $bulletinSeq['rang'] ?? '-' }}</p>
    </div>
    <div class="stat-card" style="border-left:4px solid #c0392b;">
        <p class="stat-label">Absences ce mois</p>
        <p class="stat-value" style="color:#c0392b;">{{ $absencesMois }}</p>
        <p style="font-size:11px;color:#9ca3af;">{{ $absNonJust }} non justifiées</p>
    </div>
    <div class="stat-card" style="border-left:4px solid #e67e22;">
        <p class="stat-label">TD actifs</p>
        <p class="stat-value" style="color:#e67e22;">{{ $tdActifs }}</p>
    </div>
</div>

<div class="grid grid-cols-2 gap-6">

    {{-- Cours du jour --}}
    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-3">
            <i data-lucide="calendar-clock" class="w-4 h-4 inline"></i>
            Cours d'aujourd'hui @if($jourAujourdhui) ({{ ucfirst($jourAujourdhui) }}) @endif
        </h4>
        @forelse ($coursAujourdhui as $c)
        <div style="display:flex;align-items:center;gap:10px;background:#f8faff;border:1px solid #dce6f5;border-radius:8px;padding:8px 12px;margin-bottom:6px;border-left:3px solid #2563eb;">
            <div style="min-width:75px;text-align:center;background:#1a3a6b;color:#fff;border-radius:5px;padding:3px 5px;font-size:10px;font-weight:bold;">
                {{ substr($c->heure_debut,0,5) }}<br>{{ substr($c->heure_fin,0,5) }}
            </div>
            <div>
                <p class="font-medium" style="font-size:13px;">{{ $c->matiere->nom }}</p>
                <p class="text-xs text-gray-500">{{ $c->enseignant->user->name }}@if($c->salle) · Salle {{ $c->salle }}@endif</p>
            </div>
        </div>
        @empty
        <p class="text-gray-400 text-sm text-center py-6">Pas de cours aujourd'hui.</p>
        @endforelse
        <a href="{{ route('eleve.emploi-du-temps') }}" class="login-link text-sm">Voir l'emploi du temps complet →</a>
    </div>

    {{-- Accès rapides --}}
    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-4">Accès rapides</h4>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('eleve.bulletins') }}" class="card-hover" style="text-align:center;padding:14px;">
                <i data-lucide="file-text" style="width:26px;height:26px;margin:0 auto 6px;display:block;color:#2563eb;"></i>
                <p style="font-size:12px;font-weight:bold;">Mes Notes</p>
            </a>
            <a href="{{ route('eleve.emploi-du-temps') }}" class="card-hover" style="text-align:center;padding:14px;">
                <i data-lucide="calendar-clock" style="width:26px;height:26px;margin:0 auto 6px;display:block;color:#1a7a1a;"></i>
                <p style="font-size:12px;font-weight:bold;">Emploi du temps</p>
            </a>
            <a href="{{ route('eleve.travaux') }}" class="card-hover" style="text-align:center;padding:14px;">
                <i data-lucide="book-marked" style="width:26px;height:26px;margin:0 auto 6px;display:block;color:#e67e22;"></i>
                <p style="font-size:12px;font-weight:bold;">Mes TD</p>
                @if($tdActifs > 0)<span class="badge-green" style="font-size:10px;">{{ $tdActifs }} actif(s)</span>@endif
            </a>
            <a href="{{ route('eleve.profil') }}" class="card-hover" style="text-align:center;padding:14px;">
                <i data-lucide="user" style="width:26px;height:26px;margin:0 auto 6px;display:block;color:#9333ea;"></i>
                <p style="font-size:12px;font-weight:bold;">Mon Profil</p>
            </a>
        </div>
    </div>
</div>
@endsection