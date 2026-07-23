@extends('layouts.admin')
@section('title', 'Travaux Dirigés')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">Mes Travaux Dirigés</h3>
        <a href="{{ route('teacher.td.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Nouveau TD
        </a>
    </div>

    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Matière</th>
                    <th>Classe</th>
                    <th>Publication</th>
                    <th>Délai accès</th>
                    <th>Statut</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tds as $td)
                @php
                    $statut = $td->statut();
                    $badgeClass = match($statut) {
                        'actif'     => 'badge-green',
                        'brouillon' => 'badge-gray',
                        'programme' => 'badge-blue',
                        'expire'    => 'badge-red',
                        default     => 'badge-gray',
                    };
                @endphp
                <tr>
                    <td class="font-medium">{{ $td->titre }}</td>
                    <td>{{ $td->matiere->nom }}</td>
                    <td>{{ $td->classe->nom }}</td>
                    <td>{{ $td->date_publication->format('d/m/Y H:i') }}</td>
                    <td>
                        <span style="color:{{ now() > $td->date_limite_acces ? '#c0392b' : '#1a7a1a' }};">
                            {{ $td->date_limite_acces->format('d/m/Y H:i') }}
                        </span>
                    </td>
                    <td><span class="{{ $badgeClass }}">{{ ucfirst($statut) }}</span></td>
                    <td class="text-right">
                        <a href="{{ route('teacher.td.show', $td) }}" class="login-link mr-2">Voir</a>
                        <a href="{{ route('teacher.td.edit', $td) }}" class="login-link mr-2">Modifier</a>
                        <form method="POST" action="{{ route('teacher.td.destroy', $td) }}" class="inline" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 text-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-gray-400 py-6">Aucun TD créé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $tds->links() }}</div>
</div>
@endsection