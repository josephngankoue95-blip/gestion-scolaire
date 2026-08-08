<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0;padding:0;box-sizing:border-box; }
body { font-family:'DejaVu Sans',Arial,sans-serif; font-size:9px; color:#111; }

.page { width:100%; padding:6mm 4mm; }

/* Table qui contient les 2 compartiments l'un sous l'autre */
.page-table { width:87%; border-collapse:collapse; }
.compartiment-cell { padding:0; vertical-align:top; }

.recu {
    width:100%;
    border:1.5px dashed #123d75;
    border-radius:4px;
    padding:4mm 5mm;
    position:relative;
}

.tag-copie {
    position:absolute;
    top:2mm; right:3mm;
    background:#eaf1ff;
    color:#123d75;
    font-size:6.5px;
    font-weight:bold;
    padding:1mm 3mm;
    border-radius:10px;
    letter-spacing:0.3px;
}

.header { text-align:center; border-bottom:1.5px dashed #333; padding-bottom:4px; margin-bottom:5px; }
.school { font-weight:bold; font-size:11px; color:#123d75; }
.title { font-weight:bold; font-size:10px; text-decoration:underline; margin:4px 0; }

.info-table { width:100%; border-collapse:collapse; margin-bottom:3px; }
.info-table td { padding:1.2px 0; font-size:8.5px; vertical-align:top; }
.info-table td.lbl { color:#555; width:38%; }
.info-table td.val { font-weight:bold; text-align:right; width:62%; }

.montant {
    font-size:15px; font-weight:bold; color:#1a3a6b;
    text-align:center; margin:7px 0; padding:4px;
    border:2px solid #1a3a6b; border-radius:4px;
}

.sig-table { width:100%; border-collapse:collapse; margin-top:10px; }
.sig-table td { width:50%; font-size:7.5px; vertical-align:top; }
.sig-table td.right { text-align:right; }
.sig-line { border-top:1px solid #333; width:32mm; text-align:center; padding-top:2px; margin-top:14px; }
.sig-line.right { margin-left:auto; }

.footer {
    border-top:1px dashed #333; margin-top:5px; padding-top:3px;
    text-align:center; font-size:6.5px; color:#666;
}

/* Ligne de découpe entre les deux compartiments */
.separateur {
    text-align:center;
    padding:3mm 0;
    position:relative;
}
.separateur-ligne {
    border-top:1px dashed #999;
    position:relative;
}
.separateur-icone {
    position:absolute;
    top:-3mm; left:50%;
    transform:translateX(-50%);
    background:#fff;
    padding:0 3mm;
    font-size:8px;
    color:#999;
}
</style>
</head>
<body>

<div class="page">
    <table class="page-table">

        {{-- ── COMPARTIMENT 1 : ORIGINAL (pour le parent) ── --}}
        <tr>
            <td class="compartiment-cell">
                <div class="recu">
                    <span class="tag-copie">ORIGINAL — PARENT</span>

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
                        <div style="font-size:7px;font-style:italic;color:#555;margin-bottom:4px;">Note : {{ $paiement->note }}</div>
                    @endif

                    <table class="sig-table">
                        <tr>
                            <td>
                                <div style="font-size:7px;">Caissier : {{ $paiement->enregistrePar->name ?? '' }}</div>
                                <div class="sig-line">Signature</div>
                            </td>
                            <td class="right">
                                <div style="font-size:7px;">{{ $etablissement->ville ?? 'Douala' }}, le {{ $paiement->date_paiement->format('d/m/Y') }}</div>
                                <div class="sig-line right">Signature &amp; Cachet</div>
                            </td>
                        </tr>
                    </table>

                    <div class="footer">{{ strtoupper($etablissement->nom ?? '') }} — Document officiel</div>
                </div>
            </td>
        </tr>

        {{-- ── SÉPARATEUR DE DÉCOUPE ── --}}
        <tr>
            <td class="separateur">
                <div class="separateur-ligne">
                    <span class="separateur-icone">✂ Découper ici</span>
                </div>
            </td>
        </tr>

        {{-- ── COMPARTIMENT 2 : DUPLICATA (pour l'établissement) ── --}}
        <tr>
            <td class="compartiment-cell">
                <div class="recu">
                    <span class="tag-copie">DUPLICATA — ÉTABLISSEMENT</span>

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
                        <div style="font-size:7px;font-style:italic;color:#555;margin-bottom:4px;">Note : {{ $paiement->note }}</div>
                    @endif

                    <table class="sig-table">
                        <tr>
                            <td>
                                <div style="font-size:7px;">Caissier : {{ $paiement->enregistrePar->name ?? '' }}</div>
                                <div class="sig-line">Signature</div>
                            </td>
                            <td class="right">
                                <div style="font-size:7px;">{{ $etablissement->ville ?? 'Douala' }}, le {{ $paiement->date_paiement->format('d/m/Y') }}</div>
                                <div class="sig-line right">Signature &amp; Cachet</div>
                            </td>
                        </tr>
                    </table>

                    <div class="footer">{{ strtoupper($etablissement->nom ?? '') }} — Document officiel — Imprimé le {{ now()->format('d/m/Y H:i') }}</div>
                </div>
            </td>
        </tr>

    </table>
</div>

</body>
</html>