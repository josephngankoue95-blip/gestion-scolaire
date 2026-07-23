@extends('layouts.admin')
@section('title', 'Frais de scolarité')

@section('content')
<div class="grid grid-cols-2 gap-6">

    {{-- Formulaire ajout --}}
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Définir une grille de frais</h3>
        <p class="text-sm text-gray-500 mb-4">
            Année : <strong>{{ $annee?->libelle }}</strong><br>
            Laissez "Niveau" vide pour appliquer à toute la section.
        </p>

        <form method="POST" action="{{ route('admin.scolarite.frais.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div class="form-group">
                    <label class="form-label">Section *</label>
                    <select name="section_id" id="sel_section" required class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach ($sections as $s)
                            <option value="{{ $s->id }}">{{ $s->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Niveau (optionnel)</label>
                    <select name="niveau" id="sel_niveau" class="form-select">
                        <option value="">-- Tous niveaux --</option>
                    </select>
                </div>
            </div>

            <div style="background:#f0f4ff;border:1px solid #dce6f5;border-radius:8px;padding:14px;">
                <div class="form-group">
                    <label class="form-label">Frais d'inscription (FCFA) *</label>
                    <input type="number" name="frais_inscription" id="fi" min="0" step="100" required class="form-input" value="0">
                </div>
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div class="form-group">
                        <label class="form-label">Tranche 1 (FCFA) *</label>
                        <input type="number" name="tranche1" id="t1" min="0" step="100" required class="form-input" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Échéance Tranche 1</label>
                        <input type="date" name="echeance_tranche1" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tranche 2 (FCFA) *</label>
                        <input type="number" name="tranche2" id="t2" min="0" step="100" required class="form-input" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Échéance Tranche 2</label>
                        <input type="date" name="echeance_tranche2" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tranche 3 (FCFA) *</label>
                        <input type="number" name="tranche3" id="t3" min="0" step="100" required class="form-input" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Échéance Tranche 3</label>
                        <input type="date" name="echeance_tranche3" class="form-input">
                    </div>
                </div>
                <div style="background:#1a3a6b;color:#fff;padding:8px 12px;border-radius:6px;margin-top:10px;display:flex;justify-content:space-between;">
                    <span>Total scolarité</span>
                    <strong id="total_frais">0 FCFA</strong>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full">Enregistrer la grille</button>
        </form>
    </div>

    <div class="card">
<h3 class="font-semibold text-gray-800 mb-4">Grilles existantes — {{ $annee?->libelle }}</h3>

@php $grouped = $grilles->groupBy(fn($g) => $g->section->nom); @endphp

@foreach ($grouped as $sectionNom => $items)
<div class="mb-4">
<p style="background:#eff6ff;color:#1a3a6b;font-size:11px;font-weight:bold;padding:3px 8px;border-radius:4px;display:inline-block;margin-bottom:6px;">
{{ $sectionNom }}
</p>

<div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
<table class="min-w-full divide-y divide-gray-200 table-auto">
<thead class="bg-gray-50">
<tr>
<th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Classes</th>
<th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Inscription</th>
<th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Tranche 1</th>
<th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Tranche 2</th>
<th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Tranche 3</th>
<th class="px-3 py-2 text-right text-xs font-medium text-gray-500">Actions</th>
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-100">
@foreach ($items as $g)
<tr>
<td class="px-3 py-2 align-middle text-sm text-gray-700">
<div class="font-medium">{{ $g->niveau ?? 'Tous niveaux' }}</div>
</td>

<td class="px-3 py-2 align-middle text-sm">
<form method="POST" action="{{ route('admin.scolarite.frais.update', $g) }}" class="flex items-center space-x-0">
@csrf @method('PUT')
<input type="number" name="frais_inscription" value="{{ $g->frais_inscription }}" class="w-full form-input px-2 py-1 text-sm border border-gray-200 rounded" style="min-width:110px;">
</td>

<td class="px-3 py-2 align-middle text-sm">
<input type="number" name="tranche1" value="{{ $g->tranche1 }}" class="w-full form-input px-2 py-1 text-sm border border-gray-200 rounded" style="min-width:110px;">
</td>

<td class="px-3 py-2 align-middle text-sm">
<input type="number" name="tranche2" value="{{ $g->tranche2 }}" class="w-full form-input px-2 py-1 text-sm border border-gray-200 rounded" style="min-width:110px;">
</td>

<td class="px-3 py-2 align-middle text-sm">
<input type="number" name="tranche3" value="{{ $g->tranche3 }}" class="w-full form-input px-2 py-1 text-sm border border-gray-200 rounded" style="min-width:110px;">
</td>

<td class="px-3 py-2 align-middle text-sm text-right">
<div class="flex items-center justify-end space-x-2">
<button type="submit" class="btn-primary px-3 py-1 text-sm inline-flex items-center" title="Sauvegarder">
<i data-lucide="save" class="w-4 h-4 mr-1"></i> Sauvegarder
</button>
</form>

<form method="POST" action="{{ route('admin.scolarite.frais.destroy', $g) }}" onsubmit="return confirm('Supprimer ?')" class="inline-block">
@csrf @method('DELETE')
<button class="text-red-500 px-2 py-1" title="Supprimer">
<i data-lucide="trash-2" class="w-4 h-4"></i>
</button>
</form>
</div>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

</div>
@endforeach

@if($grilles->isEmpty())
<p class="text-gray-400 text-center py-6">Aucune grille définie.</p>
@endif
</div>
</div>

@push('scripts')
<script>
const niveaux = @json($niveaux);
document.getElementById('sel_section').addEventListener('change', function () {
    const sel = document.getElementById('sel_niveau');
    sel.innerHTML = '<option value="">-- Tous niveaux --</option>';
    (niveaux[this.value] || []).forEach(n => {
        sel.innerHTML += `<option value="${n.nom}">${n.nom}</option>`;
    });
});
function calc() {
    const t = ['fi','t1','t2','t3'].reduce((s,id) => s + (parseFloat(document.getElementById(id).value)||0), 0);
    document.getElementById('total_frais').textContent = t.toLocaleString('fr-FR') + ' FCFA';
}
['fi','t1','t2','t3'].forEach(id => document.getElementById(id).addEventListener('input', calc));
</script>
@endpush
@endsection