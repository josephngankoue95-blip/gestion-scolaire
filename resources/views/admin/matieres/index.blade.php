@extends('layouts.admin')
@section('title', 'Matières')

@section('content')
<div class="card">
    <div class="row-between mb-6">
        <h3 class="font-semibold text-gray-800">Liste des matières</h3>

        <a href="{{ route('admin.matieres.create') }}" class="btn btn-primary">
            Nouvelle matière
        </a>
    </div>

    <form method="GET" id="filterForm" class="mb-5 flex flex-col md:flex-row gap-3">
        {{-- SEARCH --}}
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Rechercher par nom ou code..."
               class="form-input w-full md:w-1/3"
               oninput="document.getElementById('filterForm').submit();">

        {{-- FILTRE SECTION --}}
        <div class="w-full md:w-1/4">
            <label class="text-xs text-gray-500 mb-1 block">Filtrer par section</label>

            <select name="section_id"
                    class="form-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    onchange="document.getElementById('filterForm').submit();">
                <option value="">-- Toutes les sections --</option>

                @foreach($sections as $section)
                    <option value="{{ $section->id }}"
                        {{ request('section_id') == $section->id ? 'selected' : '' }}>
                        {{ $section->nom }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="table-wrapper overflow-x-auto">
        <table class="table-base w-full text-sm">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Code</th>
                    <th>Section</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($matieres as $matiere)
                    <tr>
                        <td class="py-2 px-2 font-medium text-gray-700">
                            {{ $matiere->nom }}
                        </td>

                        <td class="py-2 px-2">
                            <span class="badge badge-blue">
                                {{ $matiere->code }}
                            </span>
                        </td>

                        <td class="py-2 px-2 font-medium text-gray-700">
                            {{ $matiere->section->nom ?? '-' }}
                        </td>

                        <td class="py-2 px-2 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- MODIFIER --}}
                                <a href="{{ route('admin.matieres.edit', $matiere) }}"
                                   title="Modifier">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-200 transition">
                                        <i data-lucide="square-pen" class="w-4 h-4"></i>
                                    </span>
                                </a>

                                {{-- SUPPRIMER --}}
                                <form action="{{ route('admin.matieres.destroy', $matiere) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Supprimer cette matière ?')">
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
                        <td colspan="4" class="table-empty">
                            Aucune matière enregistrée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 pagination">
        {{ $matieres->withQueryString()->links() }}
    </div>
</div>
@endsection