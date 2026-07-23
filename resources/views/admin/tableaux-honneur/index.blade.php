@extends('layouts.admin')
@section('title', 'Tableau d\'honneur')

@section('content')
<div class="card" style="max-width:580px;">
    <h3 class="font-semibold text-gray-800 mb-1">Tableau d'honneur</h3>
    <p class="text-sm text-gray-500 mb-6">
        Élèves ayant obtenu une moyenne supérieure ou égale au seuil défini.
    </p>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 text-red-700 text-sm">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('admin.tableaux-honneur.show') }}" class="space-y-4">
        <div class="form-group">
            <label class="form-label">Classe *</label>
            <select name="classe_id" required class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($classes as $classe)
                    <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                        {{ $classe->nom }} ({{ $classe->section->code }})
                    </option>
                @endforeach
            </select>
            @error('classe_id')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Type *</label>
            <select name="type" id="type_th" required class="form-select">
                <option value="">-- Choisir --</option>
                <option value="sequentiel" {{ old('type') === 'sequentiel' ? 'selected' : '' }}>Séquentiel</option>
                <option value="trimestriel" {{ old('type') === 'trimestriel' ? 'selected' : '' }}>Trimestriel</option>
                <option value="annuel" {{ old('type') === 'annuel' ? 'selected' : '' }}>Annuel</option>
            </select>
            @error('type')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="bloc_seq_th" class="form-group hidden">
            <label class="form-label">Séquence</label>
            <select name="sequence_id" id="sequence_id" class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($sequences as $seq)
                    <option value="{{ $seq->id }}" {{ old('sequence_id') == $seq->id ? 'selected' : '' }}>
                        {{ $seq->nom }} — {{ $seq->trimestre->nom }}
                    </option>
                @endforeach
            </select>
            @error('sequence_id')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="bloc_trim_th" class="form-group hidden">
            <label class="form-label">Trimestre</label>
            <select name="trimestre_id" id="trimestre_id" class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($trimestres as $trim)
                    <option value="{{ $trim->id }}" {{ old('trimestre_id') == $trim->id ? 'selected' : '' }}>
                        {{ $trim->nom }}
                    </option>
                @endforeach
            </select>
            @error('trimestre_id')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Seuil de moyenne *</label>
            <input type="number" name="seuil" value="{{ old('seuil', 12) }}" step="0.5" min="0" max="20" required class="form-input">
            <p class="text-xs text-gray-400 mt-1">Par défaut : 12/20</p>
            @error('seuil')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full">Afficher le tableau</button>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const type = document.getElementById('type_th');
    const blocSeq = document.getElementById('bloc_seq_th');
    const blocTrim = document.getElementById('bloc_trim_th');

    function toggleBlocks() {
        blocSeq.classList.add('hidden');
        blocTrim.classList.add('hidden');

        if (type.value === 'sequentiel') blocSeq.classList.remove('hidden');
        if (type.value === 'trimestriel') blocTrim.classList.remove('hidden');
    }

    type.addEventListener('change', toggleBlocks);
    toggleBlocks();
});
</script>
@endpush
@endsection