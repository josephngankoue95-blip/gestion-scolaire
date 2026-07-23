@extends('layouts.admin')
@section('title', 'Absences')

@section('content')

<div class="card">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">

        {{-- FILTRE CLASSE --}}
        <form method="GET" class="flex-1 max-w-md">
            <select name="classe_id"
                    onchange="this.form.submit()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none">

                <option value="">Toutes les classes</option>

                @foreach($classes as $classe)
                    <option value="{{ $classe->id }}"
                        {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                        {{ $classe->nom }}
                    </option>
                @endforeach

            </select>
        </form>

        {{-- BOUTON NOUVELLE ABSENCE (IMPORTANT FIX) --}}
        <div class="flex items-center gap-2">

            <select onchange="if(this.value) window.location.href=this.value"
                    class="px-4 py-2 border rounded-lg text-sm">

                <option value="">+ Nouvelle absence</option>

                @foreach($classes as $classe)
                    <option value="{{ route('surveillant.absences.create', $classe) }}">
                        {{ $classe->nom }}
                    </option>
                @endforeach

            </select>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-200">
                    <th class="py-3 px-2">Élève</th>
                    <th class="py-3 px-2">Classe</th>
                    <th class="py-3 px-2">Date</th>
                    <th class="py-3 px-2">Type</th>
                    <th class="py-3 px-2">Statut</th>
                    <th class="py-3 px-2">Signalé par</th>
                    <th class="py-3 px-2 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($absences as $absence)
                    <tr class="hover:bg-gray-50">

                        {{-- ÉLÈVE --}}
                        <td class="py-3 px-2 font-medium text-gray-700">
                            {{ $absence->eleve?->nomComplet() ?? '-' }}
                        </td>

                        {{-- CLASSE --}}
                        <td class="py-3 px-2">
                            {{ $absence->classe->nom ?? '-' }}
                        </td>

                        {{-- DATE --}}
                        <td class="py-3 px-2">
                            {{ \Carbon\Carbon::parse($absence->date_absence)->format('d/m/Y') }}
                        </td>

                        {{-- TYPE --}}
                        <td class="py-3 px-2">
                            {{ ucfirst($absence->type) }}
                        </td>

                        {{-- STATUT --}}
                        <td class="py-3 px-2">
                            @if($absence->justifiee)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    Justifiée
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                    Non justifiée
                                </span>
                            @endif
                        </td>

                        {{-- SIGNALÉ PAR --}}
                        <td class="py-3 px-2">
                            {{ $absence->signalePar->name ?? '-' }}
                        </td>

                        {{-- ACTIONS --}}
                        <td class="py-3 px-2 text-right space-x-2">

                            @if(!$absence->justifiee)
                                <form action="{{ route('surveillant.absences.justifier', $absence) }}"
                                      method="POST"
                                      class="inline">
                                    @csrf
                                    <input type="hidden" name="motif" value="Justifiée">

                                    <button class="text-green-600 hover:underline">
                                        Justifier
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('surveillant.absences.destroy', $absence) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Supprimer cette absence ?')">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-600 hover:underline">
                                    Supprimer
                                </button>
                            </form>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-gray-400">
                            Aucune absence enregistrée.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $absences->links() }}
    </div>

</div>

@endsection