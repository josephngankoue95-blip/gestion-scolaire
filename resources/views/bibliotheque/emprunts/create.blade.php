@extends('layouts.bibliotheque')
@section('title','Nouvel emprunt')
@section('content')
<div class="card" style="max-width:500px;">
    <form method="POST" action="{{ route('bibliotheque.emprunts.store') }}" class="space-y-4">
        @csrf
        <div class="form-group">
            <label class="form-label">Livre *</label>
            <select name="livre_id" required class="form-select">
                @foreach ($livres as $l)<option value="{{ $l->id }}">{{ $l->titre }} ({{ $l->quantite_disponible }} dispo.)</option>@endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Type *</label>
            <select name="type_emprunteur" id="type_emp" required class="form-select">
                <option value="eleve">Élève</option>
                <option value="enseignant">Enseignant</option>
            </select>
        </div>
        <div class="form-group" id="bloc_eleve">
            <label class="form-label">Élève</label>
            <select name="eleve_id" class="form-select">
                @foreach ($eleves as $e)<option value="{{ $e->id }}">{{ $e->nomComplet() }}</option>@endforeach
            </select>
        </div>
        <div class="form-group hidden" id="bloc_enseignant">
            <label class="form-label">Enseignant</label>
            <select name="enseignant_id" class="form-select">
                @foreach ($enseignants as $ens)<option value="{{ $ens->id }}">{{ $ens->user->name }}</option>@endforeach
            </select>
        </div>
        <div class="form-group"><label class="form-label">Date emprunt *</label><input type="date" name="date_emprunt" required class="form-input" value="{{ date('Y-m-d') }}"></div>
        <div class="form-group"><label class="form-label">Retour prévu *</label><input type="date" name="date_retour_prevue" required class="form-input"></div>
        <button type="submit" class="btn-primary w-full">Enregistrer</button>
    </form>
</div>
@push('scripts')
<script>
document.getElementById('type_emp').addEventListener('change', function(){
    document.getElementById('bloc_eleve').classList.toggle('hidden', this.value !== 'eleve');
    document.getElementById('bloc_enseignant').classList.toggle('hidden', this.value !== 'enseignant');
});
</script>
@endpush
@endsection