@extends('layouts.secretaire')
@section('title', 'Mon Profil')

@section('content')
<div class="card" style="max-width:500px;">
    <h3 class="font-semibold text-gray-800 mb-4">Mon Profil</h3>

    <form method="POST" action="{{ route('secretaire.profil.update') }}" class="space-y-4">
        @csrf
        <div class="form-group">
            <label class="form-label">Nom complet *</label>
            <input type="text" name="name" required class="form-input" value="{{ old('name', $user->name) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-input" value="{{ old('telephone', $user->telephone) }}">
        </div>
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" class="form-input" value="{{ $user->email }}" disabled style="background:#f3f4f6;">
            <p class="text-xs text-gray-400 mt-1">L'email ne peut pas être modifié. Contactez l'administrateur.</p>
        </div>
        <div class="form-group">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="password" class="form-input">
        </div>
        <div class="form-group">
            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="form-input">
        </div>
        <button type="submit" class="btn-primary w-full">Mettre à jour</button>
    </form>
</div>
@endsection