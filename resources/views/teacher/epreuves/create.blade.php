@extends('layouts.admin')
@section('title', 'Envoyer une épreuve')
@section('content')
<div class="card" style="max-width:600px;">
    <h3 class="font-semibold mb-4">Envoyer une épreuve</h3>

    @if($errors->any())
    <div class="alert-error mb-4">
        <ul style="margin:0;padding-left:16px;">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('teacher.epreuves.store') }}" enctype="multipart/form-data" class="space-y-4" id="form-epreuve">
        @csrf

        <div class="form-group">
            <label class="login-checkbox-label">
                <input type="checkbox" name="archive" id="chk_archive" value="1" class="form-checkbox">
                <strong>Épreuve d'une année précédente</strong> (archive — non liée à la séquence en cours)
            </label>
        </div>

        {{-- ── Bloc "épreuve de l'année en cours" ── --}}
        <div id="bloc_courant">
            <div class="form-group">
                <label class="form-label">Matière / Classe (année en cours) *</label>
                <select name="pair_courant" id="sel_pair" class="form-select">
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
                <select name="sequence_id" id="sel_sequence" class="form-select">
                    <option value="">-- Choisir --</option>
                    @foreach ($sequences as $s)
                        <option value="{{ $s->id }}">{{ $s->nom }} — {{ $s->trimestre->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ── Bloc "archive" ── --}}
        <div id="bloc_archive" style="display:none;">
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Matière *</label>
                    <select name="matiere_id_archive" id="sel_matiere_archive" class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach ($affectations->unique('matiere_id') as $a)
                            <option value="{{ $a->matiere_id }}">{{ $a->matiere->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Niveau *</label>
                    <select name="niveau_id_archive" id="sel_niveau_archive" class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach ($niveaux as $n)
                            <option value="{{ $n->id }}">{{ $n->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Année de l'examen *</label>
                <input type="number" name="annee_examen" id="in_annee_examen" class="form-input" placeholder="ex: 2022" min="2000" max="{{ date('Y')+1 }}">
            </div>
        </div>

        {{-- Champs réellement soumis (remplis dynamiquement) --}}
        <input type="hidden" name="matiere_id" id="in_matiere">
        <input type="hidden" name="classe_id" id="in_classe">
        <input type="hidden" name="niveau_id" id="in_niveau">

        <div class="form-group"><label class="form-label">Titre *</label><input name="titre" required class="form-input" placeholder="ex: Composition de Maths — Séquence 3" value="{{ old('titre') }}"></div>
        <div class="form-group"><label class="form-label">Fichier épreuve (PDF/Word) *</label><input type="file" name="fichier" required accept=".pdf,.doc,.docx" class="form-input"></div>
        <div class="form-group"><label class="form-label">Fichier corrigé (optionnel)</label><input type="file" name="fichier_corrige" accept=".pdf,.doc,.docx" class="form-input"></div>

        <div class="flex gap-3">
            <x-retour-button fallback-route="teacher.epreuves.index" label="Annuler" />
            <button type="submit" class="btn-primary w-full">Enregistrer</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
const chkArchive        = document.getElementById('chk_archive');
const blocCourant       = document.getElementById('bloc_courant');
const blocArchive       = document.getElementById('bloc_archive');
const selPair           = document.getElementById('sel_pair');
const selSequence       = document.getElementById('sel_sequence');
const selMatiereArchive = document.getElementById('sel_matiere_archive');
const selNiveauArchive  = document.getElementById('sel_niveau_archive');
const inMatiere         = document.getElementById('in_matiere');
const inClasse          = document.getElementById('in_classe');
const inNiveau          = document.getElementById('in_niveau');
const form              = document.getElementById('form-epreuve');

function toggleMode() {
    const isArchive = chkArchive.checked;
    blocCourant.style.display = isArchive ? 'none' : 'block';
    blocArchive.style.display = isArchive ? 'block' : 'none';

    // active/désactive les required selon le mode pour éviter les blocages HTML5
    selPair.required     = !isArchive;
    selSequence.required = !isArchive;
    selMatiereArchive.required = isArchive;
    selNiveauArchive.required  = isArchive;
}

chkArchive.addEventListener('change', toggleMode);
toggleMode(); // état initial

// Année en cours : remplit matière/classe/niveau depuis la paire sélectionnée
selPair.addEventListener('change', function () {
    const [m, c, n] = this.value.split('|');
    inMatiere.value = m || '';
    inClasse.value  = c || '';
    inNiveau.value  = n || '';
});

// Avant soumission : si mode archive, on bascule les valeurs des champs archive vers les hidden
form.addEventListener('submit', function () {
    if (chkArchive.checked) {
        inMatiere.value = selMatiereArchive.value;
        inNiveau.value  = selNiveauArchive.value;
        inClasse.value  = ''; // pas de classe précise pour une archive
    }
});
</script>
@endpush
@endsection