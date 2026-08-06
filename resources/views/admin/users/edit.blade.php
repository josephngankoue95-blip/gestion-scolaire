@extends('layouts.admin')

@section('title', 'Modifier — ' . $user->name)

@section('content')
<div class="container">

    <div class="topbar">
        <div class="title">
            Modifier utilisateur
        </div>

        <a
            href="{{ route('admin.users.index') }}"
            class="btn-back"
        >
            ← Retour
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Veuillez corriger les erreurs suivantes :</strong>

            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="section">
        <div
            class="card"
            style="max-width: 650px; margin: 0 auto;"
        >
            <div class="section-title">
                Modifier — {{ $user->name }}
            </div>

            <form
                method="POST"
                action="{{ route('admin.users.update', $user) }}"
                class="space-y-4"
            >
                @csrf
                @method('PUT')

                {{-- Nom --}}
                <div class="form-group">
                    <label
                        for="name"
                        class="form-label"
                    >
                        Nom complet *
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                        class="form-input"
                        value="{{ old('name', $user->name) }}"
                    >

                    @error('name')
                        <p class="form-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label
                        for="email"
                        class="form-label"
                    >
                        Email *
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        class="form-input"
                        value="{{ old('email', $user->email) }}"
                    >

                    @error('email')
                        <p class="form-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Téléphone --}}
                <div class="form-group">
                    <label
                        for="telephone"
                        class="form-label"
                    >
                        Téléphone
                    </label>

                    <input
                        type="text"
                        id="telephone"
                        name="telephone"
                        class="form-input"
                        value="{{ old('telephone', $user->telephone) }}"
                    >

                    @error('telephone')
                        <p class="form-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Rôle --}}
                @php
                    $roleActuel = old(
                        'role',
                        $user->roles->first()?->name
                    );
                @endphp

                <div class="form-group">
                    <label
                        for="role"
                        class="form-label"
                    >
                        Rôle *
                    </label>

                    <select
                        name="role"
                        id="role"
                        required
                        class="form-select"
                    >
                        <option value="">
                            -- Choisir un rôle --
                        </option>

                        @foreach ($roles as $role)
                            <option
                                value="{{ $role->name }}"
                                @selected($roleActuel === $role->name)
                            >
                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                            </option>
                        @endforeach
                    </select>

                    @error('role')
                        <p class="form-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Association des élèves --}}
                <div
                    id="bloc_parent_lien"
                    style="{{ $roleActuel === 'parent' ? '' : 'display: none;' }}"
                >
                    <div
                        style="
                            background: #f0fdf4;
                            border: 1px solid #bbf7d0;
                            border-radius: 8px;
                            padding: 14px;
                        "
                    >
                        <h4 class="font-semibold text-gray-700 mb-3">
                            <i
                                data-lucide="users"
                                class="w-4 h-4 inline"
                            ></i>

                            Élève(s) à associer à ce parent
                        </h4>

                        {{-- Classe --}}
                        <div class="form-group">
                            <label
                                for="sel_classe_parent"
                                class="form-label"
                            >
                                Classe
                            </label>

                            <select
                                name="classe_id"
                                id="sel_classe_parent"
                                class="form-select"
                            >
                                <option value="">
                                    -- Choisir une classe --
                                </option>

                                @foreach ($classes as $classe)
                                    <option
                                        value="{{ $classe->id }}"
                                        @selected(
                                            (string) old(
                                                'classe_id',
                                                $classeId
                                            ) === (string) $classe->id
                                        )
                                    >
                                        {{ $classe->nom }}

                                        @if ($classe->section)
                                            ({{ $classe->section->code }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            @error('classe_id')
                                <p class="form-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Élèves --}}
                        <div class="form-group">
                            <label
                                for="sel_eleves_parent"
                                class="form-label"
                            >
                                Élève(s) associé(s)
                            </label>

                            <select
                                name="eleves_ids[]"
                                id="sel_eleves_parent"
                                multiple
                                class="form-select"
                                style="min-height: 140px;"
                                disabled
                            >
                                <option value="">
                                    Choisir d'abord une classe
                                </option>
                            </select>

                            <p class="text-xs text-gray-400 mt-1">
                                Maintenez Ctrl ou Cmd pour sélectionner
                                plusieurs élèves.
                            </p>

                            @error('eleves_ids')
                                <p class="form-error">
                                    {{ $message }}
                                </p>
                            @enderror

                            @error('eleves_ids.*')
                                <p class="form-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Nouveau mot de passe --}}
                <div class="form-group">
                    <label
                        for="password"
                        class="form-label"
                    >
                        Nouveau mot de passe
                        <span class="text-gray-400">
                            (laisser vide = inchangé)
                        </span>
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                    >

                    @error('password')
                        <p class="form-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Confirmation mot de passe --}}
                <div class="form-group">
                    <label
                        for="password_confirmation"
                        class="form-label"
                    >
                        Confirmer le nouveau mot de passe
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-input"
                    >
                </div>

                {{-- Compte actif --}}
                <div class="form-group">
                    <label class="login-checkbox-label">
                        <input
                            type="checkbox"
                            name="actif"
                            value="1"
                            class="form-checkbox"
                            @checked(old('actif', $user->actif))
                        >

                        Compte actif
                    </label>
                </div>

                {{-- Boutons --}}
                <div class="flex gap-3 pt-2">
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="btn-secondary w-full"
                    >
                        Annuler
                    </a>

                    <button
                        type="submit"
                        class="btn-primary w-full"
                    >
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const blocParent = document.getElementById('bloc_parent_lien');
    const selClasse = document.getElementById('sel_classe_parent');
    const selEleves = document.getElementById('sel_eleves_parent');

    const oldClasseId = @json(old('classe_id', $classeId));

    const elevesLies = @json(
        old('eleves_ids', $elevesLies)
    );

    function toggleBlocParent() {
        if (roleSelect.value === 'parent') {
            blocParent.style.display = '';
        } else {
            blocParent.style.display = 'none';

            selClasse.value = '';
            selEleves.innerHTML = `
                <option value="">
                    Choisir d'abord une classe
                </option>
            `;
            selEleves.disabled = true;
        }
    }

    function loadElevesByClasse(
        classeId,
        selectedIds = []
    ) {
        selEleves.disabled = true;

        selEleves.innerHTML = `
            <option value="">
                Chargement...
            </option>
        `;

        if (!classeId) {
            selEleves.innerHTML = `
                <option value="">
                    Choisir d'abord une classe
                </option>
            `;

            return;
        }

        const url =
            "{{ route('admin.users.elevesByClasse') }}"
            + "?classe_id="
            + encodeURIComponent(classeId);

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Erreur HTTP');
            }

            return response.json();
        })
        .then(function (data) {
            selEleves.innerHTML = '';

            if (!Array.isArray(data) || data.length === 0) {
                selEleves.innerHTML = `
                    <option value="">
                        Aucun élève trouvé
                    </option>
                `;

                selEleves.disabled = true;

                return;
            }

            const selectedAsStrings = selectedIds.map(function (id) {
                return String(id);
            });

            data.forEach(function (eleve) {
                const option = document.createElement('option');

                option.value = eleve.id;

                option.textContent =
                    eleve.nom
                    + ' '
                    + (eleve.prenom ?? '')
                    + ' ('
                    + (eleve.matricule ?? '')
                    + ')';

                if (
                    selectedAsStrings.includes(
                        String(eleve.id)
                    )
                ) {
                    option.selected = true;
                }

                selEleves.appendChild(option);
            });

            selEleves.disabled = false;
        })
        .catch(function () {
            selEleves.innerHTML = `
                <option value="">
                    Erreur de chargement
                </option>
            `;

            selEleves.disabled = true;
        });
    }

    roleSelect.addEventListener('change', function () {
        toggleBlocParent();

        if (this.value === 'parent' && selClasse.value) {
            loadElevesByClasse(
                selClasse.value,
                elevesLies
            );
        }
    });

    selClasse.addEventListener('change', function () {
        loadElevesByClasse(
            this.value,
            []
        );
    });

    toggleBlocParent();

    if (roleSelect.value === 'parent') {
        if (oldClasseId) {
            selClasse.value = oldClasseId;
        }

        if (selClasse.value) {
            loadElevesByClasse(
                selClasse.value,
                elevesLies
            );
        }
    }
});
</script>
@endpush