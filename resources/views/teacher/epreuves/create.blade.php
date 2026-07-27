@extends('layouts.admin')
@section('title', 'Envoyer une épreuve')
@section('content')
<div class="card" style="max-width:600px;">
    <h3 class="font-semibold mb-4">Envoyer une épreuve</h3>
    <form method="POST" action="{{ route('teacher.epreuves.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div class="form-group">
            <label class="login-checkbox-label">
                <input type="checkbox" name="archive" id="chk_archive" value="1" class="form-checkbox">
                <strong>Épreuve d'une année précédente</strong> (archive — non liée à la séquence en cours)
            </label>
        </div>

        {{-- Bloc "épreuve de l'année en cours" --}}
        <div id="bloc_courant">
            <div class="form-group">
                <label class="form-label">Matière / Classe (année en cours) *</label>
                <select name="pair" id="sel_pair" class="form-select">
                    <option value="">-- Choisir --</option>
                    @foreach ($affectations as $a)
                        <option value="{{ $a->matiere_id }}|{{ $a->classe_id }}|{{ $a->classe->niveau_id }}">
                            {{ $a->matiere->nom }} — {{ $a->classe->nom }} ({{ $a->classe->section->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Séquence *</label>
                <select name="sequence_id" class="form-select">
                    @foreach ($sequences as $s)
                        <option value="{{ $s->id }}">{{ $s->nom }} — {{ $s->trimestre->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Bloc "archive" --}}
        <div id="bloc_archive" style="display:none;">
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Matière *</label>
                    <select name="matiere_id_archive" class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach ($affectations->unique('matiere_id') as $a)
                            <option value="{{ $a->matiere_id }}">{{ $a->matiere->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Niveau *</label>
                    <select name="niveau_id_archive" class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach ($niveaux as $n)
                            <option value="{{ $n->id }}">{{ $n->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Année de l'examen *</label>
                <input type="number" name="annee_examen" class="form-input" placeholder="ex: 2022" min="2000" max="{{ date('Y')+1 }}">
            </div>
        </div>

        <input type="hidden" name="matiere_id" id="in_matiere">
        <input type="hidden" name="classe_id" id="in_classe">
        <input type="hidden" name="niveau_id" id="in_niveau">

        <div class="form-group"><label class="form-label">Titre *</label><input name="titre" required class="form-input" placeholder="ex: Composition de Maths — 2022"></div>
        <div class="form-group"><label class="form-label">Fichier épreuve (PDF/Word) *</label><input type="file" name="fichier" required accept=".pdf,.doc,.docx" class="form-input"></div>
        <div class="form-group"><label class="form-label">Fichier corrigé (optionnel)</label><input type="file" name="fichier_corrige" accept=".pdf,.doc,.docx" class="form-input"></div>

        <button type="submit" class="btn-primary w-full">Envoyer</button>
    </form>
</div>

@push('scripts')
<script>
const chkArchive   = document.getElementById('chk_archive');
const blocCourant  = document.getElementById('bloc_courant');
const blocArchive  = document.getElementById('bloc_archive');
const selPair      = document.getElementById('sel_pair');
const inMatiere    = document.getElementById('in_matiere');
const inClasse     = document.getElementById('in_classe');
const inNiveau     = document.getElementById('in_niveau');

chkArchive.addEventListener('change', function () {
    blocCourant.style.display = this.checked ? 'none' : 'block';
    blocArchive.style.display = this.checked ? 'block' : 'none';
});

selPair.addEventListener('change', function () {
    const [m, c, n] = this.value.split('|');
    inMatiere.value = m || '';
    inClasse.value  = c || '';
    inNiveau.value  = n || '';
});

// Pour le bloc archive, on remplit les mêmes champs hidden avant soumission
document.querySelector('form').addEventListener('submit', function () {
    if (chkArchive.checked) {
        inMatiere.value = document.querySelector('[name="matiere_id_archive"]').value;
        inNiveau.value  = document.querySelector('[name="niveau_id_archive"]').value;
        inClasse.value  = '';
    }
});
</script>
@endpush
@endsection