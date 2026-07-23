@extends('layouts.admin')
@section('title', 'Bulletins')

@section('content')
<div class="card" style="max-width:600px;">
    <h3 class="font-semibold text-gray-800 mb-1">Générer des bulletins</h3>
    <p class="text-sm text-gray-500 mb-6">Choisissez le type, la classe et la période.</p>

    <form method="GET" action="{{ route('admin.bulletins.eleves') }}" class="space-y-4">

        <div class="form-group">
            <label class="form-label">Type de bulletin *</label>
            <select name="type_bulletin" id="type_bulletin" required class="form-select">
                <option value="">-- Choisir --</option>
                <option value="sequentiel">Séquentiel</option>
                <option value="trimestriel">Trimestriel</option>
                <option value="annuel">Annuel</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Classe *</label>
<select name="classe_id" required class="form-select">
    <option value="">-- Choisir --</option>

    @forelse ($classes as $classe)
        <option value="{{ $classe->id }}">
            {{ $classe->nom }} {{ $classe->section ? '('.$classe->section->code.')' : '' }}
        </option>
    @empty
        <option value="">Aucune classe trouvée</option>
    @endforelse
</select>
        </div>

        <div class="form-group hidden" id="bloc_sequence">
            <label class="form-label">Séquence</label>
            <select name="sequence_id" class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($sequences as $seq)
                    <option value="{{ $seq->id }}">
                        {{ $seq->nom }} — {{ $seq->trimestre->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group hidden" id="bloc_trimestre">
            <label class="form-label">Trimestre</label>
            <select name="trimestre_id" class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($trimestres as $trim)
                    <option value="{{ $trim->id }}">{{ $trim->nom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-primary w-full">
            <i data-lucide="users" class="w-4 h-4"></i> Voir les élèves
        </button>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('type_bulletin').addEventListener('change', function () {
    document.getElementById('bloc_sequence').classList.add('hidden');
    document.getElementById('bloc_trimestre').classList.add('hidden');
    if (this.value === 'sequentiel')  document.getElementById('bloc_sequence').classList.remove('hidden');
    if (this.value === 'trimestriel') document.getElementById('bloc_trimestre').classList.remove('hidden');
});
</script>
@endpush
@endsection