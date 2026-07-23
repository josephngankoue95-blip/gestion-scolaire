@extends('layouts.admin')

@section('title', 'Emploi du temps — ' . $classe->nom)

@section('content')
<div class="card">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">
            {{ $classe->nom }}
        </h3>

        <a href="{{ route('surveillant.emplois-temps.index') }}" class="btn-back">
            ← Retour
        </a>
    </div>

    {{-- TABLE EMPLOI DU TEMPS --}}
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 text-sm">

            {{-- JOURS --}}
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border text-left font-bold text-gray-700">
                        Heures
                    </th>

                    @foreach ($jours as $jour)
                        <th class="p-2 border text-center font-bold uppercase text-primary-700">
                            {{ ucfirst($jour) }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            {{-- LIGNES : HEURES --}}
            <tbody>
                <tbody>

            @foreach($creneauxParHoraire as $horaire => $coursDuHoraire)

            <tr>

                <td class="p-2 border bg-gray-50 font-medium">
                    {{ $horaire }}
                </td>

                @foreach($jours as $jour)

                    @php
                        $cours = $coursDuHoraire->firstWhere('jour',$jour);
                    @endphp

                    <td class="p-2 border align-top">

                        @if($cours)

                            <div class="bg-blue-50 border border-blue-100 rounded p-2">

                                <div class="font-semibold">
                                    {{ $cours->matiere->nom }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $cours->enseignant->user->name }}
                                </div>

                                @if($cours->salle)
                                    <div class="text-xs mt-1">
                                        Salle {{ $cours->salle }}
                                    </div>
                                @endif

                            </div>

                        @else

                            <span class="text-gray-300">—</span>

                        @endif

                    </td>

                @endforeach

            </tr>

            @endforeach

            </tbody>
            </tbody>

        </table>
    </div>

</div>
@endsection