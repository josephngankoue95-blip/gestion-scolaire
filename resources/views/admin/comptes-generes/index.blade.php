@extends('layouts.admin')

@section('title', 'Comptes générés')

@section('content')

<div class="card">

    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">
            Comptes utilisateurs générés
        </h3>

        @if(request()->filled('role'))
            <div class="flex gap-2 flex-wrap">

                <form
                    method="POST"
                    action="{{ route('admin.comptes-generes.envoyer-groupe') }}"
                    onsubmit="return confirm('Envoyer les identifiants par email à tous les comptes de ce rôle ayant un email ?')"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="role"
                        value="{{ request('role') }}"
                    >

                    <button type="submit" class="btn-outline">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        Envoyer à tous par email
                    </button>
                </form>

                <a
                    href="{{ route('admin.comptes-generes.export-excel', request()->query()) }}"
                    class="btn-outline"
                >
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    Excel
                </a>

                <a
                    href="{{ route('admin.comptes-generes.export-pdf', request()->query()) }}"
                    target="_blank"
                    class="btn-primary"
                >
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    PDF
                </a>
            </div>
        @endif
    </div>

    <div
        style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-bottom:20px;"
    >
        <p style="font-size:13px;color:#991b1b;">
            <i data-lucide="shield-alert" class="w-4 h-4 inline"></i>

            <strong>Zone sensible :</strong>
            les mots de passe sont affichés uniquement pour le rôle sélectionné.
        </p>
    </div>

    {{-- Formulaire de filtrage --}}
    <form method="GET" class="flex gap-3 mb-4 flex-wrap">
    <select name="role" class="form-select" style="max-width:220px;" onchange="this.form.submit()">
        <option value="">-- Sélectionner un rôle --</option>
        <option value="admin" @selected(request('role')==='admin')>Administrateurs</option>
        <option value="proviseur" @selected(request('role')==='proviseur')>Proviseurs</option>
        <option value="prefet_etudes" @selected(request('role')==='prefet_etudes')>Préfets des études</option>
        <option value="parent" @selected(request('role')==='parent')>Parents</option>
        <option value="eleve" @selected(request('role')==='eleve')>Élèves</option>
        <option value="enseignant" @selected(request('role')==='enseignant')>Enseignants</option>
        <option value="secretaire_intendant" @selected(request('role')==='secretaire_intendant')>Secrétaires</option>
        <option value="surveillant_general" @selected(request('role')==='surveillant_general')>Surveillants</option>
        <option value="bibliothecaire" @selected(request('role')==='bibliothecaire')>Bibliothécaires</option>
    </select>

    @if(request()->filled('role'))
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="form-input" style="max-width:220px;">
    <select name="tri" class="form-select" style="max-width:170px;">
        <option value="recent" @selected(request('tri')==='recent')>Plus récents</option>
        <option value="ancien" @selected(request('tri')==='ancien')>Plus anciens</option>
        <option value="nom" @selected(request('tri')==='nom')>Alphabétique</option>
    </select>
    <button type="submit" class="btn-outline">Filtrer</button>
    <a href="{{ route('admin.comptes-generes.index') }}" class="btn-secondary">Réinitialiser</a>
    @endif
</form>

    {{-- Aucun rôle sélectionné --}}
    @if(!request()->filled('role'))

        <div
            style="text-align:center;padding:60px 20px;color:#9ca3af;"
        >
            <i
                data-lucide="filter"
                style="width:44px;height:44px;margin:0 auto 14px;display:block;"
            ></i>

            <p
                class="font-medium"
                style="font-size:15px;"
            >
                Sélectionnez un rôle pour afficher les comptes correspondants.
            </p>
        </div>

    @else

        {{-- Liste des comptes --}}
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
                        <th>Envoyé</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($comptes as $compte)

                        <tr>

                            <td class="font-medium">
                                {{ $compte->nom }}
                            </td>

                            <td>
                                {{ $compte->email ?: '-' }}
                            </td>

                            <td>
                                @if($compte->mot_de_passe)
                                    <code
                                        style="background:#f3f4f6;padding:4px 8px;border-radius:4px;font-size:12px;"
                                    >
                                        {{ $compte->mot_de_passe }}
                                    </code>
                                @else
                                    <span class="text-gray-400">
                                        Non disponible
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="badge badge-blue">
                                    {{ ucfirst(str_replace('_', ' ', $compte->role)) }}
                                </span>
                            </td>

                            <td>
                                {{ $compte->eleve_lie ?: '-' }}
                            </td>

                            <td>
                                {{ optional($compte->created_at)->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                @if($compte->envoye_le)

                                    <span
                                        class="badge badge-green"
                                        title="Envoyé le {{ $compte->envoye_le->format('d/m/Y H:i') }}"
                                    >
                                        Envoyé
                                    </span>

                                @else

                                    <span class="badge badge-gray">
                                        Non envoyé
                                    </span>

                                @endif
                            </td>

                            <td class="text-right">
                                <div class="flex items-center gap-2 justify-end">

                                    <!-- {{-- Envoi SMS --}}
                                    @if($compte->user?->telephone)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.comptes-generes.envoyer-sms', $compte) }}"
                                            onsubmit="return confirm('Envoyer les identifiants par SMS ?')"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-green-600"
                                                title="Envoyer par SMS"
                                            >
                                                <i
                                                    data-lucide="message-square"
                                                    class="w-4 h-4"
                                                ></i>
                                            </button>
                                        </form>

                                    @endif -->

                                    {{-- Envoi email --}}
                                    @if($compte->email)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.comptes-generes.envoyer-mail', $compte) }}"
                                            onsubmit="return confirm('Envoyer les identifiants par email à {{ $compte->email }} ?')"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-blue-600"
                                                title="Envoyer par email"
                                            >
                                                <i
                                                    data-lucide="mail"
                                                    class="w-4 h-4"
                                                ></i>
                                            </button>
                                        </form>

                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="8"
                                class="text-center text-gray-400 py-6"
                            >
                                Aucun compte trouvé pour ce rôle.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $comptes->links() }}
        </div>

    @endif

</div>

@endsection