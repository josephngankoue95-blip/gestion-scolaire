@extends('layouts.admin')
@section('title', 'Candidatures en ligne')

@section('content')
<div class="card">
    <div class="row-between mb-6">
        <h3 class="font-semibold text-gray-800">Candidatures reçues</h3>

        <form method="GET" class="flex gap-2 items-center">
            <select name="statut" onchange="this.form.submit()" class="form-input min-w-[220px]">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="acceptee" {{ request('statut') === 'acceptee' ? 'selected' : '' }}>Acceptées</option>
                <option value="refusee" {{ request('statut') === 'refusee' ? 'selected' : '' }}>Refusées</option>
            </select>
        </form>
    </div>

    <div class="table-wrapper overflow-x-auto">
        <table class="table-base w-full text-sm">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Élève</th>
                    <th>Section</th>
                    <th>Statut</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($candidatures as $candidature)
                    <tr>
                        <td class="py-2 px-2 font-medium text-gray-700 cell-center">
                            {{ $candidature->reference }}
                        </td>

                        <td class="py-2 px-2 font-medium text-gray-700 cell-center">
                            {{ $candidature->nomComplet() }}
                        </td>

                        <td class="py-2 px-2 font-medium text-gray-700 cell-center">
                            {{ $candidature->section?->nom ?? '-' }}
                        </td>

                        <td class="py-2 px-2 font-medium text-gray-700 cell-center">
                            @if($candidature->statut === 'en_attente')
                                <span class="badge badge-blue">En attente</span>
                            @elseif($candidature->statut === 'acceptee')
                                <span class="badge badge-green">Acceptée</span>
                            @elseif($candidature->statut === 'refusee')
                                <span class="badge badge-red">Refusée</span>
                            @else
                                <span class="badge badge-gray">{{ str_replace('_', ' ', $candidature->statut) }}</span>
                            @endif
                        </td>

                        <td class="py-2 px-2 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.candidatures.show', $candidature) }}"
                                   title="Traiter">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="table-empty">Aucune candidature.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 pagination">
        {{ $candidatures->links() }}
    </div>
</div>
@endsection