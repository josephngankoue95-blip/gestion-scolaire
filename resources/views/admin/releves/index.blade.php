@extends('layouts.admin')
@section('title', 'Relevés de notes')

@section('content')
<div class="grid grid-cols-2 gap-6">

    {{-- Par classe --}}
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-1">Relevé par classe</h3>
        <p class="text-sm text-gray-500 mb-4">
            Tableau vierge de toutes les matières d'une classe pour une séquence.
        </p>
        <form method="GET" action="{{ route('admin.releves.classe') }}" target="_blank" class="space-y-4">
            <div class="form-group">
                <label class="form-label">Classe *</label>
                <select name="classe_id" required class="form-select">
                    <option value="">-- Choisir --</option>
                    @foreach ($classes as $classe)
                        <option value="{{ $classe->id }}">{{ $classe->nom }} ({{ $classe->section->code }})</option>
                    @endforeach
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
            <button type="submit" class="btn-primary w-full">
                <i data-lucide="printer" class="w-4 h-4"></i> Générer le relevé
            </button>
        </form>
    </div>

    {{-- Par enseignant --}}
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-1">Relevé par enseignant</h3>
        <p class="text-sm text-gray-500 mb-4">
            Fiche individuelle de l'enseignant avec ses classes et matières.
        </p>
        <form method="GET" action="{{ route('admin.releves.enseignant') }}" target="_blank" class="space-y-4">
            <div class="form-group">
                <label class="form-label">Enseignant *</label>
                <select name="enseignant_id" required class="form-select">
                    <option value="">-- Choisir --</option>
                    @foreach ($enseignants as $ens)
                        <option value="{{ $ens->id }}">{{ $ens->user->name }} ({{ $ens->matricule }})</option>
                    @endforeach
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
            <button type="submit" class="btn-primary w-full">
                <i data-lucide="printer" class="w-4 h-4"></i> Générer la fiche
            </button>
        </form>
    </div>
</div>
@endsection