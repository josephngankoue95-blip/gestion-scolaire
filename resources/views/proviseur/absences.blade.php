@extends('layouts.proviseur')
@section('title', 'Absences')

@section('content')
<div class="card mb-4">
    <form method="GET" class="flex gap-3">
        <select name="classe_id" class="form-select" style="max-width:220px;" onchange="this.form.submit()">
            <option value="">Toutes les classes</option>
            @foreach ($classes as $c)
                <option value="{{ $c->id }}" {{ request('classe_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
            @endforeach
        </select>
        <select name="mois" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">Tous les mois</option>
            @foreach (range(1,12) as $m)
                <option value="{{ $m }}" {{ request('mois') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<div class="grid grid-cols-2 gap-6 mb-6">

    {{-- Par classe --}}
    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-4">Absences par classe</h4>
        @forelse ($statsParClasse as $s)
        <div class="flex justify-between items-center py-2 border-t text-sm">
            <span class="font-medium">{{ $s->classe }}</span>
            <div class="flex gap-2">
                <span class="badge badge-gray">{{ $s->total }} total</span>
                @if($s->nj > 0)
                    <span class="badge badge-red">{{ $s->nj }} NJ</span>
                @endif
            </div>
        </div>
        @empty
        <p class="text-gray-400 text-center py-6">Aucune absence enregistrée.</p>
        @endforelse
    </div>

    {{-- Top élèves absents --}}
    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-4">Top 10 élèves les plus absents</h4>
        @forelse ($elevesAbsents as $i => $e)
        <div class="flex justify-between items-center py-2 border-t text-sm">
            <div class="flex items-center gap-2">
                <span style="width:20px;height:20px;border-radius:50%;background:#1a3a6b;color:#fff;font-size:10px;display:flex;align-items:center;justify-content:center;font-weight:bold;">
                    {{ $i + 1 }}
                </span>
                <span class="font-medium">{{ $e->nom }} {{ $e->prenom }}</span>
            </div>
            <div class="flex gap-2">
                <span class="badge badge-amber">{{ $e->total }}</span>
                @if($e->nj > 0)<span class="badge badge-red">{{ $e->nj }} NJ</span>@endif
            </div>
        </div>
        @empty
        <p class="text-gray-400 text-center py-6">Aucune donnée.</p>
        @endforelse
    </div>
</div>

<div class="card">
    <h4 class="font-semibold text-gray-800 mb-4">Détail des absences</h4>
    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr><th>Date</th><th>Élève</th><th>Classe</th><th>Type</th><th>Statut</th><th>Signalé par</th></tr>
            </thead>
            <tbody>
                @forelse ($absences as $a)
                <tr>
                    <td>{{ $a->date_absence->format('d/m/Y') }}</td>
                    <td class="font-medium">{{ $a->eleve->nomComplet() }}</td>
                    <td>{{ $a->classe->nom ?? '-' }}</td>
                    <td>{{ $a->type === 'absence' ? 'Absence' : 'Retard' }}</td>
                    <td>
                        @if($a->justifiee) <span class="badge badge-green">Justifiée</span>
                        @else <span class="badge badge-red">Non justifiée</span>
                        @endif
                    </td>
                    <td>{{ $a->signalePar->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray-400 py-6">Aucune absence.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $absences->links() }}</div>
</div>
@endsection