@extends('layouts.admin')
@section('title', 'Nouvelle classe')

@section('content')
<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Nouvelle classe</div>
    </div>

    <form method="POST" action="{{ route('admin.classes.store') }}">
        @csrf

        <div class="grid-card">

            <!-- NOM -->
            <div class="card">
                <div class="label">Nom de la classe *</div>
                <input type="text" name="nom" required
                       class="form-input mt-2"
                       value="{{ old('nom') }}"
                       placeholder="ex: 6ème A">
                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- NIVEAU -->
            <div class="card">
                <label class="form-label">Niveau *</label>
                <select name="niveau_id" id="select_niveau_classe" required class="form-select">
                    <option value="">-- Choisir d'abord une section --</option>
                   @foreach ($niveaux as $n)
                        <option
                            value="{{ $n->id }}"
                            data-section="{{ $n->section_id }}"
                            {{ old('niveau_id') == $n->id ? 'selected' : '' }}>
                            {{ $n->nom }}
                        </option>
                    @endforeach
                </select>
                @error('niveau_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <!-- SECTION -->
            <div class="card">
                <div class="label">Section *</div>
                <select name="section_id" required class="form-input mt-2">
                    <option value="">-- Choisir --</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                            {{ $section->nom }}
                        </option>
                    @endforeach
                </select>
                @error('section_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- CAPACITÉ -->
            <div class="card">
                <div class="label">Capacité max *</div>
                <input type="number" name="capacite_max" required min="1" max="200"
                       class="form-input mt-2"
                       value="{{ old('capacite_max', 50) }}">
                @error('capacite_max') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- PROFESSEUR -->
            <div class="card">
                <div class="label">Professeur principal</div>
                <select name="professeur_principal_id" class="form-input mt-2">
                    <option value="">-- Aucun --</option>
                    @foreach ($enseignants as $ens)
                        <option value="{{ $ens->id }}" {{ old('professeur_principal_id') == $ens->id ? 'selected' : '' }}>
                            {{ $ens->user->name }} ({{ $ens->matricule }})
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <!-- ACTIONS -->
        <div class="section">
            <div class="grid-card">
                <div class="card" style="grid-column:1/-1; display:flex; justify-content:flex-end; gap:10px;">
                    <a href="{{ route('admin.classes.index') }}" class="btn-back">
                        Annuler
                    </a>
                    <button type="submit" class="btn-save">
                        Créer la classe
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection