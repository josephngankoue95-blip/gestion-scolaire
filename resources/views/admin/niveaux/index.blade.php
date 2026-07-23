@extends('layouts.admin')
@section('title', 'Niveaux')

@section('content')
<div class="grid grid-cols-2 gap-6">

    <!-- {{-- Formulaire ajout --}}
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Ajouter un niveau</h3>
        <form method="POST" action="{{ route('admin.niveaux.store') }}" class="space-y-4">
            @csrf
            <div class="form-group">
                <label class="form-label">Section *</label>
                <select name="section_id" required class="form-select">
                    <option value="">-- Choisir --</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->nom }}</option>
                    @endforeach
                </select>
                @error('section_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="form-group">
                    <label class="form-label">Nom (FR) *</label>
                    <input type="text" name="nom" required class="form-input"
                           placeholder="ex: 6ème, Seconde">
                    @error('nom') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Nom (EN)</label>
                    <input type="text" name="nom_en" class="form-input"
                           placeholder="ex: Form 1, Lower Sixth">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="form-group">
                    <label class="form-label">Code *</label>
                    <input type="text" name="code" required class="form-input"
                           placeholder="ex: 6EME">
                    @error('code') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Ordre d'affichage</label>
                    <input type="number" name="ordre" min="0" class="form-input" value="0">
                </div>
            </div>

            <div class="form-group">
                <label class="login-checkbox-label">
                    <input type="checkbox" name="est_terminale" value="1" class="form-checkbox">
                    <span>
                        <strong>Niveau terminal</strong>
                        <span class="text-gray-400"> — fin de cycle (ex: Terminale, 3ème, Upper Sixth)</span>
                    </span>
                </label>
                <p class="text-xs text-gray-400 mt-1">
                    Les élèves de ce niveau ne seront pas transférables sans confirmation explicite
                    lors des opérations de transfert de classe.
                </p>
            </div>

            <button type="submit" class="btn-primary w-full">Créer le niveau</button>
        </form>
    </div> -->

    {{-- Liste des niveaux --}}
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Niveaux configurés</h3>

        @php $grouped = $niveaux->groupBy('section.nom'); @endphp

        @foreach ($grouped as $sectionNom => $items)
        <div class="mb-4">
            <p class="text-xs font-bold text-primary-700 uppercase mb-2"
               style="padding:3px 8px;background:#eff6ff;border-radius:4px;display:inline-block;">
                {{ $sectionNom }}
            </p>
            @foreach ($items as $niveau)
            <div style="border:1px solid #e5e7eb;border-radius:8px;padding:10px 12px;margin-bottom:6px;">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span class="font-medium text-gray-800">{{ $niveau->nom }}</span>
                        @if($niveau->nom_en)
                            <span class="text-gray-400 text-xs ml-1">/ {{ $niveau->nom_en }}</span>
                        @endif
                        <span class="badge badge-blue ml-2" style="font-size:10px;">{{ $niveau->code }}</span>
                        @if($niveau->est_terminale)
                            <span class="badge badge-red ml-1" style="font-size:10px;">
                                <i data-lucide="flag" style="width:10px;height:10px;display:inline;"></i> TERMINAL
                            </span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('admin.niveaux.destroy', $niveau) }}"
                          onsubmit="return confirm('Supprimer ce niveau ?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500" title="Supprimer">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>

                {{-- Modification rapide --}}
                <form method="POST" action="{{ route('admin.niveaux.update', $niveau) }}"
                      class="flex items-center gap-2 flex-wrap">
                    @csrf @method('PUT')
                    <input type="hidden" name="nom" value="{{ $niveau->nom }}">
                    <input type="hidden" name="nom_en" value="{{ $niveau->nom_en }}">
                    <input type="hidden" name="code" value="{{ $niveau->code }}">
                    <input type="hidden" name="section_id" value="{{ $niveau->section_id }}">

                    <label style="font-size:11px;color:#6b7280;">Ordre</label>
                    <input type="number" name="ordre" value="{{ $niveau->ordre }}" min="0"
                           class="form-input" style="width:55px;padding:3px 6px;font-size:12px;">

                    <label class="login-checkbox-label" style="font-size:11px;margin-left:8px;">
                        <input type="checkbox" name="est_terminale" value="1" class="form-checkbox"
                               {{ $niveau->est_terminale ? 'checked' : '' }}>
                        Terminal
                    </label>

                    <button type="submit" class="text-primary-600" title="Sauvegarder" style="margin-left:auto;">
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endforeach

        @if($niveaux->isEmpty())
            <p class="text-gray-400 text-center py-6">Aucun niveau configuré.</p>
        @endif
    </div>
</div>
@endsection