@extends('layouts.admin')
@section('title', 'Tableau de bord — Administration')

@section('content')

{{-- ── Année active ── --}}
<div class="dash-card fade-in" style="background:linear-gradient(135deg,#1a3a6b,#2563eb);color:#fff;border-radius:14px;padding:18px 22px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 8px 26px rgba(37,99,235,0.25);">
    <div>
        <p style="font-size:12px;opacity:0.8;">Année scolaire active</p>
        <p style="font-size:21px;font-weight:800;">{{ $annee?->libelle ?? 'Non définie' }}</p>
    </div>
    <div style="text-align:right;font-size:12px;opacity:0.8;">
        <p>{{ now()->format('l d F Y') }}</p>
        <p style="font-size:17px;font-weight:bold;" id="live-clock">{{ now()->format('H:i') }}</p>
    </div>
</div>

{{-- ── Stat cards ── --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="dash-card stat-card fade-in" style="--delay:.05s;border-left:4px solid #2563eb;">
        <p class="stat-label">Élèves actifs</p>
        <p class="stat-value counter" data-target="{{ $totalEleves }}">0</p>
    </div>
    <div class="dash-card stat-card fade-in" style="--delay:.12s;border-left:4px solid #1a7a1a;">
        <p class="stat-label">Enseignants</p>
        <p class="stat-value counter" data-target="{{ $totalEnseignants }}">0</p>
    </div>
    <div class="dash-card stat-card fade-in" style="--delay:.19s;border-left:4px solid #e67e22;">
        <p class="stat-label">Classes</p>
        <p class="stat-value counter" data-target="{{ $totalClasses }}">0</p>
    </div>
    <div class="dash-card stat-card fade-in" style="--delay:.26s;border-left:4px solid #c0392b;">
        <p class="stat-label">Absences aujourd'hui</p>
        <p class="stat-value counter" style="color:#c0392b;" data-target="{{ $absAujourdhui }}">0</p>
        <p style="font-size:11px;color:#9ca3af;">{{ $absNonJustif }} NJ ce mois</p>
    </div>
</div>

{{-- ── Scolarité + Graphique donut ── --}}
<div class="grid grid-cols-2 gap-6 mb-6">

    <div class="dash-card card fade-in" style="--delay:.1s;">
        <h4 class="font-semibold text-gray-800 mb-4">
            <i data-lucide="wallet" class="w-4 h-4 inline"></i>
            Scolarité — Vue d'ensemble
        </h4>

        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="mini-stat" style="background:#f0f4ff;">
                <p style="font-size:11px;color:#555;">Total dû</p>
                <p style="font-size:16px;font-weight:bold;color:#c0392b;">{{ number_format($totalDu, 0, ',', ' ') }}</p>
                <p style="font-size:10px;color:#9ca3af;">FCFA</p>
            </div>
            <div class="mini-stat" style="background:#f0fdf4;">
                <p style="font-size:11px;color:#555;">Encaissé</p>
                <p style="font-size:16px;font-weight:bold;color:#1a7a1a;">{{ number_format($totalPaye, 0, ',', ' ') }}</p>
                <p style="font-size:10px;color:#9ca3af;">FCFA</p>
            </div>
            <div class="mini-stat" style="background:#fff3cd;">
                <p style="font-size:11px;color:#555;">Reste à percevoir</p>
                <p style="font-size:16px;font-weight:bold;color:#e67e22;">{{ number_format($totalDu - $totalPaye, 0, ',', ' ') }}</p>
                <p style="font-size:10px;color:#9ca3af;">FCFA</p>
            </div>
            <div class="mini-stat" style="background:#f9f9f9;">
                <p style="font-size:11px;color:#555;">Taux recouvrement</p>
                <p style="font-size:16px;font-weight:bold;color:#1a3a6b;">{{ $tauxRecouvrement }}%</p>
            </div>
        </div>

        <div>
            <div style="display:flex;justify-content:space-between;font-size:11px;color:#555;margin-bottom:4px;">
                <span>{{ $nbSoldes }} soldés</span>
                <span>{{ $nbDettes }} avec dette</span>
            </div>
            <div class="progress-track">
                <div class="progress-bar animated-bar" style="--target-width:{{ $tauxRecouvrement }}%;"></div>
            </div>
        </div>

        @if($dettesEleves->isNotEmpty())
        <div class="mt-4">
            <p style="font-size:12px;font-weight:bold;color:#c0392b;margin-bottom:6px;">Top dettes en cours</p>
            @foreach ($dettesEleves as $sc)
            <div style="display:flex;justify-content:space-between;padding:4px 0;border-top:1px solid #f3f4f6;font-size:12px;">
                <span>{{ $sc->eleve->nomComplet() }} <span style="color:#9ca3af;">({{ $sc->classe->nom }})</span></span>
                <span style="color:#c0392b;font-weight:bold;">{{ number_format($sc->solde(), 0, ',', ' ') }} F</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Graphique donut recouvrement --}}
    <div class="dash-card card fade-in" style="--delay:.18s;">
        <h4 class="font-semibold text-gray-800 mb-4">
            <i data-lucide="pie-chart" class="w-4 h-4 inline"></i>
            Répartition des dossiers scolarité
        </h4>
        <div style="display:flex;align-items:center;justify-content:center;gap:24px;flex-wrap:wrap;">
            <div style="width:200px;height:200px;">
                <canvas id="chartScolarite"></canvas>
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;align-items:center;gap:8px;font-size:13px;">
                    <span style="width:12px;height:12px;border-radius:3px;background:#1a7a1a;display:inline-block;"></span>
                    Soldés — <strong>{{ $nbSoldes }}</strong>
                </div>
                <div style="display:flex;align-items:center;gap:8px;font-size:13px;">
                    <span style="width:12px;height:12px;border-radius:3px;background:#c0392b;display:inline-block;"></span>
                    Avec dette — <strong>{{ $nbDettes }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Effectifs par classe (graphique barres) ── --}}
<div class="dash-card card mb-6 fade-in" style="--delay:.1s;">
    <h4 class="font-semibold text-gray-800 mb-4">
        <i data-lucide="users" class="w-4 h-4 inline"></i>
        Effectifs par classe
    </h4>
    <div style="height:260px;">
        <canvas id="chartEffectifs"></canvas>
    </div>
</div>

{{-- ── Taux de réussite ── --}}
@if(!empty($tauxReussiteParClasse))
<div class="dash-card card mb-6 fade-in" style="--delay:.15s;">
    <h4 class="font-semibold text-gray-800 mb-4">
        <i data-lucide="bar-chart-3" class="w-4 h-4 inline"></i>
        Taux de réussite — {{ $derniereSequence?->nom }}
    </h4>

    <div style="height:280px;margin-bottom:20px;">
        <canvas id="chartReussite"></canvas>
    </div>

    <div style="overflow-x:auto;">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Classe</th><th>Section</th><th>Effectif</th><th>Réussis (≥10)</th><th>Taux</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tauxReussiteParClasse as $t)
                <tr>
                    <td class="font-medium">{{ $t['classe'] }}</td>
                    <td><span class="badge-blue">{{ $t['section'] }}</span></td>
                    <td>{{ $t['effectif'] }}</td>
                    <td style="color:#1a7a1a;font-weight:bold;">{{ $t['reussis'] }}</td>
                    <td>
                        <span style="font-weight:bold;color:{{ $t['taux'] >= 70 ? '#1a7a1a' : ($t['taux'] >= 50 ? '#e67e22' : '#c0392b') }};">
                            {{ $t['taux'] }}%
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ── Absences + Accès rapides ── --}}
<div class="grid grid-cols-2 gap-6">

    <div class="dash-card card fade-in" style="--delay:.1s;">
        <h4 class="font-semibold text-gray-800 mb-4">
            <i data-lucide="calendar-x" class="w-4 h-4 inline"></i>
            Absences ce mois par classe
        </h4>
        @forelse ($absencesParClasse as $ab)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-top:1px solid #f3f4f6;font-size:13px;">
            <span class="font-medium">{{ $ab->classe }}</span>
            <div class="flex gap-2">
                <span class="badge-gray">{{ $ab->total }} total</span>
                @if($ab->non_justifiees > 0)
                    <span class="badge-red">{{ $ab->non_justifiees }} NJ</span>
                @endif
            </div>
        </div>
        @empty
        <p class="text-gray-400 text-center py-4">Aucune absence ce mois.</p>
        @endforelse
    </div>

    <div class="dash-card card fade-in" style="--delay:.16s;">
        <h4 class="font-semibold text-gray-800 mb-4">
            <i data-lucide="zap" class="w-4 h-4 inline"></i>
            Accès rapides
        </h4>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('admin.eleves.create') }}" class="card-hover quick-access" style="text-align:center;padding:12px;">
                <i data-lucide="user-plus" style="width:24px;height:24px;margin:0 auto 6px;display:block;color:#2563eb;"></i>
                <p style="font-size:12px;font-weight:bold;">Nouvel élève</p>
            </a>
            <a href="{{ route('admin.scolarite.create') }}" class="card-hover quick-access" style="text-align:center;padding:12px;">
                <i data-lucide="wallet" style="width:24px;height:24px;margin:0 auto 6px;display:block;color:#1a7a1a;"></i>
                <p style="font-size:12px;font-weight:bold;">Inscrire</p>
            </a>
            <a href="{{ route('admin.bulletins.index') }}" class="card-hover quick-access" style="text-align:center;padding:12px;">
                <i data-lucide="file-text" style="width:24px;height:24px;margin:0 auto 6px;display:block;color:#e67e22;"></i>
                <p style="font-size:12px;font-weight:bold;">Bulletins</p>
            </a>
            <a href="{{ route('admin.transferts.index') }}" class="card-hover quick-access" style="text-align:center;padding:12px;">
                <i data-lucide="arrow-right-left" style="width:24px;height:24px;margin:0 auto 6px;display:block;color:#9333ea;"></i>
                <p style="font-size:12px;font-weight:bold;">Transferts</p>
            </a>
            <a href="{{ route('admin.candidatures.index') }}" class="card-hover quick-access" style="text-align:center;padding:12px;">
                <i data-lucide="file-check" style="width:24px;height:24px;margin:0 auto 6px;display:block;color:#0891b2;"></i>
                <p style="font-size:12px;font-weight:bold;">Candidatures</p>
            </a>
            <a href="{{ route('admin.releves.index') }}" class="card-hover quick-access" style="text-align:center;padding:12px;">
                <i data-lucide="clipboard" style="width:24px;height:24px;margin:0 auto 6px;display:block;color:#c0392b;"></i>
                <p style="font-size:12px;font-weight:bold;">Relevés</p>
            </a>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
@keyframes fadeInUpDash {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.fade-in {
    opacity: 0;
    animation: fadeInUpDash .55s ease forwards;
    animation-delay: var(--delay, 0s);
}
.dash-card { transition: box-shadow .25s ease, transform .25s ease; }
.dash-card:hover { box-shadow: 0 10px 28px rgba(0,0,0,0.07); }

.mini-stat { border-radius: 10px; padding: 12px; text-align: center; transition: transform .2s; }
.mini-stat:hover { transform: translateY(-2px); }

.quick-access { transition: transform .2s ease, box-shadow .2s ease; }
.quick-access:hover { transform: translateY(-3px); box-shadow: 0 8px 18px rgba(0,0,0,0.08); }

.progress-track { position: relative; overflow: hidden; }
.animated-bar {
    width: 0;
    transition: width 1.2s cubic-bezier(.22,.9,.4,1);
}
.animated-bar.filled { width: var(--target-width); }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Horloge en direct ──
    setInterval(() => {
        const el = document.getElementById('live-clock');
        if (el) el.textContent = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    }, 30000);

    // ── Compteurs animés ──
    document.querySelectorAll('.counter').forEach(el => {
        const target = parseInt(el.dataset.target || '0', 10);
        let current = 0;
        const step = Math.max(1, Math.ceil(target / 40));
        const timer = setInterval(() => {
            current += step;
            if (current >= target) { current = target; clearInterval(timer); }
            el.textContent = current.toLocaleString('fr-FR');
        }, 25);
    });

    // ── Barre de progression animée ──
    setTimeout(() => {
        document.querySelectorAll('.animated-bar').forEach(bar => bar.classList.add('filled'));
    }, 200);

    // ── Chart.js : couleurs & options communes ──
    Chart.defaults.font.family = "'Inter','DejaVu Sans',sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = '#6b7280';

    // Donut Scolarité
    const ctxScolarite = document.getElementById('chartScolarite');
    if (ctxScolarite) {
        new Chart(ctxScolarite, {
            type: 'doughnut',
            data: {
                labels: ['Soldés', 'Avec dette'],
                datasets: [{
                    data: [{{ $nbSoldes }}, {{ $nbDettes }}],
                    backgroundColor: ['#1a7a1a', '#c0392b'],
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '68%',
                plugins: { legend: { display: false } },
                animation: { animateScale: true, animateRotate: true, duration: 1000 }
            }
        });
    }

    // Barres Effectifs par classe
    const ctxEffectifs = document.getElementById('chartEffectifs');
    if (ctxEffectifs) {
        new Chart(ctxEffectifs, {
            type: 'bar',
            data: {
                labels: [@foreach($effectifsClasses as $c){{ Js::from($c->nom) }},@endforeach],
                datasets: [
                    {
                        label: 'Effectif',
                        data: [@foreach($effectifsClasses as $c){{ $c->nb_eleves }},@endforeach],
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                        maxBarThickness: 26,
                    },
                    {
                        label: 'Capacité',
                        data: [@foreach($effectifsClasses as $c){{ $c->capacite_max }},@endforeach],
                        backgroundColor: '#e5edff',
                        borderRadius: 6,
                        maxBarThickness: 26,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', align: 'end', labels: { boxWidth: 10, boxHeight: 10 } } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, grid: { color: '#f3f4f6' } }
                },
                animation: { duration: 900, easing: 'easeOutQuart' }
            }
        });
    }

    // Ligne / barres Taux de réussite
    const ctxReussite = document.getElementById('chartReussite');
    if (ctxReussite) {
        new Chart(ctxReussite, {
            type: 'bar',
            data: {
                labels: [@foreach($tauxReussiteParClasse as $t){{ Js::from($t['classe']) }},@endforeach],
                datasets: [{
                    label: 'Taux de réussite (%)',
                    data: [@foreach($tauxReussiteParClasse as $t){{ $t['taux'] }},@endforeach],
                    backgroundColor: [@foreach($tauxReussiteParClasse as $t)'{{ $t['taux'] >= 70 ? '#1a7a1a' : ($t['taux'] >= 50 ? '#e67e22' : '#c0392b') }}',@endforeach],
                    borderRadius: 6,
                    maxBarThickness: 30,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, max: 100, grid: { color: '#f3f4f6' }, ticks: { callback: v => v + '%' } }
                },
                animation: { duration: 1000, easing: 'easeOutQuart' }
            }
        });
    }
});
</script>
@endpush