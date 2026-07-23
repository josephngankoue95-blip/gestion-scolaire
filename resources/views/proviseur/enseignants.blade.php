@extends('layouts.proviseur')
@section('title', 'Enseignants')

@section('content')
<div class="card">
    <h4 class="font-semibold text-gray-800 mb-4">Corps enseignant — {{ $annee?->libelle }}</h4>

    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Matricule</th>
                    <th>Matières / Classes</th>
                    <th>Nb Affectations</th>
                    <th>TD publiés</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($enseignants as $ens)
                <tr>
                    <td class="font-medium">{{ $ens->user->name }}</td>
                    <td>{{ $ens->matricule }}</td>
                    <td>
                        @foreach ($ens->affectations->take(3) as $aff)
                            <span class="badge badge-blue" style="font-size:10px;margin-right:2px;">
                                {{ $aff->matiere->nom }} — {{ $aff->classe->nom }}
                            </span>
                        @endforeach
                        @if($ens->affectations->count() > 3)
                            <span class="text-xs text-gray-400">+{{ $ens->affectations->count() - 3 }} autres</span>
                        @endif
                    </td>
                    <td>{{ $ens->nb_affectations }}</td>
                    <td>
                        <span class="{{ $ens->nb_td > 0 ? 'badge badge-green' : 'badge badge-gray' }}">{{ $ens->nb_td }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-gray-400 py-6">Aucun enseignant.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection