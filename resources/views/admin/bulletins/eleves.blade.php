@extends('layouts.admin')
@section('title', 'Bulletins — ' . $classe->nom)

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold text-gray-800">{{ $classe->nom }}</h3>
            <p class="text-sm text-gray-500">
                Bulletin
                @if($type === 'sequentiel') séquentiel
                @elseif($type === 'trimestriel') trimestriel
                @else annuel
                @endif
                · {{ $eleves->count() }} élève(s)
            </p>
        </div>
        <a href="{{ route('admin.bulletins.index', $classe) }}" class="btn-secondary">← Changer</a>
        <a href="{{ route('admin.bulletins.imprimerTous', request()->only(['classe_id','type_bulletin','sequence_id','trimestre_id'])) }}"
            target="_blank" class="btn-primary">
            <i data-lucide="printer" class="w-4 h-4"></i> Imprimer tous les bulletins
        </a>
    </div>

    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom complet</th>
                    <th class="text-right">Bulletin</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($eleves as $eleve)
                <tr>
                    <td>{{ $eleve->matricule }}</td>
                    <td>{{ $eleve->nomComplet() }}</td>
                    <td class="text-right">
                        @if ($type === 'sequentiel' && $sequenceId)
                            <a href="{{ route('admin.bulletins.sequence', [$eleve, $sequenceId]) }}"
                               target="_blank" class="btn-primary"
                               style="padding:5px 10px;font-size:12px;">
                                <i data-lucide="printer" class="w-3 h-3"></i> Séquentiel
                            </a>
                        @elseif ($type === 'trimestriel' && $trimestreId)
                            <a href="{{ route('admin.bulletins.trimestre', [$eleve, $trimestreId]) }}"
                               target="_blank" class="btn-primary"
                               style="padding:5px 10px;font-size:12px;">
                                <i data-lucide="printer" class="w-3 h-3"></i> Trimestriel
                            </a>
                        @else
                            <a href="{{ route('admin.bulletins.annuel', $eleve) }}"
                               target="_blank" class="btn-primary"
                               style="padding:5px 10px;font-size:12px;">
                                <i data-lucide="printer" class="w-3 h-3"></i> Annuel
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-gray-400 py-6">
                        Aucun élève inscrit dans cette classe.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection