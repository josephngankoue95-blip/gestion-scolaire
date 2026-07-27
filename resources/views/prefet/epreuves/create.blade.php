@extends('layouts.prefet')
@section('title', 'Insérer une épreuve')
@section('content')
<div class="card" style="max-width:600px;">
    <h3 class="font-semibold mb-4">Insérer une épreuve de composition</h3>

    <form method="POST" action="{{ route('prefet.epreuves.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div class="form-group">
            <label class="login-checkbox-label">
                <input type="checkbox" name="archive" id="chk_archive" value="1" class="form-checkbox" checked>
                <strong>Épreuve d'une année précédente</strong> (archive)
            </label>
        </div>

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

        {{-- Bloc année en cours (visible si archive décoché) --}}
        <div id="bloc_courant" style="display:none;">
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Classe</label>
                    <select name="classe_id" class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach ($classes as $c)<option value="{{ $c->id }}">{{ $c->nom }} ({{ $c->section->code }})</option>@endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Séquence</label>
                    <select name="sequence_id" class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach ($sequences as $s)<option value="{{ $s->id }}">{{ $s->nom }} — {{ $s->trimestre->nom }}</option>@endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Année de l'examen *</label>
            <input type="number" name="annee_examen" required class="form-input" placeholder="ex: 2022" min="2000" max="{{ date('Y')+1 }}">
        </div>

        <div class="form-group"><label class="form-label">Titre *</label><input name="titre" required class="form-input" placeholder="ex: Composition de Maths — 2022"></div>
        <div class="form-group"><label class="form-label">Fichier épreuve (PDF/Word) *</label><input type="file" name="fichier" required accept=".pdf,.doc,.docx" class="form-input"></div>
        <div class="form-group"><label class="form-label">Fichier corrigé (optionnel)</label><input type="file" name="fichier_corrige" accept=".pdf,.doc,.docx" class="form-input"></div>

        <div class="flex gap-3">
            <a href="{{ route('prefet.epreuves.index') }}" class="btn-secondary w-full">Annuler</a>
            <button type="submit" class="btn-primary w-full">Enregistrer</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('chk_archive').addEventListener('change', function () {
    document.getElementById('bloc_courant').style.display = this.checked ? 'none' : 'block';
});
</script>
@endpush
@endsection