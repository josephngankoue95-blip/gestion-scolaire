<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'DejaVu Sans', Arial, sans-serif; font-size:8px; color:#111; }

.page { width:100%; padding:4mm; }
.row { display:flex; gap:4mm; margin-bottom:4mm; }
.page-break { page-break-after:always; }

.carte {
    width:88mm; height:52mm;
    border:1.5px solid #123d75;
    border-radius:4px;
    overflow:hidden;
    position:relative;
    background:#fff;
}

/* Header */
.carte-header {
    background:#fff;
    border-bottom:2px solid #123d75;
    padding:2mm 3mm;
    display:flex;
    align-items:center;
    gap:2mm;
}
.logo-box {
    width:10mm; height:10mm;
    border-radius:50%;
    overflow:hidden;
    border:1px solid #123d75;
    flex-shrink:0;
    background:#fff;
}
.logo-box img { width:100%; height:100%; object-fit:cover; }
.header-text { flex:1; text-align:center; }
.header-text .rep { font-size:6px; font-weight:bold; color:#123d75; }
.header-text .devise { font-size:5px; color:#555; }
.header-text .school { font-size:8.5px; font-weight:800; color:#123d75; text-transform:uppercase; line-height:1.1; }

.flag-box {
    width:10mm; height:7mm;
    flex-shrink:0;
    display:flex;
    border:0.5px solid #999;
    overflow:hidden;
}
.flag-part { flex:1; }
.flag-green { background:#007a5e; }
.flag-red { background:#ce1126; display:flex; align-items:center; justify-content:center; }
.flag-star { color:#fcd116; font-size:5px; }
.flag-yellow { background:#fcd116; }

/* Badge titre */
.badge-titre {
    background:#123d75;
    color:#fff;
    text-align:center;
    font-size:9px;
    font-weight:bold;
    letter-spacing:0.5px;
    padding:1.5mm 0;
}
.badge-sous {
    background:#eaf1ff;
    color:#123d75;
    text-align:center;
    font-size:6.5px;
    font-weight:bold;
    padding:0.8mm 0;
}

/* Corps */
.corps {
    display:flex;
    padding:2mm 3mm;
    gap:3mm;
}
.photo-box {
    width:19mm; height:23mm;
    border:1px solid #6f95c9;
    background:#eef5ff;
    overflow:hidden;
    flex-shrink:0;
}
.photo-box img { width:100%; height:100%; object-fit:cover; }
.photo-placeholder { display:flex; align-items:center; justify-content:center; height:100%; font-size:6px; color:#8098bd; }

.infos { flex:1; }
.champ { display:flex; margin-bottom:1.3mm; font-size:7.5px; }
.champ .lbl { width:20mm; font-weight:bold; color:#123d75; }
.champ .val { flex:1; font-weight:bold; color:#111; }

.footer {
    position:absolute; bottom:0; left:0; right:0;
    background:#123d75;
    color:#fff;
    font-size:6px;
    text-align:center;
    padding:1mm;
}
</style>
</head>
<body>

@php $chunks = $eleves->chunk(10); @endphp

@foreach ($chunks as $pageIndex => $chunk)
<div class="page">
    @foreach ($chunk->chunk(2) as $row)
    <div class="row">
        @foreach ($row as $eleve)
        <div class="carte">

            <div class="carte-header">
                <div class="flag-box">
                    <div class="flag-part flag-green"></div>
                    <div class="flag-part flag-red"><span class="flag-star">★</span></div>
                    <div class="flag-part flag-yellow"></div>
                </div>
                <div class="header-text">
                    <div class="rep">RÉPUBLIQUE DU CAMEROUN</div>
                    <div class="devise">Paix - Travail - Patrie</div>
                    <div class="school">{{ strtoupper($etablissement->nom ?? '') }}</div>
                </div>
                @if($etablissement->logo)
                    <div class="logo-box"><img src="{{ public_path('storage/'.$etablissement->logo) }}" alt="Logo"></div>
                @else
                    <div class="logo-box"></div>
                @endif
            </div>

            <div class="badge-titre">CARTE SCOLAIRE</div>
            <div class="badge-sous">ANNÉE SCOLAIRE {{ $classe->anneeScolaire->libelle }}</div>

            <div class="corps">
                <div class="photo-box">
                    @if($eleve->photo)
                        <img src="{{ public_path('storage/'.$eleve->photo) }}" alt="Photo">
                    @else
                        <div class="photo-placeholder">PHOTO</div>
                    @endif
                </div>

                <div class="infos">
                    <div class="champ"><span class="lbl">NOM :</span><span class="val">{{ strtoupper($eleve->nom) }}</span></div>
                    <div class="champ"><span class="lbl">PRÉNOMS :</span><span class="val">{{ strtoupper($eleve->prenom) }}</span></div>
                    <div class="champ"><span class="lbl">CLASSE :</span><span class="val">{{ $classe->nom }}</span></div>
                    <div class="champ"><span class="lbl">NÉ(E) LE :</span><span class="val">{{ $eleve->date_naissance?->format('d/m/Y') }} à {{ strtoupper($eleve->lieu_naissance ?? '') }}</span></div>
                    <div class="champ"><span class="lbl">SEXE :</span><span class="val">{{ $eleve->sexe }}</span></div>
                    <div class="champ"><span class="lbl">TÉL. PARENTS :</span><span class="val">{{ $eleve->telephone_parent ?? '-' }}</span></div>
                    <div class="champ"><span class="lbl">ADRESSE :</span><span class="val" style="font-size:6.5px;">{{ $eleve->adresse ?? '-' }}</span></div>
                </div>
            </div>

            <div class="footer">
                Carte strictement personnelle et valable pour l'année scolaire en cours
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>
@if(!$loop->last)<div class="page-break"></div>@endif
@endforeach

</body>
</html>