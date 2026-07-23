@extends('layouts.admin')

@section('title', 'Nouvel utilisateur')

@section('content')
<div class="card" style="max-width:580px;">
    <h3 class="font-semibold text-gray-800 mb-4">Créer un utilisateur</h3>

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
        @csrf

        <div class="form-group">
            <label class="form-label">Nom complet *</label>
            <input type="text" name="name" required class="form-input" value="{{ old('name') }}">
            @error('name') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email *</label>
            <input type="email" name="email" required class="form-input" value="{{ old('email') }}">
            @error('email') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-input" value="{{ old('telephone') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Rôle *</label>
            <select name="role" id="sel_role" required class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                    </option>
                @endforeach
            </select>
            @error('role') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div id="bloc_parent_lien" style="{{ old('role') === 'parent' ? '' : 'display:none;' }}">
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
            <label class="form-label">Mot de passe *</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" required class="form-input">
                <button type="button" data-toggle-password="#password" class="password-toggle">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Confirmer le mot de passe *</label>
            <input type="password" name="password_confirmation" required class="form-input">
        </div>

        <div class="form-group">
            <label class="login-checkbox-label">
                <input type="checkbox" name="actif" value="1" checked class="form-checkbox">
                Compte actif
            </label>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary w-full">Annuler</a>
            <button type="submit" class="btn-primary w-full">Créer</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const urlEleves = @json(route('admin.users.elevesByClasse'));
    const roleSelect = document.getElementById('sel_role');
    const blocParent = document.getElementById('bloc_parent_lien');
    const selClasse = document.getElementById('sel_classe_parent');
    const selEleves = document.getElementById('sel_eleves_parent');

    function toggleBloc() {
        const isParent = roleSelect.value === 'parent';
        blocParent.style.display = isParent ? 'block' : 'none';

        if (!isParent) {
            selClasse.value = '';
            selEleves.innerHTML = '<option value="">Choisir d\'abord une classe</option>';
            selEleves.disabled = true;
        }
    }

    function loadEleves(classeId) {
        selEleves.disabled = true;
        selEleves.innerHTML = '<option value="">Chargement...</option>';

        if (!classeId) {
            selEleves.innerHTML = '<option value="">Choisir d\'abord une classe</option>';
            return;
        }

        fetch(`${urlEleves}?classe_id=${classeId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(eleves => {
            selEleves.innerHTML = '';

            if (!eleves.length) {
                selEleves.innerHTML = '<option value="">Aucun élève trouvé</option>';
                selEleves.disabled = true;
                return;
            }

            eleves.forEach(e => {
                const opt = document.createElement('option');
                opt.value = e.id;
                opt.textContent = `${e.prenom} ${e.nom} (${e.matricule ?? ''})`;
                selEleves.appendChild(opt);
            });

            selEleves.disabled = false;
        })
        .catch(() => {
            selEleves.innerHTML = '<option value="">Erreur de chargement</option>';
            selEleves.disabled = true;
        });
    }

    roleSelect.addEventListener('change', toggleBloc);
    selClasse.addEventListener('change', function () {
        loadEleves(this.value);
    });

    toggleBloc();
});
</script>
@endpush