@extends('layouts.public')
@section('title', 'Candidature en ligne')

@section('content')
<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Candidature en ligne</div>
    </div>

    <!-- INTRO -->
    <div class="section">
        
            <div class="value" style="color:#4b5563;">
                Remplissez le formulaire ci-dessous et joignez les documents demandés.
                Votre dossier sera examiné par l'administration et vous recevrez une notification par SMS ou WhatsApp dès qu'une décision sera prise.
            </div>
    </div>

    @if ($errors->any())
        <div class="section">
            <div class="card">
                <ul style="margin:0; padding-left:18px; color:#b91c1c;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('public.candidature.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- ELEVE -->
        <div class="section">
            <div class="section-title">Informations de l'élève</div>

            <div class="grid-card">
                <div class="card">
                    <div class="label">Nom <span class="required">*</span></div>
                    <input type="text" name="nom" value="{{ old('nom') }}" class="form-control" required>
                </div>

                <div class="card">
                    <div class="label">Prénom <span class="required">*</span></div>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" class="form-control" required>
                </div>

                <div class="card">
                    <div class="label">Date de naissance <span class="required">*</span></div>
                    <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" class="form-control" required>
                </div>

                <div class="card">
                    <div class="label">Lieu de naissance</div>
                    <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance') }}" class="form-control">
                </div>

                <div class="card">
                    <div class="label">Sexe <span class="required">*</span></div>
                    <select name="sexe" class="form-control" required>
                        <option value="">Sélectionner</option>
                        <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>

                <div class="card">
                    <div class="label">Section souhaitée <span class="required">*</span></div>
                    <select name="section_id" class="form-control" required>
                        <option value="">Sélectionner</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="card">
                    <div class="label">Classe demandée <span class="required">*</span></div>
                    <input type="text" name="classe_demandee" value="{{ old('classe_demandee') }}" class="form-control" required>
                </div>

                <div class="card">
                    <div class="label">Établissement d'origine</div>
                    <input type="text" name="etablissement_origine" value="{{ old('etablissement_origine') }}" class="form-control">
                </div>
            </div>
        </div>

        <!-- PARENT -->
        <div class="section">
            <div class="section-title">Parent ou tuteur légal</div>

            <div class="grid-card">
                <div class="card" style="grid-column:1/-1;">
                    <div class="label">Nom complet <span class="required">*</span></div>
                    <input type="text" name="nom_parent" value="{{ old('nom_parent') }}" class="form-control" required>
                </div>

                <div class="card">
                    <div class="label">Téléphone WhatsApp <span class="required">*</span></div>
                    <input type="text" name="telephone_parent" value="{{ old('telephone_parent') }}" class="form-control" required>
                </div>

                <div class="card">
                    <div class="label">Email</div>
                    <input type="email" name="email_parent" value="{{ old('email_parent') }}" class="form-control">
                </div>

                <div class="card" style="grid-column:1/-1;">
                    <div class="label">Adresse</div>
                    <textarea name="adresse" rows="3" class="form-control">{{ old('adresse') }}</textarea>
                </div>
            </div>
        </div>

        <!-- DOCUMENTS -->
        <div class="section">
            <div class="section-title">Documents à fournir</div>

            <div class="grid-card">
                <div class="card">
                    <div class="label">Acte de naissance *</div>
                    <input type="file" name="acte_naissance" class="form-control" required>
                </div>

                <div class="card">
                    <div class="label">Dernier bulletin</div>
                    <input type="file" name="bulletin_precedent" class="form-control">
                </div>

                <div class="card">
                    <div class="label">Certificat de scolarité</div>
                    <input type="file" name="certificat_scolarite" class="form-control">
                </div>

                <div class="card">
                    <div class="label">Photo d'identité</div>
                    <input type="file" name="photo" class="form-control">
                </div>
            </div>

            <div class="section">
                <div class="card">
                    <div class="value" style="color:#6b7280;">
                        Formats autorisés : PDF, JPG, JPEG et PNG.
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="card" style="text-align:center;">
                <button type="submit" class="btn-submit">
                    Soumettre ma candidature
                </button>
            </div>
        </div>
    </form>

</div>
@endsection