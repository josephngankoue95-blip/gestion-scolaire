@extends('layouts.prefet')
@section('title', 'Tableau de bord')

@section('content')

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="stat-card">
        <p class="stat-label">Classes</p>
        <p class="stat-value">{{ $totalClasses }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Séquence consultée</p>
        <p class="stat-value" style="font-size:16px;">{{ $sequenceSelectionnee?->nom ?? '-' }}</p>
    </div>
    <div class="stat-card" style="border-left:4px solid #c0392b;">
        <p class="stat-label">Saisies incomplètes</p>
        <p class="stat-value" style="color:#c0392b;">{{ $incompletes }}</p>
    </div>
</div>

<div class="card mb-4">
    <form method="GET" class="flex gap-3 items-end">
        <div class="form-group" style="margin-bottom:0;max-width:320px;flex:1;">
            <label class="form-label">Choisir une séquence à suivre</label>
            <select name="sequence_id" class="form-select" onchange="this.form.submit()">
                @foreach ($sequences as $seq)
                    <option value="{{ $seq->id }}" {{ $sequenceSelectionnee?->id == $seq->id ? 'selected' : '' }}>
                        {{ $seq->nom }} — {{ $seq->trimestre->nom }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="card">
    <h4 class="font-semibold text-gray-800 mb-4">
        Suivi des saisies de notes — {{ $sequenceSelectionnee?->nom ?? '' }}
    </h4>

    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Classe</th><th>Matière</th><th>Effectif</th>
                    <th>Notes saisies</th><th>Statut</th><th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suiviSaisies as $s)
                <tr>
                    <td class="font-medium">{{ $s['classe'] }}</td>
                    <td>{{ $s['matiere'] }}</td>
                    <td>{{ $s['effectif'] }}</td>
                    <td>{{ $s['saisies'] }}</td>
                    <td>
                        @if($s['complet'])
                            <span class="badge badge-green">Complet</span>
                        @else
                            <span class="badge badge-red">Incomplet</span>
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- Formulaire caché qui pré-remplit classe/matière/séquence puis redirige vers le formulaire de saisie --}}
                        <form method="GET" action="{{ route('prefet.saisie.form') }}" class="inline">
                            <input type="hidden" name="classe_id" value="{{ $s['classe_id'] }}">
                            <input type="hidden" name="matiere_id" value="{{ $s['matiere_id'] }}">
                            <input type="hidden" name="sequence_id" value="{{ $sequenceSelectionnee->id }}">
                            <button type="submit" class="login-link">
                                {{ $s['complet'] ? 'Contrôler' : 'Saisir' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray-400 py-6">Aucune donnée pour cette séquence.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection