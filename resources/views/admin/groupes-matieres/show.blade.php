@extends('layouts.admin')
@section('title', 'Groupe — ' . $groupeMatiere->nom)

@section('content')
<div class="grid grid-cols-1 gap-6">

    {{-- En-tête --}}
    <div class="card">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="font-semibold text-gray-800 text-lg">{{ $groupeMatiere->nom }}</h3>
                <p class="text-sm text-gray-500">{{ $groupeMatiere->section->nom }} · Code : {{ $groupeMatiere->code }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.groupes-matieres.edit', $groupeMatiere) }}" class="btn-outline">Modifier</a>
                <a href="{{ route('admin.groupes-matieres.index') }}" class="btn-secondary">← Retour</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">

        {{-- Colonne gauche : matières du groupe --}}
        <div class="card">
            <h4 class="font-semibold text-gray-800 mb-4">
                Matières de ce groupe
                <span class="badge-blue ml-2">{{ $groupeMatiere->matieres->count() }}</span>
            </h4>

            <div class="table-wrapper mb-4">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Matière</th>
                            <th>Coef. groupe</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($groupeMatiere->matieres as $matiere)
                        <tr>
                            <td>{{ $matiere->nom }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.groupes-matieres.matieres.coefficient', [$groupeMatiere, $matiere]) }}" class="flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <input type="number" name="coefficient_groupe" value="{{ $matiere->pivot->coefficient_groupe }}" step="0.5" min="0.5" max="10"
                                           class="form-input" style="width:70px;padding:4px 8px;">
                                    <button type="submit" class="text-primary-600" title="Enregistrer">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('admin.groupes-matieres.matieres.retirer', [$groupeMatiere, $matiere]) }}" onsubmit="return confirm('Retirer cette matière ?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-gray-400 py-4">Aucune matière encore ajoutée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Formulaire d'ajout de matière --}}
            @if ($matieresDisponibles->isNotEmpty())
            <form method="POST" action="{{ route('admin.groupes-matieres.matieres.ajouter', $groupeMatiere) }}" class="flex gap-2 items-end">
                @csrf
                <div class="flex-1">
                    <label class="form-label">Ajouter une matière</label>
                    <select name="matiere_id" required class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach ($matieresDisponibles as $m)
                            <option value="{{ $m->id }}">{{ $m->nom }} (coef. base : {{ $m->coefficient }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Coefficient</label>
                    <input type="number" name="coefficient_groupe" value="1" required step="0.5" min="0.5" max="10"
                           class="form-input" style="width:80px;">
                </div>
                <button type="submit" class="btn-primary" style="margin-bottom:1px;">Ajouter</button>
            </form>
            @else
            <p class="text-sm text-gray-400 mt-2">Toutes les matières de cette section sont déjà dans ce groupe.</p>
            @endif
        </div>

        {{-- Colonne droite : classes rattachées --}}
        <div class="card">
            <h4 class="font-semibold text-gray-800 mb-4">
                Classes utilisant ce groupe
                <span class="badge-green ml-2">{{ $classes->count() }}</span>
            </h4>

            @forelse ($classe as $classes)
            <div class="flex justify-between items-center py-2 border-t">
                <p class="font-medium">{{ $classes->nom }}</p>
                <form method="POST" action="{{ route('admin.groupes-matieres.classes.retirer', [$groupeMatiere, $classes]) }}" onsubmit="return confirm('Retirer cette classe du groupe ?')">
                    @csrf @method('DELETE')
                    <button class="text-red-500"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                </form>
            </div>
            @empty
            <p class="text-gray-400 py-4">Aucune classe rattachée à ce groupe.</p>
            @endforelse
        </div>

    </div>
</div>
@endsection