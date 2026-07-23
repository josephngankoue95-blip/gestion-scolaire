@extends('layouts.eleve')
@section('title', 'Mon Profil')

@section('content')
<div class="grid grid-cols-2 gap-6">

    <div class="card">
        <div class="flex items-center gap-4 mb-6">
            @if($eleve->photo)
                <img src="{{ asset('storage/'.$eleve->photo) }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #dce6f5;">
            @else
                <div style="width:80px;height:80px;border-radius:50%;background:#e8edf5;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:bold;color:#1a3a6b;">
                    {{ strtoupper(substr($eleve->prenom,0,1)) }}
                </div>
            @endif
            <div>
                <p class="font-bold text-gray-800 text-lg">{{ $eleve->nomComplet() }}</p>
                <p class="text-sm text-gray-500">{{ $eleve->matricule }}</p>
            </div>
        </div>

        <dl class="space-y-2 text-sm">
            @foreach ([
                'Date de naissance' => $eleve->date_naissance?->format('d/m/Y'),
                'Lieu de naissance' => $eleve->lieu_naissance,
                'Sexe' => $eleve->sexe === 'M' ? 'Masculin' : 'Féminin',
                'Classe actuelle' => $eleve->classe?->nom ?? 'Non inscrit',
                'Téléphone parent' => $eleve->telephone_parent,
                'Adresse' => $eleve->adresse,
                'Statut' => ucfirst($eleve->statut),
            ] as $label => $value)
            <div class="flex gap-3 py-2 border-t">
                <dt style="width:150px;color:#6b7280;">{{ $label }}</dt>
                <dd class="font-medium">{{ $value ?? '-' }}</dd>
            </div>
            @endforeach
        </dl>
    </div>

    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-4">Changer mon mot de passe</h4>
        <form method="POST" action="{{ route('eleve.profil.update') }}" class="space-y-4">
            @csrf
            <div class="form-group">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-input">
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirmer</label>
                <input type="password" name="password_confirmation" class="form-input">
            </div>
            <button type="submit" class="btn-primary w-full">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection