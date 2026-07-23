@extends('layouts.public')
@section('title', 'Suivi de candidature')

@section('content')
<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Suivi de candidature</div>
    </div>

    @php
        $badges = [
            'en_attente' => ['badge badge-warning', 'En attente de traitement'],
            'en_cours_examen' => ['badge badge-info', "En cours d'examen"],
            'acceptee' => ['badge badge-success', 'Candidature acceptée'],
            'refusee' => ['badge badge-danger', 'Candidature refusée'],
        ];

        [$class, $label] = $badges[$candidature->statut] ?? ['badge badge-other', 'Statut inconnu'];
    @endphp

    <!-- INFO CANDIDATURE -->
    <div class="grid-card">

        <div class="card">
            <div class="label">Référence</div>
            <div class="value">{{ $candidature->reference }}</div>
        </div>

        <div class="card">
            <div class="label">Nom complet</div>
            <div class="value">{{ $candidature->nomComplet() }}</div>
        </div>

        <div class="card">
            <div class="label">Date de naissance</div>
            <div class="value">
                {{ $candidature->date_naissance?->format('d/m/Y') ?? '-' }}
            </div>
        </div>

        <div class="card">
            <div class="label">Sexe</div>
            <div class="value">
                {{ $candidature->sexe === 'M' ? 'Masculin' : ($candidature->sexe === 'F' ? 'Féminin' : '-') }}
            </div>
        </div>

        <div class="card">
            <div class="label">Section</div>
            <div class="value">{{ $candidature->section?->nom ?? '-' }}</div>
        </div>

        <div class="card">
            <div class="label">Classe demandée</div>
            <div class="value">{{ $candidature->classe_demandee ?? '-' }}</div>
        </div>

        <div class="card">
            <div class="label">Parent</div>
            <div class="value">{{ $candidature->nom_parent ?? '-' }}</div>
        </div>

        <div class="card">
            <div class="label">Téléphone</div>
            <div class="value">{{ $candidature->telephone_parent ?? '-' }}</div>
        </div>

        <div class="card" style="grid-column:1/-1;">
            <div class="label">Statut</div>
            <div class="value">
                <span class="{{ $class }}">{{ $label }}</span>
            </div>
        </div>
    </div>

    <!-- DOCUMENTS -->
    <div class="section">
        <div class="section-title">Documents fournis</div>

        @forelse ($candidature->documents as $doc)
            <div class="card" style="margin-bottom: 10px;">
                <div class="label">{{ ucfirst(str_replace('_', ' ', $doc->type)) }}</div>
                <div class="value">
                    <a href="{{ asset('storage/' . $doc->chemin) }}" target="_blank">
                        {{ $doc->nom_fichier }}
                    </a>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="value" style="color:#6b7280;">
                    Aucun document fourni.
                </div>
            </div>
        @endforelse
    </div>

    @if($candidature->statut === 'refusee' && $candidature->motif_refus)
        <div class="section">
            <div class="section-title">Motif du refus</div>
            <div class="card">
                <div class="value">{{ $candidature->motif_refus }}</div>
            </div>
        </div>
    @endif

    @if($candidature->statut === 'acceptee')
        <div class="section">
            <div class="section-title">Information</div>
            <div class="card">
                <div class="value">
                    Félicitations ! Votre candidature a été acceptée.
                    Veuillez contacter l'établissement afin de finaliser l'inscription de votre enfant.
                </div>
            </div>
        </div>
    @endif

    <div class="section">
        <div class="card">
            <div class="value" style="color:#6b7280;">
                Vous recevrez automatiquement une notification par SMS ou WhatsApp dès qu'une décision sera prise concernant cette candidature.
            </div>
        </div>
    </div>

</div>
@endsection