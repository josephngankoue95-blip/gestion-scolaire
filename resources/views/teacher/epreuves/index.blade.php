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
                        <form method="POST" action="{{ route('teacher.epreuves.destroy', $e) }}"
                            data-confirm-delete
                            data-confirm-message="Supprimer {{ $e->titre }} ? Cette action est irréversible.">
                            @csrf @method('DELETE')
                            <button type="button" class="text-red-500" onclick="this.closest('form').requestSubmit()">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </span>
                            </button>
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