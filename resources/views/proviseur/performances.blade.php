@extends('layouts.proviseur')
@section('title', 'Performances')

@section('content')
<div class="card mb-4">
    <form method="GET" class="flex gap-3 items-end">
        <div class="form-group" style="margin-bottom:0;flex:1;max-width:300px;">
            <label class="form-label">Séquence</label>
            <select name="sequence_id" class="form-select" onchange="this.form.submit()">
                @foreach ($sequences as $seq)
                    <option value="{{ $seq->id }}" {{ $selectedSeq?->id == $seq->id ? 'selected' : '' }}>
                        {{ $seq->nom }} — {{ $seq->trimestre->nom }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="card">
    <h4 class="font-semibold text-gray-800 mb-4">
        Taux de réussite par classe — {{ $selectedSeq?->nom }}
    </h4>

    @if(empty($resultats))
        <p class="text-gray-400 text-center py-6">Aucune donnée disponible.</p>
    @else
    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Classe</th>
                    <th>Section</th>
                    <th>Effectif</th>
                    <th>≥ 10/20</th>
                    <th>Taux réussite</th>
                    <th>Moy. classe</th>
                    <th>Moy. max</th>
                    <th>Moy. min</th>
                    <th style="width:150px;">Visualisation</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultats as $r)
                <tr>
                    <td class="font-medium">{{ $r['classe'] }}</td>
                    <td><span class="badge-blue">{{ $r['section'] }}</span></td>
                    <td>{{ $r['effectif'] }}</td>
                    <td style="color:#1a7a1a;font-weight:bold;">{{ $r['reussis'] }}</td>
                    <td>
                        <span style="font-weight:bold;color:{{ $r['taux'] >= 70 ? '#1a7a1a' : ($r['taux'] >= 50 ? '#e67e22' : '#c0392b') }};">
                            {{ $r['taux'] }}%
                        </span>
                    </td>
                    <td class="font-medium">{{ $r['moy_classe'] ?? '-' }}</td>
                    <td style="color:#1a7a1a;">{{ $r['moy_max'] ?? '-' }}</td>
                    <td style="color:#c0392b;">{{ $r['moy_min'] ?? '-' }}</td>
                    <td>
                        <div class="progress-track" style="height:10px;">
                            <div class="progress-bar" style="width:{{ $r['taux'] }}%;background:{{ $r['taux'] >= 70 ? '#1a7a1a' : ($r['taux'] >= 50 ? '#e67e22' : '#c0392b') }};"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection