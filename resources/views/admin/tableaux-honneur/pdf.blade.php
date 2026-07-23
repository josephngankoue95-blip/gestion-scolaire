<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
@page { size: A4 landscape; margin:0; }
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'DejaVu Sans', Arial, sans-serif; color:#111; }

.page {
    width:297mm; height:210mm;
    position:relative;
    page-break-after:always;
    padding:10mm 14mm;
}
.page:last-child { page-break-after:auto; }

.border-outer { position:absolute; top:6mm; left:6mm; right:6mm; bottom:6mm; border:2px solid #123d75; border-radius:4px; }

.header-table { width:100%; border-collapse:collapse; margin-top:4mm; }
.header-left { width:66%; }
.header-right { width:34%; text-align:right; }

.rep-line { font-size:11px; font-weight:bold; color:#123d75; }
.devise-line { font-size:9px; color:#555; }
.school { font-size:26px; font-weight:800; color:#123d75; margin-top:1mm; }
.souligne { font-size:10px; color:#555; margin-top:1mm; }

.flag-img {
    width:32mm; height:20mm;
    display:flex;
    border:1px solid #999;
}
.flag-part { flex:1; }
.flag-green { background:#007a5e; }
.flag-red { background:#ce1126; display:flex; align-items:center; justify-content:center; }
.flag-star { color:#fcd116; font-size:16px; }
.flag-yellow { background:#fcd116; }

.ruban-wrap { text-align:center; margin-top:8mm; }
.ruban {
    display:inline-block;
    background:#123d75;
    color:#fff;
    padding:4mm 14mm;
    font-size:20px;
    font-weight:bold;
    letter-spacing:1px;
    clip-path: polygon(3% 0, 97% 0, 100% 50%, 97% 100%, 3% 100%, 0 50%);
}
.ruban-sub { font-size:12px; color:#123d75; margin-top:2mm; font-weight:bold; }
.annee-scolaire { text-align:center; font-size:11px; color:#555; margin-top:2mm; }

.corps-table { width:100%; margin-top:8mm; }
.left-info { width:32%; vertical-align:top; }
.right-content { width:68%; vertical-align:top; text-align:center; }

.info-box {
    border:1px solid #ccc;
    border-radius:6px;
    padding:4mm;
}
.info-box .item { margin-bottom:4mm; }
.info-box .lbl { font-size:9px; color:#666; }
.info-box .val { font-size:12px; font-weight:bold; color:#111; border-bottom:1px solid #eee; padding-bottom:1mm; }
.info-box .val.big { font-size:20px; color:#123d75; }

.intro { font-size:12px; color:#333; margin-bottom:3mm; }
.eleve-nom { font-size:26px; font-weight:800; color:#123d75; margin:3mm 0; }
.texte-merite { font-size:11px; color:#333; line-height:1.7; max-width:160mm; margin:0 auto; }
.encouragement { font-style:italic; font-size:11px; color:#555; margin-top:4mm; }

.sign-table { width:100%; margin-top:14mm; }
.sign-cell { text-align:center; font-size:9px; }
.sign-name { margin-top:8mm; font-weight:bold; font-size:10px; }
.sign-line { border-top:1px solid #333; margin-top:2mm; padding-top:1mm; font-size:8.5px; color:#555; }

.date-fait { text-align:right; font-size:10px; color:#333; margin-top:6mm; }
</style>
</head>
<body>

@foreach ($resultats as $r)
@php
    $eleve = $r['eleve'];
    $moy   = $r['bulletin']['moyenne_generale'] ?? 0;
    $rang  = $r['rang'] ?? '-';
    $mention = match(true){
        $moy >= 16 => 'EXCELLENTE',
        $moy >= 14 => 'TRÈS BIEN',
        $moy >= 12 => 'BIEN',
        default    => 'ASSEZ BIEN',
    };
@endphp
<div class="page">
    <div class="border-outer"></div>

    <table class="header-table">
        <tr>
            <td class="header-left">
                <div class="rep-line">RÉPUBLIQUE DU CAMEROUN</div>
                <div class="devise-line">Paix - Travail - Patrie</div>
                <div class="school">{{ strtoupper($etablissement->nom ?? '') }}</div>
                <div class="souligne">{{ $etablissement->devise ?? 'Discipline - Travail - Réussite' }}</div>
            </td>
            <td class="header-right">
                <div class="flag-img" style="margin-left:auto;">
                    <div class="flag-part flag-green"></div>
                    <div class="flag-part flag-red"><span class="flag-star">★</span></div>
                    <div class="flag-part flag-yellow"></div>
                </div>
            </td>
        </tr>
    </table>

    <div class="ruban-wrap">
        <div class="ruban">TABLEAU D'HONNEUR</div>
        <div class="ruban-sub">PAR MÉRITE</div>
        <div class="annee-scolaire">ANNÉE SCOLAIRE {{ $classe->anneeScolaire->libelle }}</div>
    </div>

    <table class="corps-table">
        <tr>
            <td class="left-info">
                <div class="info-box">
                    <div class="item"><div class="lbl">NOM & PRÉNOMS :</div><div class="val">{{ strtoupper($eleve->nom) }} {{ $eleve->prenom }}</div></div>
                    <div class="item"><div class="lbl">CLASSE :</div><div class="val">{{ $classe->nom }}</div></div>
                    <div class="item"><div class="lbl">ANNÉE SCOLAIRE :</div><div class="val">{{ $classe->anneeScolaire->libelle }}</div></div>
                    <div class="item"><div class="lbl">MOYENNE GÉNÉRALE :</div><div class="val big">{{ number_format($moy,2) }} /20</div></div>
                    <div class="item"><div class="lbl">RANG :</div><div class="val">{{ $rang }}<sup>{{ $rang == 1 ? 'er' : 'e' }}</sup> / {{ count($resultats) }}</div></div>
                    <div class="item"><div class="lbl">MENTION :</div><div class="val">{{ $mention }}</div></div>
                </div>
            </td>
            <td class="right-content">
                <div class="intro">Le {{ strtoupper($etablissement->nom ?? '') }} félicite</div>
                <div class="eleve-nom">{{ strtoupper($eleve->nom) }} {{ strtoupper($eleve->prenom) }}</div>
                <div class="texte-merite">
                    pour son excellent travail, son assiduité et son sens des responsabilités
                    qui lui ont permis de se distinguer en occupant le
                    <strong>{{ $rang }}<sup>{{ $rang == 1 ? 'er' : 'e' }}</sup> rang</strong>
                    du Tableau d'Honneur par Mérite.
                </div>
                <div class="encouragement">
                    Nous l'encourageons à maintenir cet esprit d'excellence et à être un modèle pour ses camarades.<br>
                    Félicitations et plein succès dans son parcours scolaire !
                </div>
                <div class="date-fait">Fait à {{ $etablissement->ville ?? 'Yaoundé' }}, le {{ now()->format('d F Y') }}</div>
            </td>
        </tr>
    </table>

    <table class="sign-table">
        <tr>
            <td class="sign-cell" style="width:50%;">
                Le Professeur Principal
                <div class="sign-line">Signature</div>
            </td>
            <td class="sign-cell" style="width:50%;">
                Le Proviseur / Chef d'Établissement
                <div class="sign-line">Signature &amp; Cachet</div>
            </td>
        </tr>
    </table>
</div>
@endforeach

</body>
</html>