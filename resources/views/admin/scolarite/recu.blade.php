<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0;padding:0;box-sizing:border-box; }
body { font-family:'DejaVu Sans',Arial,sans-serif;font-size:9px;color:#111;width:80mm;padding:5mm; }

.header { text-align:center;border-bottom:2px dashed #333;padding-bottom:5px;margin-bottom:5px; }
.school { font-weight:bold;font-size:11px; }
.title { font-weight:bold;font-size:10px;text-decoration:underline;margin:4px 0; }

/* Remplace .row (flex) par une table */
.info-table { width:100%; border-collapse:collapse; margin-bottom:3px; }
.info-table td { padding:1px 0; font-size:8.5px; vertical-align:top; }
.info-table td.lbl { color:#555; width:40%; }
.info-table td.val { font-weight:bold; text-align:right; width:60%; }

.montant { font-size:16px;font-weight:bold;color:#1a3a6b;text-align:center;margin:8px 0;padding:5px;border:2px solid #1a3a6b;border-radius:4px; }

/* Remplace .sig (flex) par une table */
.sig-table { width:100%; border-collapse:collapse; margin-top:12px; }
.sig-table td { width:50%; font-size:7.5px; vertical-align:top; }
.sig-table td.right { text-align:right; }
.sig-line { border-top:1px solid #333; width:35mm; text-align:center; padding-top:3px; margin-top:20px; }
.sig-line.right { margin-left:auto; }

.footer { border-top:1px dashed #333;margin-top:6px;padding-top:4px;text-align:center;font-size:7px;color:#666; }
</style>
</head>
<body>
<div class="header">
    <div class="school">{{ strtoupper($etablissement->nom ?? '') }}</div>
    <div style="font-size:7.5px;">{{ $etablissement->adresse ?? '' }} — Tel : {{ $etablissement->telephone ?? '' }}</div>
    <div class="title">REÇU DE PAIEMENT SCOLARITÉ</div>
    <div style="font-size:8px;font-weight:bold;">N° {{ $paiement->numero_recu }}</div>
</div>

<table class="info-table">
    <tr><td class="lbl">Élève :</td><td class="val">{{ strtoupper($paiement->scolarite->eleve->nomComplet()) }}</td></tr>
    <tr><td class="lbl">Matricule :</td><td class="val">{{ $paiement->scolarite->eleve->matricule }}</td></tr>
    <tr><td class="lbl">Classe :</td><td class="val">{{ $paiement->scolarite->classe->nom }}</td></tr>
    <tr><td class="lbl">Année :</td><td class="val">{{ $paiement->scolarite->anneeScolaire->libelle }}</td></tr>
    <tr><td class="lbl">Rubrique :</td><td class="val">{{ ucfirst(str_replace(['tranche','inscription','transport'],['Tranche ','Inscription','Transport'],$paiement->type)) }}</td></tr>
    <tr><td class="lbl">Date :</td><td class="val">{{ $paiement->date_paiement->format('d/m/Y') }}</td></tr>
</table>

<div class="montant">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</div>

@if($paiement->note)
    <div style="font-size:7.5px;font-style:italic;color:#555;margin-bottom:5px;">Note : {{ $paiement->note }}</div>
@endif

<table class="sig-table">
    <tr>
        <td>
            <div style="font-size:7.5px;">Caissier : {{ $paiement->enregistrePar->name ?? '' }}</div>
            <div class="sig-line">Signature</div>
        </td>
        <td class="right">
            <div style="font-size:7.5px;">{{ $etablissement->ville ?? 'Douala' }}, le {{ $paiement->date_paiement->format('d/m/Y') }}</div>
            <div class="sig-line right">Signature &amp; Cachet</div>
        </td>
    </tr>
</table>

<div class="footer">{{ strtoupper($etablissement->nom ?? '') }} — Ce reçu est un document officiel — Imprimé le {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>