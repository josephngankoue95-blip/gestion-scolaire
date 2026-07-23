@extends('layouts.admin')
@section('title', 'Procès verbal — ' . $classe->nom)

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold text-gray-800">{{ $classe->nom }}</h3>
            <p class="text-sm text-gray-500">
                {{ $periode?->nom ?? 'Annuel' }} · {{ count($resultats) }} élève(s)
            </p>
        </div>
        <div class="flex gap-2">
                    <a href="{{ route('admin.proces-verbaux.index') }}" class="btn-back">
            ← Retour
        </a>
            <a href="{{ route('admin.proces-verbaux.imprimer', request()->all()) }}"
               target="_blank" class="btn-primary">
                <i data-lucide="printer" class="w-4 h-4"></i> Imprimer PDF
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Rang</th>
                    <th>Matricule</th>
                    <th>Nom complet</th>
                    <th>Sexe</th>
                    <th>Moyenne</th>
                    <th>Mention</th>
                    <th>Admis</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultats as $i => $r)
                @php $moy = $r['bulletin']['moyenne_generale'] ?? null; @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="font-bold text-primary-700">{{ $rangs[$r['eleve']->id] ?? '-' }}</td>
                    <td>{{ $r['eleve']->matricule }}</td>
                    <td class="font-medium">{{ $r['eleve']->nomComplet() }}</td>
                    <td>{{ $r['eleve']->sexe }}</td>
                    <td>
                        @if($moy !== null)
                            <span class="font-bold" style="color:{{ $moy >= 10 ? '#1a7a1a' : '#c0392b' }};">
                                {{ number_format($moy, 2) }}
                            </span>
                        @else — @endif
                    </td>
                    <td>{{ $r['bulletin']['mention'] ?? '-' }}</td>
                    <td>
                        @if($moy >= 10)
                            <span class="badge badge-green">OUI</span>
                        @else
                            <span class="badge badge-red">NON</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection