@extends('layouts.parent')
@section('title', 'Emploi du temps')

@section('content')
@if($enfants->count() > 1)
<div class="card mb-4">
    <form method="GET" class="flex gap-3">
        <select name="eleve_id" class="form-select" style="max-width:250px;" onchange="this.form.submit()">
            @foreach ($enfants as $e)
                <option value="{{ $e->id }}" {{ $enfant->id == $e->id ? 'selected' : '' }}>
                    {{ $e->nomComplet() }}
                </option>
            @endforeach
        </select>
    </form>
</div>
@endif

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">
        Emploi du temps — {{ $enfant->nomComplet() }}
        @if($scolarite) <span class="badge-blue ml-2">{{ $scolarite->classe->nom }}</span> @endif
    </h3>

    @if(!$scolarite || $creneaux->isEmpty())
        <p class="text-gray-400 text-center py-6">Aucun emploi du temps disponible.</p>
    @else
        @php
            $heures = $creneaux->flatten()->pluck('heure_debut')->merge($creneaux->flatten()->pluck('heure_fin'))->unique()->sort()->values();
            $heuresRange = $creneaux->flatten()->map(function($c) {
                return [
                    'debut' => $c->heure_debut,
                    'fin' => $c->heure_fin,
                ];
            })->unique()->sortBy('debut')->values();
        @endphp

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead style="background:#1a3a6b;color:#fff;">
                    <tr>
                        <th style="min-width:120px;">Heures</th>
                        @foreach ($jours as $jour)
                            <th style="min-width:140px;">{{ $jour }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($heuresRange as $heure)
                        <tr>
                            <th style="background:#f3f6fb;">
                                {{ substr($heure['debut'],0,5) }} - {{ substr($heure['fin'],0,5) }}
                            </th>

                            @foreach ($jours as $jour)
                                @php
                                    $cours = $creneaux->get($jour, collect())->first(function($c) use ($heure) {
                                        return $c->heure_debut === $heure['debut'] && $c->heure_fin === $heure['fin'];
                                    });
                                @endphp

                                <td style="vertical-align:middle;">
                                    @if($cours)
                                        <div style="display:flex;flex-direction:column;gap:4px;">
                                            <p class="font-medium mb-0">{{ $cours->matiere->nom }}</p>
                                            <p class="text-xs text-gray-500 mb-0">
                                                {{ $cours->enseignant->user->name }}
                                                @if($cours->salle) · Salle {{ $cours->salle }}@endif
                                            </p>
                                        </div>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection