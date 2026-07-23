@extends('layouts.admin')
@section('title', 'Enseignants')

@section('content')
<div class="card">
    <div class="row-between mb-6"> 
        <h3 class="font-semibold text-gray-800">Liste des enseignants(es)</h3>
        <a href="{{ route('admin.enseignants.create') }}" class="btn btn-primary">
            Ajouter Un(e) Enseignant(e)
        </a>
    </div>

    <form method="GET" class="mb-5">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Rechercher par nom ou matricule..."
               class="form-input w-full md:w-1/3">
    </form>

    <div class="table-wrapper overflow-x-auto">
        <table class="table-base w-full text-sm">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Spécialité</th>
                    <th>Statut</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($enseignants as $enseignant)
                    <tr>
                        <td class="py-2 px-2 font-medium text-gray-700 cell-center">{{ $enseignant->matricule }}</td>
                        <td>{{ $enseignant->user->name }}</td>
                        <td class="py-2 px-2 font-medium text-gray-700 cell-center">{{ $enseignant->user->email }}</td>
                        <td class="py-2 px-2 font-medium text-gray-700 cell-center">{{ $enseignant->user->telephone ?? '-' }}</td>
                        <td class="py-2 px-2 font-medium text-gray-700 cell-center">{{ $enseignant->specialite ?? '-' }}</td>
                        <td class="py-2 px-2 font-medium text-gray-700 cell-center">
                            @if($enseignant->statut === 'actif')
                                <span class="badge badge-green">Actif</span>
                            @else
                                <span class="badge badge-red">Inactif</span>
                            @endif
                        </td>
                        <td class="py-2 px-2 text-center">
                            <div class="flex items-center justify-center gap-2">

                                {{-- AFFECTATIONS --}}
                                <a href="{{ route('admin.enseignants.affectations', $enseignant) }}"
                                title="Affectations">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition">
                                        <i data-lucide="book-open" class="w-4 h-4"></i>
                                    </span>
                                </a>

                                {{-- VOIR --}}
                                <a href="{{ route('admin.enseignants.show', $enseignant) }}"
                                title="Voir">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </span>
                                </a>

                                {{-- MODIFIER --}}
                                <a href="{{ route('admin.enseignants.edit', $enseignant) }}"
                                title="Modifier">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-200 transition">
                                        <i data-lucide="square-pen" class="w-4 h-4"></i>
                                    </span>
                                </a>

                                {{-- SUPPRIMER --}}
                                <form action="{{ route('admin.enseignants.destroy', $enseignant) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Supprimer cet enseignant ?')">

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
                        <td colspan="7" class="table-empty">Aucun enseignant trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 pagination">
        {{ $enseignants->links() }}
    </div>
</div>
@endsection