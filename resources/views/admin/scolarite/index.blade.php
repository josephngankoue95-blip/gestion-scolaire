@extends('layouts.admin')

@section('title', 'Scolarité')

@section('content')
<div class="container">

    <!-- HEADER -->
    <div class="topbar flex items-center justify-between gap-3 flex-wrap">
        <div class="title">Scolarité — {{ $annee?->libelle }}</div>

        <div class="flex gap-2 flex-wrap justify-center">
            <a href="{{ route('admin.scolarite.create') }}" class="btn-save">
                <i data-lucide="plus" class="w-4 h-4"></i> Inscrire un élève
            </a>
            <a href="{{ route('admin.scolarite.frais.index') }}" class="btn-back">
                <i data-lucide="settings" class="w-4 h-4"></i> Grilles de frais
            </a>
            <a href="{{ route('admin.scolarite.transport.index') }}" class="btn-back">
                <i data-lucide="bus" class="w-4 h-4"></i> Transport
            </a>
        </div>
    </div>

    <!-- STATISTIQUES -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3 mb-4">
        <div class="card text-center p-4">
            <div class="label">Inscrits</div>
            <div class="value">{{ $scolarites->total() }}</div>
        </div>

        <div class="card text-center p-4">
            <div class="label">Total dû (FCFA)</div>
            <div class="value" style="color:#c0392b;">{{ number_format($totalDu, 0, ',', ' ') }}</div>
        </div>

        <div class="card text-center p-4">
            <div class="label">Total encaissé</div>
            <div class="value" style="color:#1a7a1a;">{{ number_format($totalPaye, 0, ',', ' ') }}</div>
        </div>

        <div class="card text-center p-4">
            <div class="label">Reste à percevoir</div>
            <div class="value" style="color:#e67e22;">{{ number_format($totalDu - $totalPaye, 0, ',', ' ') }}</div>
        </div>
    </div>

    <!-- FILTRES -->
    <div class="section">
        <div class="section-title">Filtres</div>

        <form method="GET" class="flex gap-2 flex-wrap justify-center">
            <select name="classe_id" class="form-select" style="max-width:180px;">
                <option value="">Toutes les classes</option>
                @foreach ($classes as $c)
                    <option value="{{ $c->id }}" {{ request('classe_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->nom }}
                    </option>
                @endforeach
            </select>

            <select name="statut" class="form-select" style="max-width:160px;">
                <option value="">Tous</option>
                <option value="solde" {{ request('statut') === 'solde' ? 'selected' : '' }}>Soldé</option>
                <option value="dette" {{ request('statut') === 'dette' ? 'selected' : '' }}>Avec dette</option>
            </select>

            <button type="submit" class="btn-save">Filtrer</button>
        </form>
    </div>

    <!-- TABLEAU -->
    <div class="section">
        <div class="section-title">Dossiers scolarité</div>

        <div class="table-wrapper">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Élève</th>
                        <th>Classe</th>
                        <th>Inscript.</th>
                        <th>T1</th>
                        <th>T2</th>
                        <th>T3</th>
                        <th>Transport</th>
                        <th>Total dû</th>
                        <th>Payé</th>
                        <th>Solde</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($scolarites as $sc)
                        @php
                            $solde = $sc->solde();
                            $badge = [
                                'paye' => 'badge-green',
                                'partiel' => 'badge-amber',
                                'non_paye' => 'badge-red',
                                'na' => 'badge-gray'
                            ];
                        @endphp
                        <tr>
                            <td class="font-medium">{{ $sc->eleve->nomComplet() }}</td>
                            <td>{{ $sc->classe->nom }}</td>
                            <td>
                                @php $s = $sc->statutTranche('inscription'); @endphp
                                <span class="{{ $badge[$s] }}">
                                    {{ $s === 'paye' ? '✓' : number_format($sc->frais_inscription - $sc->paye_inscription, 0) }}
                                </span>
                            </td>
                            @foreach (['tranche1','tranche2','tranche3'] as $t)
                                <td>
                                    @php $s = $sc->statutTranche($t); @endphp
                                    <span class="{{ $badge[$s] }}">
                                        @if($s === 'paye') ✓
                                        @elseif($s === 'na') -
                                        @else {{ number_format($sc->{"montant_{$t}"} - $sc->{"paye_{$t}"}, 0) }}
                                        @endif
                                    </span>
                                </td>
                            @endforeach
                            <td>
                                @if($sc->montant_transport > 0)
                                    @php $s = $sc->statutTranche('transport'); @endphp
                                    <span class="{{ $badge[$s] }}">{{ $s === 'paye' ? '✓' : '✗' }}</span>
                                @else
                                    <span class="badge-gray">-</span>
                                @endif
                            </td>
                            <td>{{ number_format($sc->totalDu(), 0, ',', ' ') }}</td>
                            <td style="color:#1a7a1a;font-weight:bold;">{{ number_format($sc->totalPaye(), 0, ',', ' ') }}</td>
                            <td style="color:{{ $solde > 0 ? '#c0392b' : '#1a7a1a' }};font-weight:bold;">
                                {{ number_format($solde, 0, ',', ' ') }}
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.scolarite.show', $sc) }}" class="login-link">Détail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-gray-400 py-6">Aucun dossier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $scolarites->links() }}
        </div>
    </div>
</div>
@endsection