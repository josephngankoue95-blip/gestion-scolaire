@extends('layouts.admin')

@section('title', 'Candidature ' . $candidature->reference)

@section('content')
<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Fiche candidature</div>

        <a href="{{ route('admin.candidatures.index') }}" class="btn-back">
            ← Retour
        </a>


    </div>

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
            <div class="label">Date naissance</div>
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
                @if($candidature->statut === 'en_attente')
                    <span class="badge badge-active">En attente</span>
                @elseif($candidature->statut === 'en_cours_examen')
                    <span class="badge badge-inactive">En cours d'examen</span>
                @elseif($candidature->statut === 'acceptee')
                    <span class="badge badge-active">Acceptée</span>
                @elseif($candidature->statut === 'refusee')
                    <span class="badge badge-other">Refusée</span>
                @else
                    <span class="badge badge-other">{{ ucfirst($candidature->statut ?? '-') }}</span>
                @endif
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
                    <a href="{{ asset('storage/' . $doc->chemin) }}" target="_blank" class="text-primary-600 hover:underline">
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

    <!-- DECISION -->
    <div class="section">
        <div class="section-title">Décision</div>

        @if ($candidature->statut === 'en_attente' || $candidature->statut === 'en_cours_examen')
            <div class="grid-card">

                <div class="card" style="grid-column:1/-1;">
                    <form method="POST" action="{{ route('admin.candidatures.accepter', $candidature) }}" class="space-y-4">
                        @csrf
                        <select name="classe_id" required class="w-full px-3 py-2 border rounded-lg">
                            <option value="">-- Choisir --</option>
                            @foreach ($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                            @endforeach
                        </select>

                        <button type="submit"
                                class="btn-save w-full"
                                onclick="return confirm('Accepter cette candidature de {{ $candidature->nomComplet() }} ?')">
                            Accepter l'élève
                        </button>
                    </form>
                </div>

                <div class="card" style="grid-column:1/-1;">
                    <form method="POST" action="{{ route('admin.candidatures.refuser', $candidature) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="label block mb-1">Motif de refus</label>
                            <textarea name="motif_refus" required rows="4"
                                      class="w-full px-3 py-2 border rounded-lg"></textarea>
                        </div>

                        <button type="submit"
                                class="btn-back w-full"
                                onclick="return confirm('Refuser cette candidature ?')">
                            Refuser la candidature
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="card">
                <div class="value" style="color:#6b7280;">
                    Cette candidature a déjà été traitée ({{ $candidature->statut }}).
                </div>
            </div>
        @endif
    </div>

</div>
@endsection