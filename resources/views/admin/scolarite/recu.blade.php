<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<style>

@page {
    margin: 8mm;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    font-family: DejaVu Sans, sans-serif;
    font-size: 9px;
    color:#222;

}


/* ==============================
        CONTAINER REÇU
============================== */


.receipt{

    width:100%;

    height:132mm;

    border:1px solid #333;

    padding:5px;

    overflow:hidden;

    page-break-inside:avoid;

}



/* ==============================
        HEADER
============================== */


.header-table{

    width:100%;

    border-collapse:collapse;

}


.header-table td{

    vertical-align:middle;

}


.logo{

    width:65px;

}


.logo img{

    width:40px;
    height:40px;

}



.school{

    text-align:center;

    font-size:16px;

    font-weight:bold;

    color:#0d47a1;

}



.address{

    text-align:center;

    font-size:8px;

    color:#555;

    margin-top:2px;

}



.title{

    text-align:center;

    font-size:12px;

    font-weight:bold;

    margin-top:5px;

}



.badge{

    width:120px;

    margin:5px auto;

    padding:3px;

    text-align:center;

    color:white;

    background:#0d47a1;

    font-size:8px;

    font-weight:bold;

    border-radius:3px;

}



.numero{

    text-align:center;

    font-weight:bold;

    font-size:10px;

}



/* ==============================
        INFORMATIONS
============================== */


.info{

    width:100%;

    border-collapse:collapse;

    margin-top:5px;

}


.info td{

    padding:3px;

    border-bottom:1px dotted #aaa;

}


.label{

    width:30%;

    font-weight:bold;

    background:#f4f4f4;

}


.value{

    width:70%;

}




/* ==============================
        MONTANT
============================== */


.amount{

    margin-top:7px;

    padding:7px;

    text-align:center;

    border:2px solid #0d47a1;

    background:#eef5ff;

}



.amount-title{

    font-size:8px;

    color:#555;

}



.amount-value{

    font-size:20px;

    font-weight:bold;

    color:#0d47a1;

}



.amount-letter{

    font-size:8px;

    margin-top:2px;

}



/* ==============================
        NOTE
============================== */


.note{

    margin-top:5px;

    padding:4px;

    border:1px dashed #999;

    font-size:8px;

}



/* ==============================
        SIGNATURE
============================== */


.signature{

    width:100%;

    margin-top:5px;

    border-collapse:collapse;

}



.signature td{

    text-align:center;

    width:33%;

    font-size:8px;

}



.line{

    width:25mm;

    margin:6px auto 2px;

    border-top:1px solid #444;

}




/* ==============================
        FOOTER
============================== */


.footer{

    margin-top:5px;

    padding-top:4px;

    border-top:1px solid #ccc;

    text-align:center;

    font-size:7px;

    color:#666;

}



/* ==============================
        DECOUPE
============================== */


.cut{

    height:10mm;

    text-align:center;

    font-size:9px;

    font-weight:bold;

    padding:3px;

    margin:2mm 0;

    border-top:1px dashed #000;

    border-bottom:1px dashed #000;

}



</style>

</head>


<body>


@php

$rubrique = match($paiement->type){

    'inscription' => 'Inscription',

    'transport' => 'Transport',

    'tranche1' => '1ère Tranche',

    'tranche2' => '2ème Tranche',

    'tranche3' => '3ème Tranche',

    default => ucfirst($paiement->type)

};

@endphp



<!-- ==========================================
                RECU PARENT
=========================================== -->


<div class="receipt">


<table class="header-table">

<tr>


<td class="logo">

@if(!empty($etablissement->logo))

<img src="{{ public_path('storage/'.$etablissement->logo) }}">

@endif

</td>



<td>


<div class="school">

{{ strtoupper($etablissement->nom) }}

</div>



<div class="address">

{{ $etablissement->adresse }}

<br>

Tél : {{ $etablissement->telephone }}

@if($etablissement->email)

<br>

{{ $etablissement->email }}

@endif

</div>



<div class="title">

REÇU DE PAIEMENT DE SCOLARITÉ

</div>



<div class="badge">

ORIGINAL PARENT

</div>



<div class="numero">

N° {{ $paiement->numero_recu }}

</div>



</td>


<td width="15%"></td>


</tr>

</table>



<table class="info">


<tr>

<td class="label">
Élève
</td>

<td class="value">

{{ strtoupper($paiement->scolarite->eleve->nomComplet()) }}

</td>

</tr>



<tr>

<td class="label">
Matricule
</td>

<td class="value">

{{ $paiement->scolarite->eleve->matricule }}

</td>

</tr>



<tr>

<td class="label">
Classe
</td>

<td class="value">

{{ $paiement->scolarite->classe->nom }}

</td>

</tr>



<tr>

<td class="label">
Année scolaire
</td>

<td class="value">

{{ $paiement->scolarite->anneeScolaire->libelle }}

</td>

</tr>


<tr>

<td class="label">
Rubrique
</td>

<td class="value">

{{ $rubrique }}

</td>

</tr>

<tr>

<td class="label">
Date de paiement
</td>

<td class="value">

{{ $paiement->date_paiement->format('d/m/Y') }}

</td>

</tr>



<tr>

<td class="label">
Heure
</td>

<td class="value">

{{ $paiement->created_at->format('H:i') }}

</td>

</tr>



<tr>

<td class="label">
Caissier(ère)
</td>

<td class="value">

{{ $paiement->enregistrePar->name }}

</td>

</tr>


</table>



<!-- ==========================
        MONTANT PARENT
========================== -->


<div class="amount">


<div class="amount-title">

MONTANT PAYÉ

</div>



<div class="amount-value">

{{ number_format($paiement->montant,0,',',' ') }} FCFA

</div>



<div class="amount-letter">

Montant encaissé

</div>


</div>




@if($paiement->note)


<div class="note">


<strong>

Observation :

</strong>


{{ $paiement->note }}


</div>


@endif





<!-- ==========================
        SIGNATURE PARENT
========================== -->


<table class="signature">


<tr>


<td>

Parent


<div class="line"></div>


Signature


</td>



<td>

Caissière


<div class="line"></div>


{{ $paiement->enregistrePar->name }}


</td>



<td>

Direction


<div class="line"></div>


Cachet


</td>


</tr>


</table>





<div class="footer">


Document généré automatiquement le


{{ now()->format('d/m/Y à H:i') }}


<br><br>


<strong>

{{ strtoupper($etablissement->nom) }}

</strong>


@if($etablissement->telephone)

<br>

Tél :

{{ $etablissement->telephone }}

@endif


@if($etablissement->email)

<br>

{{ $etablissement->email }}

@endif


</div>



</div>





<!-- =====================================
            LIGNE DE DECOUPE
===================================== -->


<div class="cut">


✂ ─────────────── DÉCOUPER ICI ─────────────── ✂


</div>





<!-- =====================================
            SOUCHE CAISSE
===================================== -->


<div class="receipt">



<table class="header-table">


<tr>


<td class="logo">


@if(!empty($etablissement->logo))


<img src="{{ public_path('storage/'.$etablissement->logo) }}">


@endif


</td>



<td>



<div class="school">


{{ strtoupper($etablissement->nom) }}


</div>



<div class="address">


{{ $etablissement->adresse }}


<br>


Tél : {{ $etablissement->telephone }}



@if($etablissement->email)


<br>


{{ $etablissement->email }}


@endif



</div>




<div class="title">


REÇU DE PAIEMENT DE SCOLARITÉ


</div>




<div class="badge" style="background:#555;">


SOUCHE CAISSE


</div>




<div class="numero">


N° {{ $paiement->numero_recu }}


</div>




</td>



<td width="15%"></td>



</tr>



</table>





<table class="info">



<tr>

<td class="label">

Élève

</td>


<td class="value">


{{ strtoupper($paiement->scolarite->eleve->nomComplet()) }}


</td>


</tr>





<tr>


<td class="label">


Matricule


</td>



<td class="value">


{{ $paiement->scolarite->eleve->matricule }}


</td>


</tr>





<tr>


<td class="label">


Classe


</td>



<td class="value">


{{ $paiement->scolarite->classe->nom }}


</td>


</tr>





<tr>


<td class="label">


Année scolaire


</td>



<td class="value">


{{ $paiement->scolarite->anneeScolaire->libelle }}


</td>


</tr>




<tr>


<td class="label">


Rubrique


</td>



<td class="value">


{{ $rubrique }}


</td>


</tr>

<tr>


<td class="label">


Date de paiement


</td>



<td class="value">


{{ $paiement->date_paiement->format('d/m/Y') }}


</td>


</tr>




<tr>


<td class="label">


Heure


</td>



<td class="value">


{{ $paiement->created_at->format('H:i') }}


</td>


</tr>




<tr>


<td class="label">


Caissier(ère)


</td>



<td class="value">


{{ $paiement->enregistrePar->name }}


</td>


</tr>



</table>





<!-- ==========================
        MONTANT CAISSE
========================== -->


<div class="amount">



<div class="amount-title">


MONTANT ENCAISSÉ


</div>




<div class="amount-value">


{{ number_format($paiement->montant,0,',',' ') }} FCFA


</div>




<div class="amount-letter">


À conserver dans le registre de caisse


</div>



</div>






@if($paiement->note)


<div class="note">


<strong>

Observation :

</strong>



{{ $paiement->note }}



</div>


@endif








<!-- ==========================
        SIGNATURE CAISSE
========================== -->


<table class="signature">


<tr>



<td>


Caissière


<div class="line"></div>


{{ $paiement->enregistrePar->name }}


</td>





<td>


Contrôle


<div class="line"></div>


Visa Direction


</td>





<td>


Cachet


<div class="line"></div>


Établissement


</td>



</tr>


</table>







<!-- ==========================
        FOOTER CAISSE
========================== -->


<div class="footer">



Souche conservée par la caisse



<br><br>



Document généré automatiquement le


{{ now()->format('d/m/Y à H:i') }}



<br><br>



<strong>


{{ strtoupper($etablissement->nom) }}


</strong>



@if($etablissement->telephone)


<br>


Tél :


{{ $etablissement->telephone }}



@endif




@if($etablissement->email)


<br>


{{ $etablissement->email }}



@endif



</div>






</div>






</body>


</html>