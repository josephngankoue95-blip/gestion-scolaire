@extends('layouts.admin')
@section('title', 'Procès verbal')

@section('content')
<div class="card" style="max-width:600px;">
    <h3 class="font-semibold text-gray-800 mb-1">Procès verbal de classe</h3>
    <p class="text-sm text-gray-500 mb-6">Liste complète des élèves par mérite ou ordre alphabétique.</p>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 text-red-700 text-sm">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('admin.proces-verbaux.show') }}" class="space-y-4">
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
            <select name="type" id="type_pv" required class="form-select">
                <option value="">-- Choisir --</option>
                <option value="sequentiel" {{ old('type') === 'sequentiel' ? 'selected' : '' }}>Séquentiel</option>
                <option value="trimestriel" {{ old('type') === 'trimestriel' ? 'selected' : '' }}>Trimestriel</option>
                <option value="annuel" {{ old('type') === 'annuel' ? 'selected' : '' }}>Annuel</option>
            </select>
            @error('type')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="bloc_seq_pv" class="form-group hidden">
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

        <div id="bloc_trim_pv" class="form-group hidden">
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
            <label class="form-label">Ordre d'affichage *</label>
            <select name="ordre" required class="form-select">
                <option value="merite" {{ old('ordre', 'merite') === 'merite' ? 'selected' : '' }}>
                    Par mérite (du meilleur au moins bon)
                </option>
                <option value="alphabetique" {{ old('ordre') === 'alphabetique' ? 'selected' : '' }}>
                    Par ordre alphabétique
                </option>
            </select>
            @error('ordre')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full">Afficher</button>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const type = document.getElementById('type_pv');
    const blocSeq = document.getElementById('bloc_seq_pv');
    const blocTrim = document.getElementById('bloc_trim_pv');

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