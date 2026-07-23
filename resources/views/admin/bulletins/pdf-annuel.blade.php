<!DOCTYPE html>
<html lang="{{ $lang ?? 'fr' }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 12px 14px 10px 14px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }

        body {
            font-size: 8.5px;
            color: #111;
            background: #fff;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            table-layout: fixed;
        }

        .header-left,
        .header-right {
            width: 38%;
            vertical-align: top;
            padding: 2px 4px;
            font-size: 7.5px;
            text-align: center;
            word-wrap: break-word;
        }

        .header-center {
            width: 24%;
            text-align: center;
            vertical-align: middle;
        }

        .header-school {
            font-weight: bold;
            font-size: 10px;
            text-decoration: underline;
        }

        .header-sub {
            font-size: 7px;
            color: #333;
        }

        .logo-box {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            overflow: hidden;
            border: 2.5px solid #1a3a6b;
            margin: 0 auto;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-placeholder {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #1a3a6b;
            margin: 0 auto;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
            line-height: 72px;
        }

        .titre-annee {
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            margin: 3px 0 2px;
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
            padding: 2px 0;
        }

        .titre-bulletin {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin: 3px 0;
            border: 2px solid #1a3a6b;
            padding: 4px;
            letter-spacing: 0.5px;
            background: #f0f4ff;
            color: #1a3a6b;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
        }

        .info-table td {
            border: 1px solid #333;
            padding: 2px 5px;
            font-size: 8px;
        }

        .info-label {
            background: #e8edf5;
            font-weight: bold;
            width: 24%;
        }

        .info-value {
            font-weight: bold;
            font-size: 8.5px;
        }

        .notes-table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
            table-layout: fixed;
        }

        .notes-table th {
            background: #1a3a6b;
            color: #fff;
            padding: 3px 2px;
            font-size: 7.5px;
            text-align: center;
            border: 1px solid #333;
        }

        .notes-table td {
            border: 1px solid #aaa;
            padding: 2px 3px;
            font-size: 8px;
            text-align: center;
            vertical-align: middle;
        }

        .notes-table td.mat-name {
            text-align: left;
            padding-left: 4px;
        }

        .mat-nom {
            font-weight: bold;
        }

        .mat-ens {
            font-size: 6.5px;
            color: #555;
            font-style: italic;
        }

        .groupe-titre {
            background: #dce6f5;
            font-weight: bold;
            font-size: 8.5px;
            color: #1a3a6b;
            text-align: left;
            padding: 3px 5px;
            border: 1px solid #1a3a6b;
        }

        .total-row {
            background: #1a3a6b;
            color: #fff;
            font-weight: bold;
            font-size: 8.5px;
        }

        .total-row td {
            border: 1px solid #1a3a6b;
            padding: 3px;
            color: #fff;
        }

        .note-inf {
            color: #c0392b;
            font-weight: bold;
        }

        .note-sup {
            color: #1a7a1a;
            font-weight: bold;
        }

        .appre-ap {
            color: #0a5aad;
            font-weight: bold;
        }

        .appre-a {
            color: #1a7a1a;
            font-weight: bold;
        }

        .appre-eca {
            color: #e67e22;
            font-weight: bold;
        }

        .appre-na {
            color: #c0392b;
            font-weight: bold;
        }

        .resume-table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
            table-layout: fixed;
        }

        .resume-table td {
            border: 1px solid #333;
            padding: 2px 4px;
            font-size: 7.5px;
            vertical-align: top;
        }

        .resume-header {
            background: #1a3a6b;
            color: #fff;
            font-weight: bold;
            font-size: 8px;
            text-align: center;
            padding: 3px;
        }

        .resultat-label {
            background: #e8edf5;
            font-weight: bold;
        }

        .mention-box {
            font-weight: bold;
            font-size: 9px;
            background: #fff3cd;
            border: 2px solid #e6a817;
            padding: 3px 6px;
            text-align: center;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            padding: 2px 4px;
            font-size: 7.5px;
            border: 1px solid #333;
            vertical-align: top;
            height: 42px;
        }

        .footer-note {
            font-size: 6.5px;
            color: #666;
            text-align: center;
            margin-top: 4px;
            border-top: 1px solid #ccc;
            padding-top: 2px;
        }

        .legende {
            font-size: 6.5px;
            color: #333;
            margin-top: 3px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
@php
    $lang = $lang ?? 'fr';
    $isEn = $lang === 'en';

    $groupeNoms = [
        1 => $isEn ? 'GROUP I' : 'GROUPE I',
        2 => $isEn ? 'GROUP II' : 'GROUPE II',
        3 => $isEn ? 'GROUP III' : 'GROUPE III',
    ];

    $details = collect($bulletin['details'] ?? []);
    $groupes = $bulletin['groupes'] ?? [];
@endphp

<table class="header-table">
    <tr>
        <td class="header-left">
            <div>{{ $isEn ? 'REPUBLIC OF CAMEROON' : 'REPUBLIQUE DU CAMEROUN' }}</div>
            <div class="header-sub">{{ $isEn ? 'PEACE - WORK - FATHERLAND' : 'PAIX - TRAVAIL - PATRIE' }}</div>
            <div style="height:3px;"></div>
            <div>{{ $isEn ? 'MINISTRY OF SECONDARY EDUCATION' : 'MINISTERE DES ENSEIGNEMENTS SECONDAIRES' }}</div>
            @if(!empty($etablissement->region))
                <div>{{ $isEn ? 'REGIONAL DELEGATION' : 'DELEGATION REGIONALE' }} {{ strtoupper($etablissement->region) }}</div>
            @endif
            <div style="height:3px;"></div>
            <div class="header-school">{{ strtoupper($etablissement->nom ?? '') }}</div>
            <div style="font-size:7px;margin-top:2px;">
                {{ $isEn ? 'P.O. BOX' : 'B.P' }} : {{ $etablissement->bp ?? '----' }}
                {{ $etablissement->ville ?? '' }}
                - {{ $isEn ? 'Phone' : 'Tél' }} : {{ $etablissement->telephone ?? '---' }}
            </div>
        </td>

        <td class="header-center">
            @if(!empty($logoBase64))
                <div class="logo-box">
                    <img src="{{ $logoBase64 }}" alt="Logo">
                </div>
            @elseif(!empty($etablissement->logo))
                <div class="logo-box">
                    <img src="file://{{ public_path('storage/' . $etablissement->logo) }}" alt="Logo">
                </div>
            @else
                <div class="logo-placeholder">
                    {{ strtoupper(substr($etablissement->sigle ?? 'LC', 0, 2)) }}
                </div>
            @endif

            @if(!empty($etablissement->devise))
                <div style="font-size:6.5px;font-style:italic;margin-top:3px;color:#1a3a6b;">
                    {{ $etablissement->devise }}
                </div>
            @endif
        </td>

        <td class="header-right">
            <div>{{ $isEn ? 'REPUBLIC OF CAMEROON' : 'REPUBLIQUE DU CAMEROUN' }}</div>
            <div class="header-sub">{{ $isEn ? 'PEACE - WORK - FATHERLAND' : 'PAIX - TRAVAIL - PATRIE' }}</div>
            <div style="height:3px;"></div>
            <div>{{ $isEn ? 'MINISTRY OF SECONDARY EDUCATION' : 'MINISTERE DES ENSEIGNEMENTS SECONDAIRES' }}</div>
            @if(!empty($etablissement->region))
                <div>{{ $isEn ? 'REGIONAL DELEGATION' : 'DELEGATION REGIONALE' }} {{ strtoupper($etablissement->region) }}</div>
            @endif
            <div style="height:3px;"></div>
            <div class="header-school">{{ strtoupper($etablissement->nom ?? '') }}</div>
            <div style="font-size:7px;margin-top:2px;">
                {{ $isEn ? 'P.O. BOX' : 'B.P' }} : {{ $etablissement->bp ?? '----' }}
                {{ $etablissement->ville ?? '' }}
                - {{ $isEn ? 'Phone' : 'Tél' }} : {{ $etablissement->telephone ?? '---' }}
            </div>
        </td>
    </tr>
</table>

<div class="titre-annee">
    {{ $isEn ? 'ACADEMIC YEAR' : 'ANNÉE SCOLAIRE' }} : {{ $classe->anneeScolaire->libelle ?? '' }}
</div>

<div class="titre-bulletin">
    {{ $isEn ? 'ANNUAL REPORT CARD' : 'BULLETIN ANNUEL' }}
</div>

<table class="info-table">
    <tr>
        <td class="info-label">{{ $isEn ? 'FULL NAME' : 'NOMS ET PRÉNOMS' }}</td>
        <td class="info-value" colspan="3">{{ strtoupper($eleve->nomComplet()) }}</td>
        <td class="info-label">{{ $isEn ? 'CLASS' : 'CLASSE' }}</td>
        <td class="info-value">{{ $classe->nom ?? '' }}</td>
    </tr>
    <tr>
        <td class="info-label">{{ $isEn ? 'REGISTRATION No.' : 'MATRICULE' }}</td>
        <td class="info-value" colspan="3">{{ $eleve->matricule ?? '' }}</td>
        <td class="info-label">{{ $isEn ? 'SEX' : 'SEXE' }}</td>
        <td class="info-value">
            {{ $eleve->sexe === 'M' ? ($isEn ? 'MALE' : 'MASCULIN') : ($isEn ? 'FEMALE' : 'FÉMININ') }}
        </td>
    </tr>
    <tr>
        <td class="info-label">{{ $isEn ? 'DATE & PLACE OF BIRTH' : 'DATE ET LIEU DE NAISSANCE' }}</td>
        <td class="info-value" colspan="3">
            {{ $eleve->date_naissance?->format('d/m/Y') ?? '-' }}
            @if(!empty($eleve->lieu_naissance))
                {{ $isEn ? 'in' : 'à' }} {{ strtoupper($eleve->lieu_naissance) }}
            @endif
        </td>
        <td class="info-label">{{ $isEn ? 'REPEATER' : 'REDOUBLANT' }}</td>
        <td class="info-value">{{ $isEn ? 'No' : 'Non' }}</td>
    </tr>
    <tr>
        <td class="info-label">{{ $isEn ? 'FORM MASTER' : 'PROFESSEUR PRINCIPAL' }}</td>
        <td class="info-value" colspan="3">
            {{ strtoupper($classe->professeurPrincipal?->user?->name ?? '-') }}
        </td>
        <td class="info-label">{{ $isEn ? 'CLASS SIZE' : 'EFFECTIF' }}</td>
        <td class="info-value">{{ $bulletin['effectif'] ?? '-' }}</td>
    </tr>
</table>

<table class="notes-table">
    <thead>
        <tr>
            <th style="width:20%; text-align:left; padding-left:4px;">
                {{ $isEn ? 'Subjects' : 'Matières' }}
            </th>
            <th style="width:7%;">{{ $isEn ? 'T1 Avg' : 'Moy T1' }}</th>
            <th style="width:7%;">{{ $isEn ? 'T2 Avg' : 'Moy T2' }}</th>
            <th style="width:7%;">{{ $isEn ? 'T3 Avg' : 'Moy T3' }}</th>
            <th style="width:7%;">{{ $isEn ? 'Ann. Avg' : 'Moy An.' }}</th>
            <th style="width:5%;">{{ $isEn ? 'Coef.' : 'Coef.' }}</th>
            <th style="width:8%;">{{ $isEn ? 'Points' : 'Points' }}</th>
            <th style="width:10%;">{{ $isEn ? 'Remarks' : 'Appréciations' }}</th>
        </tr>
    </thead>
    <tbody>
        @php $currentGroupe = null; @endphp

        @foreach($details as $detail)
            @if($currentGroupe !== ($detail['groupe'] ?? 0))
                @php $currentGroupe = $detail['groupe'] ?? 0; @endphp
                @if($currentGroupe > 0)
                    <tr>
                        <td colspan="8" class="groupe-titre">
                            {{ $groupeNoms[$currentGroupe] ?? (($isEn ? 'GROUP' : 'GROUPE') . ' ' . $currentGroupe) }}
                        </td>
                    </tr>
                @endif
            @endif

            @php
                $moy = $detail['moyenne'] ?? null;
                $moyClass = ($moy !== null && $moy < 10) ? 'note-inf' : 'note-sup';
                $appClass = match($detail['appreciation'] ?? '') {
                    'A+' => 'appre-ap',
                    'A'  => 'appre-a',
                    'ECA' => 'appre-eca',
                    default => 'appre-na',
                };
            @endphp

            <tr>
                <td class="mat-name">
                    <div class="mat-nom">{{ $detail['matiere'] ?? '' }}</div>
                    @if(!empty($detail['enseignant']))
                        <div class="mat-ens">{{ $detail['enseignant'] }}</div>
                    @endif
                </td>
                <td>{{ isset($detail['moy_t1']) ? number_format($detail['moy_t1'], 1) : '-' }}</td>
                <td>{{ isset($detail['moy_t2']) ? number_format($detail['moy_t2'], 1) : '-' }}</td>
                <td>{{ isset($detail['moy_t3']) ? number_format($detail['moy_t3'], 1) : '-' }}</td>
                <td class="{{ $moyClass }}">{{ $moy !== null ? number_format($moy, 1) : '-' }}</td>
                <td>{{ $detail['coefficient'] ?? '-' }}</td>
                <td>{{ isset($detail['points']) ? number_format($detail['points'], 1) : '-' }}</td>
                <td class="{{ $appClass }}">{{ $detail['appreciation'] ?? '-' }}</td>
            </tr>
        @endforeach

        @foreach($groupes as $numGroupe => $dataGroupe)
            <tr class="total-row">
                <td colspan="4" style="text-align:right;">
                    {{ $isEn ? 'TOTAL' : 'TOTAL' }} {{ $groupeNoms[$numGroupe] ?? '' }}
                </td>
                <td></td>
                <td style="text-align:center;">{{ $dataGroupe['total_coef'] ?? '-' }}</td>
                <td style="text-align:center;">{{ isset($dataGroupe['total_points']) ? number_format($dataGroupe['total_points'], 1) : '-' }}</td>
                <td style="text-align:center;font-size:9px;font-weight:bold;">
                    {{ isset($dataGroupe['moyenne']) ? number_format($dataGroupe['moyenne'], 2).'/20' : '-' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<table class="resume-table">
    <tr>
        <td style="width:25%; vertical-align:top;">
            <div class="resume-header">{{ $isEn ? 'STUDENT RESULT' : 'RÉSULTAT DE L\'ÉLÈVE' }}</div>
            <table style="width:100%;border-collapse:collapse;margin-top:2px;">
                <tr>
                    <td class="resultat-label" style="border:1px solid #ccc;padding:2px 3px;font-size:7.5px;">
                        {{ $isEn ? 'Annual Avg' : 'Moyenne Annuelle' }}
                    </td>
                    <td style="border:1px solid #ccc;padding:2px 3px;font-weight:bold;font-size:9px;color:#1a3a6b;">
                        {{ isset($bulletin['moyenne_generale']) ? number_format($bulletin['moyenne_generale'],2).'/20' : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="resultat-label" style="border:1px solid #ccc;padding:2px 3px;font-size:7.5px;">
                        {{ $isEn ? 'Rank' : 'Rang' }}
                    </td>
                    <td style="border:1px solid #ccc;padding:2px 3px;font-weight:bold;">
                        {{ $bulletin['rang'] ?? '-' }} / {{ $bulletin['effectif'] ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="resultat-label" style="border:1px solid #ccc;padding:2px 3px;font-size:7.5px;">
                        {{ $isEn ? 'Total Points' : 'Total Points' }}
                    </td>
                    <td style="border:1px solid #ccc;padding:2px 3px;">
                        {{ number_format($bulletin['total_points'] ?? 0, 1) }}
                        / {{ number_format(($bulletin['total_coef'] ?? 0) * 20, 1) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center;padding:3px;border:1px solid #ccc;">
                        <div class="mention-box">{{ $bulletin['mention'] ?? '-' }}</div>
                    </td>
                </tr>
            </table>
        </td>

        <td style="width:30%; vertical-align:top;">
            <div class="resume-header">{{ $isEn ? 'CLASS PROFILE' : 'PROFIL DE LA CLASSE' }}</div>
            <table style="width:100%;border-collapse:collapse;margin-top:2px;">
                @foreach ([
                    ['key' => 'moy_sup10', 'val' => $bulletin['profil_classe']['nb_sup10'] ?? '-'],
                    ['key' => 'moy_classe', 'val' => isset($bulletin['profil_classe']['moyenne']) ? number_format($bulletin['profil_classe']['moyenne'],2) : '-'],
                    ['key' => 'taux_reussite', 'val' => ($bulletin['profil_classe']['taux_reussite'] ?? '0').'%'],
                    ['key' => 'moy_premier', 'val' => isset($bulletin['profil_classe']['moy_premier']) ? number_format($bulletin['profil_classe']['moy_premier'],2) : '-'],
                    ['key' => 'moy_dernier', 'val' => isset($bulletin['profil_classe']['moy_dernier']) ? number_format($bulletin['profil_classe']['moy_dernier'],2) : '-'],
                ] as $row)
                    <tr>
                        <td class="resultat-label" style="border:1px solid #ccc;padding:2px 3px;font-size:7px;">
                            @if($row['key'] === 'moy_sup10')
                                {{ $isEn ? 'Average ≥ 10' : 'Moyenne ≥ 10' }}
                            @elseif($row['key'] === 'moy_classe')
                                {{ $isEn ? 'Class Avg' : 'Moy. Classe' }}
                            @elseif($row['key'] === 'taux_reussite')
                                {{ $isEn ? 'Pass Rate' : 'Taux Réussite' }}
                            @elseif($row['key'] === 'moy_premier')
                                {{ $isEn ? 'Best Avg' : 'Moy. Premier' }}
                            @else
                                {{ $isEn ? 'Lowest Avg' : 'Moy. Dernier' }}
                            @endif
                        </td>
                        <td style="border:1px solid #ccc;padding:2px 3px;font-weight:bold;">{{ $row['val'] }}</td>
                    </tr>
                @endforeach
            </table>
        </td>

        <td style="width:25%; vertical-align:top;">
            <div class="resume-header">{{ $isEn ? 'WORK & CONDUCT' : 'TRAVAIL & CONDUITE' }}</div>
            <table style="width:100%;border-collapse:collapse;margin-top:2px;">
                <tr>
                    <td class="resultat-label" style="border:1px solid #ccc;padding:2px 3px;font-size:7px;">
                        {{ $isEn ? 'Honor Roll' : 'Tableau d\'Honneur' }}
                    </td>
                    <td style="border:1px solid #ccc;padding:2px 3px;">{{ $isEn ? 'No' : 'Non' }}</td>
                </tr>
                <tr>
                    <td class="resultat-label" style="border:1px solid #ccc;padding:2px 3px;font-size:7px;">
                        {{ $isEn ? 'Total Absences' : 'Absences Totales' }}
                    </td>
                    <td style="border:1px solid #ccc;padding:2px 3px;font-weight:bold;">
                        {{ $bulletin['absences_total'] ?? 0 }} H
                    </td>
                </tr>
                <tr>
                    <td class="resultat-label" style="border:1px solid #ccc;padding:2px 3px;font-size:7px;">
                        {{ $isEn ? 'Unj. Absences' : 'Absences NJ' }}
                    </td>
                    <td style="border:1px solid #ccc;padding:2px 3px;font-weight:bold;color:#c0392b;">
                        {{ $bulletin['absences_nj'] ?? 0 }} H
                    </td>
                </tr>
            </table>
        </td>

        <td style="width:20%; vertical-align:top;">
            <div class="resume-header">{{ $isEn ? 'CLASS DECISION' : 'DÉCISION CONSEIL' }}</div>
            <div style="border:1px solid #ccc;padding:5px;font-size:7.5px;margin-top:2px;min-height:55px;">
                @php
                    $mats10 = $details->filter(fn($d) => ($d['moyenne'] ?? 0) >= 10)->count();
                    $total = $details->count();
                @endphp
                {{ $isEn ? 'PASSES IN' : 'OBTIENT LA MOYENNE DANS' }}
                <strong>{{ $mats10 }}/{{ $total }}</strong>
                {{ $isEn ? 'SUBJECT(S)' : 'MATIÈRE(S)' }}
            </div>
        </td>
    </tr>
</table>

<table class="signature-table">
    <tr>
        <td>
            <div style="font-weight:bold;border-bottom:1px solid #ccc;margin-bottom:3px;">
                {{ $isEn ? 'PARENT\'S SIGNATURE' : 'VISA DU PARENT' }}
            </div>
            <div style="height:28px;"></div>
        </td>
        <td>
            <div style="font-weight:bold;border-bottom:1px solid #ccc;margin-bottom:3px;">
                {{ $isEn ? 'CLASS COUNCIL DECISION' : 'DÉCISION DU CONSEIL DE CLASSE' }}
            </div>
            <div style="height:28px;"></div>
        </td>
        <td>
            <div style="font-weight:bold;border-bottom:1px solid #ccc;margin-bottom:3px;">
                {{ $isEn ? 'PRINCIPAL\'S SIGNATURE' : 'VISA DU CHEF D\'ÉTABLISSEMENT' }}
            </div>
            <div style="font-size:7px;margin-top:2px;">
                {{ $etablissement->ville ?? 'Douala' }}, {{ $isEn ? 'the' : 'le' }} {{ now()->format('d/m/Y') }}
            </div>
            <div style="font-size:7px;">
                {{ $isEn ? 'THE PRINCIPAL' : 'LE '.strtoupper($etablissement->type_etablissement ?? 'PROVISEUR') }}
            </div>
            <div style="height:15px;"></div>
        </td>
    </tr>
</table>

<div class="legende">
    @if($isEn)
        <strong>Legend:</strong> NA = NOT ACQUIRED | ECA = IN PROGRESS | A = ACQUIRED | A+ = PERFECTLY ACQUIRED
    @else
        <strong>Légende :</strong> NA = NON ACQUIS | ECA = EN COURS D'ACQUISITION | A = ACQUIS | A+ = PARFAITEMENT ACQUIS
    @endif
</div>

<div class="footer-note">
    {{ $isEn ? 'This report is issued without erasure or overwriting' : 'Le bulletin est délivré sans rature, ni surcharge' }}
    — {{ $isEn ? 'Printed on' : 'Imprimé le' }} {{ now()->format('d/m/Y à H:i') }}
</div>
</body>
</html>