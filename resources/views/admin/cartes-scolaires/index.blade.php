@extends('layouts.admin')
@section('title', 'Cartes scolaires')

@section('content')
<div class="card" style="max-width:500px;">
    <h3 class="font-semibold text-gray-800 mb-1">Impression des cartes scolaires</h3>
    <p class="text-sm text-gray-500 mb-6">
        Sélectionnez une classe pour imprimer toutes les cartes en PDF (4 par page A4).
    </p>
    <form method="GET" action="{{ route('admin.cartes-scolaires.imprimer') }}" target="_blank" class="space-y-4">
        <div class="form-group">
            <label class="form-label">Classe *</label>
            <select name="classe_id" required class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($classes as $classe)
                    <option value="{{ $classe->id }}">{{ $classe->nom }} ({{ $classe->section->code }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary w-full">
            <i data-lucide="id-card" class="w-4 h-4"></i> Imprimer les cartes
        </button>
    </form>
</div>
@endsection