@extends('layouts.admin')
@section('title', 'Conseils de classe')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">Conseils de classe — {{ $annee?->libelle }}</h3>
        <a href="{{ route('admin.conseils.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Nouveau conseil
        </a>
    </div>

    <form method="GET" class="flex gap-3 mb-4">
        <select name="classe_id" class="form-select" style="max-width:200px;" onchange="this.form.submit()">
            <option value="">Toutes les classes</option>
            @foreach ($classes as $c)
                <option value="{{ $c->id }}" {{ request('classe_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
            @endforeach
        </select>
    </form>

    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr><th>Classe</th><th>Trimestre</th><th>Date</th><th>Président</th><th>Décisions</th><th>Statut</th><th class="text-right">Action</th></tr>
            </thead>
            <tbody>
                @forelse ($conseils as $c)
                <tr>
                    <td class="font-medium">{{ $c->classe->nom }} <span class="badge badge-blue">{{ $c->classe->section->code }}</span></td>
                    <td>{{ $c->trimestre?->nom ?? '-' }}</td>
                    <td>{{ $c->date_conseil->format('d/m/Y') }}</td>
                    <td>{{ $c->president?->name ?? '-' }}</td>
                    <td>{{ $c->decisions()->count() }}</td>
                    <td>
                        @if($c->statut === 'cloture') <span class="badge badge-green">Clôturé</span>
                        @else <span class="badge badge-amber">En cours</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.conseils.show', $c) }}" class="login-link">Ouvrir</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-gray-400 py-6">Aucun conseil de classe.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $conseils->links() }}</div>
</div>
@endsection