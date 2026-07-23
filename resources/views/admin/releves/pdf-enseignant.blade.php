<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0;padding:0;box-sizing:border-box; }
body { font-family:'DejaVu Sans',Arial,sans-serif;font-size:8px;color:#111; }
.header { text-align:center;border-bottom:2px solid #1a3a6b;padding-bottom:4px;margin-bottom:6px; }
.titre { font-weight:bold;font-size:10px;color:#1a3a6b;margin:3px 0; }
.section-titre { background:#1a3a6b;color:#fff;font-weight:bold;font-size:8.5px;padding:4px 6px;margin:8px 0 3px; }
table { width:100%;border-collapse:collapse;margin-bottom:6px; }
th { background:#dce6f5;padding:3px;font-size:7.5px;border:1px solid #333;text-align:center;color:#1a3a6b; }
td { border:1px solid #aaa;padding:2px 4px;font-size:7.5px;text-align:center; }
td.nom { text-align:left;font-weight:bold;padding-left:5px; }
.note-cell { min-width:35px;height:22px; }
.footer { font-size:6.5px;color:#666;text-align:right;margin-top:8px; }
</style>
</head>
<body>

<div class="header">
    <div style="font-weight:bold;">{{ strtoupper($etablissement->nom ?? '') }}</div>
    <div class="titre">
        FICHE DE RELEVÉ DE NOTES<br>
        {{ strtoupper($enseignant->user->name) }} — {{ strtoupper($sequence->nom) }}
    </div>
    <div style="font-size:7.5px;">Matricule : {{ $enseignant->matricule }}</div>
</div>

@foreach ($affectations as $affectation)
<div class="section-titre">
    {{ strtoupper($affectation->matiere->nom) }} — {{ strtoupper($affectation->classe->nom) }}
    ({{ strtoupper($affectation->classe->section->code) }})
    · Coefficient : {{ $affectation->classe->matieres()->where('matiere_id', $affectation->matiere_id)->first()?->pivot->coefficient ?? 1 }}
</div>

<table>
<thead>
<tr>
    <th style="width:5%;">#</th>
    <th style="text-align:left;padding-left:4px;">NOM ET PRÉNOMS</th>
    <th style="width:12%;">MATRICULE</th>
    <th style="width:6%;">SEXE</th>
    <th style="width:15%;">NOTE /20</th>
    <th style="width:15%;">OBSERVATIONS</th>
</tr>
</thead>
<tbody>
@foreach ($affectation->classe->eleves->sortBy('nom') as $i => $eleve)
<tr>
    <td>{{ $i + 1 }}</td>
    <td class="nom">{{ strtoupper($eleve->nom) }} {{ $eleve->prenom }}</td>
    <td>{{ $eleve->matricule }}</td>
    <td>{{ $eleve->sexe }}</td>
    <td class="note-cell"></td>
    <td class="note-cell"></td>
</tr>
@endforeach
<tr style="background:#fff3cd;">
    <td colspan="4" style="text-align:right;font-weight:bold;">Moyenne de la classe</td>
    <td class="note-cell"></td>
    <td></td>
</tr>
</tbody>
</table>
@endforeach

<div style="margin-top:15px;display:flex;justify-content:space-between;font-size:7.5px;">
    <div style="text-align:center;width:45%;">
        Signature de l'enseignant<br>
        <div style="border-top:1px solid #333;margin-top:25px;padding-top:2px;">{{ strtoupper($enseignant->user->name) }}</div>
    </div>
    <div style="text-align:center;width:45%;">
        Le Chef d'Établissement<br>
        <div style="border-top:1px solid #333;margin-top:25px;">Signature &amp; Cachet</div>
    </div>
</div>

<div class="footer">Imprimé le {{ now()->format('d/m/Y à H:i') }}</div>
</body>
</html>