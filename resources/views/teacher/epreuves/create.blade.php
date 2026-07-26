@extends('layouts.admin')
@section('title', 'Envoyer une épreuve')
@section('content')
<div class="card" style="max-width:560px;">
    <h3 class="font-semibold mb-4">Envoyer une épreuve de composition</h3>
    <form method="POST" action="{{ route('teacher.epreuves.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="form-group">
            <label class="form-label">Matière / Classe *</label>
            <select name="pair" id="sel_pair" required class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($affectations as $a)
                    <option value="{{ $a->matiere_id }}|{{ $a->classe_id }}">{{ $a->matiere->nom }} — {{ $a->classe->nom }} ({{ $a->classe->section->code }})</option>
                @endforeach
            </select>
            <input type="hidden" name="matiere_id" id="in_matiere">
            <input type="hidden" name="classe_id" id="in_classe">
        </div>
        <div class="form-group">
            <label class="form-label">Séquence *</label>
            <select name="sequence_id" required class="form-select">
                @foreach ($sequences as $s)
                    <option value="{{ $s->id }}">{{ $s->nom }} — {{ $s->trimestre->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group"><label class="form-label">Titre *</label><input name="titre" required class="form-input" placeholder="ex: Composition de Maths — Séquence 3"></div>
        <div class="form-group"><label class="form-label">Fichier épreuve (PDF/Word) *</label><input type="file" name="fichier" required accept=".pdf,.doc,.docx" class="form-input"></div>
        <div class="form-group"><label class="form-label">Fichier corrigé (optionnel)</label><input type="file" name="fichier_corrige" accept=".pdf,.doc,.docx" class="form-input"></div>
        <button type="submit" class="btn-primary w-full">Envoyer</button>
    </form>
</div>
@push('scripts')
<script>
document.getElementById('sel_pair').addEventListener('change', function(){
    const [m,c] = this.value.split('|');
    document.getElementById('in_matiere').value = m || '';
    document.getElementById('in_classe').value = c || '';
});
</script>
@endpush
@endsection