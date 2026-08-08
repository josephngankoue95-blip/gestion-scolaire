<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'DejaVu Sans', Arial, sans-serif; font-size:8px; color:#111; }

.page { width:100%; padding:4mm; }
.page-break { page-break-after:always; }

.row-table { width:100%; border-collapse:separate; border-spacing:4mm 0; margin-bottom:4mm; }
.row-table td { width:50%; vertical-align:top; }

.carte {
    width:92mm; height:58mm;
    border:1.5px solid #123d75;
    border-radius:4px;
    overflow:hidden;
    position:relative;
    background:#fff;
}

.carte-header-table { width:100%; border-collapse:collapse; border-bottom:2px solid #123d75; }
.carte-header-table td { padding:1.5mm 2.5mm; vertical-align:middle; }
.td-flag { width:11mm; }
.td-text { text-align:center; }
.td-logo { width:11mm; text-align:right; }

.logo-box {
    width:9mm; height:9mm;
    border-radius:50%;
    overflow:hidden;
    border:1px solid #123d75;
    background:#fff;
    display:inline-block;
}
.logo-box img { width:100%; height:100%; object-fit:cover; }
.header-text .rep { font-size:5.5px; font-weight:bold; color:#123d75; }
.header-text .devise { font-size:4.5px; color:#555; }
.header-text .school { font-size:8px; font-weight:800; color:#123d75; text-transform:uppercase; line-height:1.1; }

.flag-box-table { width:9mm; border-collapse:collapse; }
.flag-box-table td { width:3mm; height:6mm; border:0.5px solid #999; padding:0; }
.flag-green { background:#007a5e; }
.flag-red { background:#ce1126; text-align:center; vertical-align:middle; }
.flag-star { color:#fcd116; font-size:4.5px; }
.flag-yellow { background:#fcd116; }

.badge-titre {
    background:#123d75; color:#fff; text-align:center;
    font-size:8.5px; font-weight:bold; letter-spacing:0.5px; padding:1.3mm 0;
}
.badge-sous {
    background:#eaf1ff; color:#123d75; text-align:center;
    font-size:6px; font-weight:bold; padding:0.7mm 0;
}

.corps-table { width:100%; border-collapse:collapse; }
.corps-table td { padding:1.5mm 2.5mm; vertical-align:top; }
.td-photo { width:20mm; }

.photo-box {
    width:18mm; height:22mm;
    border:1px solid #6f95c9;
    background:#eef5ff;
    overflow:hidden;
}
.photo-box img { width:100%; height:100%; object-fit:cover; }
.photo-placeholder { text-align:center; padding-top:8mm; font-size:5.5px; color:#8098bd; }

.champ-table { width:100%; border-collapse:collapse; }
.champ-table td { padding:0.4mm 0; font-size:6.8px; vertical-align:top; }
.champ-table td.lbl { width:19mm; font-weight:bold; color:#123d75; }
.champ-table td.val { font-weight:bold; color:#111; }

/* Zone signature réservée à droite du corps */
.sig-zone {
    width:20mm;
    text-align:center;
    vertical-align:bottom;
    padding-top:1mm;
}
.sig-box {
    width:18mm; height:9mm;
    border:1px dashed #9ebae0;
    border-radius:2px;
    background:#fbfdff;
    overflow:hidden;
    text-align:center;
}
.sig-box img {
    max-width:18mm;
    max-height:9mm;
}
.sig-placeholder {
    font-size:5px;
    color:#c3d3e8;
    line-height:9mm;
}
.sig-label { font-size:4.5px; color:#7c93b0; margin-top:0.6mm; }

.footer {
    position:absolute; bottom:0; left:0; right:0;
    background:#123d75; color:#fff;
    font-size:5.5px; text-align:center; padding:1mm;
}
</style>
</head>
<body>

@php $chunks = $eleves->chunk(8); @endphp

@foreach ($chunks as $pageIndex => $chunk)
<div class="page">
    @foreach ($chunk->chunk(2) as $row)
    <table class="row-table">
        <tr>
        @foreach ($row as $eleve)
            <td>
                <div class="carte">

                    <table class="carte-header-table">
                        <tr>
                            <td class="td-flag">
                                <table class="flag-box-table">
                                    <tr>
                                        <td class="flag-green"></td>
                                        <td class="flag-red"><span class="flag-star">★</span></td>
                                        <td class="flag-yellow"></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="td-text">
                                <div class="header-text">
                                    <div class="rep">RÉPUBLIQUE DU CAMEROUN</div>
                                    <div class="devise">Paix - Travail - Patrie</div>
                                    <div class="school">{{ strtoupper($etablissement->nom ?? '') }}</div>
                                </div>
                            </td>
                            <td class="td-logo">
                                @if($etablissement->logo)
                                    <div class="logo-box"><img src="{{ public_path('storage/'.$etablissement->logo) }}" alt="Logo"></div>
                                @else
                                    <div class="logo-box"></div>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="badge-titre">CARTE D'IDENTITÉ SCOLAIRE</div>
                    <div class="badge-sous">ANNÉE SCOLAIRE {{ $classe->anneeScolaire->libelle }}</div>

                    <table class="corps-table">
                        <tr>
                            <td class="td-photo">
                                <div class="photo-box">
                                    @if($eleve->photo)
                                        <img src="{{ public_path('storage/'.$eleve->photo) }}" alt="Photo">
                                    @else
                                        <div class="photo-placeholder">PHOTO</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <table class="champ-table">
                                    <tr><td class="lbl">MATRICULE :</td><td class="val">{{ $eleve->matricule }}</td></tr>
                                    <tr><td class="lbl">NOM :</td><td class="val">{{ strtoupper($eleve->nom) }}</td></tr>
                                    <tr><td class="lbl">PRÉNOMS :</td><td class="val">{{ strtoupper($eleve->prenom) }}</td></tr>
                                    <tr><td class="lbl">CLASSE :</td><td class="val">{{ $classe->nom }}</td></tr>
                                    <tr><td class="lbl">SECTION :</td><td class="val">{{ $classe->section->nom ?? '-' }}</td></tr>
                                    <tr><td class="lbl">NÉ(E) LE :</td><td class="val">{{ $eleve->date_naissance?->format('d/m/Y') }}</td></tr>
                                    <tr><td class="lbl">LIEU :</td><td class="val">{{ strtoupper($eleve->lieu_naissance ?? '-') }}</td></tr>
                                    <tr><td class="lbl">SEXE :</td><td class="val">{{ $eleve->sexe === 'M' ? 'MASCULIN' : 'FÉMININ' }}</td></tr>
                                    <tr><td class="lbl">TÉL. PARENTS :</td><td class="val">{{ $eleve->telephone_parent ?? '-' }}</td></tr>
                                </table>
                            </td>
                            <td class="sig-zone">
                                <div class="sig-box">
                                    @if($etablissement->signature_proviseur ?? false)
                                        <img src="{{ public_path('storage/'.$etablissement->signature_proviseur) }}" alt="Signature">
                                    @else
                                        <div class="sig-placeholder">Signature</div>
                                    @endif
                                </div>
                                <div class="sig-label">Signature<br>Proviseur</div>
                            </td>
                        </tr>
                    </table>

                    <div class="footer">
                        Carte strictement personnelle — Valable {{ $classe->anneeScolaire->libelle }}
                    </div>
                </div>
            </td>
        @endforeach

        @if($row->count() === 1)
            <td></td>
        @endif
        </tr>
    </table>
    @endforeach
</div>
@if(!$loop->last)<div class="page-break"></div>@endif
@endforeach

</body>
</html>