@extends('layouts.secretaire')
@section('title', 'Scolarité')
@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">Dossiers scolarité — {{ $annee?->libelle }}</h3>
        <a href="{{ route('secretaire.inscription.create') }}" class="btn-save">
            <i data-lucide="plus" class="w-4 h-4"></i> Inscrire un élève
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
        <div class="stat-card"><p class="stat-label">Total dû</p><p class="stat-value" style="color:#c0392b;">{{ number_format($totalDu,0,',',' ') }}</p></div>
        <div class="stat-card"><p class="stat-label">Total payé</p><p class="stat-value" style="color:#1a7a1a;">{{ number_format($totalPaye,0,',',' ') }}</p></div>
    </div>

    <form method="GET" class="flex gap-2 flex-wrap mb-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un élève..." class="form-input" style="max-width:220px;">
        <select name="classe_id" class="form-select" style="max-width:180px;">
            <option value="">Toutes les classes</option>
            @foreach ($classes as $c)<option value="{{ $c->id }}" {{ request('classe_id')==$c->id?'selected':'' }}>{{ $c->nom }}</option>@endforeach
        </select>
        <select name="statut" class="form-select" style="max-width:160px;">
            <option value="">Tous</option>
            <option value="solde" {{ request('statut')==='solde'?'selected':'' }}>Soldé</option>
            <option value="dette" {{ request('statut')==='dette'?'selected':'' }}>Avec dette</option>
        </select>
        <button type="submit" class="btn-save">Filtrer</button>
    </form>

    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Élève</th><th>Classe</th><th>Total dû</th><th>Payé</th><th>Solde</th><th class="text-right">Action</th></tr></thead>
            <tbody>
                @forelse ($scolarites as $sc)
                <tr>
                    <td class="font-medium">{{ $sc->eleve->nomComplet() }}</td>
                    <td>{{ $sc->classe->nom }}</td>
                    <td>{{ number_format($sc->totalDu(),0,',',' ') }}</td>
                    <td style="color:#1a7a1a;font-weight:bold;">{{ number_format($sc->totalPaye(),0,',',' ') }}</td>
                    <td style="color:{{ $sc->solde()>0?'#c0392b':'#1a7a1a' }};font-weight:bold;">{{ number_format($sc->solde(),0,',',' ') }}</td>
                    <td class="text-right"><a href="{{ route('secretaire.scolarite.show', $sc) }}" class="login-link">Détail</a></td>
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