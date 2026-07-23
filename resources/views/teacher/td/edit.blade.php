@extends('layouts.admin')
@section('title', 'Modifier le TD')

@section('content')
<div class="card" style="max-width:720px;">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">Modifier — {{ $travailDirige->titre }}</h3>
        <a href="{{ route('teacher.td.index') }}" class="btn-secondary">← Retour</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-error mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('teacher.td.update', $travailDirige) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Titre *</label>
            <input type="text" name="titre" required class="form-input" value="{{ old('titre', $travailDirige->titre) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" rows="2" class="form-textarea">{{ old('description', $travailDirige->description) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Contenu *</label>
            <textarea name="contenu" rows="10" required class="form-textarea">{{ old('contenu', $travailDirige->contenu) }}</textarea>
        </div>

        @if($travailDirige->fichier)
            <div style="background:#f0f4ff;padding:10px 12px;border-radius:8px;">
                <p class="text-sm text-gray-600 mb-2">Fichier actuel :</p>
                <a href="{{ asset('storage/'.$travailDirige->fichier) }}" target="_blank" class="login-link text-sm">
                    <i data-lucide="file" class="w-4 h-4 inline"></i>
                    {{ basename($travailDirige->fichier) }}
                </a>
            </div>
        @endif

        <div class="form-group">
            <label class="form-label">Nouveau fichier joint (remplace l'ancien)</label>
            <input type="file" name="fichier" accept=".pdf,.doc,.docx,.ppt,.pptx" class="form-input">
        </div>

        <div style="background:#f0f4ff;border:1px solid #dce6f5;border-radius:10px;padding:16px;">
            <h4 class="font-semibold text-gray-700 mb-3">Délai de validité</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Date/heure de publication *</label>
                    <input
                        type="datetime-local"
                        name="date_publication"
                        required
                        class="form-input"
                        value="{{ old('date_publication', optional($travailDirige->date_publication)->format('Y-m-d\TH:i')) }}"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">Date/heure limite d'accès *</label>
                    <input
                        type="datetime-local"
                        name="date_limite_acces"
                        required
                        class="form-input"
                        value="{{ old('date_limite_acces', optional($travailDirige->date_limite_acces)->format('Y-m-d\TH:i')) }}"
                    >
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="login-checkbox-label">
                <input
                    type="checkbox"
                    name="publie"
                    value="1"
                    class="form-checkbox"
                    {{ old('publie', $travailDirige->publie) ? 'checked' : '' }}
                >
                Publié (visible par les élèves dans le délai défini)
            </label>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('teacher.td.index') }}" class="btn-secondary w-full">Annuler</a>
            <button type="submit" class="btn-primary w-full">
                <i data-lucide="save" class="w-4 h-4"></i> Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection