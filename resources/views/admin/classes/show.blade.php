@extends('layouts.admin')
@section('title', 'Détails de la classe')

@section('content')

<div class="form-card">
 
    <div class="form-header">
        <h3 class="form-title">Détails de la classe</h3> 
    </div>
    
    <div class="form-group">
        <label class="form-label">Nom de la classe</label>
        <input type="text"
               class="form-input"
               value="{{ $classe->nom }}"
               readonly>
    </div>

    <div class="form-group">
        <label class="form-label">Niveau</label>
        <input type="text"
               class="form-input"
               value="{{ $classe->niveau }}"
               readonly>
    </div>

    <div class="form-group">
        <label class="form-label">Section</label>
        <input type="text"
               class="form-input"
               value="{{ $classe->section?->nom }}"
               readonly>
    </div>

    <div class="form-group">
        <label class="form-label">Capacité maximale</label>
        <input type="text"
               class="form-input"
               value="{{ $classe->capacite_max }}"
               readonly>
    </div>

    <div class="form-group">
        <label class="form-label">Effectif actuel</label>
        <input type="text"
               class="form-input"
               value="{{ $classe->effectif() }}"
               readonly>
    </div>

    <div class="form-group">
        <label class="form-label">Année scolaire</label>
        <input type="text"
               class="form-input"
               value="{{ $classe->anneeScolaire?->nom ?? '—' }}"
               readonly>
    </div>

    <div class="actions">
        <a href="{{ route('admin.classes.index', ['section' => request('section')]) }}"
           class="btn-cancel">
            Retour à la liste
        </a>

        <a href="{{ route('admin.classes.edit', $classe) }}" class="btn-save">
            Modifier la classe
        </a>
    </div>

</div>

@endsection