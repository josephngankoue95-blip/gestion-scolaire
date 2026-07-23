@extends('layouts.admin')
@section('title', 'Saisie des notes')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
  <div class="card w-full max-w-[600px]">
    <h3 class="font-semibold text-gray-800 mb-1">Saisie des notes</h3>
    <p class="text-sm text-gray-500 mb-6">Sélectionnez la section, la classe, la matière et la séquence.</p>

    @if (session('success'))
        <div class="alert-success mb-4">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('teacher.saisie.form') }}" id="form-saisie" class="space-y-4">

        <div class="form-group">
            <label class="form-label">Section *</label>
            <select name="section_id" id="select_section" required class="form-select">
                <option value="">-- Choisir une section --</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Classe *</label>
            <select name="classe_id" id="select_classe" required class="form-select" disabled>
                <option value="">-- Choisir d'abord une section --</option>
            </select>
            <p id="msg_classe" class="text-xs text-gray-400 mt-1 hidden">Chargement...</p>
        </div>

        <div class="form-group">
            <label class="form-label">Matière *</label>
            <select name="matiere_id" id="select_matiere" required class="form-select" disabled>
                <option value="">-- Choisir d'abord une classe --</option>
            </select>
            <p id="msg_matiere" class="text-xs text-gray-400 mt-1 hidden">Chargement...</p>
        </div>

        {{-- Coefficient passé en paramètre GET (rempli automatiquement par JS) --}}
        <input type="hidden" name="coefficient" id="input_coefficient" value="">

        <div class="form-group">
            <label class="form-label">Séquence *</label>
            <select name="sequence_id" required class="form-select">
                <option value="">-- Choisir une séquence --</option>
                @foreach ($sequences as $seq)
                    <option value="{{ $seq->id }}">
                        {{ $seq->nom }} — {{ $seq->trimestre->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-primary w-full" id="btn-submit" disabled>
            <i data-lucide="table" class="w-4 h-4"></i> Charger les élèves
        </button>
    </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const urlClasses  = "{{ route('teacher.ajax.classes') }}";
    const urlMatieres = "{{ route('teacher.ajax.matieres') }}";

    const selectSection  = document.getElementById('select_section');
    const selectClasse   = document.getElementById('select_classe');
    const selectMatiere  = document.getElementById('select_matiere');
    const inputCoef      = document.getElementById('input_coefficient');
    const btnSubmit      = document.getElementById('btn-submit');
    const msgClasse      = document.getElementById('msg_classe');
    const msgMatiere     = document.getElementById('msg_matiere');

    function resetSelect(el, placeholder) {
        el.innerHTML = `<option value="">${placeholder}</option>`;
    }

    // ── Section → Classes ───────────────────────────────────────
    selectSection.addEventListener('change', function () {
        resetSelect(selectClasse,  '-- Choisir une classe --');
        resetSelect(selectMatiere, '-- Choisir d\'abord une classe --');
        inputCoef.value        = '';
        selectClasse.disabled  = true;
        selectMatiere.disabled = true;
        btnSubmit.disabled     = true;

        if (!this.value) return;

        msgClasse.classList.remove('hidden');
        msgClasse.textContent = 'Chargement des classes...';

        fetch(`${urlClasses}?section_id=${this.value}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(classes => {
            msgClasse.classList.add('hidden');

            if (classes.length === 0) {
                selectClasse.innerHTML = '<option value="">Aucune classe disponible</option>';
                return;
            }

            resetSelect(selectClasse, '-- Choisir une classe --');
            classes.forEach(c => {
                const opt = document.createElement('option');
                opt.value       = c.id;
                opt.textContent = c.nom;
                selectClasse.appendChild(opt);
            });
            selectClasse.disabled = false;
        })
        .catch(err => {
            msgClasse.textContent = 'Erreur de chargement.';
            console.error(err);
        });
    });

    // ── Classe → Matières ───────────────────────────────────────
    selectClasse.addEventListener('change', function () {
        resetSelect(selectMatiere, '-- Choisir une matière --');
        inputCoef.value        = '';
        selectMatiere.disabled = true;
        btnSubmit.disabled     = true;

        if (!this.value) return;

        msgMatiere.classList.remove('hidden');
        msgMatiere.textContent = 'Chargement des matières...';

        fetch(`${urlMatieres}?classe_id=${this.value}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(matieres => {
            msgMatiere.classList.add('hidden');

            if (matieres.length === 0) {
                selectMatiere.innerHTML = '<option value="">Aucune matière configurée pour cette classe</option>';
                return;
            }

            resetSelect(selectMatiere, '-- Choisir une matière --');
            matieres.forEach(m => {
                const opt        = document.createElement('option');
                opt.value        = m.id;
                opt.textContent  = `${m.nom} (coef. ${m.coefficient})`;
                opt.dataset.coef = m.coefficient;
                selectMatiere.appendChild(opt);
            });
            selectMatiere.disabled = false;
        })
        .catch(err => {
            msgMatiere.textContent = 'Erreur de chargement.';
            console.error(err);
        });
    });

    // ── Matière → remplir le coefficient caché + activer submit ─
    selectMatiere.addEventListener('change', function () {
        const opt   = this.options[this.selectedIndex];
        inputCoef.value   = opt.dataset.coef ?? '';
        btnSubmit.disabled = !this.value;
    });
});
</script>
@endpush