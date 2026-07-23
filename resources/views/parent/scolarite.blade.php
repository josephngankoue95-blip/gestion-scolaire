@extends('layouts.parent')
@section('title', 'Situation scolarité')

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
    <h3 class="font-semibold text-gray-800 mb-4">
        Situation scolarité — {{ $enfant->nomComplet() }}
        @if($scolarite) <span class="badge-blue">{{ $scolarite->classe->nom }}</span> @endif
    </h3>

    @if(!$scolarite)
    <p class="text-gray-400 text-center py-8">Aucune inscription trouvée pour l'année active.</p>
    @else

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="stat-card">
            <p class="stat-label">Total dû</p>
            <p class="stat-value" style="color:#c0392b;font-size:16px;">
                {{ number_format($scolarite->totalDu(), 0, ',', ' ') }} FCFA
            </p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Total payé</p>
            <p class="stat-value" style="color:#1a7a1a;font-size:16px;">
                {{ number_format($scolarite->totalPaye(), 0, ',', ' ') }} FCFA
            </p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Solde restant</p>
            <p class="stat-value" style="color:{{ $scolarite->solde() > 0 ? '#c0392b' : '#1a7a1a' }};font-size:16px;">
                {{ number_format($scolarite->solde(), 0, ',', ' ') }} FCFA
            </p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Rubrique</th><th>Dû</th><th>Payé</th><th>Solde</th><th>Statut</th></tr></thead>
            <tbody>
                @php
                    $rubriques = [
                        ['label' => 'Inscription', 'du' => $scolarite->frais_inscription, 'paye' => $scolarite->paye_inscription, 'type' => 'inscription'],
                        ['label' => 'Tranche 1', 'du' => $scolarite->montant_tranche1, 'paye' => $scolarite->paye_tranche1, 'type' => 'tranche1'],
                        ['label' => 'Tranche 2', 'du' => $scolarite->montant_tranche2, 'paye' => $scolarite->paye_tranche2, 'type' => 'tranche2'],
                        ['label' => 'Tranche 3', 'du' => $scolarite->montant_tranche3, 'paye' => $scolarite->paye_tranche3, 'type' => 'tranche3'],
                        ['label' => 'Transport', 'du' => $scolarite->montant_transport, 'paye' => $scolarite->paye_transport, 'type' => 'transport'],
                    ];
                @endphp
                @foreach ($rubriques as $r)
                @if($r['du'] > 0)
                @php $reste = $r['du'] - $r['paye']; $s = $scolarite->statutTranche($r['type']); @endphp
                <tr>
                    <td class="font-medium">{{ $r['label'] }}</td>
                    <td>{{ number_format($r['du'], 0, ',', ' ') }}</td>
                    <td style="color:#1a7a1a;font-weight:bold;">{{ number_format($r['paye'], 0, ',', ' ') }}</td>
                    <td style="color:{{ $reste > 0 ? '#c0392b' : '#1a7a1a' }};font-weight:bold;">
                        {{ number_format($reste, 0, ',', ' ') }}
                    </td>
                    <td>
                        @if($s === 'paye') <span class="badge badge-green">Payé</span>
                        @elseif($s === 'partiel') <span class="badge badge-amber">Partiel</span>
                        @else <span class="badge-red">Non payé</span>
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>

    @if($scolarite->zoneTransport)
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;margin-top:12px;">
        <p class="text-sm"><strong>Transport :</strong> {{ $scolarite->zoneTransport->nom }}</p>
    </div>
    @endif

    @endif
</div>
@endsection