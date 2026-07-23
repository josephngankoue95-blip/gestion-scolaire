@extends('layouts.parent')
@section('title', 'Absences')

@section('content')
@if($enfants->count() > 1)
<div class="card mb-4">
    <form method="GET" class="flex gap-3">
        <select name="eleve_id" class="form-select" style="max-width:250px;" onchange="this.form.submit()">
            @foreach ($enfants as $e)
                <option value="{{ $e->id }}" {{ $enfant->id == $e->id ? 'selected' : '' }}>{{ $e->nomComplet() }}</option>
            @endforeach
        </select>
    </form>
</div>
@endif

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Absences — {{ $enfant->nomComplet() }}</h3>

    @php
        $totalAbsences = $absences->total();
        $nonJustifiees = $absences->getCollection()->where('justifiee', false)->count();
    @endphp

    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="stat-card">
            <p class="stat-label">Total absences</p>
            <p class="stat-value">{{ $totalAbsences }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label" style="color:#c0392b;">Non justifiées</p>
            <p class="stat-value" style="color:#c0392b;">{{ $nonJustifiees }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label" style="color:#1a7a1a;">Justifiées</p>
            <p class="stat-value" style="color:#1a7a1a;">{{ $totalAbsences - $nonJustifiees }}</p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Date</th><th>Type</th><th>Motif</th><th>Statut</th></tr></thead>
            <tbody>
                @forelse ($absences as $absence)
                <tr>
                    <td>{{ $absence->date_absence->format('d/m/Y') }}</td>
                    <td>{{ $absence->type === 'absence' ? 'Absence' : 'Retard' }}</td>
                    <td>{{ $absence->motif ?? '-' }}</td>
                    <td>
                        @if($absence->justifiee)
                            <span class="badge-green">Justifiée</span>
                        @else
                            <span class="badge-red">Non justifiée</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-gray-400 py-6">Aucune absence enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $absences->links() }}</div>
</div>
@endsection