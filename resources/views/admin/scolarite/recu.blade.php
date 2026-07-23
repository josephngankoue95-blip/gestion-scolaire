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
.row { display:flex;justify-content:space-between;margin-bottom:3px;font-size:8.5px; }
.lbl { color:#555; }
.val { font-weight:bold; }
.montant { font-size:16px;font-weight:bold;color:#1a3a6b;text-align:center;margin:8px 0;padding:5px;border:2px solid #1a3a6b;border-radius:4px; }
.sig { margin-top:12px;display:flex;justify-content:space-between;font-size:8px; }
.sig-line { border-top:1px solid #333;width:35mm;text-align:center;padding-top:3px;margin-top:20px; }
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

<div class="row"><span class="lbl">Élève :</span><span class="val">{{ strtoupper($paiement->scolarite->eleve->nomComplet()) }}</span></div>
<div class="row"><span class="lbl">Matricule :</span><span class="val">{{ $paiement->scolarite->eleve->matricule }}</span></div>
<div class="row"><span class="lbl">Classe :</span><span class="val">{{ $paiement->scolarite->classe->nom }}</span></div>
<div class="row"><span class="lbl">Année :</span><span class="val">{{ $paiement->scolarite->anneeScolaire->libelle }}</span></div>
<div class="row"><span class="lbl">Rubrique :</span><span class="val">{{ ucfirst(str_replace(['tranche','inscription','transport'],['Tranche ','Inscription','Transport'],$paiement->type)) }}</span></div>
<div class="row"><span class="lbl">Date :</span><span class="val">{{ $paiement->date_paiement->format('d/m/Y') }}</span></div>

<div class="montant">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</div>

@if($paiement->note)
    <div style="font-size:7.5px;font-style:italic;color:#555;margin-bottom:5px;">Note : {{ $paiement->note }}</div>
@endif

<div class="sig">
    <div>
        <div style="font-size:7.5px;">Caissier : {{ $paiement->enregistrePar->name ?? '' }}</div>
        <div class="sig-line">Signature</div>
    </div>
    <div>
        <div style="font-size:7.5px;">{{ $etablissement->ville ?? 'Douala' }}, le {{ $paiement->date_paiement->format('d/m/Y') }}</div>
        <div class="sig-line">Signature &amp; Cachet</div>
    </div>
</div>

<div class="footer">{{ strtoupper($etablissement->nom ?? '') }} — Ce reçu est un document officiel — Imprimé le {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>