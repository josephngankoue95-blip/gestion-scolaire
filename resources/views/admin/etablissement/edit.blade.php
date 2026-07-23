@extends('layouts.admin')

@section('title', 'Configuration de l\'établissement')

@section('content')

<div class="container">

    <form method="POST"
          action="{{ route('admin.etablissement.update') }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- IDENTITE -->
         <div style="background: #f2f2f7ff;border:1px solid #4eb459ff;border-radius:10px;padding:16px;margin-top:20px;">
        <h4 class="font-semibold text-gray-700 mb-3">Informations collège/lycée</h4> 
        <div class="section">
            <div class="section-title">Identité</div>

            <div class="grid-card">
                <div class="card">
                    <div class="label">Nom complet *</div>
                    <div class="value">
                        <input type="text" name="nom" required
                               value="{{ old('nom', $etablissement->nom) }}"
                               placeholder="ex: Lycée Bilingue de Douala">
                        @error('nom') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Sigle</div>
                    <div class="value">
                        <input type="text" name="sigle"
                               value="{{ old('sigle', $etablissement->sigle) }}"
                               placeholder="ex: LBD">
                        @error('sigle') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Type d'établissement</div>
                    <div class="value">
                        <select name="type_etablissement">
                            <option value="">-- Choisir --</option>
                            @foreach (['Lycée', 'Collège', 'CES', 'Lycée Technique', 'Collège Technique'] as $type)
                                <option value="{{ $type }}"
                                    {{ old('type_etablissement', $etablissement->type_etablissement) == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_etablissement') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Ordre d'enseignement</div>
                    <div class="value">
                        <select name="ordre_enseignement">
                            <option value="">-- Choisir --</option>
                            @foreach (['Public', 'Privé Laïc', 'Privé Confessionnel', 'Privé Islamique'] as $ordre)
                                <option value="{{ $ordre }}"
                                    {{ old('ordre_enseignement', $etablissement->ordre_enseignement) == $ordre ? 'selected' : '' }}>
                                    {{ $ordre }}
                                </option>
                            @endforeach
                        </select>
                        @error('ordre_enseignement') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Ministère de tutelle</div>
                    <div class="value">
                        <input type="text" name="ministre_tutelle"
                               value="{{ old('ministre_tutelle', $etablissement->ministre_tutelle) }}"
                               placeholder="ex: MINESEC">
                        @error('ministre_tutelle') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Code établissement</div>
                    <div class="value">
                        <input type="text" name="code_etablissement"
                               value="{{ old('code_etablissement', $etablissement->code_etablissement) }}">
                        @error('code_etablissement') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card" style="grid-column:1/-1;">
                    <div class="label">Devise / Slogan</div>
                    <div class="value">
                        <input type="text" name="devise"
                               value="{{ old('devise', $etablissement->devise) }}"
                               placeholder="ex: Excellence et Intégrité">
                        @error('devise') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- LOGO -->
        <div class="section">
            <div class="section-title">Logo</div>

            <div class="card">
                @if ($etablissement->logo)
                    <img src="{{ $etablissement->logoUrl() }}"
                         alt="Logo"
                         style="height:80px;margin-bottom:12px;border-radius:8px;">
                @endif

                <input type="file" name="logo" accept="image/png,image/jpeg">
                @error('logo') <p class="error-text">{{ $message }}</p> @enderror

                <div class="value" style="margin-top:8px;color:#6b7280;">
                    PNG ou JPG · Max 2 Mo · Fond transparent recommandé
                </div>
            </div>
        </div>

        <!-- LOCALISATION -->
        <div class="section">
            <div class="section-title">Localisation</div>

            <div class="grid-card">
                <div class="card" style="grid-column:1/-1;">
                    <div class="label">Adresse</div>
                    <div class="value">
                        <textarea name="adresse" rows="2" placeholder="Quartier, rue...">{{ old('adresse', $etablissement->adresse) }}</textarea>
                        @error('adresse') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Ville</div>
                    <div class="value">
                        <input type="text" name="ville"
                               value="{{ old('ville', $etablissement->ville) }}"
                               placeholder="ex: Douala">
                        @error('ville') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Région</div>
                    <div class="value">
                        <select name="region">
                            <option value="">-- Région --</option>
                            @foreach (['Adamaoua','Centre','Est','Extrême-Nord','Littoral','Nord','Nord-Ouest','Ouest','Sud','Sud-Ouest'] as $region)
                                <option value="{{ $region }}"
                                    {{ old('region', $etablissement->region) == $region ? 'selected' : '' }}>
                                    {{ $region }}
                                </option>
                            @endforeach
                        </select>
                        @error('region') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Pays</div>
                    <div class="value">
                        <input type="text" name="pays"
                               value="{{ old('pays', $etablissement->pays ?? 'Cameroun') }}">
                        @error('pays') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Boîte postale</div>
                    <div class="value">
                        <input type="text" name="bp"
                               value="{{ old('bp', $etablissement->bp) }}"
                               placeholder="ex: BP 1234">
                        @error('bp') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTACTS -->
        <div class="section">
            <div class="section-title">Contacts</div>

            <div class="grid-card">
                <div class="card">
                    <div class="label">Téléphone principal</div>
                    <div class="value">
                        <input type="text" name="telephone"
                               value="{{ old('telephone', $etablissement->telephone) }}"
                               placeholder="ex: +237 6XX XXX XXX">
                        @error('telephone') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Téléphone secondaire</div>
                    <div class="value">
                        <input type="text" name="telephone2"
                               value="{{ old('telephone2', $etablissement->telephone2) }}">
                        @error('telephone2') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Email</div>
                    <div class="value">
                        <input type="email" name="email"
                               value="{{ old('email', $etablissement->email) }}"
                               placeholder="contact@lycee.cm">
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="label">Site web</div>
                    <div class="value">
                        <input type="url" name="site_web"
                               value="{{ old('site_web', $etablissement->site_web) }}"
                               placeholder="https://www.lycee.cm">
                        @error('site_web') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Université -->
         <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:16px;margin-top:20px;">
            <h4 class="font-semibold text-gray-700 mb-3">Informations Université</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Nom de l'université/ institut</label>
                    <input type="text" name="nom_universite" class="form-input" value="{{ old('nom_universite', $etablissement->nom_universite) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Sigle</label>
                    <input type="text" name="sigle_universite" class="form-input" value="{{ old('sigle_universite', $etablissement->sigle_universite) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Logo université</label>
                    <input type="file" name="logo_universite" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Université partenaire (ex: Univ. de Dschang)</label>
                    <input type="text" name="universite_partenaire" class="form-input" value="{{ old('universite_partenaire', $etablissement->universite_partenaire) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Logo université partenaire</label>
                    @if($etablissement->logo_universite_partenaire)
                        <img src="{{ asset('storage/'.$etablissement->logo_universite_partenaire) }}"
                            style="height:80px;margin-bottom:10px;">
                    @endif

                    <input type="file" name="logo_universite_partenaire" accept="image/png,image/jpeg">
                    @error('logo_universite_partenaire') <p class="error-text">{{ $message }}</p> @enderror

                    <div class="value" style="margin-top:8px;color:#6b7280;">
                        PNG ou JPG · Max 2 Mo · Fond transparent recommandé
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">N° Autorisation MINESUP</label>
                    <input type="text" name="autorisation_minesup" class="form-input" value="{{ old('autorisation_minesup', $etablissement->autorisation_minesup) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Année académique</label>
                    <input type="text" name="annee_academique" class="form-input" placeholder="2026-2027" value="{{ old('annee_academique', $etablissement->annee_academique) }}">
                </div>
                <div class="form-group" style="grid-column:span 2;">
                    <label class="form-label">Campus (un par ligne)</label>
                    <textarea name="campus" rows="3" class="form-textarea">{{ old('campus', $etablissement->campus) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Téléphones université</label>
                    <input type="text" name="telephones_universite" class="form-input" value="{{ old('telephones_universite', $etablissement->telephones_universite) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Email université</label>
                    <input type="email" name="email_universite" class="form-input" value="{{ old('email_universite', $etablissement->email_universite) }}">
                </div>
            </div>
        </div>

        <!-- ACTIONS -->
        <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:20px;">
            <a href="{{ route('admin.dashboard') }}" class="btn-back">
                Annuler
            </a>

            <button type="submit" class="btn-save">
                Enregistrer
            </button>
        </div>
        
    </form>
</div>

@endsection