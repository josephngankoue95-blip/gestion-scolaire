@extends('layouts.admin')
@section('title', 'Épreuves externes')
@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold">Liens d'épreuves externes (Examens Cameroun...)</h3>
        <a href="{{ route('admin.epreuves-externes.create') }}" class="btn-primary">+ Ajouter</a>
    </div>
    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Titre</th><th>Niveau</th><th>Matière</th><th>Année</th><th>Statut</th><th class="text-right">Action</th></tr></thead>
            <tbody>
                @foreach ($epreuves as $e)
                <tr>
                    <td class="font-medium">{{ $e->titre }}</td>
                    <td>{{ $e->niveau ?? '-' }}</td>
                    <td>{{ $e->matiere ?? '-' }}</td>
                    <td>{{ $e->annee_examen ?? '-' }}</td>
                    <td><span class="{{ $e->actif ? 'badge-green' : 'badge-gray' }}">{{ $e->actif ? 'Actif' : 'Inactif' }}</span></td>
                    <td class="text-right">
                        <a href="{{ route('admin.epreuves-externes.edit', $e) }}" class="login-link mr-2">Modifier</a>
                        <form method="POST" action="{{ route('admin.epreuves-externes.destroy', $e) }}" class="inline" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')<button class="text-red-500">Suppr.</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $epreuves->links() }}</div>
</div>
@endsection