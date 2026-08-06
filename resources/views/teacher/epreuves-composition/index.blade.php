@extends('layouts.admin')
@section('title', 'Épreuves de composition')
@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold">Épreuves de composition</h3>
            <p class="text-sm text-gray-500">Épreuves de la séquence en cours — année scolaire active</p>
        </div>
        <a href="{{ route('teacher.epreuves-composition.create') }}" class="btn-primary">+ Envoyer une épreuve</a>
    </div>
    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Titre</th><th>Matière</th><th>Classe</th><th>Séquence</th><th></th></tr></thead>
            <tbody>
                @forelse ($epreuves as $e)
                <tr>
                    <td class="font-medium">{{ $e->titre }}</td>
                    <td>{{ $e->matiere?->nom ?? '—' }}</td>
                    <td>{{ $e->classe?->nom ?? '—' }}</td>
                    <td>{{ $e->sequence?->nom ?? '—' }}</td>
                    <td class="text-right">
                        <div class="flex items-center gap-2 justify-end">
                            <a href="{{ route('teacher.epreuves-composition.show', $e) }}" class="login-link">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <form method="POST" action="{{ route('teacher.epreuves-composition.destroy', $e) }}"
                                  class="inline" data-confirm-delete data-confirm-message="Supprimer l'épreuve « {{ $e->titre }} » ?">
                                @csrf @method('DELETE')
                                <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                                <button type="button" class="text-red-500" onclick="this.closest('form').requestSubmit()"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </form>
                        </div>
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