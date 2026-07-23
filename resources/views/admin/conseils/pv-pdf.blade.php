<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0;padding:0;box-sizing:border-box; }
body { font-family:'DejaVu Sans',Arial,sans-serif;font-size:9px;color:#111; }
.header { text-align:center;border-bottom:2px solid #1a3a6b;padding-bottom:6px;margin-bottom:10px; }
.school { font-weight:bold;font-size:12px;color:#1a3a6b; }
.title { font-size:11px;font-weight:bold;margin-top:4px; }
table { width:100%;border-collapse:collapse;margin-top:10px; }
th { background:#1a3a6b;color:#fff;padding:5px;font-size:8px;border:1px solid #333; }
td { border:1px solid #aaa;padding:4px;font-size:8.5px; }
.footer { margin-top:20px;display:flex;justify-content:space-between;font-size:8px; }
</style>
</head>
<body>

<div class="header">
    <div class="school">{{ strtoupper($etablissement->nom ?? '') }}</div>
    <div class="title">PROCÈS-VERBAL DU CONSEIL DE CLASSE — {{ strtoupper($conseil->classe->nom) }}</div>
    <div style="font-size:8px;margin-top:3px;">
        {{ $conseil->trimestre?->nom ?? 'Conseil ponctuel' }} · Séance du {{ $conseil->date_conseil->format('d/m/Y') }}
        @if($conseil->president) · Présidé par {{ $conseil->president->name }} @endif
    </div>
</div>

<table>
<thead><tr><th>Élève</th><th>Décision</th><th>Motif / Observation</th></tr></thead>
<tbody>
@foreach ($conseil->decisions as $d)
<tr>
    <td>{{ strtoupper($d->eleve->nom) }} {{ $d->eleve->prenom }}</td>
    <td>{{ $d->libelle() }}</td>
    <td>{{ $d->motif ?? $d->observation ?? '-' }}</td>
</tr>
@endforeach
</tbody>
</table>

<div class="footer">
    <div>Le Président du Conseil<br><br><br>_______________________</div>
    <div>Fait à {{ $etablissement->ville ?? 'Douala' }}, le {{ now()->format('d/m/Y') }}</div>
    <div>Le Chef d'Établissement<br><br><br>_______________________</div>
</div>

</body>
</html>