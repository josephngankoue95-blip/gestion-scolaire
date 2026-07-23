@extends('layouts.admin')
@section('title', 'Classes')

@section('content')
<div class="card">
    <div class="row-between mb-6">
        <h3 class="font-semibold text-gray-800">
            Classes — Année {{ $annee?->libelle }}
        </h3>
        <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">
            Ajouter une classe
        </a>
    </div>

    <form method="GET" id="filterForm" class="mb-5 flex flex-wrap gap-3 items-center">

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Rechercher une classe..."
        class="form-input w-full md:w-80">

    <select
        name="section_id"
        class="form-select w-full md:w-64"
        onchange="document.getElementById('filterForm').submit();">

        <option value="">Sélectionner une section</option>

        @foreach($sections as $section)

            <option
                value="{{ $section->id }}"
                {{ request('section_id')==$section->id?'selected':'' }}>

                {{ $section->nom }}

            </option>

        @endforeach

    </select>

</form>

    <div class="table-wrapper overflow-x-auto">
        <table class="table-base w-full text-sm">
            <thead>
                <tr>
                    <th>Classe</th>
                    <th>Niveau</th>
                    <th>Section</th>
                    <th>Prof. Principal</th>
                    <th>Effectif</th>
                    <th>Capacité</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($classes as $classe)
                    <tr>
                        <td class="py-2 px-2 font-medium text-gray-700">{{ $classe->nom }}</td>
                        <td class="py-2 px-2 font-medium text-gray-700">{{ $classe->niveau?->nom}}</td>
                        <td class="py-2 px-2 font-medium text-gray-700">
                            <span class="badge badge-blue">{{ $classe->section?->code ?? '-' }}</span>
                        </td>
                        <td class="py-2 px-2 font-medium text-gray-700">
                            {{ $classe->professeurPrincipal?->user?->name ?? '-' }}
                        </td>
                        <td class="py-2 px-2 font-medium text-gray-700">
                            @php $eff = $classe->effectif(); @endphp
                            @if($eff >= $classe->capacite_max)
                                <span class="badge badge-red">{{ $eff }}</span>
                            @else
                                <span class="badge badge-green">{{ $eff }}</span>
                            @endif
                        </td>
                        <td class="py-2 px-2 font-medium text-gray-700">{{ $classe->capacite_max }}</td>

                        <td class="py-2 px-2 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.classes.show', $classe) }}" title="Voir">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </span>
                                </a>

                                <a href="{{ route('admin.classes.matieres.index', $classe) }}" title="Matières">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition">
                                        <i data-lucide="book-open" class="w-4 h-4"></i>
                                    </span>
                                </a>

                                <a href="{{ route('admin.classes.edit', $classe) }}" title="Modifier">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-200 transition">
                                        <i data-lucide="square-pen" class="w-4 h-4"></i>
                                    </span>
                                </a>

                                <form action="{{ route('admin.classes.destroy', $classe) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Supprimer cette classe ?')">
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
                        <td colspan="7" class="table-empty">Aucune classe.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 pagination">
        {{ $classes->links() }}
    </div>
</div>
<script>

let timer;

document.querySelector('input[name="search"]').addEventListener('keyup',function(){

    clearTimeout(timer);

    timer=setTimeout(function(){

        document.getElementById('filterForm').submit();

    },500);

});

</script>
@endsection