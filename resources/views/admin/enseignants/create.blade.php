@extends('layouts.admin')

@section('title', 'Créer un enseignant')

@section('content')

<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Nouvel enseignant</div>

        <a href="{{ route('admin.enseignants.index') }}"
           class="btn-back">
            ← Retour
        </a>
    </div>

    <form method="POST"
          action="{{ route('admin.enseignants.store') }}">

        @csrf

        <div class="grid-card">

            <!-- NOM -->
            <div class="card">
                <div class="label">Nom complet</div>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="form-input mt-2"
                       placeholder="Ex: Jean Dupont">

                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- EMAIL -->
            <div class="card">
                <div class="label">Email</div>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-input mt-2"
                       placeholder="exemple@mail.com">

                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- TÉLÉPHONE -->
            <div class="card">
                <div class="label">Téléphone</div>
                <input type="text"
                       name="telephone"
                       value="{{ old('telephone') }}"
                       class="form-input mt-2"
                       placeholder="+237...">

                @error('telephone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- SPÉCIALITÉ -->
            <div class="card">
                <div class="label">Spécialité</div>
                <input type="text"
                       name="specialite"
                       value="{{ old('specialite') }}"
                       class="form-input mt-2"
                       placeholder="Ex: Mathématiques">

                @error('specialite')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DIPLÔME -->
            <div class="card">
                <div class="label">Diplôme</div>
                <input type="text"
                       name="diplome"
                       value="{{ old('diplome') }}"
                       class="form-input mt-2"
                       placeholder="Ex: Master en Physique">

                @error('diplome')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DATE RECRUTEMENT -->
            <div class="card">
                <div class="label">Date de recrutement</div>
                <input type="date"
                       name="date_recrutement"
                       value="{{ old('date_recrutement') }}"
                       class="form-input mt-2">

                @error('date_recrutement')
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
                        Enregistrer
                    </button>

                </div>
            </div>
        </div>

    </form>

</div>

@endsection