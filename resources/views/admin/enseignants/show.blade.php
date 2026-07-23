@extends('layouts.admin')

@section('title', 'Détails enseignant')

@section('content')

<div class="container">

    <!-- HEADER -->
    <div class="topbar">

        <div class="title">
            Fiche enseignant
        </div>

        <div class="flex gap-2">

            {{-- RETOUR --}}
            <a href="{{ route('admin.enseignants.index', [
                    'search' => request('search')
                ]) }}"
               class="btn-back">
                ← Retour
            </a>

            {{-- MODIFIER --}}
            <a href="{{ route('admin.enseignants.edit', $enseignant) }}"
               class="btn-save">
                Modifier
            </a>

        </div>

    </div>

    <!-- INFO ENSEIGNANT -->
    <div class="grid-card">

        <div class="card">
            <div class="label">Matricule</div>
            <div class="value">{{ $enseignant->matricule }}</div>
        </div>

        <div class="card">
            <div class="label">Nom</div>
            <div class="value">{{ $enseignant->user->name }}</div>
        </div>

        <div class="card">
            <div class="label">Email</div>
            <div class="value">{{ $enseignant->user->email }}</div>
        </div>

        <div class="card">
            <div class="label">Téléphone</div>
            <div class="value">{{ $enseignant->user->telephone ?? '-' }}</div>
        </div>

        <div class="card">
            <div class="label">Spécialité</div>
            <div class="value">{{ $enseignant->specialite ?? '-' }}</div>
        </div>

        <div class="card">
            <div class="label">Diplôme</div>
            <div class="value">{{ $enseignant->diplome ?? '-' }}</div>
        </div>

        <div class="card">
            <div class="label">Statut</div>
            <div class="value">
                @if($enseignant->statut === 'actif')
                    <span class="badge badge-active">Actif</span>
                @else
                    <span class="badge badge-inactive">Inactif</span>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="label">Date recrutement</div>
            <div class="value">
                {{ $enseignant->date_recrutement?->format('d/m/Y') ?? '-' }}
            </div>
        </div>

    </div>

    <!-- AFFECTATIONS -->
    <div class="section">

        <div class="section-title">
            Affectations
        </div>

        @forelse($enseignant->affectationsAnneeActive as $affectation)

            <div class="inscription">

                <div class="grid-card">

                    <div class="card">
                        <div class="label">Matière</div>
                        <div class="value">
                            {{ $affectation->matiere?->nom ?? '-' }}
                        </div>
                    </div>

                    <div class="card">
                        <div class="label">Classe</div>
                        <div class="value">
                            {{ $affectation->classe?->nom ?? '-' }}
                        </div>
                    </div>

                    <div class="card">
                        <div class="label">Section</div>
                        <div class="value">
                            {{ $affectation->classe?->section?->code ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>

        @empty

            <div class="card">
                <div class="value" style="color:#6b7280;">
                    Aucune affectation pour cet enseignant.
                </div>
            </div>

        @endforelse

    </div>

</div>

@endsection