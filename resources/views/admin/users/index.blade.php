@extends('layouts.admin')
@section('title', 'Utilisateurs')

@section('content')
<div class="card">
    <div class="row-between mb-6">
        <h3 class="font-semibold text-gray-800">Gestion des utilisateurs</h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            Ajouter un utilisateur
        </a>
    </div>

    <form method="GET" class="mb-5 flex gap-3 items-center">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Nom ou email..."
               class="form-input w-full md:w-1/3">

        <select name="role" class="form-select w-full md:w-1/5">
            <option value="">Tous les rôles</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-outline">Filtrer</button>
    </form>

    <div class="table-wrapper overflow-x-auto">
        <table class="table-base w-full text-sm">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="py-2 px-2 font-medium text-gray-700">{{ $user->name }}</td>
                        <td class="py-2 px-2 font-medium text-gray-700">{{ $user->email }}</td>
                        <td class="py-2 px-2 font-medium text-gray-700">{{ $user->telephone ?? '-' }}</td>
                        <td class="py-2 px-2 font-medium text-gray-700">
                            @forelse($user->roles as $role)
                                <span class="badge badge-blue">{{ str_replace('_',' ',$role->name) }}</span>
                            @empty
                                <span class="text-gray-400">-</span>
                            @endforelse
                        </td>
                        <td class="py-2 px-2 font-medium text-gray-700">
                            @if($user->actif)
                                <span class="badge badge-green">Actif</span>
                            @else
                                <span class="badge badge-red">Inactif</span>
                            @endif
                        </td>

                        <td class="py-2 px-2 text-center">
                            <div class="flex items-center justify-center gap-2">

                                {{-- VOIR --}}
                                <a href="{{ route('admin.users.show', $user) }}" title="Voir">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </span>
                                </a>

                                {{-- MODIFIER --}}
                                <a href="{{ route('admin.users.edit', $user) }}" title="Modifier">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-200 transition">
                                        <i data-lucide="square-pen" class="w-4 h-4"></i>
                                    </span>
                                </a>

                                {{-- ACTIVER / DESACTIVER --}}
                                <form action="{{ route('admin.users.toggle', $user) }}"
                                      method="POST"
                                      class="inline">
                                    @csrf
                                    <button type="submit" title="{{ $user->actif ? 'Désactiver' : 'Activer' }}">
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 hover:bg-emerald-200 transition">
                                            <i data-lucide="power" class="w-4 h-4"></i>
                                        </span>
                                    </button>
                                </form>

                                {{-- SUPPRIMER --}}
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" title="Supprimer">
                                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </span>
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="table-empty">Aucun utilisateur trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 pagination">
        {{ $users->links() }}
    </div>
</div>
@endsection