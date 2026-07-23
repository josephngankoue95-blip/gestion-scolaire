@extends('layouts.admin')

@section('title', 'Nouvelle matière')

@section('content')

<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Nouvelle matière</div>
    </div>

    <form method="POST" action="{{ route('admin.matieres.store') }}">
        @csrf

        <div class="section">
            <div class="section-title">Informations matière</div>

            <div class="grid-card">
                <div class="card">
                    <div class="label">Nom *</div>
                    <div class="value">
                        <input type="text" name="nom" required
                               value="{{ old('nom') }}"
                               placeholder="ex: Mathématiques">
                        @error('nom') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Code *</div>
                    <div class="value">
                        <input type="text" name="code" required
                               value="{{ old('code') }}"
                               placeholder="ex: MATH-FR">
                        @error('code') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>


                <div class="card">
                    <div class="label">Section *</div>
                    <div class="value">
                        <select name="section_id" required>
                            <option value="">-- Choisir --</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}"
                                    {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('section_id') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- ACTIONS -->
        <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;">
            <a href="{{ route('admin.matieres.index') }}" class="btn-back">
                Annuler
            </a>

            <button type="submit" class="btn-save">
                Créer
            </button>
        </div>
    </form>
</div>

@endsection