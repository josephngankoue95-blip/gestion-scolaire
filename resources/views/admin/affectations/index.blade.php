@extends('layouts.admin')

@section('title', 'Affectations')

@section('content')

<div class="space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="card">

        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Affectations de {{ $enseignant->user->name }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des matières et classes attribuées à cet enseignant.
                </p>
            </div>

            <a href="{{ route('admin.enseignants.index') }}"
               class="btn-cancel">
                ← Retour
            </a>
        </div>

    </div>

    {{-- ================= FORM AJOUT ================= --}}
<div class="card max-w-3xl mx-auto">

    <h3 class="form-title mb-6">
        Nouvelle affectation
    </h3>

    <form method="POST"
          action="{{ route('admin.enseignants.affectations.store', $enseignant) }}">

        @csrf

        {{-- GRID mieux structuré + espacement --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Matière --}}
            <div class="form-group">
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Matière *
                </label>

                <select name="matiere_id"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">

                    <option value="">-- Choisir une matière --</option>

                    @foreach($matieres as $matiere)
                        <option value="{{ $matiere->id }}" required>
                            {{ $matiere->nom }}
                        </option>
                    @endforeach

                </select>

                @error('matiere_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Classe --}}
            <div class="form-group">
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Classe *
                </label>

                <select name="classe_id"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">

                    <option value="" required>-- Choisir une classe --</option>

                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" required>
                            {{ $classe->nom }}
                            @if($classe->section)
                                ({{ $classe->section->nom }})
                            @endif
                        </option>
                    @endforeach

                </select>

                @error('classe_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- bouton mieux espacé --}}
        <div class="mt-8 flex justify-end">
            <button type="submit"
                    class="btn-save px-6 py-2 rounded-lg shadow-sm">
                + Ajouter affectation
            </button>
        </div>

    </form>
</div>

    {{-- ================= TABLE ================= --}}
    <div class="card">

        <h3 class="form-title mb-4">
            Affectations enregistrées
        </h3>

        @if($affectations->count())

            <div class="table-wrapper overflow-x-auto">

                <table class="table-base w-full text-sm">

                    <thead>
                        <tr>
                            <th>Matière</th>
                            <th>Classe</th>
                            <th>Année scolaire</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($affectations as $affectation)
                            <tr>

                                <td class="cell-center font-medium text-gray-700">
                                    {{ $affectation->matiere->nom }}
                                </td>

                                <td class="cell-center">
                                    {{ $affectation->classe->nom }}
                                </td>

                                <td class="cell-center">
                                    {{ $affectation->anneeScolaire->libelle }}
                                </td>

                                <td class="cell-center">

                                    <div class="flex items-center justify-center gap-2">

                                        {{-- SUPPRIMER --}}
                                        <form method="POST"
                                              action="{{ route('admin.enseignants.affectations.destroy', [$enseignant, $affectation]) }}"
                                              onsubmit="return confirm('Retirer cette affectation ?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 text-red-500 hover:bg-red-50 hover:text-red-600 transition shadow-sm"
                                                    title="Supprimer">

                                                <i data-lucide="trash-2" class="w-4 h-4"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="text-center py-8 text-gray-500">
                Aucune affectation enregistrée.
            </div>

        @endif

    </div>

</div>

@endsection