@extends('layouts.admin')

@section('title', 'Détails élève')

@section('content')

<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Fiche élève</div>

        <a href="{{ route('admin.eleves.index', [
                'classe_id' => request('classe_id')
            ]) }}"
        class="btn-back">
            ← Retour
        </a>
        <a href="{{ route('admin.eleves.edit', [
        $eleve,
        'classe_id' => request('classe_id')
            ]) }}"
        class="btn-save">
            Modifier
        </a>
    </div>

    <!-- INFO ELEVE -->
    <div class="grid-card">

        <div class="card">
            <div class="label">Matricule</div>
            <div class="value">{{ $eleve->matricule }}</div>
        </div>

        <div class="card">
            <div class="label">Nom complet</div>
            <div class="value">{{ $eleve->nomComplet() }}</div>
        </div>

        <div class="card">
            <div class="label">Date naissance</div>
            <div class="value">
                {{ $eleve->date_naissance?->format('d/m/Y') ?? '-' }}
            </div>
        </div>

        <div class="card">
            <div class="label">Lieu naissance</div>
            <div class="value">{{ $eleve->lieu_naissance ?? '-' }}</div>
        </div>

        <div class="card">
            <div class="label">Sexe</div>
            <div class="value">
                {{ $eleve->sexe === 'M' ? 'Masculin' : ($eleve->sexe === 'F' ? 'Féminin' : '-') }}
            </div>
        </div>

        <div class="card">
            <div class="label">Statut</div>
            <div class="value">
                @if($eleve->statut === 'actif')
                    <span class="badge badge-active">Actif</span>
                @elseif($eleve->statut === 'inactif')
                    <span class="badge badge-inactive">Inactif</span>
                @else
                    <span class="badge badge-other">{{ ucfirst($eleve->statut ?? '-') }}</span>
                @endif
            </div>
        </div>

    </div>

    <!-- PARENTS -->
    <div class="section">
        <div class="section-title">Informations parent / tuteur</div>

        <div class="grid-card">

            <div class="card">
                <div class="label">Téléphone</div>
                <div class="value">{{ $eleve->telephone_parent ?? '-' }}</div>
            </div>

            <div class="card">
                <div class="label">Adresse</div>
                <div class="value">{{ $eleve->adresse ?? '-' }}</div>
            </div>

        </div>
    </div>

    <!-- SCOLARITÉ PAR ANNÉE -->
    <div class="section">
        <div class="section-title flex items-center justify-between gap-3 flex-wrap">
            <span>Scolarité par année</span>

            <form method="GET" class="flex items-center gap-2">
                @if(request('classe_id'))
                    <input type="hidden" name="classe_id" value="{{ request('classe_id') }}">
                @endif
                <label class="text-sm text-gray-500">Consulter l'année :</label>
                <select name="annee_id" class="form-select" style="max-width:220px;" onchange="this.form.submit()">
                    @foreach ($toutesAnnees as $a)
                        <option value="{{ $a->id }}" {{ $anneeConsultee?->id == $a->id ? 'selected' : '' }}>
                            {{ $a->libelle }} {{ $a->active ? '(active)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($scolariteAnnee)
        <div class="grid-card">

            <div class="card">
                <div class="label">Classe ({{ $anneeConsultee->libelle }})</div>
                <div class="value">
                    {{ $scolariteAnnee->classe->nom }}
                    <span class="text-gray-400" style="font-size:12px;">
                        ({{ $scolariteAnnee->classe->section->nom }})
                    </span>
                </div>
            </div>

            <div class="card">
                <div class="label">Type d'inscription</div>
                <div class="value">{{ ucfirst($scolariteAnnee->type_inscription ?? '-') }}</div>
            </div>

            <div class="card">
                <div class="label">Total dû</div>
                <div class="value">{{ number_format($scolariteAnnee->totalDu(), 0, ',', ' ') }} FCFA</div>
            </div>

            <div class="card">
                <div class="label">Total payé</div>
                <div class="value" style="color:#1a7a1a;">
                    {{ number_format($scolariteAnnee->totalPaye(), 0, ',', ' ') }} FCFA
                </div>
            </div>

            <div class="card">
                <div class="label">Solde</div>
                <div class="value" style="color:{{ $scolariteAnnee->solde() > 0 ? '#c0392b' : '#1a7a1a' }};">
                    {{ number_format($scolariteAnnee->solde(), 0, ',', ' ') }} FCFA
                </div>
            </div>

            <div class="card">
                <div class="label">Transport</div>
                <div class="value">{{ $scolariteAnnee->zoneTransport?->nom ?? 'Non' }}</div>
            </div>

        </div>

        <div class="flex gap-2 mt-3 flex-wrap">
            <a href="{{ route('admin.scolarite.show', $scolariteAnnee) }}" class="btn-back">
                <i data-lucide="wallet" class="w-4 h-4"></i> Dossier scolarité complet
            </a>
            <a href="{{ route('admin.bulletins.index', ['classe_id' => $scolariteAnnee->classe_id]) }}" class="btn-back">
                <i data-lucide="file-text" class="w-4 h-4"></i> Bulletins de {{ $anneeConsultee->libelle }}
            </a>
        </div>

        @else
        <div class="card text-center" style="color:#9ca3af;padding:24px;">
            Aucune inscription trouvée pour l'année {{ $anneeConsultee->libelle }}.
        </div>
        @endif
    </div>

</div>

@endsection