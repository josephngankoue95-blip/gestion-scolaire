@extends('layouts.bibliotheque')
@section('title','Catalogue')
@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold">Catalogue des livres</h3>
        <a href="{{ route('bibliotheque.livres.create') }}" class="btn-primary">+ Ajouter</a>
    </div>
    <form method="GET" class="mb-4"><input name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="form-input" style="max-width:250px;"></form>
    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Titre</th><th>Auteur</th><th>Catégorie</th><th>Total</th><th>Disponible</th><th>Action</th></tr></thead>
            <tbody>
                @foreach ($livres as $l)
                <tr>
                    <td class="font-medium">{{ $l->titre }}</td>
                    <td>{{ $l->auteur ?? '-' }}</td>
                    <td>{{ $l->categorie ?? '-' }}</td>
                    <td>{{ $l->quantite_totale }}</td>
                    <td><span class="{{ $l->quantite_disponible > 0 ? 'badge-green' : 'badge-red' }}">{{ $l->quantite_disponible }}</span></td>
                    <td class="text-right">
                        <a href="{{ route('bibliotheque.livres.edit',$l) }}" class="login-link">Modifier</a>
                    </td>
                    <td class="text-right">
                        <form method="POST" action="{{ route('bibliotheque.livres.destroy', $l) }}"
                            data-confirm-delete
                            data-confirm-message="Supprimer {{ $l->titre }} ? Cette action est irréversible.">
                            @csrf @method('DELETE')
                            <button type="button" class="text-red-500" onclick="this.closest('form').requestSubmit()">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </span>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $livres->links() }}</div>
</div>
@endsection