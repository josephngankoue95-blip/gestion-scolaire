<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0;padding:0;box-sizing:border-box; }
body { font-family:'DejaVu Sans',Arial,sans-serif;font-size:8px;color:#111; }
.header { text-align:center;border-bottom:2px solid #1a3a6b;padding-bottom:5px;margin-bottom:8px; }
.header h2 { font-size:11px;font-weight:bold;color:#1a3a6b; }
.info { font-size:7.5px;margin-bottom:6px; }
table { width:100%;border-collapse:collapse; }
th { background:#1a3a6b;color:#fff;padding:3px 4px;font-size:7.5px;border:1px solid #333; }
td { border:1px solid #aaa;padding:2px 4px;font-size:7.5px; }
.sup { color:#1a7a1a;font-weight:bold; }
.inf { color:#c0392b;font-weight:bold; }
.rang { font-weight:bold;color:#1a3a6b; }
.footer { margin-top:15px;display:flex;justify-content:space-between;font-size:7.5px; }
.sig { text-align:center;width:33%; }
.sig-line { border-top:1px solid #333;margin-top:30px;padding-top:3px; }
</style>
</head>
<body>

<div class="header">
    <div>{{ strtoupper($etablissement->nom ?? '') }} — {{ strtoupper($classe->anneeScolaire->libelle) }}</div>
    <h2>
        PROCÈS VERBAL — {{ strtoupper($classe->nom) }} —
        {{ $type === 'annuel' ? 'ANNUEL' : strtoupper($periode->nom ?? '') }}
        ({{ $ordre === 'merite' ? 'PAR MÉRITE' : 'ORDRE ALPHABÉTIQUE' }})
    </h2>
</div>

<table>
<thead>
<tr>
    <th>#</th>
    <th>Rang</th>
    <th>Matricule</th>
    <th>Nom et Prénoms</th>
    <th>Sexe</th>
    <th>Moyenne</th>
    <th>Mention</th>
    <th>Notes ≥10</th>
    <th>Admis</th>
</tr>
</thead>
<tbody>
@foreach ($resultats as $i => $r)
@php
    $moy = $r['bulletin']['moyenne_generale'] ?? null;
    $nb10 = collect($r['bulletin']['details'] ?? [])->filter(fn($d) => ($d['moyenne'] ?? 0) >= 10)->count();
    $total = count($r['bulletin']['details'] ?? []);
@endphp
<tr>
    <td>{{ $i + 1 }}</td>
    <td class="rang">{{ $rangs[$r['eleve']->id] ?? '-' }}</td>
    <td>{{ $r['eleve']->matricule }}</td>
    <td>{{ strtoupper($r['eleve']->nom) }} {{ $r['eleve']->prenom }}</td>
    <td style="text-align:center;">{{ $r['eleve']->sexe }}</td>
    <td style="text-align:center;" class="{{ ($moy ?? 0) >= 10 ? 'sup' : 'inf' }}">
        {{ $moy !== null ? number_format($moy, 2) : '-' }}
    </td>
    <td>{{ $r['bulletin']['mention'] ?? '-' }}</td>
    <td style="text-align:center;">{{ $nb10 }}/{{ $total }}</td>
    <td style="text-align:center;" class="{{ ($moy ?? 0) >= 10 ? 'sup' : 'inf' }}">
        {{ ($moy ?? 0) >= 10 ? 'OUI' : 'NON' }}
    </td>
</tr>
@endforeach
</tbody>
</table>

<div class="footer">
    <div class="sig">
        <div>Le Professeur Principal</div>
        <div>{{ strtoupper($classe->professeurPrincipal?->user?->name ?? '_______________') }}</div>
        <div class="sig-line">Signature</div>
    </div>
    <div class="sig">
        <div>Fait à {{ $etablissement->ville ?? 'Douala' }}, le {{ now()->format('d/m/Y') }}</div>
    </div>
    <div class="sig">
        <div>Le Chef d'Établissement</div>
        <div class="sig-line">Signature &amp; Cachet</div>
    </div>
</div>

</body>
</html>