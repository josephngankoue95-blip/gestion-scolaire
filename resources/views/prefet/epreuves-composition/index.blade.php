@extends('layouts.prefet')
@section('title', 'Épreuves de composition')
@section('content')
<div class="card">
    <div class="mb-4">
        <h3 class="font-semibold text-gray-800">Épreuves de composition — envoyées par les enseignants</h3>
        <p class="text-sm text-gray-500">Consultation uniquement — vous ne pouvez pas ajouter, modifier ou supprimer.</p>
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
                    <td>{{ $e->matiere?->nom ?? 'Aucune matière' }}</td>
                    <td>{{ $e->classe?->nom ?? 'Aucune classe' }}</td>
                    <td>{{ $e->sequence?->nom ?? 'Aucune séquence' }}</td>
                    <td>{{ $e->enseignant?->user?->name ?? 'Aucun enseignant' }}</td>
                    <td class="text-right">
                        <a href="{{ route('prefet.epreuves-composition.show', $e) }}" class="login-link">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray-400 py-6">Aucune épreuve reçue.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $epreuves->links() }}</div>
</div>
@endsection