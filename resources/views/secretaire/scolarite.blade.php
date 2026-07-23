@extends('layouts.secretaire')
@section('title', 'Scolarité')

@section('content')
<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Dossiers scolarité — {{ $annee?->libelle }}</h3>

    <form method="GET" class="flex gap-3 mb-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un élève..." class="form-input" style="max-width:220px;">
        <select name="classe_id" class="form-select" style="max-width:180px;">
            <option value="">Toutes les classes</option>
            @foreach ($classes as $c)
                <option value="{{ $c->id }}" {{ request('classe_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
            @endforeach
        </select>
        <select name="statut" class="form-select" style="max-width:160px;">
            <option value="">Tous</option>
            <option value="solde" {{ request('statut') === 'solde' ? 'selected' : '' }}>Soldé</option>
            <option value="dette" {{ request('statut') === 'dette' ? 'selected' : '' }}>Avec dette</option>
        </select>
        <button type="submit" class="btn-outline">Filtrer</button>
    </form>
    <div class="flex justify-between items-center mb-4">
    <h3 class="font-semibold text-gray-800">Dossiers scolarité — {{ $annee?->libelle }}</h3>
    <a href="{{ route('secretaire.scolarite') }}" class="btn-primary">
        <i data-lucide="plus" class="w-4 h-4"></i> Inscrire un élève
    </a>
    </div>

    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Élève</th><th>Classe</th><th>Total dû</th><th>Payé</th><th>Solde</th><th class="text-right">Action</th></tr></thead>
            <tbody>
                @forelse ($scolarites as $sc)
                <tr>
                    <td class="font-medium">{{ $sc->eleve->nomComplet() }}</td>
                    <td>{{ $sc->classe->nom }}</td>
                    <td>{{ number_format($sc->totalDu(), 0, ',', ' ') }}</td>
                    <td style="color:#1a7a1a;font-weight:bold;">{{ number_format($sc->totalPaye(), 0, ',', ' ') }}</td>
                    <td style="color:{{ $sc->solde() > 0 ? '#c0392b' : '#1a7a1a' }};font-weight:bold;">
                        {{ number_format($sc->solde(), 0, ',', ' ') }}
                    </td>
                    <td class="text-right">
                        <a href="{{ route('secretaire.scolarite.show', $sc) }}" class="login-link">Détail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray-400 py-6">Aucun dossier.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $scolarites->links() }}</div>
</div>
@endsection