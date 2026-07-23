@extends('layouts.prefet')
@section('title', 'Saisir des notes')

@section('content')
<div class="card" style="max-width:600px;">
    <h3 class="font-semibold text-gray-800 mb-1">Saisie / Contrôle des notes</h3>
    <p class="text-sm text-gray-500 mb-6">
        En tant que préfet des études, vous pouvez saisir les notes de n'importe quelle classe
        (utile en cas d'absence d'un enseignant).
    </p>

    <form method="GET" action="{{ route('prefet.saisie.form') }}" class="space-y-4">
        <div class="form-group">
            <label class="form-label">Classe *</label>
            <select name="classe_id" id="sel_classe" required class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($classes as $c)
                    <option value="{{ $c->id }}">{{ $c->nom }} ({{ $c->section->code }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Matière *</label>
            <select name="matiere_id" id="sel_matiere" required class="form-select" disabled>
                <option value="">-- Choisir d'abord une classe --</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Séquence *</label>
            <select name="sequence_id" required class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($sequences as $seq)
                    <option value="{{ $seq->id }}">{{ $seq->nom }} — {{ $seq->trimestre->nom }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary w-full">Charger les élèves</button>
    </form>
</div>

@push('scripts')
<script>
const urlMatieres = "{{ route('prefet.ajax.matieres') }}";
document.getElementById('sel_classe').addEventListener('change', function () {
    const sel = document.getElementById('sel_matiere');
    sel.innerHTML = '<option>Chargement...</option>';
    sel.disabled = true;
    if (!this.value) return;
    fetch(`${urlMatieres}?classe_id=${this.value}`, { headers: { 'Accept':'application/json' } })
        .then(r => r.json())
        .then(matieres => {
            sel.innerHTML = '<option value="">-- Choisir --</option>';
            matieres.forEach(m => sel.innerHTML += `<option value="${m.id}">${m.nom} (coef. ${m.coefficient})</option>`);
            sel.disabled = false;
        });
});
</script>
@endpush
@endsection