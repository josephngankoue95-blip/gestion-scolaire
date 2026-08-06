@extends('layouts.admin')
@section('title', 'Envoyer une épreuve de composition')
@section('content')
<div class="card" style="max-width:560px;">
    <h3 class="font-semibold mb-4">Nouvelle épreuve de composition</h3>

    @if($affectations->isEmpty())
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px;font-size:13px;color:#991b1b;">
        Vous n'avez aucune affectation valide pour l'année active. Contactez l'administration.
    </div>
    @else
    <form method="POST" action="{{ route('teacher.epreuves-composition.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="form-group">
            <label class="form-label">Matière / Classe *</label>
            <select name="pair" id="sel_pair" required class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($affectations as $a)
                    <option value="{{ $a->matiere_id }}|{{ $a->classe_id }}">
                        {{ $a->matiere->nom }} — {{ $a->classe->nom }} ({{ $a->classe->section?->code ?? '' }})
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="matiere_id" id="in_matiere">
            <input type="hidden" name="classe_id" id="in_classe">
        </div>
        <div class="form-group">
            <label class="form-label">Séquence *</label>
            <select name="sequence_id" required class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($sequences as $s)
                    <option value="{{ $s->id }}">{{ $s->nom }} — {{ $s->trimestre?->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group"><label class="form-label">Titre *</label><input name="titre" required class="form-input"></div>
        <div class="form-group"><label class="form-label">Fichier épreuve (PDF/Word) *</label><input type="file" name="fichier" required accept=".pdf,.doc,.docx" class="form-input"></div>
        <div class="form-group"><label class="form-label">Fichier corrigé (optionnel)</label><input type="file" name="fichier_corrige" accept=".pdf,.doc,.docx" class="form-input"></div>
        <div class="flex gap-3">
            <x-retour-button fallback-route="teacher.epreuves-composition.index" label="Annuler" />
            <button type="submit" class="btn-primary w-full">Enregistrer</button>
        </div>
    </form>
    @endif
</div>
@push('scripts')
<script>
document.getElementById('sel_pair')?.addEventListener('change', function(){
    const [m,c] = this.value.split('|');
    document.getElementById('in_matiere').value = m || '';
    document.getElementById('in_classe').value = c || '';
});
</script>
@endpush
@endsection