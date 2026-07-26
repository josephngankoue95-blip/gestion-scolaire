@extends('layouts.bibliotheque')
@section('title','Nouvel emprunt')
@section('content')
<div class="card" style="max-width:520px;">
    <form method="POST" action="{{ route('bibliotheque.emprunts.store') }}" class="space-y-4">
        @csrf

        <div class="form-group">
            <label class="form-label">Livre *</label>
            <select name="livre_id" required class="form-select">
                @foreach ($livres as $l)<option value="{{ $l->id }}">{{ $l->titre }} ({{ $l->quantite_disponible }} dispo.)</option>@endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Type d'emprunteur *</label>
            <select name="type_emprunteur" id="type_emp" required class="form-select">
                <option value="">-- Choisir --</option>
                <option value="eleve">Élève</option>
                <option value="enseignant">Enseignant</option>
            </select>
        </div>

        {{-- ── Champ unique dynamique avec recherche ── --}}
        <div class="form-group" id="bloc_emprunteur" style="display:none;">
            <label class="form-label" id="label_emprunteur">Emprunteur *</label>
            <input type="text" id="recherche_emprunteur" class="form-input mb-2" placeholder="Rechercher par nom..." autocomplete="off">
            <select name="emprunteur_id" id="sel_emprunteur" class="form-select" size="6" style="height:auto;">
            </select>
            <input type="hidden" name="eleve_id" id="hidden_eleve_id">
            <input type="hidden" name="enseignant_id" id="hidden_enseignant_id">
        </div>

        <div class="form-group"><label class="form-label">Date emprunt *</label><input type="date" name="date_emprunt" required class="form-input" value="{{ date('Y-m-d') }}"></div>
        <div class="form-group"><label class="form-label">Retour prévu *</label><input type="date" name="date_retour_prevue" required class="form-input"></div>

        <button type="submit" class="btn-primary w-full">Enregistrer</button>
    </form>
</div>

@push('scripts')
<script>
// Données préchargées côté serveur (élèves et enseignants séparés)
const eleves = @json($eleves->map(fn($e) => ['id' => $e->id, 'label' => $e->nomComplet() . ' — ' . $e->matricule]));
const enseignants = @json($enseignants->map(fn($e) => ['id' => $e->id, 'label' => $e->user->name . ' — ' . $e->matricule]));

const typeSelect     = document.getElementById('type_emp');
const blocEmprunteur = document.getElementById('bloc_emprunteur');
const labelEmprunteur= document.getElementById('label_emprunteur');
const selEmprunteur  = document.getElementById('sel_emprunteur');
const rechercheInput = document.getElementById('recherche_emprunteur');
const hiddenEleve     = document.getElementById('hidden_eleve_id');
const hiddenEnseignant= document.getElementById('hidden_enseignant_id');

let currentList = [];

function remplirListe(liste) {
    selEmprunteur.innerHTML = '';
    liste.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = item.label;
        selEmprunteur.appendChild(opt);
    });
}

typeSelect.addEventListener('change', function () {
    hiddenEleve.value = '';
    hiddenEnseignant.value = '';
    rechercheInput.value = '';

    if (this.value === 'eleve') {
        currentList = eleves;
        labelEmprunteur.textContent = 'Élève * (recherchez puis sélectionnez)';
        blocEmprunteur.style.display = 'block';
        remplirListe(currentList);
    } else if (this.value === 'enseignant') {
        currentList = enseignants;
        labelEmprunteur.textContent = 'Enseignant * (recherchez puis sélectionnez)';
        blocEmprunteur.style.display = 'block';
        remplirListe(currentList);
    } else {
        blocEmprunteur.style.display = 'none';
        currentList = [];
    }
});

rechercheInput.addEventListener('input', function () {
    const terme = this.value.toLowerCase();
    const filtres = currentList.filter(item => item.label.toLowerCase().includes(terme));
    remplirListe(filtres);
});

selEmprunteur.addEventListener('change', function () {
    if (typeSelect.value === 'eleve') {
        hiddenEleve.value = this.value;
        hiddenEnseignant.value = '';
    } else {
        hiddenEnseignant.value = this.value;
        hiddenEleve.value = '';
    }
});
</script>
@endpush
@endsection