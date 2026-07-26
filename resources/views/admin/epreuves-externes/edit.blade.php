@extends('layouts.admin')
@section('title', 'Épreuve externe')
@section('content')
<div class="card" style="max-width:520px;">
    <form method="POST" action="{{ isset($epreuveExterne) ? route('admin.epreuves-externes.update',$epreuveExterne) : route('admin.epreuves-externes.store') }}" class="space-y-4">
        @csrf
        @isset($epreuveExterne) @method('PUT') @endisset
        <div class="form-group"><label class="form-label">Titre *</label><input name="titre" required class="form-input" value="{{ $epreuveExterne->titre ?? '' }}"></div>
        <div class="form-group"><label class="form-label">Niveau (ex: 6ème)</label><input name="niveau" class="form-input" value="{{ $epreuveExterne->niveau ?? '' }}"></div>
        <div class="form-group"><label class="form-label">Matière</label><input name="matiere" class="form-input" value="{{ $epreuveExterne->matiere ?? '' }}"></div>
        <div class="form-group"><label class="form-label">Année de l'examen</label><input name="annee_examen" class="form-input" value="{{ $epreuveExterne->annee_examen ?? '' }}"></div>
        <div class="form-group"><label class="form-label">Source</label><input name="source" class="form-input" placeholder="Examens Cameroun" value="{{ $epreuveExterne->source ?? '' }}"></div>
        <div class="form-group"><label class="form-label">Lien externe *</label><input type="url" name="lien_externe" required class="form-input" placeholder="https://..." value="{{ $epreuveExterne->lien_externe ?? '' }}"></div>
        <div class="form-group">
            <label class="login-checkbox-label"><input type="checkbox" name="actif" value="1" class="form-checkbox" {{ ($epreuveExterne->actif ?? true) ? 'checked' : '' }}> Actif</label>
        </div>
        <button type="submit" class="btn-primary w-full">Enregistrer</button>
    </form>
</div>
@endsection