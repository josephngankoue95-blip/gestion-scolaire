<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0;padding:0;box-sizing:border-box; }
body { font-family:'DejaVu Sans',Arial,sans-serif;font-size:7.5px;color:#111; }
.header { text-align:center;border-bottom:2px solid #1a3a6b;padding-bottom:4px;margin-bottom:6px; }
.titre { font-weight:bold;font-size:10px;color:#1a3a6b;margin:4px 0 2px; }
.info { font-size:7.5px;margin-bottom:6px; }
table { width:100%;border-collapse:collapse; }
th { background:#1a3a6b;color:#fff;padding:3px 2px;font-size:7px;border:1px solid #333;text-align:center; }
td { border:1px solid #aaa;padding:2px 3px;font-size:7px;text-align:center; }
td.nom { text-align:left;padding-left:4px;font-weight:bold; }
.note-cell { min-width:30px;height:20px; }
.footer { font-size:6.5px;color:#666;text-align:right;margin-top:6px; }
.sig { display:flex;justify-content:space-between;margin-top:12px;font-size:7.5px; }
.sig-item { text-align:center;width:30%; }
.sig-line { border-top:1px solid #333;margin-top:25px;padding-top:2px; }
</style>
</head>
<body>

<div class="header">
    <div style="font-weight:bold;">{{ strtoupper($etablissement->nom ?? '') }}</div>
    <div class="titre">
        RELEVÉ DE NOTES — {{ strtoupper($classe->nom) }}
        — {{ strtoupper($sequence->nom) }} ({{ strtoupper($sequence->trimestre->nom) }})
    </div>
    <div>Année scolaire : {{ $classe->anneeScolaire->libelle }}</div>
</div>

<table>
<thead>
<tr>
    <th style="width:5%;">#</th>
    <th style="width:18%;text-align:left;padding-left:3px;">NOM ET PRÉNOMS</th>
    <th style="width:10%;">MATRICULE</th>
    <th style="width:4%;">SEXE</th>
    @foreach ($matieres as $matiere)
        <th style="max-width:35px;">
            {{ strtoupper(substr($matiere->nom, 0, 8)) }}<br>
            <span style="font-size:6px;">({{ $matiere->pivot->coefficient }})</span>
        </th>
    @endforeach
    <th style="width:6%;">MOY<br>/20</th>
    <th style="width:5%;">RANG</th>
</tr>
</thead>
<tbody>
@foreach ($eleves->sortBy('nom') as $i => $eleve)
<tr>
    <td>{{ $i + 1 }}</td>
    <td class="nom">{{ strtoupper($eleve->nom) }} {{ $eleve->prenom }}</td>
    <td>{{ $eleve->matricule }}</td>
    <td>{{ $eleve->sexe }}</td>
    @foreach ($matieres as $matiere)
        <td class="note-cell"></td>
    @endforeach
    <td class="note-cell"></td>
    <td class="note-cell"></td>
</tr>
@endforeach

{{-- Ligne coefficient --}}
<tr style="background:#eff6ff;">
    <td colspan="4" style="text-align:right;font-weight:bold;padding-right:4px;">Coefficient</td>
    @foreach ($matieres as $matiere)
        <td style="font-weight:bold;">{{ $matiere->pivot->coefficient }}</td>
    @endforeach
    <td colspan="2"></td>
</tr>
{{-- Ligne Moy. classe --}}
<tr style="background:#fff3cd;">
    <td colspan="4" style="text-align:right;font-weight:bold;padding-right:4px;">Moy. Classe</td>
    @foreach ($matieres as $matiere)
        <td class="note-cell"></td>
    @endforeach
    <td colspan="2"></td>
</tr>
</tbody>
</table>

<div class="sig">
    <div class="sig-item">
        Le Professeur Principal<br>
        <strong>{{ strtoupper($classe->professeurPrincipal?->user?->name ?? '___') }}</strong>
        <div class="sig-line">Signature</div>
    </div>
    <div class="sig-item">
        Fait à {{ $etablissement->ville ?? 'Douala' }},
        le {{ now()->format('d/m/Y') }}
    </div>
    <div class="sig-item">
        Le Chef d'Établissement
        <div class="sig-line">Signature &amp; Cachet</div>
    </div>
</div>

<div class="footer">Imprimé le {{ now()->format('d/m/Y à H:i') }}</div>
</body>
</html>