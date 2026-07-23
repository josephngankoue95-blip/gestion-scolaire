@extends('layouts.bibliotheque')
@section('title','Bibliothèque')
@section('content')
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="stat-card"><p class="stat-label">Livres (total)</p><p class="stat-value">{{ $totalLivres }}</p></div>
    <div class="stat-card"><p class="stat-label">Disponibles</p><p class="stat-value" style="color:#1a7a1a;">{{ $disponibles }}</p></div>
    <div class="stat-card"><p class="stat-label">Emprunts en cours</p><p class="stat-value">{{ $enCours }}</p></div>
    <div class="stat-card"><p class="stat-label">En retard</p><p class="stat-value" style="color:#c0392b;">{{ $enRetard }}</p></div>
</div>
<div class="flex gap-3">
    <a href="{{ route('bibliotheque.livres.create') }}" class="btn-primary">+ Ajouter un livre</a>
    <a href="{{ route('bibliotheque.emprunts.create') }}" class="btn-outline">+ Nouvel emprunt</a>
</div>
@endsection