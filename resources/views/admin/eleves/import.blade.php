{{-- resources/views/admin/eleves/import.blade.php --}}
@extends('layouts.admin')
@section('title', 'Importer des élèves')
@section('content')
<div class="card" style="max-width:560px;">
    <h3 class="font-semibold text-gray-800 mb-2">Importer des élèves depuis Excel</h3>
    <p class="text-sm text-gray-500 mb-4">
        Téléchargez d'abord le modèle, remplissez-le, puis importez-le ici.
        La colonne "classe" doit correspondre exactement au nom d'une classe existante.
    </p>

    <a href="{{ route('admin.eleves.import.modele') }}" class="btn-outline w-full mb-4">
        <i data-lucide="download" class="w-4 h-4"></i> Télécharger le modèle CSV
    </a>

    @if(session('erreurs_import'))
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px;margin-bottom:16px;">
        <p class="font-bold text-red-700 text-sm mb-2">Lignes en erreur :</p>
        @foreach (session('erreurs_import') as $f)
            <p class="text-xs text-red-600">Ligne {{ $f->row() }} : {{ implode(', ', $f->errors()) }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('admin.eleves.import.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="form-group">
            <label class="form-label">Fichier Excel/CSV *</label>
            <input type="file" name="fichier" required accept=".xlsx,.xls,.csv" class="form-input">
        </div>
        <div class="flex gap-3">
            <x-retour-button fallback-route="admin.eleves.index" label="Annuler" />
            <button type="submit" class="btn-primary w-full">
                <i data-lucide="upload" class="w-4 h-4"></i> Importer
            </button>
        </div>
    </form>
</div>
@endsection