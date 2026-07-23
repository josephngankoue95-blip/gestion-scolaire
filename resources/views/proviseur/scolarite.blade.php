@extends('layouts.proviseur')
@section('title', 'Finances & Scolarité')

@section('content')
<div class="card mb-4">
    <form method="GET" class="flex gap-3">
        <select name="classe_id" class="form-select" style="max-width:220px;" onchange="this.form.submit()">
            <option value="">Toutes les classes</option>
            @foreach ($classes as $c)
                <option value="{{ $c->id }}" {{ request('classe_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->nom }} ({{ $c->section->code }})
                </option>
            @endforeach
        </select>
    </form>
</div>

{{-- Stats par classe --}}
<div class="card mb-6">
    <h4 class="font-semibold text-gray-800 mb-4">Recouvrement par classe — {{ $annee?->libelle }}</h4>
    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Classe</th>
                    <th>Section</th>
                    <th>Inscrits</th>
                    <th>Total dû</th>
                    <th>Total payé</th>
                    <th>Solde</th>
                    <th style="width:180px;">Taux recouvrement</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($statsClasses as $s)
                <tr>
                    <td class="font-medium">{{ $s['classe'] }}</td>
                    <td><span class="badge badge-blue">{{ $s['section'] }}</span></td>
                    <td>{{ $s['nb_inscrits'] }}</td>
                    <td>{{ number_format($s['total_du'], 0, ',', ' ') }}</td>
                    <td style="color:#1a7a1a;font-weight:bold;">{{ number_format($s['total_paye'], 0, ',', ' ') }}</td>
                    <td style="color:{{ $s['solde'] > 0 ? '#c0392b' : '#1a7a1a' }};font-weight:bold;">
                        {{ number_format($s['solde'], 0, ',', ' ') }}
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="progress-track" style="flex:1;height:8px;">
                                <div class="progress-bar" style="width:{{ $s['taux'] }}%;background:{{ $s['taux'] >= 70 ? '#1a7a1a' : ($s['taux'] >= 40 ? '#e67e22' : '#c0392b') }};"></div>
                            </div>
                            <span style="font-size:12px;font-weight:bold;min-width:36px;">{{ $s['taux'] }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-gray-400 py-6">Aucune donnée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Détail dossiers --}}
<div class="card">
    <h4 class="font-semibold text-gray-800 mb-4">Dossiers élèves</h4>
    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Élève</th>
                    <th>Classe</th>
                    <th>Total dû</th>
                    <th>Payé</th>
                    <th>Solde</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scolarites as $sc)
                <tr>
                    <td class="font-medium">{{ $sc->eleve->nomComplet() }}</td>
                    <td>{{ $sc->classe->nom }}</td>
                    <td>{{ number_format($sc->totalDu(), 0, ',', ' ') }}</td>
                    <td style="color:#1a7a1a;">{{ number_format($sc->totalPaye(), 0, ',', ' ') }}</td>
                    <td style="color:{{ $sc->solde() > 0 ? '#c0392b' : '#1a7a1a' }};font-weight:bold;">
                        {{ number_format($sc->solde(), 0, ',', ' ') }}
                    </td>
                    <td>
                        <span class="{{ $sc->solde() <= 0 ? 'badge badge-green' : 'badge badge-red' }}">
                            {{ $sc->solde() <= 0 ? 'Soldé' : 'Dette' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray-400 py-6">Aucun dossier.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $scolarites->links() }}</div>
</div>
@endsection