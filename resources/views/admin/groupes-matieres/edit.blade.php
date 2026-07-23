@extends('layouts.admin')
@section('title', 'Modifier le groupe')

@section('content')
<div class="card" style="max-width:520px;">
    <h3 class="font-semibold text-gray-800 mb-4">Modifier — {{ $groupeMatiere->nom }}</h3>

    <form method="POST" action="{{ route('admin.groupes-matieres.update', $groupeMatiere) }}" class="space-y-4">
        @csrf @method('PUT')

        <div class="form-group">
            <label class="form-label">Nom *</label>
            <input type="text" name="nom" required class="form-input" value="{{ old('nom', $groupeMatiere->nom) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Code *</label>
            <input type="text" name="code" required class="form-input" value="{{ old('code', $groupeMatiere->code) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Section *</label>
            <select name="section_id" required class="form-select">
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}" {{ $groupeMatiere->section_id == $section->id ? 'selected' : '' }}>{{ $section->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ route('admin.groupes-matieres.show', $groupeMatiere) }}" class="btn-secondary w-full">Annuler</a>
            <button type="submit" class="btn-primary w-full">Enregistrer</button>
        </div>
    </form>
</div>
@endsection