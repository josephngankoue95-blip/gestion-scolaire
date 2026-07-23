@extends('layouts.admin')
@section('title', 'Nouvel élève')

@section('content')
<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Nouvel élève</div>
    </div>

    <form method="POST"
          action="{{ route('admin.eleves.store') }}"
          enctype="multipart/form-data">

        @csrf

        <div class="grid-card">

            <!-- NOM -->
            <div class="card">
                <div class="label">Nom</div>
                <input type="text" name="nom"
                       value="{{ old('nom') }}"
                       class="form-input mt-2">
                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- PRÉNOM -->
            <div class="card">
                <div class="label">Prénom</div>
                <input type="text" name="prenom"
                       value="{{ old('prenom') }}"
                       class="form-input mt-2">
            </div>

            <!-- DATE NAISSANCE -->
            <div class="card">
                <div class="label">Date de naissance</div>
                <input type="date" name="date_naissance"
                       value="{{ old('date_naissance') }}"
                       class="form-input mt-2">
            </div>

            <!-- LIEU -->
            <div class="card">
                <div class="label">Lieu de naissance</div>
                <input type="text" name="lieu_naissance"
                       value="{{ old('lieu_naissance') }}"
                       class="form-input mt-2">
            </div>

            <!-- SEXE -->
            <div class="card">
                <div class="label">Sexe</div>
                <select name="sexe" class="form-input mt-2">
                    <option value="">-- Choisir --</option>
                    <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                    <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                </select>
            </div>

            <!-- PHOTO -->
            <div class="card">
                <div class="label">Photo</div>
                <input type="file" name="photo" class="form-input mt-2">
            </div>



            {{-- Création compte parent --}}
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:16px;" id="bloc_parent_compte">
    <div class="flex justify-between items-center mb-3">
        <h4 class="font-semibold text-gray-700">
            <i data-lucide="user-check" class="w-4 h-4 inline"></i>
            Compte d'accès parent
        </h4>
        <label class="login-checkbox-label">
            <input type="checkbox" id="toggle_parent" name="creer_compte_parent" value="1"
                   class="form-checkbox" {{ old('creer_compte_parent') ? 'checked' : '' }}>
            Créer un compte parent
        </label>
    </div>

    <div id="champs_parent" style="{{ old('creer_compte_parent') ? '' : 'display:none;' }}">
        <p class="text-xs text-gray-500 mb-3">
            Un compte sera créé avec un mot de passe aléatoire affiché une seule fois.
            Le parent pourra le modifier après sa première connexion.
        </p>
        <div class="grid grid-cols-2 gap-4">
            <div class="form-group">
                <label class="form-label">Nom complet du parent *</label>
                <input type="text" name="parent_nom" class="form-input"
                       value="{{ old('parent_nom') }}" placeholder="ex: Jean Dupont">
            </div>
            <div class="form-group">
                <label class="form-label">Email du parent (identifiant de connexion) *</label>
                <input type="email" name="parent_email" class="form-input"
                       value="{{ old('parent_email') }}" placeholder="parent@email.com">
                @error('parent_email') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>

{{-- Compte élève --}}
<div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:16px;">
    <div class="flex justify-between items-center mb-3">
        <h4 class="font-semibold text-gray-700">
            <i data-lucide="graduation-cap" class="w-4 h-4 inline"></i> Compte d'accès élève
        </h4>
        <label class="login-checkbox-label">
            <input type="checkbox" id="toggle_eleve" name="creer_compte_eleve" value="1" class="form-checkbox"
                   {{ old('creer_compte_eleve') ? 'checked' : '' }}>
            Créer un compte élève
        </label>
    </div>

    <div id="champs_eleve" style="{{ old('creer_compte_eleve') ? '' : 'display:none;' }}">
        <p class="text-xs text-gray-500 mb-3">L'élève pourra consulter ses notes, bulletins, TD et soumettre des requêtes.</p>
        <div class="form-group">
            <label class="form-label">Email de l'élève (identifiant de connexion) *</label>
            <input type="email" name="eleve_email" class="form-input" value="{{ old('eleve_email') }}"
                   placeholder="eleve@email.com">
            @error('eleve_email') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('toggle_eleve').addEventListener('change', function () {
    document.getElementById('champs_eleve').style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush

@push('scripts')
<script>
document.getElementById('toggle_parent').addEventListener('change', function () {
    document.getElementById('champs_parent').style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush

            <!-- TÉLÉPHONE -->
            <div class="card">
                <div class="label">Téléphone parent</div>
                <input type="text" name="telephone_parent"
                       value="{{ old('telephone_parent') }}"
                       class="form-input mt-2">
            </div>


                        <!-- CLASSE -->
            <div class="card">
                <div class="label">Classe</div>
                <select name="classe_id" class="form-input mt-2" required>
                    <option value="">-- Choisir --</option>
                    @foreach ($classes as $classe)
                        <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom }} ({{ $classe->section?->code }})
                        </option>
                    @endforeach
                </select>
                @error('classe_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- ADRESSE -->
            <div class="card" style="grid-column:1/-1;">
                <div class="label">Adresse</div>
                <textarea name="adresse"
                          class="form-input mt-2"
                          rows="3">{{ old('adresse') }}</textarea>
            </div>

        </div>

        <!-- ACTIONS -->
        <div class="section">
            <div class="grid-card">
                <div class="card"
                     style="grid-column:1/-1; display:flex; justify-content:flex-end; gap:10px;">
                    <a href="{{ route('admin.eleves.index') }}" class="btn-back">
                        Annuler
                    </a>

                    <button type="submit" class="btn-save">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection