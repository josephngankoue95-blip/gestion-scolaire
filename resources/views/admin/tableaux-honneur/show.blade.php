@extends('layouts.admin')
@section('title', 'Tableau d\'honneur — ' . $classe->nom)

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold text-gray-800">
                Tableau d'honneur — {{ $classe->nom }}
            </h3>
            <p class="text-sm text-gray-500">
                {{ $type === 'annuel' ? 'Annuel' : ($periode->nom ?? '') }}
                · Seuil : {{ $seuil }}/20
                · {{ count($resultats) }} élève(s) retenu(s)
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.tableaux-honneur.index') }}" class="btn-back">← Retour</a>
            <a href="{{ route('admin.tableaux-honneur.imprimer', request()->all()) }}"
               target="_blank" class="btn-primary">
                <i data-lucide="printer" class="w-4 h-4"></i> Imprimer
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 text-red-700 text-sm">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4 p-3 rounded bg-gray-50 text-sm text-gray-600">
        @if($type === 'sequentiel')
            Séquence : {{ $periode->nom ?? '-' }}
        @elseif($type === 'trimestriel')
            Trimestre : {{ $periode->nom ?? '-' }}
        @else
            Moyenne annuelle
        @endif
    </div>

    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr>
                    <th style="width:40px;">Rang</th>
                    <th>Nom complet</th>
                    <th>Matricule</th>
                    <th>Sexe</th>
                    <th>Moyenne</th>
                    <th>Mention</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($resultats as $i => $r)
                    <tr>
                        <td class="text-center font-bold text-primary-700">{{ $i + 1 }}</td>
                        <td class="font-medium">{{ $r['eleve']->nomComplet() }}</td>
                        <td>{{ $r['eleve']->matricule }}</td>
                        <td>{{ $r['eleve']->sexe === 'M' ? 'M' : 'F' }}</td>
                        <td>{{ number_format($r['bulletin']['moyenne_generale'] ?? 0, 2) }}/20</td>
                        <td>{{ $r['bulletin']['mention'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-400 py-8">
                            Aucun élève n'a atteint le seuil de {{ $seuil }}/20.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection