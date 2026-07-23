@extends('layouts.admin')

@section('title', 'Modifier — ' . $user->name)

@section('content')
<div class="container">

    <div class="topbar">
        <div class="title">Modifier utilisateur</div>

        <a href="{{ route('admin.users.index', $user) }}" class="btn-back">
            ← Retour
        </a>
    </div>

    <div class="section">
        <div class="card" style="max-width:560px; margin:0 auto;">
            <div class="section-title">Modifier — {{ $user->name }}</div>

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nom complet *</label>
                    <input type="text" name="name" required class="form-input"
                           value="{{ old('name', $user->name) }}">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" required class="form-input"
                           value="{{ old('email', $user->email) }}">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-input"
                           value="{{ old('telephone', $user->telephone) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Rôle *</label>
                    <select name="role" id="role" required class="form-select">
                        <option value="">-- Choisir un rôle --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ old('role', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Bloc parent : visible uniquement si rôle = parent --}}
                <div id="bloc_parent_lien" style="{{ old('role', $user->roles->first()?->name) === 'parent' ? '' : 'display:none;' }}">
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:14px;">
                        <h4 class="font-semibold text-gray-700 mb-3">
                            <i data-lucide="users" class="w-4 h-4 inline"></i>
                            Élève(s) à associer à ce parent
                        </h4>

                        <div class="form-group">
                            <label class="form-label">Classe</label>
                            <select id="sel_classe_parent" class="form-select">
                                <option value="">-- Choisir une classe --</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}">
                                        {{ $classe->nom }} ({{ $classe->section->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Élève(s) associé(s) *</label>
                            <select name="eleves_ids[]" id="sel_eleves_parent" multiple class="form-select"
                                    style="min-height:120px;" disabled>
                                <option value="">Choisir d'abord une classe</option>
                            </select>
                            <p class="text-xs text-gray-400 mt-1">
                                Ctrl+clic pour sélectionner plusieurs élèves (frères/sœurs).
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Nouveau mot de passe
                        <span class="text-gray-400">(laisser vide = inchangé)</span>
                    </label>
                    <input type="password" name="password" class="form-input">
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-input">
                </div>

                <div class="form-group">
                    <label class="login-checkbox-label">
                        <input type="checkbox" name="actif" value="1" class="form-checkbox"
                               {{ old('actif', $user->actif) ? 'checked' : '' }}>
                        Compte actif
                    </label>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary w-full">Annuler</a>
                    <button type="submit" class="btn-primary w-full">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const blocParent = document.getElementById('bloc_parent_lien');
    const selClasse = document.getElementById('sel_classe_parent');
    const selEleves = document.getElementById('sel_eleves_parent');
    const oldClasseId = "{{ old('classe_id') }}";
    const elevesLies = @json(old('eleves_ids', $elevesLies));

    function toggleBlocParent() {
        if (roleSelect.value === 'parent') {
            blocParent.style.display = '';
        } else {
            blocParent.style.display = 'none';
        }
    }

    function loadElevesByClasse(classeId, selectedIds = []) {
        selEleves.disabled = true;
        selEleves.innerHTML = '<option value="">Chargement...</option>';

        if (!classeId) {
            selEleves.innerHTML = '<option value="">Choisir d\'abord une classe</option>';
            return;
        }

        fetch("{{ route('admin.users.elevesByClasse') }}?classe_id=" + classeId, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            selEleves.innerHTML = '';

            if (!data.length) {
                selEleves.innerHTML = '<option value="">Aucun élève trouvé</option>';
                selEleves.disabled = true;
                return;
            }

            data.forEach(function (eleve) {
                const option = document.createElement('option');
                option.value = eleve.id;
                option.textContent = eleve.nom + ' ' + eleve.prenom + ' (' + (eleve.matricule ?? '') + ')';

                if (selectedIds.map(String).includes(String(eleve.id))) {
                    option.selected = true;
                }

                selEleves.appendChild(option);
            });

            selEleves.disabled = false;
        })
        .catch(() => {
            selEleves.innerHTML = '<option value="">Erreur de chargement</option>';
            selEleves.disabled = true;
        });
    }

    roleSelect.addEventListener('change', function () {
        toggleBlocParent();
        if (this.value !== 'parent') {
            selClasse.value = '';
            selEleves.innerHTML = '<option value="">Choisir d\'abord une classe</option>';
            selEleves.disabled = true;
        }
    });

    selClasse.addEventListener('change', function () {
        loadElevesByClasse(this.value);
    });

    toggleBlocParent();

    if (roleSelect.value === 'parent') {
        if (oldClasseId) {
            selClasse.value = oldClasseId;
        } else {
            const firstClasse = selClasse.querySelector('option[value!=""]');
            if (firstClasse) selClasse.value = firstClasse.value;
        }

        if (selClasse.value) {
            loadElevesByClasse(selClasse.value, elevesLies);
        }
    }
});
</script>
@endsection