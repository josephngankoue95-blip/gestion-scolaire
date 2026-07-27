@extends('layouts.prefet')
@section('title', 'Épreuves de composition')
@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">Épreuves de composition</h3>

    </div>

    <form method="GET" class="flex gap-3 mb-4">
        <select name="classe_id" class="form-select" onchange="this.form.submit()">
            <option value="">Toutes les classes</option>
            @foreach ($classes as $c)<option value="{{ $c->id }}" {{ request('classe_id')==$c->id?'selected':'' }}>{{ $c->nom }}</option>@endforeach
        </select>
        <select name="sequence_id" class="form-select" onchange="this.form.submit()">
            <option value="">Toutes les séquences</option>
            @foreach ($sequences as $s)<option value="{{ $s->id }}" {{ request('sequence_id')==$s->id?'selected':'' }}>{{ $s->nom }}</option>@endforeach
        </select>
    </form>

    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Titre</th><th>Matière</th><th>Classe</th><th>Séquence</th><th>Enseignant</th><th class="text-right">Action</th></tr></thead>
            <tbody>
                @forelse ($epreuves as $e)
                <tr>
                    <td class="font-medium">{{ $e->titre }}</td>
                    <td>{{ $e->matiere->nom }}</td>
                    <td>{{ $e->classe->nom ?? '-' }}</td>
                    <td>{{ $e->sequence->nom ?? '-' }}</td>
                    <td>{{ $e->enseignant->user->name ?? 'Préfecture' }}</td>
                    <td class="text-right">
                        <a href="{{ asset('storage/'.$e->fichier) }}" target="_blank" class="login-link mr-2">
                            <i data-lucide="download" class="w-3 h-3 inline"></i> Télécharger
                        </a>
                        @if($e->fichier_corrige)
                        <a href="{{ asset('storage/'.$e->fichier_corrige) }}" target="_blank" class="login-link">Corrigé</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray-400 py-6">Aucune épreuve.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $epreuves->links() }}</div>
</div>
@endsection