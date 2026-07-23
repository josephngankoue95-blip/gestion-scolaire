@extends('layouts.proviseur')
@section('title', 'Tableau de bord')

@section('content')
<div style="background:linear-gradient(135deg,#1a3a6b,#2563eb);color:#fff;border-radius:12px;padding:16px 20px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
    <div>
        <p style="font-size:12px;opacity:0.8;">Année scolaire active</p>
        <p style="font-size:20px;font-weight:bold;">{{ $annee?->libelle ?? 'Non définie' }}</p>
    </div>
    <div style="text-align:right;font-size:12px;opacity:0.8;">
        <p>{{ now()->format('l d F Y') }}</p>
    </div>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="stat-card" style="border-left:4px solid #2563eb;">
        <p class="stat-label">Élèves actifs</p>
        <p class="stat-value">{{ number_format($stats['eleves']) }}</p>
    </div>
    <div class="stat-card" style="border-left:4px solid #1a7a1a;">
        <p class="stat-label">Enseignants</p>
        <p class="stat-value">{{ $stats['enseignants'] }}</p>
    </div>
    <div class="stat-card" style="border-left:4px solid #e67e22;">
        <p class="stat-label">Classes</p>
        <p class="stat-value">{{ $stats['classes'] }}</p>
    </div>
    <div class="stat-card" style="border-left:4px solid #c0392b;">
        <p class="stat-label">Absences aujourd'hui</p>
        <p class="stat-value" style="color:#c0392b;">{{ $stats['abs_today'] }}</p>
    </div>
</div>

<div class="grid grid-cols-2 gap-6 mb-6">

    {{-- Finances --}}
    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-4"><i data-lucide="wallet" class="w-4 h-4 inline"></i> Finances</h4>
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div style="background:#fef2f2;border-radius:8px;padding:10px;text-align:center;">
                <p style="font-size:11px;color:#555;">Total dû</p>
                <p style="font-size:14px;font-weight:bold;color:#c0392b;">{{ number_format($finance['total_du'],0,',',' ') }}</p>
                <p style="font-size:10px;color:#9ca3af;">FCFA</p>
            </div>
            <div style="background:#f0fdf4;border-radius:8px;padding:10px;text-align:center;">
                <p style="font-size:11px;color:#555;">Encaissé</p>
                <p style="font-size:14px;font-weight:bold;color:#1a7a1a;">{{ number_format($finance['total_paye'],0,',',' ') }}</p>
                <p style="font-size:10px;color:#9ca3af;">FCFA</p>
            </div>
            <div style="background:#fff3cd;border-radius:8px;padding:10px;text-align:center;">
                <p style="font-size:11px;color:#555;">Reste</p>
                <p style="font-size:14px;font-weight:bold;color:#e67e22;">{{ number_format($finance['total_du']-$finance['total_paye'],0,',',' ') }}</p>
            </div>
            <div style="background:#f0f4ff;border-radius:8px;padding:10px;text-align:center;">
                <p style="font-size:11px;color:#555;">Taux recouvrement</p>
                <p style="font-size:18px;font-weight:bold;color:#1a3a6b;">{{ $finance['taux_recouv'] }}%</p>
            </div>
        </div>
        <div class="progress-track">
            <div class="progress-bar" style="width:{{ $finance['taux_recouv'] }}%;"></div>
        </div>
        <div class="flex justify-between text-xs text-gray-400 mt-1">
            <span>{{ $finance['nb_soldes'] }} soldés</span>
            <span>{{ $finance['nb_dettes'] }} avec dette</span>
        </div>
    </div>

    {{-- Effectifs --}}
    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-4"><i data-lucide="users" class="w-4 h-4 inline"></i> Effectifs par classe</h4>
        <div style="max-height:220px;overflow-y:auto;">
            @foreach ($effectifsClasses as $c)
            @php $taux = $c->capacite_max > 0 ? round(($c->nb_eleves/$c->capacite_max)*100) : 0; @endphp
            <div style="margin-bottom:7px;">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:2px;">
                    <span class="font-medium">{{ $c->nom }} <span style="color:#9ca3af;">({{ $c->section->code }})</span></span>
                    <span style="color:{{ $taux >= 90 ? '#c0392b' : '#555' }};">{{ $c->nb_eleves }}/{{ $c->capacite_max }}</span>
                </div>
                <div class="progress-track" style="height:7px;">
                    <div class="progress-bar" style="width:{{ min(100,$taux) }}%;background:{{ $taux >= 90 ? '#c0392b' : '#2563eb' }};"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Alertes --}}
<div class="grid grid-cols-4 gap-4">
    <a href="{{ route('proviseur.absences') }}" class="card-hover" style="text-align:center;padding:16px;">
        <i data-lucide="calendar-x" style="width:28px;height:28px;display:block;margin:0 auto 8px;color:#c0392b;"></i>
        <p style="font-size:12px;font-weight:bold;">{{ $stats['abs_mois_nj'] }}</p>
        <p style="font-size:11px;color:#9ca3af;">Abs. NJ ce mois</p>
    </a>
    <a href="{{ route('proviseur.performances') }}" class="card-hover" style="text-align:center;padding:16px;">
        <i data-lucide="bar-chart-3" style="width:28px;height:28px;display:block;margin:0 auto 8px;color:#2563eb;"></i>
        <p style="font-size:12px;font-weight:bold;">Performances</p>
        <p style="font-size:11px;color:#9ca3af;">par classe</p>
    </a>
    <div class="card-hover" style="text-align:center;padding:16px;">
        <i data-lucide="file-check" style="width:28px;height:28px;display:block;margin:0 auto 8px;color:#1a7a1a;"></i>
        <p style="font-size:12px;font-weight:bold;">{{ $stats['candidatures_attente'] }}</p>
        <p style="font-size:11px;color:#9ca3af;">Candidatures en attente</p>
    </div>
    <div class="card-hover" style="text-align:center;padding:16px;">
        <i data-lucide="mail" style="width:28px;height:28px;display:block;margin:0 auto 8px;color:#9333ea;"></i>
        <p style="font-size:12px;font-weight:bold;">{{ $stats['requetes_attente'] }}</p>
        <p style="font-size:11px;color:#9ca3af;">Requêtes en attente</p>
    </div>
</div>
@endsection