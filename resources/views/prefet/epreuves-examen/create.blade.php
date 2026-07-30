@extends('layouts.prefet')
@section('title', 'Insérer une épreuve d\'examen')
@section('content')
<div class="card" style="max-width:560px;">
    <h3 class="font-semibold mb-4">Nouvelle épreuve d'examen (archive)</h3>
    <form method="POST" action="{{ route('prefet.epreuves-examen.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="form-group">
                <label class="form-label">Matière *</label>
                <select name="matiere_id" required class="form-select">
                    <option value="">-- Choisir --</option>
                    @foreach ($matieres as $m)<option value="{{ $m->id }}">{{ $m->nom }}</option>@endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Niveau *</label>
                <select name="niveau_id" required class="form-select">
                    <option value="">-- Choisir --</option>
                    @foreach ($niveaux as $n)<option value="{{ $n->id }}">{{ $n->nom }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Année de l'examen *</label>
            <input type="number" name="annee_examen" required class="form-input" placeholder="ex: 2022" min="2000" max="{{ date('Y')+1 }}">
        </div>
        <div class="form-group"><label class="form-label">Titre *</label><input name="titre" required class="form-input" placeholder="ex: Composition Maths 2022"></div>
        <div class="form-group"><label class="form-label">Fichier épreuve (PDF/Word) *</label><input type="file" name="fichier" required accept=".pdf,.doc,.docx" class="form-input"></div>
        <div class="form-group"><label class="form-label">Fichier corrigé (optionnel)</label><input type="file" name="fichier_corrige" accept=".pdf,.doc,.docx" class="form-input"></div>
        <div class="flex gap-3">
            <x-retour-button fallback-route="prefet.epreuves-examen.index" label="Annuler" />
            <button type="submit" class="btn-primary w-full">Enregistrer</button>
        </div>
    </form>
</div>
@endsection