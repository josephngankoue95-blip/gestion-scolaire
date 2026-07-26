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
                    <i data-lucide="users" class="w-4 h-4 inline"></i> Enfant(s) à associer à ce parent
                </h4>
                <p class="text-xs text-gray-500 mb-3">
                    Ce parent peut avoir des enfants dans des classes différentes. Ajoutez une ligne par classe.
                </p>

                <div id="lignes-classes-eleves">
                    {{-- Ligne initiale --}}
                    <div class="ligne-classe-eleve" style="display:flex;gap:10px;margin-bottom:10px;align-items:flex-start;">
                        <div style="flex:1;">
                            <select class="form-select sel-classe-parent">
                                <option value="">-- Choisir une classe --</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->nom }} ({{ $classe->section->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="flex:2;">
                            <select name="eleves_ids[]" class="form-select sel-eleves-parent" multiple style="min-height:80px;" disabled>
                                <option>Choisir d'abord une classe</option>
                            </select>
                        </div>
                        <button type="button" class="btn-retirer-ligne" style="padding:8px;color:#c0392b;" title="Retirer cette ligne">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <button type="button" id="ajouter-ligne-classe" class="btn-outline" style="font-size:12px;padding:6px 12px;">
                    <i data-lucide="plus" class="w-4 h-4"></i> Ajouter une autre classe
                </button>
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
const urlEleves = "{{ route('admin.users.elevesByClasse') }}";
const container = document.getElementById('lignes-classes-eleves');

function chargerElevesPourLigne(ligne) {
    const selClasse  = ligne.querySelector('.sel-classe-parent');
    const selEleves  = ligne.querySelector('.sel-eleves-parent');
    const classeId   = selClasse.value;

    selEleves.innerHTML = '';
    selEleves.disabled  = true;

    if (!classeId) {
        selEleves.innerHTML = '<option>Choisir d\'abord une classe</option>';
        return;
    }

    selEleves.innerHTML = '<option>Chargement...</option>';

    fetch(`${urlEleves}?classe_id=${classeId}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
    .then(eleves => {
        selEleves.innerHTML = '';
        if (eleves.length === 0) {
            selEleves.innerHTML = '<option>Aucun élève dans cette classe</option>';
            return;
        }
        eleves.forEach(e => {
            const opt = document.createElement('option');
            opt.value = e.id;
            opt.textContent = `${e.prenom} ${e.nom} (${e.matricule})`;
            selEleves.appendChild(opt);
        });
        selEleves.disabled = false;
    })
    .catch(() => { selEleves.innerHTML = '<option>Erreur de chargement</option>'; });
}

function attacherEvenements(ligne) {
    ligne.querySelector('.sel-classe-parent').addEventListener('change', function () {
        chargerElevesPourLigne(ligne);
    });
    const btnRetirer = ligne.querySelector('.btn-retirer-ligne');
    btnRetirer.addEventListener('click', function () {
        if (container.querySelectorAll('.ligne-classe-eleve').length > 1) {
            ligne.remove();
        }
    });
}

// Ligne initiale
attacherEvenements(container.querySelector('.ligne-classe-eleve'));

// Ajout dynamique de nouvelles lignes
document.getElementById('ajouter-ligne-classe').addEventListener('click', function () {
    const template = container.querySelector('.ligne-classe-eleve').cloneNode(true);
    template.querySelector('.sel-classe-parent').value = '';
    const selEleves = template.querySelector('.sel-eleves-parent');
    selEleves.innerHTML = '<option>Choisir d\'abord une classe</option>';
    selEleves.disabled = true;
    container.appendChild(template);
    attacherEvenements(template);
});

document.getElementById('sel_role').addEventListener('change', function () {
    document.getElementById('bloc_parent_lien').style.display = this.value === 'parent' ? 'block' : 'none';
});
</script>
@endpush