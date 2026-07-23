<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0;padding:0;box-sizing:border-box; }
body { font-family:'DejaVu Sans',Arial,sans-serif;font-size:9px;color:#111; }
.header { text-align:center;border-bottom:3px solid #1a3a6b;padding-bottom:10px;margin-bottom:16px; }
.logo-box { width:60px;height:60px;border-radius:50%;overflow:hidden;margin:0 auto 8px;border:2px solid #1a3a6b; }
.logo-box img { width:100%;height:100%;object-fit:cover; }
.school { font-weight:bold;font-size:14px;color:#1a3a6b; }
.title { font-size:12px;font-weight:bold;margin-top:6px;text-transform:uppercase;letter-spacing:1px; }
.section-title { background:#1a3a6b;color:#fff;padding:5px 10px;font-weight:bold;font-size:10px;margin:16px 0 8px;border-radius:4px; }
.stats-grid { display:flex;gap:10px;margin-bottom:10px; }
.stat-box { flex:1;background:#f0f4ff;border-radius:6px;padding:10px;text-align:center; }
.stat-label { font-size:8px;color:#555; }
.stat-value { font-size:16px;font-weight:bold;color:#1a3a6b;margin-top:2px; }
table { width:100%;border-collapse:collapse;margin-bottom:10px; }
th { background:#dce6f5;color:#1a3a6b;padding:5px;font-size:8px;border:1px solid #333;text-align:center; }
td { border:1px solid #aaa;padding:4px;font-size:8.5px;text-align:center; }
.footer { text-align:center;font-size:7px;color:#888;margin-top:20px;border-top:1px solid #ccc;padding-top:6px; }
</style>
</head>
<body>

<div class="header">
    @if($etablissement->logo)
        <div class="logo-box"><img src="{{ public_path('storage/'.$etablissement->logo) }}"></div>
    @endif
    <div class="school">{{ strtoupper($etablissement->nom ?? '') }}</div>
    <div class="title">Rapport Général de Gestion Scolaire</div>
    <div style="font-size:9px;margin-top:4px;">Année scolaire : {{ $annee?->libelle }} — Généré le {{ now()->format('d/m/Y à H:i') }}</div>
</div>

<div class="section-title">Effectifs</div>
<div class="stats-grid">
    <div class="stat-box"><div class="stat-label">Élèves actifs</div><div class="stat-value">{{ $totalEleves }}</div></div>
    <div class="stat-box"><div class="stat-label">Enseignants</div><div class="stat-value">{{ $totalEns }}</div></div>
    <div class="stat-box"><div class="stat-label">Classes</div><div class="stat-value">{{ $effectifs->count() }}</div></div>
</div>

<table>
<thead><tr><th>Classe</th><th>Section</th><th>Effectif</th><th>Capacité</th><th>Taux</th></tr></thead>
<tbody>
@foreach ($effectifs as $c)
@php $taux = $c->capacite_max > 0 ? round(($c->nb_eleves/$c->capacite_max)*100) : 0; @endphp
<tr>
    <td>{{ $c->nom }}</td>
    <td>{{ $c->section->code }}</td>
    <td>{{ $c->nb_eleves }}</td>
    <td>{{ $c->capacite_max }}</td>
    <td>{{ $taux }}%</td>
</tr>
@endforeach
</tbody>
</table>

<div class="section-title">Situation Financière</div>
<div class="stats-grid">
    <div class="stat-box" style="background:#fef2f2;">
        <div class="stat-label">Total dû</div>
        <div class="stat-value" style="color:#c0392b;">{{ number_format($totalDu, 0, ',', ' ') }} F</div>
    </div>
    <div class="stat-box" style="background:#f0fdf4;">
        <div class="stat-label">Total encaissé</div>
        <div class="stat-value" style="color:#1a7a1a;">{{ number_format($totalPaye, 0, ',', ' ') }} F</div>
    </div>
    <div class="stat-box" style="background:#fff3cd;">
        <div class="stat-label">Reste à percevoir</div>
        <div class="stat-value" style="color:#e67e22;">{{ number_format($totalDu-$totalPaye, 0, ',', ' ') }} F</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Taux recouvrement</div>
        <div class="stat-value">{{ $totalDu > 0 ? round(($totalPaye/$totalDu)*100,1) : 0 }}%</div>
    </div>
</div>

<div class="footer">
    Document confidentiel généré automatiquement — {{ strtoupper($etablissement->nom ?? '') }} — {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>