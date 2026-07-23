@extends('layouts.admin')

@section('title', 'Modifier enseignant')

@section('content')

<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Modifier enseignant</div>

        <a href="{{ route('admin.enseignants.index') }}"
           class="btn-back">
            ← Retour
        </a>
    </div>

    <form method="POST"
          action="{{ route('admin.enseignants.update', $enseignant) }}">

        @csrf
        @method('PUT')

        <div class="grid-card">

            <!-- NOM -->
            <div class="card">
                <div class="label">Nom complet</div>
                <input type="text"
                       name="name"
                       value="{{ old('name', $enseignant->user->name) }}"
                       class="form-input mt-2">

                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- EMAIL -->
            <div class="card">
                <div class="label">Email</div>
                <input type="email"
                       name="email"
                       value="{{ old('email', $enseignant->user->email) }}"
                       class="form-input mt-2">

                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- TÉLÉPHONE -->
            <div class="card">
                <div class="label">Téléphone</div>
                <input type="text"
                       name="telephone"
                       value="{{ old('telephone', $enseignant->user->telephone) }}"
                       class="form-input mt-2">

                @error('telephone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- SPÉCIALITÉ -->
            <div class="card">
                <div class="label">Spécialité</div>
                <input type="text"
                       name="specialite"
                       value="{{ old('specialite', $enseignant->specialite) }}"
                       class="form-input mt-2">

                @error('specialite')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DIPLÔME -->
            <div class="card">
                <div class="label">Diplôme</div>
                <input type="text"
                       name="diplome"
                       value="{{ old('diplome', $enseignant->diplome) }}"
                       class="form-input mt-2">

                @error('diplome')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- STATUT -->
            <div class="card">
                <div class="label">Statut</div>

                <select name="statut" class="form-input mt-2">
                    <option value="actif"
                        {{ old('statut', $enseignant->statut) == 'actif' ? 'selected' : '' }}>
                        Actif
                    </option>

                    <option value="inactif"
                        {{ old('statut', $enseignant->statut) == 'inactif' ? 'selected' : '' }}>
                        Inactif
                    </option>
                </select>

                @error('statut')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- ACTIONS -->
        <div class="section">
            <div class="grid-card">

                <div class="card" style="grid-column:1/-1; display:flex; justify-content:flex-end; gap:10px;">

                    <a href="{{ route('admin.enseignants.index') }}"
                       class="btn-back">
                        Annuler
                    </a>

                    <button type="submit"
                            class="btn-save">
                        Modifier
                    </button>

                </div>

            </div>
        </div>

    </form>

</div>

@endsection