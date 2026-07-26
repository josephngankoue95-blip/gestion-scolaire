@extends('layouts.admin')
@section('title', 'Épreuves de composition')
@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold">Mes épreuves de composition</h3>
        <a href="{{ route('teacher.epreuves.create') }}" class="btn-primary">+ Envoyer une épreuve</a>
    </div>
    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Titre</th><th>Matière</th><th>Classe</th><th>Séquence</th><th class="text-right">Action</th></tr></thead>
            <tbody>
                @forelse ($epreuves as $e)
                <tr>
                    <td class="font-medium">{{ $e->titre }}</td>
                    <td>{{ $e->matiere->nom }}</td>
                    <td>{{ $e->classe->nom }}</td>
                    <td>{{ $e->sequence->nom }}</td>
                    <td class="text-right">
                        <a href="{{ asset('storage/'.$e->fichier) }}" target="_blank" class="login-link mr-2">Voir</a>
                        <form method="POST" action="{{ route('teacher.epreuves.destroy', $e) }}" class="inline" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')<button class="text-red-500">Suppr.</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-gray-400 py-6">Aucune épreuve envoyée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $epreuves->links() }}</div>
</div>
@endsection