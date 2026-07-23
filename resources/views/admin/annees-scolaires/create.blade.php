@extends('layouts.admin')

@section('title', 'Nouvelle année scolaire')

@section('content')

<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Nouvelle année scolaire</div>

        <a href="{{ route('admin.annees-scolaires.index') }}"
           class="btn-back">
            ← Retour
        </a>
    </div>

    <!-- FORM GRID -->
    <form method="POST"
          action="{{ route('admin.annees-scolaires.store') }}">

        @csrf

        <div class="grid-card">

            <!-- LIBELLÉ -->
            <div class="card">
                <div class="label">Libellé</div>
                <input type="text"
                       name="libelle"
                       value="{{ old('libelle') }}"
                       class="form-input mt-2"
                       placeholder="Ex: 2025-2026">

                @error('libelle')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DATE DÉBUT -->
            <div class="card">
                <div class="label">Date de début</div>
                <input type="date"
                       name="date_debut"
                       value="{{ old('date_debut') }}"
                       class="form-input mt-2">

                @error('date_debut')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DATE FIN -->
            <div class="card">
                <div class="label">Date de fin</div>
                <input type="date"
                       name="date_fin"
                       value="{{ old('date_fin') }}"
                       class="form-input mt-2">

                @error('date_fin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- ACTIONS -->
        <div class="section">

            <div class="grid-card">

                <div class="card" style="grid-column:1/-1; display:flex; justify-content:flex-end; gap:10px;">

                    <a href="{{ route('admin.annees-scolaires.index') }}"
                       class="btn-back">
                        Annuler
                    </a>

                    <button type="submit"
                            class="btn-save">
                        Enregistrer
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection