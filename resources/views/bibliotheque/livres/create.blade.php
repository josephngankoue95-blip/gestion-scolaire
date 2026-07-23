@extends('layouts.bibliotheque')
@section('title','Livre')
@section('content')
<div class="card" style="max-width:500px;">
    <form method="POST" action="{{ isset($livre) ? route('bibliotheque.livres.store',$livre) : route('bibliotheque.livres.store') }}" class="space-y-4">
        @csrf
        @isset($livre) @method('PUT') @endisset
        <div class="form-group"><label class="form-label">Titre *</label><input name="titre" required class="form-input" value="{{ $livre->titre ?? '' }}"></div>
        <div class="form-group"><label class="form-label">Auteur</label><input name="auteur" class="form-input" value="{{ $livre->auteur ?? '' }}"></div>
        <div class="form-group"><label class="form-label">Éditeur</label><input name="editeur" class="form-input" value="{{ $livre->editeur ?? '' }}"></div>
        <div class="form-group"><label class="form-label">ISBN</label><input name="isbn" class="form-input" value="{{ $livre->isbn ?? '' }}"></div>
        <div class="form-group"><label class="form-label">Catégorie</label><input name="categorie" class="form-input" value="{{ $livre->categorie ?? '' }}"></div>
        <div class="form-group"><label class="form-label">Quantité totale *</label><input type="number" name="quantite_totale" min="1" required class="form-input" value="{{ $livre->quantite_totale ?? 1 }}"></div>
        <div class="form-group"><label class="form-label">Emplacement</label><input name="emplacement" class="form-input" value="{{ $livre->emplacement ?? '' }}"></div>
        <button type="submit" class="btn-primary w-full">Créer</button>
    </form>
</div>
@endsection