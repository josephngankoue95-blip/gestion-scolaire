@extends('layouts.admin')
@section('title', 'Épreuves d\'examen')
@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold">Épreuves d'examen (archives)</h3>
            <p class="text-sm text-gray-500">Sujets des années précédentes, consultables par les élèves</p>
        </div>
        <a href="{{ route('teacher.epreuves-examen.create') }}" class="btn-primary">+ Insérer une épreuve</a>
    </div>

    <form method="GET" class="flex gap-3 mb-4">
        <select name="niveau_id" class="form-select" onchange="this.form.submit()">
            <option value="">Tous les niveaux</option>
            @foreach ($niveaux as $n)<option value="{{ $n->id }}" {{ request('niveau_id')==$n->id?'selected':'' }}>{{ $n->nom }}</option>@endforeach
        </select>
        <input type="number" name="annee_examen" value="{{ request('annee_examen') }}" placeholder="Année" class="form-input" style="max-width:150px;">
        <button type="submit" class="btn-outline">Filtrer</button>
    </form>

    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Titre</th><th>Matière</th><th>Niveau</th><th>Année</th><th>Inséré par</th><th></th></tr></thead>
            <tbody>
                @forelse ($epreuves as $e)
                <tr>
                    <td class="font-medium">{{ $e->titre }}</td>
                    <td>{{ $e->matiere?->nom ?? '—' }}</td>
                    <td>{{ $e->niveau?->nom ?? '—' }}</td>
                    <td><span class="badge badge-amber">{{ $e->annee_examen }}</span></td>
                    <td>{{ $e->inserePar?->name ?? '—' }}</td>
                    <td class="text-right">
                        <div class="flex items-center gap-2 justify-end">
                            <a href="{{ route('teacher.epreuves-examen.show', $e) }}" class="login-link"><i data-lucide="eye" class="w-4 h-4"></i></a>
                            <form method="POST" action="{{ route('teacher.epreuves-examen.destroy', $e) }}"
                                  class="inline" data-confirm-delete data-confirm-message="Supprimer l'épreuve « {{ $e->titre }} » ?">
                                @csrf @method('DELETE')
                                <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                                <button type="button" class="text-red-500" onclick="this.closest('form').requestSubmit()"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray-400 py-6">Aucune épreuve d'examen.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $epreuves->links() }}</div>
</div>
@endsection