@extends('layouts.secretaire')
@section('title', 'Inscrire un élève')

@section('content')
<div class="container">

    <div class="topbar flex items-center justify-between gap-3 flex-wrap">
        <div class="title">Inscrire un élève — {{ $annee?->libelle }}</div>
        <a href="{{ route('secretaire.scolarite') }}" class="btn-back">← Retour</a>
    </div>

    <div class="card mx-auto" style="max-width:720px;">
        <form method="POST" action="{{ route('secretaire.scolarite.store') }}" class="space-y-4" id="form-inscription">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="form-group text-center">
                    <label class="form-label block text-center">Élève *</label>
                    <select name="eleve_id" id="sel_eleve" required class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach ($eleves as $eleve)
                            <option value="{{ $eleve->id }}" data-classe-id="{{ $eleve->classe_id ?? '' }}">
                                {{ $eleve->nomComplet() }} ({{ $eleve->matricule }})
                            </option>
                        @endforeach
                    </select>
                    @error('eleve_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group text-center">
                    <label class="form-label block text-center">Classe *</label>
                    <select
                        name="classe_id"
                        id="sel_classe"
                        required
                        class="form-select bg-gray-100 text-gray-500 cursor-not-allowed opacity-80"
                        disabled
                    >
                        <option value="">-- Choisir --</option>
                        @foreach ($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }} ({{ $classe->section->code }})</option>
                        @endforeach
                    </select>
                    @error('classe_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <input type="hidden" name="classe_id" id="classe_hidden">

                <div class="form-group text-center">
                    <label class="form-label block text-center">Date d'inscription *</label>
                    <input type="date" name="date_inscription" required class="form-input text-center" value="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group text-center">
                    <label class="form-label block text-center">Type d'inscription *</label>
                    <select name="type_inscription" required class="form-select text-center">
                        <option value="nouvelle">Nouvelle inscription</option>
                        <option value="redoublant">Redoublant</option>
                        <option value="transfert">Transfert</option>
                    </select>
                </div>
            </div>

            <div class="rounded-lg border p-4" style="background:#f0f4ff;border-color:#dce6f5;">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-semibold text-gray-700">Frais de scolarité</h4>
                    <span id="frais-auto-msg" class="text-xs text-primary-600 hidden">✓ Rempli automatiquement</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="form-group text-center">
                        <label class="form-label block text-center">Frais d'inscription (FCFA) *</label>
                        <input type="number" name="frais_inscription" id="fi" min="0" step="100" required class="form-input bg-gray-100 text-gray-600 cursor-not-allowed text-center" value="0" readonly>
                    </div>

                    <div class="form-group text-center">
                        <label class="form-label block text-center">Tranche 1 (FCFA) *</label>
                        <input type="number" name="montant_tranche1" id="mt1" min="0" step="100" required class="form-input bg-gray-100 text-gray-600 cursor-not-allowed text-center" value="0" readonly>
                    </div>

                    <div class="form-group text-center">
                        <label class="form-label block text-center">Tranche 2 (FCFA) *</label>
                        <input type="number" name="montant_tranche2" id="mt2" min="0" step="100" required class="form-input bg-gray-100 text-gray-600 cursor-not-allowed text-center" value="0" readonly>
                    </div>

                    <div class="form-group text-center">
                        <label class="form-label block text-center">Tranche 3 (FCFA) *</label>
                        <input type="number" name="montant_tranche3" id="mt3" min="0" step="100" required class="form-input bg-gray-100 text-gray-600 cursor-not-allowed text-center" value="0" readonly>
                    </div>
                </div>

                <div class="mt-3 rounded-md px-4 py-2 flex justify-between items-center text-white" style="background:#1a3a6b;">
                    <span>Total scolarité</span>
                    <strong id="total_scolarite">0 FCFA</strong>
                </div>
            </div>

            <div class="rounded-lg border p-4" style="background:#f0fdf4;border-color:#bbf7d0;">
                <h4 class="font-semibold text-gray-700 mb-3 text-center">Transport (optionnel)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="form-group text-center">
                        <label class="form-label block text-center">Zone de transport</label>
                        <select name="zone_transport_id" id="sel_zone" class="form-select text-center">
                            <option value="">-- Pas de transport --</option>
                            @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}" data-montant="{{ $zone->montant }}">
                                    {{ $zone->nom }} — {{ number_format($zone->montant, 0, ',', ' ') }} FCFA/an
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group text-center">
                        <label class="form-label block text-center">Montant transport (FCFA)</label>
                        <input type="number" name="montant_transport" id="mt" min="0" step="500" class="form-input text-center" value="0">
                    </div>
                </div>
            </div>

            <div class="flex gap-2 pt-1">
                <a href="{{ route('secretaire.scolarite') }}" class="btn-back w-full text-center">Annuler</a>
                <button type="submit" class="btn-save w-full">Enregistrer l'inscription</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const urlFrais = "{{ route('admin.scolarite.frais.pour-classe') }}";
const selEleve = document.getElementById('sel_eleve');
const selClasse = document.getElementById('sel_classe');

function setClasseReadonly(state) {
    selClasse.disabled = state;
    selClasse.classList.toggle('bg-gray-100', state);
    selClasse.classList.toggle('text-gray-600', state);
    selClasse.classList.toggle('cursor-not-allowed', state);
}

function chargerFrais(classeId) {
    if (!classeId) return;

    fetch(`${urlFrais}?classe_id=${classeId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async (r) => {
        if (!r.ok) return null;
        return r.json();
    })
    .then(data => {
        if (!data) return;
        document.getElementById('fi').value = data.frais_inscription ?? 0;
        document.getElementById('mt1').value = data.tranche1 ?? 0;
        document.getElementById('mt2').value = data.tranche2 ?? 0;
        document.getElementById('mt3').value = data.tranche3 ?? 0;
        document.getElementById('frais-auto-msg').classList.remove('hidden');
        calculerTotal();
    })
    .catch(() => {});
}

selEleve.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const classeId = opt.dataset.classeId || '';

    if (classeId) {
        selClasse.value = classeId;
        document.getElementById('classe_hidden').value = classeId;
        setClasseReadonly(true);
        chargerFrais(classeId);
    } else {
        selClasse.value = '';
        document.getElementById('classe_hidden').value = '';
        setClasseReadonly(false);
        document.getElementById('fi').value = 0;
        document.getElementById('mt1').value = 0;
        document.getElementById('mt2').value = 0;
        document.getElementById('mt3').value = 0;
        document.getElementById('frais-auto-msg').classList.add('hidden');
        calculerTotal();
    }
});

selClasse.addEventListener('change', function () {
    if (selClasse.disabled) return;
    if (this.value) chargerFrais(this.value);
});

document.getElementById('sel_zone').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    document.getElementById('mt').value = opt.dataset.montant ?? 0;
    calculerTotal();
});

function calculerTotal() {
    const fi = parseFloat(document.getElementById('fi').value) || 0;
    const mt1 = parseFloat(document.getElementById('mt1').value) || 0;
    const mt2 = parseFloat(document.getElementById('mt2').value) || 0;
    const mt3 = parseFloat(document.getElementById('mt3').value) || 0;
    const mt = parseFloat(document.getElementById('mt').value) || 0;
    document.getElementById('total_scolarite').textContent = (fi + mt1 + mt2 + mt3 + mt).toLocaleString('fr-FR') + ' FCFA';
}

['mt1', 'mt2', 'mt3', 'mt'].forEach(id => document.getElementById(id).addEventListener('input', calculerTotal));
calculerTotal();
</script>
@endpush
@endsection