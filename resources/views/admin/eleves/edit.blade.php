@extends('layouts.admin')

@section('title', 'Modifier élève')

@section('content')

<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Modifier élève</div>
    </div>

    <form method="POST"
          action="{{ route('admin.eleves.update', $eleve) }}"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <input type="hidden"
               name="classe_id"
               value="{{ request('classe_id') }}">

        <div class="grid-card">

            <!-- NOM -->
            <div class="card">
                <div class="label">Nom</div>
                <input type="text" name="nom"
                       value="{{ old('nom', $eleve->nom) }}"
                       class="form-input mt-2">
            </div>

            <!-- PRÉNOM -->
            <div class="card">
                <div class="label">Prénom</div>
                <input type="text" name="prenom"
                       value="{{ old('prenom', $eleve->prenom) }}"
                       class="form-input mt-2">
            </div>

            <!-- DATE NAISSANCE -->
            <div class="card">
                <div class="label">Date de naissance</div>
                <input type="date" name="date_naissance"
                       value="{{ old('date_naissance', $eleve->date_naissance?->format('Y-m-d')) }}"
                       class="form-input mt-2">
            </div>

            <!-- LIEU -->
            <div class="card">
                <div class="label">Lieu de naissance</div>
                <input type="text" name="lieu_naissance"
                       value="{{ old('lieu_naissance', $eleve->lieu_naissance) }}"
                       class="form-input mt-2">
            </div>

            <!-- SEXE -->
            <div class="card">
                <div class="label">Sexe</div>

                <select name="sexe" class="form-input mt-2">
                    <option value="M" {{ old('sexe', $eleve->sexe)=='M'?'selected':'' }}>Masculin</option>
                    <option value="F" {{ old('sexe', $eleve->sexe)=='F'?'selected':'' }}>Féminin</option>
                </select>
            </div>

            <!-- PHOTO -->
            <div class="card">
                <div class="label">Photo</div>
                <input type="file" name="photo" class="form-input mt-2">

                @if($eleve->photo)
                    <img src="{{ asset('storage/'.$eleve->photo) }}"
                         style="width:80px;height:80px;border-radius:8px;margin-top:10px;">
                @endif
            </div>

            <!-- PÈRE -->
            <!-- <div class="card">
                <div class="label">Nom du père</div>
                <input type="text" name="nom_pere"
                       value="{{ old('nom_pere', $eleve->nom_pere) }}"
                       class="form-input mt-2">
            </div> -->

            <!-- MÈRE -->
            <!-- <div class="card">
                <div class="label">Nom de la mère</div>
                <input type="text" name="nom_mere"
                       value="{{ old('nom_mere', $eleve->nom_mere) }}"
                       class="form-input mt-2">
            </div> -->

            {{-- Compte Parent --}}
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:16px;">
    <div class="flex justify-between items-center mb-3">
        <h4 class="font-semibold text-gray-700">
            <i data-lucide="user-check" class="w-4 h-4 inline"></i> Compte d'accès Parent
        </h4>
        @if($eleve->parent_user_id)
            <span class="badge-green">Compte actif</span>
        @else
            <label class="login-checkbox-label">
                <input type="checkbox" id="toggle_parent" name="creer_compte_parent" value="1" class="form-checkbox">
                Créer un compte
            </label>
        @endif
    </div>

    @if($eleve->parent_user_id)
        {{-- Compte existant --}}
        <input type="hidden" name="creer_compte_parent" value="1">
        <div class="grid grid-cols-2 gap-4">
            <div class="form-group">
                <label class="form-label">Nom du parent</label>
                <input type="text" name="parent_nom" class="form-input" value="{{ old('parent_nom', $eleve->parentUser?->name) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Email (identifiant)</label>
                <input type="email" name="parent_email" class="form-input" value="{{ old('parent_email', $eleve->parentUser?->email) }}">
            </div>
            <div class="form-group" style="grid-column:span 2;">
                <label class="form-label">Nouveau mot de passe <span class="text-gray-400">(laisser vide = inchangé)</span></label>
                <input type="password" name="parent_password" class="form-input">
            </div>
        </div>
    @else
        {{-- Aucun compte : formulaire création --}}
        <div id="champs_parent" style="display:none;">
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Nom complet du parent *</label>
                    <input type="text" name="parent_nom" class="form-input" value="{{ old('parent_nom') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Email du parent *</label>
                    <input type="email" name="parent_email" class="form-input" value="{{ old('parent_email') }}">
                    @error('parent_email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group" style="grid-column:span 2;">
                    <label class="form-label">Mot de passe <span class="text-gray-400">(laisser vide = généré automatiquement)</span></label>
                    <input type="password" name="parent_password" class="form-input">
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Compte Élève --}}
<div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:16px;">
    <div class="flex justify-between items-center mb-3">
        <h4 class="font-semibold text-gray-700">
            <i data-lucide="graduation-cap" class="w-4 h-4 inline"></i> Compte d'accès Élève
        </h4>
        @if($eleve->eleve_user_id)
            <span class="badge-green">Compte actif</span>
        @else
            <label class="login-checkbox-label">
                <input type="checkbox" id="toggle_eleve" name="creer_compte_eleve" value="1" class="form-checkbox">
                Créer un compte
            </label>
        @endif
    </div>

    @if($eleve->eleve_user_id)
        <input type="hidden" name="creer_compte_eleve" value="1">
        <div class="grid grid-cols-2 gap-4">
            <div class="form-group">
                <label class="form-label">Email (identifiant)</label>
                <input type="email" name="eleve_email" class="form-input" value="{{ old('eleve_email', $eleve->eleveUser?->email) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Nouveau mot de passe <span class="text-gray-400">(laisser vide = inchangé)</span></label>
                <input type="password" name="eleve_password" class="form-input">
            </div>
        </div>
    @else
        <div id="champs_eleve" style="display:none;">
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Email de l'élève *</label>
                    <input type="email" name="eleve_email" class="form-input" value="{{ old('eleve_email') }}">
                    @error('eleve_email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe <span class="text-gray-400">(laisser vide = généré automatiquement)</span></label>
                    <input type="password" name="eleve_password" class="form-input">
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.getElementById('toggle_parent')?.addEventListener('change', function () {
    document.getElementById('champs_parent').style.display = this.checked ? 'block' : 'none';
});
document.getElementById('toggle_eleve')?.addEventListener('change', function () {
    document.getElementById('champs_eleve').style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush

            <!-- TÉLÉPHONE -->
            <div class="card">
                <div class="label">Téléphone parent</div>
                <input type="text" name="telephone_parent"
                       value="{{ old('telephone_parent', $eleve->telephone_parent) }}"
                       class="form-input mt-2">
            </div>

            <!-- EMAIL -->
            <!-- <div class="card">
                <div class="label">Email parent</div>
                <input type="email" name="email_parent"
                       value="{{ old('email_parent', $eleve->email_parent) }}"
                       class="form-input mt-2">
            </div> -->

                        <!-- STATUT -->
            <div class="card">
                <div class="label">Statut</div>

                <select name="statut" class="form-input mt-2">
                    <option value="actif" {{ old('statut',$eleve->statut)=='actif'?'selected':'' }}>Actif</option>
                    <option value="inactif" {{ old('statut',$eleve->statut)=='inactif'?'selected':'' }}>Inactif</option>
                    <option value="transfere" {{ old('statut',$eleve->statut)=='transfere'?'selected':'' }}>Transféré</option>
                    <option value="diplome" {{ old('statut',$eleve->statut)=='diplome'?'selected':'' }}>Diplômé</option>
                </select>
            </div>

            <!-- ADRESSE -->
            <div class="card" style="grid-column:1/-1;">
                <div class="label">Adresse</div>
                <textarea name="adresse"
                          class="form-input mt-2"
                          rows="3">{{ old('adresse', $eleve->adresse) }}</textarea>
            </div>

        </div>

        <!-- ACTIONS -->
        <div class="section">
            <div class="grid-card">

                <div class="card"
                     style="grid-column:1/-1; display:flex; justify-content:flex-end; gap:10px;">

                    <a href="{{ route('admin.eleves.index', ['classe_id' => request('classe_id')]) }}"
                       class="btn-back">
                        Annuler
                    </a>

                    <button type="submit"
                            class="btn-save">
                        Modifier
                    </button>

                </div>

            </div>
        </div>

    </form>

</div>

@endsection