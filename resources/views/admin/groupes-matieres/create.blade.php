@extends('layouts.admin')
@section('title', 'Nouveau groupe de matières')

@section('content')
<div class="card" style="max-width:520px;">
    <h3 class="font-semibold text-gray-800 mb-4">Nouveau groupe de matières</h3>
    <p class="text-sm text-gray-500 mb-4">Un groupe définit l'ensemble des matières enseignées dans une filière (ex: Tronc commun, Scientifique, Littéraire). Chaque classe sera ensuite rattachée à un groupe.</p>

    <form method="POST" action="{{ route('admin.groupes-matieres.store') }}" class="space-y-4">
        @csrf

        <div class="form-group">
            <label class="form-label">Nom *</label>
            <input type="text" name="nom" required class="form-input" value="{{ old('nom') }}" placeholder="ex: Tronc commun, Scientifique...">
            @error('nom') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Code *</label>
            <input type="text" name="code" required class="form-input" value="{{ old('code') }}" placeholder="ex: TC-FR, SCI-FR">
            @error('code') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Section *</label>
            <select name="section_id" required class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->nom }}</option>
                @endforeach
            </select>
            @error('section_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ route('admin.groupes-matieres.index') }}" class="btn-secondary w-full">Annuler</a>
            <button type="submit" class="btn-primary w-full">Créer</button>
        </div>
    </form>
</div>
@endsection