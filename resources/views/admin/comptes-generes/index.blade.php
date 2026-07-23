@extends('layouts.admin')
@section('title', 'Comptes générés — Identifiants')

@section('content')

<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-bottom:20px;">
    <p style="font-size:13px;color:#991b1b;">
        <i data-lucide="shield-alert" class="w-4 h-4 inline"></i>
        <strong>Zone sensible :</strong> cette page affiche les mots de passe en clair. Distribuez-les de
        manière sécurisée puis purgez la liste après distribution.
    </p>
</div>

<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">Liste des identifiants générés</h3>
        @if(request()->filled('role'))
        <div class="flex gap-2">
            <a href="{{ route('admin.comptes-generes.export-excel', request()->all()) }}" class="btn-outline">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Export Excel
            </a>
            <a href="{{ route('admin.comptes-generes.export-pdf', request()->all()) }}" target="_blank" class="btn-primary">
                <i data-lucide="printer" class="w-4 h-4"></i> Export PDF
            </a>
        </div>
        @endif
    </div>

    <form method="GET" class="flex gap-3 mb-4">
        <select name="role" class="form-select" style="max-width:220px;" onchange="this.form.submit()">
            <option value="">-- Choisir un rôle --</option>
            <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Administrateurs</option>
            <option value="proviseur" {{ request('role')==='proviseur'?'selected':'' }}>Proviseurs</option>
            <option value="parent" {{ request('role')==='parent'?'selected':'' }}>Parents</option>
            <option value="eleve" {{ request('role')==='eleve'?'selected':'' }}>Élèves</option>
            <option value="enseignant" {{ request('role')==='enseignant'?'selected':'' }}>Enseignants</option>
            <option value="secretaire_intendant" {{ request('role')==='secretaire_intendant'?'selected':'' }}>Secrétaires</option>
            <option value="surveillant_general" {{ request('role')==='surveillant_general'?'selected':'' }}>Surveillants Généraux</option>
        </select>

        @if(request()->filled('role'))
        <select name="statut" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">Tous</option>
            <option value="non_exporte" {{ request('statut')==='non_exporte'?'selected':'' }}>Non exportés</option>
            <option value="exporte" {{ request('statut')==='exporte'?'selected':'' }}>Déjà exportés</option>
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="form-input" style="max-width:200px;">
        <button type="submit" class="btn-outline">Filtrer</button>
        <a href="{{ route('admin.comptes-generes.index') }}" class="btn-secondary">Réinitialiser</a>
        <input type="hidden" name="role" value="{{ request('role') }}">
        @endif
    </form>

    @if(!request()->filled('role'))
        <div style="text-align:center;padding:50px 20px;color:#9ca3af;">
            <i data-lucide="filter" style="width:44px;height:44px;margin:0 auto 12px;display:block;"></i>
            <p class="font-medium">Sélectionnez un rôle ci-dessus pour afficher les comptes correspondants.</p>
        </div>
    @else
        <div class="table-wrapper">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Mot de passe</th>
                        <th>Rôle</th>
                        <th>Élève lié</th>
                        <th>Créé le</th>
                        <th>Statut</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($comptes as $c)
                    <tr>
                        <td class="font-medium">{{ $c->nom }}</td>
                        <td>{{ $c->email }}</td>
                        <td>
                            <code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:12px;">
                                {{ $c->mot_de_passe }}
                            </code>
                        </td>
                        <td><span class="badge-blue">{{ ucfirst(str_replace('_',' ',$c->role)) }}</span></td>
                        <td>{{ $c->eleve_lie ?? '-' }}</td>
                        <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($c->exporte)
                                <span class="badge-green">Exporté</span>
                            @else
                                <span class="badge-amber">Non exporté</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('admin.comptes-generes.destroy', $c) }}"
                                  onsubmit="return confirm('Supprimer cette entrée ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-gray-400 py-6">Aucun compte pour ce rôle.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $comptes->links() }}</div>

        <div style="border-top:1px solid #e5e7eb;margin-top:20px;padding-top:16px;">
            <form method="POST" action="{{ route('admin.comptes-generes.purger') }}"
                  onsubmit="return confirm('Purger définitivement les mots de passe déjà exportés ?')">
                @csrf
                <label class="login-checkbox-label">
                    <input type="checkbox" name="confirmation" value="1" required class="form-checkbox">
                    Je confirme vouloir purger les mots de passe déjà exportés
                </label>
                <button type="submit" class="btn-danger mt-2">
                    <i data-lucide="shield-x" class="w-4 h-4"></i> Purger les comptes exportés
                </button>
            </form>
        </div>
    @endif
</div>
@endsection