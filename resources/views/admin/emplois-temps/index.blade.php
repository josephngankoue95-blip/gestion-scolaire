@extends('layouts.admin')
@section('title', 'Emploi du temps')

@section('content')

@php
    $joursOrdre = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];

    $creneauxParHoraire = $creneaux->flatten()
        ->groupBy(function ($c) {
            return substr($c->heure_debut, 0, 5) . '-' . substr($c->heure_fin, 0, 5);
        })
        ->sortKeys();
@endphp

{{-- Sélecteur de classe --}}
<div class="card mb-6">
    <h3 class="font-semibold text-gray-800 mb-4">
        <i data-lucide="calendar-clock" class="w-5 h-5 inline"></i>
        Emploi du temps — {{ $annee?->libelle }}
    </h3>

    <form method="GET" action="{{ route('admin.emplois-temps.index') }}" class="flex gap-3 items-end">
        <div class="form-group" style="flex:1;max-width:350px;margin-bottom:0;">
            <label class="form-label">Sélectionner une classe</label>
            <select name="classe_id" required class="form-select" id="select-classe">
                <option value="">-- Choisir une classe --</option>
                @foreach ($classes as $c)
                    <option value="{{ $c->id }}" {{ request('classe_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->nom }} ({{ $c->section?->code }})
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary">
            <i data-lucide="eye" class="w-4 h-4"></i> Afficher
        </button>
    </form>
</div>

@if($classe)
<div class="grid gap-6" style="grid-template-columns:280px 1fr;">

    {{-- Formulaire ajout créneau --}}
    <div class="flex flex-col gap-4">
        <div class="card">
            <h4 class="font-semibold text-gray-800 mb-4">
                Ajouter un créneau
                <span class="badge-blue ml-1" style="font-size:11px;">{{ $classe->nom }}</span>
            </h4>

            @if(session('error'))
                <div class="alert-error mb-3">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.emplois-temps.store') }}" class="space-y-3">
                @csrf

                <input type="hidden" name="classe_id" value="{{ $classe->id }}">

                <div class="form-group">
                    <label class="form-label">Jour *</label>
                    <select name="jour" required class="form-select">
                        @foreach ($joursOrdre as $jour)
                            <option value="{{ $jour }}" {{ old('jour') === $jour ? 'selected' : '' }}>
                                {{ ucfirst($jour) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Matière *</label>
                    <select name="matiere_id" required class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach ($matieres as $matiere)
                            <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                {{ $matiere->nom }}
                            </option>
                        @endforeach
                    </select>
                    @if($matieres->isEmpty())
                        <p class="text-xs text-amber-600 mt-1">
                            <i data-lucide="alert-triangle" class="w-3 h-3 inline"></i>
                            Aucune matière configurée pour cette classe.
                        </p>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Enseignant *</label>
                    <select name="enseignant_id" required class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach ($enseignants as $ens)
                            <option value="{{ $ens->id }}" {{ old('enseignant_id') == $ens->id ? 'selected' : '' }}>
                                {{ $ens->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($enseignants->isEmpty())
                        <p class="text-xs text-amber-600 mt-1">
                            <i data-lucide="alert-triangle" class="w-3 h-3 inline"></i>
                            Aucun enseignant affecté à cette classe.
                        </p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="form-group">
                        <label class="form-label">Heure début *</label>
                        <input type="time" name="heure_debut" required class="form-input" value="{{ old('heure_debut') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Heure fin *</label>
                        <input type="time" name="heure_fin" required class="form-input" value="{{ old('heure_fin') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Salle</label>
                    <input type="text" name="salle" class="form-input" value="{{ old('salle') }}" placeholder="ex: Salle 12">
                </div>

                <button type="submit" class="btn-primary w-full">
                    <i data-lucide="plus" class="w-4 h-4"></i> Ajouter le créneau
                </button>
            </form>
        </div>

        {{-- Résumé --}}
        <div class="card">
            <h4 class="font-semibold text-gray-800 mb-3">Résumé</h4>
            <div class="space-y-1 text-sm">
                @foreach ($joursOrdre as $jour)
                    @php $nb = $creneaux->get($jour, collect())->count(); @endphp
                    <div class="flex justify-between py-1 border-t">
                        <span class="text-gray-600">{{ ucfirst($jour) }}</span>
                        <span class="{{ $nb > 0 ? 'badge-blue' : 'badge-gray' }}">{{ $nb }} cours</span>
                    </div>
                @endforeach
                <div class="flex justify-between py-2 border-t font-bold">
                    <span>Total</span>
                    <span>{{ $creneaux->flatten()->count() }} créneaux</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Grille hebdomadaire --}}
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h4 class="font-semibold text-gray-800">
                Grille hebdomadaire — {{ $classe->nom }}
                <span class="text-gray-400 font-normal text-sm ml-2">({{ $classe->section?->nom }})</span>
            </h4>
            @if($creneaux->isNotEmpty())
                <span class="badge-green">{{ $creneaux->flatten()->count() }} créneaux configurés</span>
            @endif
        </div>

        @if($creneaux->isEmpty())
            <div style="text-align:center;padding:40px;color:#9ca3af;">
                <i data-lucide="calendar-x" style="width:40px;height:40px;margin:0 auto 12px;display:block;"></i>
                <p class="font-medium">Aucun créneau configuré pour cette classe.</p>
                <p class="text-sm mt-1">Utilisez le formulaire à gauche pour ajouter des cours.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="border px-3 py-2 text-left whitespace-nowrap">Heure</th>
                            @foreach ($joursOrdre as $jour)
                                <th class="border px-3 py-2 text-center uppercase">{{ $jour }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($creneauxParHoraire as $horaire => $coursDuHoraire)
                            <tr>
                                <td class="border px-3 py-2 font-medium whitespace-nowrap">
                                    {{ $horaire }}
                                </td>

                                @foreach ($joursOrdre as $jour)
                                    @php
                                        $creneau = $coursDuHoraire->firstWhere('jour', $jour);
                                    @endphp

                                    <td class="border px-3 py-2 align-top">
                                        @if($creneau)
                                            <div class="font-semibold text-gray-800">
                                                {{ $creneau->matiere->nom }}
                                            </div>
                                            <div class="text-xs text-gray-600 mt-1">
                                                {{ $creneau->enseignant->user->name }}
                                            </div>
                                            @if($creneau->salle)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Salle {{ $creneau->salle }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@else
    <div class="card" style="text-align:center;padding:60px 20px;color:#9ca3af;">
        <i data-lucide="calendar-clock" style="width:56px;height:56px;margin:0 auto 16px;display:block;color:#dce6f5;"></i>
        <p class="font-semibold" style="font-size:16px;color:#374151;">Sélectionnez une classe</p>
        <p class="text-sm mt-2">Choisissez une classe dans le menu ci-dessus pour voir et configurer son emploi du temps.</p>
    </div>
@endif

@endsection