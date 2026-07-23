@extends('layouts.prefet')
@section('title', 'Contrôle de saisie')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold text-gray-800">{{ $classe->nom }} — {{ $matiere->nom }}</h3>
            <p class="text-sm text-gray-500">
                {{ $sequence->nom }} · Enseignant : {{ $affectation?->enseignant?->user?->name ?? 'Non affecté' }}
            </p>
        </div>
        <span class="badge-blue">Consultation — lecture seule</span>
    </div>

    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Élève</th><th>Note /20</th><th>Statut</th></tr></thead>
            <tbody>
                @foreach ($eleves as $eleve)
                @php $note = $notes->get($eleve->id); @endphp
                <tr>
                    <td class="font-medium">{{ $eleve->nomComplet() }}</td>
                    <td>{{ $note?->note ?? '-' }}</td>
                    <td>
                        @if(!$note) <span class="badge-red">Non saisi</span>
                        @elseif($note->absent) <span class="badge-gray">Absent</span>
                        @else <span class="badge-green">Saisi</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection