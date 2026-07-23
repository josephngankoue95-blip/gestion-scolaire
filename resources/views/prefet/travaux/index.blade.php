@extends('layouts.prefet')
@section('title', 'Travaux Dirigés')

@section('content')
<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Travaux Dirigés — tous enseignants</h3>

    <form method="GET" class="flex gap-3 mb-4">
        <select name="classe_id" class="form-select" onchange="this.form.submit()">
            <option value="">Toutes les classes</option>
            @foreach ($classes as $c)
                <option value="{{ $c->id }}" {{ request('classe_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
            @endforeach
        </select>
        <select name="enseignant_id" class="form-select" onchange="this.form.submit()">
            <option value="">Tous les enseignants</option>
            @foreach ($enseignants as $e)
                <option value="{{ $e->id }}" {{ request('enseignant_id') == $e->id ? 'selected' : '' }}>{{ $e->user->name }}</option>
            @endforeach
        </select>
    </form>

    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Titre</th><th>Matière</th><th>Classe</th><th>Enseignant</th><th>Statut</th><th class="text-right">Action</th></tr></thead>
            <tbody>
                @forelse ($travaux as $td)
                <tr>
                    <td class="font-medium">{{ $td->titre }}</td>
                    <td>{{ $td->matiere->nom }}</td>
                    <td>{{ $td->classe->nom }}</td>
                    <td>{{ $td->enseignant->user->name }}</td>
                    <td>
                        @if($td->statut() === 'actif') <span class="badge-green">Actif</span>
                        @elseif($td->statut() === 'expire') <span class="badge-red">Expiré</span>
                        @else <span class="badge-gray">{{ ucfirst($td->statut()) }}</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <a href="{{ route('prefet.travaux.show', $td) }}" class="login-link mr-2">Voir</a>
                        <a href="{{ route('prefet.travaux.imprimer', $td) }}" target="_blank" class="login-link">Imprimer</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray-400 py-6">Aucun TD.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $travaux->links() }}</div>
</div>
@endsection