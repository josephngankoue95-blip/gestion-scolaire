@extends('layouts.admin')

@section('title', 'Matières — ' . $classe->nom)

@section('content')

<div class="container w-full max-w-7xl mx-auto px-6">

    <!-- HEADER -->
    <div class="topbar">
        <div>
            <div class="title">{{ $classe->nom }}</div>
            <div class="text-sm text-gray-500">
                {{ $classe->section->nom }} · {{ $matieres->count() }} matière(s) configurée(s)
            </div>
        </div>

        <a href="{{ route('admin.classes.index') }}" class="btn-back">
            ← Retour
        </a>
    </div>

    <!-- AJOUT MATIÈRE -->
        <div class="card">

            <div class="label mb-3">Ajouter une matière</div>

            @if ($matieresDisponibles->isEmpty())
                <p class="text-sm text-gray-400">
                    Toutes les matières sont déjà configurées.
                </p>
            @else

                <form method="POST"
                    action="{{ route('admin.classes.matieres.store', $classe) }}">

                    @csrf

                    <div class="flex flex-nowrap gap-4 overflow-x-auto">

                          <!-- Matière -->
                            <div class="card p-4 flex-1 min-w-[300px]">
                                <label class="label">Matière</label>
                                <select name="matiere_id" class="form-input w-full">
                                    <option value="">-- Choisir --</option>
                                    @foreach ($matieresDisponibles as $m)
                                        <option value="{{ $m->id }}">{{ $m->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Coef -->
                            <div class="card p-4 min-w-[120px]">
                                <label class="label">Coef.</label>
                                <input type="number" name="coefficient" value="1" class="form-input w-full">
                            </div>

                            <!-- Groupe -->
                            <div class="card p-4 min-w-[120px]">
                                <label class="label">Groupe</label>
                                <select name="groupe" class="form-input w-full">
                                    <option value="1">G1</option>
                                    <option value="2">G2</option>
                                    <option value="3">G3</option>
                                </select>
                            </div>

                            <!-- Ordre -->
                            <div class="card p-4 min-w-[120px]">
                                <label class="label">Ordre</label>
                                <input type="number" name="ordre" value="{{ $matieres->count() }}" class="form-input w-full">
                            </div>

                            <!-- Bouton -->
                            <div class="card p-4 flex items-end min-w-[140px]">
                                <button type="submit" class="btn-save w-full">
                                    Ajouter
                                </button>
                            </div>

                    </div>

                </form>
            @endif

        </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">

        <!-- LISTE MATIÈRES -->
        <div class="card" style="grid-column:1/-1;">

            <div class="label mb-3">Matières configurées</div>

            @if ($matieres->isEmpty())
                <p class="text-gray-400 text-center py-6">
                    Aucune matière configurée.
                </p>
            @else

                @foreach ([1, 2, 3] as $numGroupe)
                    @php
                        $matieresGroupe = $matieresParGroupe->get($numGroupe, collect());
                    @endphp

                    @if ($matieresGroupe->isNotEmpty())

                        <div class="mb-5">

                            <div class="inline-block text-xs font-bold uppercase px-2 py-1 rounded"
                                 style="background:#eff6ff;color:#1d4ed8;">
                                Groupe {{ $numGroupe }}
                            </div>

                            <table class="table-base mt-3">
                                <thead>
                                    <tr>
                                        <th>Matière</th>
                                        <th>Coef.</th>
                                        <th>Groupe</th>
                                        <th>Ordre</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($matieresGroupe as $matiere)
                                        <tr>
                                            <td class="font-medium">
                                                {{ $matiere->nom }}
                                            </td>

                                            <td>
                                                <form method="POST"
                                                    action="{{ route('admin.classes.matieres.update', [$classe, $matiere]) }}"
                                                    class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="number"
                                                        name="coefficient"
                                                        value="{{ $matiere->pivot->coefficient }}"
                                                        step="0.5" min="0.5" max="20"
                                                        class="form-input"
                                                        style="width:70px;">
                                                </form>
                                            </td>

                                            <td>
                                                <form method="POST"
                                                    action="{{ route('admin.classes.matieres.update', [$classe, $matiere]) }}"
                                                    class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')

                                                    <select name="groupe" class="form-select" style="width:80px;">
                                                        @foreach ([1,2,3] as $g)
                                                            <option value="{{ $g }}"
                                                                {{ $matiere->pivot->groupe == $g ? 'selected' : '' }}>
                                                                G{{ $g }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            </td>

                                            <td>
                                                <form method="POST"
                                                    action="{{ route('admin.classes.matieres.update', [$classe, $matiere]) }}"
                                                    class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="number"
                                                        name="ordre"
                                                        value="{{ $matiere->pivot->ordre }}"
                                                        class="form-input"
                                                        style="width:60px;">
                                                </form>
                                            </td>

                                            <td class="text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <form method="POST"
                                                        action="{{ route('admin.classes.matieres.update', [$classe, $matiere]) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <input type="hidden" name="coefficient" value="{{ $matiere->pivot->coefficient }}">
                                                        <input type="hidden" name="groupe" value="{{ $matiere->pivot->groupe }}">
                                                        <input type="hidden" name="ordre" value="{{ $matiere->pivot->ordre }}">

                                                        <button type="submit" class="text-green-600">
                                                            ✔
                                                        </button>
                                                    </form>

                                                    <form method="POST"
                                                        action="{{ route('admin.classes.matieres.destroy', [$classe, $matiere]) }}"
                                                        onsubmit="return confirm('Retirer {{ $matiere->nom }} ?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="text-red-500">
                                                            🗑
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>

                        </div>

                    @endif
                @endforeach

            @endif

        </div>

    </div>
</div>

@endsection