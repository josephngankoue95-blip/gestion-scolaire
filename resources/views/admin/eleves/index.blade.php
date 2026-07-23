@extends('layouts.admin')
@section('title', 'Gestion des élèves')

@section('content')
<div class="card">
    <div class="row-between mb-6">
        <h3 class="font-semibold text-gray-800">Gestion des élèves</h3>
        <a href="{{ route('admin.eleves.create') }}" class="btn btn-primary">
            Ajouter un élève
        </a>
    </div>

    <form method="GET" id="filterForm" class="mb-5 flex flex-wrap gap-3 items-center">

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Rechercher par nom, prénom ou matricule..."
        class="form-input w-full md:w-80">

    <select
        name="classe_id"
        class="form-input w-full md:w-60"
        onchange="document.getElementById('filterForm').submit()">

        <option value="">Toutes les classes</option>

        @foreach($classes as $classe)
            <option value="{{ $classe->id }}"
                {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                {{ $classe->nom }}
            </option>
        @endforeach

    </select>

    <a href="{{ route('admin.eleves.index') }}" class="btn btn-secondary">
        Réinitialiser
    </a>

</form>

    <div class="table-wrapper overflow-x-auto">
        <table class="table-base w-full text-sm">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom complet</th>
                    <th>Sexe</th>
                    <th>Classe</th>
                    <th>Statut</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($eleves as $eleve)
                    <tr>
                        <td class="py-2 px-2 font-medium text-gray-700">{{ $eleve->matricule }}</td>
                        <td class="py-2 px-2 font-medium text-gray-700">{{ $eleve->nomComplet() }}</td>
                        <td class="py-2 px-2 font-medium text-gray-700">
                            {{ $eleve->sexe === 'M' ? 'Masculin' : 'Féminin' }}
                        </td>
                        <td class="py-2 px-2 font-medium text-gray-700">{{ $eleve->classe?->nom }}</td>
                        <td class="py-2 px-2 font-medium text-gray-700">
                            @if($eleve->statut === 'actif')
                                <span class="badge badge-green">Actif</span>
                            @elseif($eleve->statut === 'inactif')
                                <span class="badge badge-gray">Inactif</span>
                            @elseif($eleve->statut === 'transfere')
                                <span class="badge badge-blue">Transféré</span>
                            @else
                                <span class="badge badge-amber">Diplômé</span>
                            @endif
                        </td>

                        <td class="py-2 px-2 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.eleves.show', $eleve) }}" title="Voir">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </span>
                                </a>

                                <a href="{{ route('admin.eleves.edit', $eleve) }}" title="Modifier">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-200 transition">
                                        <i data-lucide="square-pen" class="w-4 h-4"></i>
                                    </span>
                                </a>

                                <form action="{{ route('admin.eleves.destroy', $eleve) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Supprimer cet élève ?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" title="Supprimer">
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        @if(!request('classe_id'))
<tr>
    <td colspan="6" class="text-center py-10 text-gray-500">
        <i data-lucide="graduation-cap" class="w-10 h-10 mx-auto mb-2"></i>
        Veuillez sélectionner une classe pour afficher les élèves.
    </td>
</tr>
@else
<tr>
    <td colspan="6" class="text-center py-10 text-gray-500">
        Aucun élève trouvé dans cette classe.
    </td>
</tr>
@endif
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 pagination">
        {{ $eleves->links() }}
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const search = document.querySelector('input[name="search"]');
    let timer;

    search.addEventListener('keyup', function () {

        clearTimeout(timer);

        timer = setTimeout(function () {
            document.getElementById('filterForm').submit();
        }, 500);

    });

});
</script>
@endpush
@endsection