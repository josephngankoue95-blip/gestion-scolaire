@extends('layouts.secretaire')
@section('title', 'Dossier scolarité')

@section('content')
<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 flex flex-col gap-4">

        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-semibold text-gray-800 text-lg">{{ $scolarite->eleve->nomComplet() }}</h3>
                    <p class="text-sm text-gray-500">{{ $scolarite->classe->nom }} — {{ $scolarite->anneeScolaire->libelle }}</p>
                </div>
                <span class="{{ $scolarite->solde() <= 0 ? 'badge-green' : 'badge-red' }}">
                    {{ $scolarite->solde() <= 0 ? 'Soldé' : 'Redevable' }}
                </span>
            </div>

            <div class="table-wrapper">
                <table class="table-base">
                    <thead><tr><th>Rubrique</th><th>Dû</th><th>Payé</th><th>Solde</th><th>Statut</th></tr></thead>
                    <tbody>
                    @php
                        $rubriques = [
                            ['label' => 'Inscription','du' => $scolarite->frais_inscription,'paye' => $scolarite->paye_inscription,'type' => 'inscription'],
                            ['label' => 'Tranche 1','du' => $scolarite->montant_tranche1,'paye' => $scolarite->paye_tranche1,'type' => 'tranche1'],
                            ['label' => 'Tranche 2','du' => $scolarite->montant_tranche2,'paye' => $scolarite->paye_tranche2,'type' => 'tranche2'],
                            ['label' => 'Tranche 3','du' => $scolarite->montant_tranche3,'paye' => $scolarite->paye_tranche3,'type' => 'tranche3'],
                            ['label' => 'Transport','du' => $scolarite->montant_transport,'paye' => $scolarite->paye_transport,'type' => 'transport'],
                        ];
                    @endphp
                    @foreach ($rubriques as $r)
                    @if($r['du'] > 0)
                    @php $reste = $r['du']-$r['paye']; $s = $scolarite->statutTranche($r['type']); @endphp
                    <tr>
                        <td class="font-medium">{{ $r['label'] }}</td>
                        <td>{{ number_format($r['du'],0,',',' ') }}</td>
                        <td style="color:#1a7a1a;font-weight:bold;">{{ number_format($r['paye'],0,',',' ') }}</td>
                        <td style="color:{{ $reste>0?'#c0392b':'#1a7a1a' }};font-weight:bold;">{{ number_format($reste,0,',',' ') }}</td>
                        <td>
                            @if($s==='paye')<span class="badge-green">Payé</span>
                            @elseif($s==='partiel')<span class="badge-amber">Partiel</span>
                            @else<span class="badge-red">Non payé</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#1a3a6b;color:#fff;">
                            <td class="font-bold">TOTAL</td>
                            <td>{{ number_format($scolarite->totalDu(),0,',',' ') }}</td>
                            <td>{{ number_format($scolarite->totalPaye(),0,',',' ') }}</td>
                            <td>{{ number_format($scolarite->solde(),0,',',' ') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card">
            <h4 class="font-semibold text-gray-800 mb-3">Historique des paiements</h4>
            @forelse ($scolarite->paiements->sortByDesc('date_paiement') as $p)
            <div class="flex justify-between items-center py-2 border-t text-sm">
                <div>
                    <span class="font-medium">{{ ucfirst(str_replace(['tranche','inscription','transport'],['Tranche ','Inscription','Transport'],$p->type)) }}</span>
                    <span class="text-gray-400 ml-2">{{ $p->date_paiement->format('d/m/Y') }}</span>
                    <span class="badge-blue ml-1" style="font-size:10px;">{{ $p->numero_recu }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-bold" style="color:#1a7a1a;">{{ number_format($p->montant,0,',',' ') }} FCFA</span>
                    <a href="{{ route('admin.scolarite.paiements.recu', $p) }}" target="_blank" class="login-link text-xs">Reçu</a>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm text-center py-4">Aucun paiement.</p>
            @endforelse
        </div>
    </div>

    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-4">Enregistrer un paiement</h4>
        <form method="POST" action="{{ route('secretaire.scolarite.paiement', $scolarite) }}" class="space-y-3">
            @csrf
            <div class="form-group">
                <label class="form-label">Rubrique *</label>
                <select name="type" required class="form-select">
                    @if($scolarite->frais_inscription > $scolarite->paye_inscription)
                        <option value="inscription">Inscription ({{ number_format($scolarite->frais_inscription-$scolarite->paye_inscription,0,',',' ') }} restant)</option>
                    @endif
                    @foreach (['tranche1','tranche2','tranche3'] as $t)
                    @if($scolarite->{"montant_{$t}"} > $scolarite->{"paye_{$t}"})
                        <option value="{{ $t }}">{{ ucfirst(str_replace('tranche','Tranche ',$t)) }} ({{ number_format($scolarite->{"montant_{$t}"}-$scolarite->{"paye_{$t}"},0,',',' ') }} restant)</option>
                    @endif
                    @endforeach
                    @if($scolarite->montant_transport > $scolarite->paye_transport)
                        <option value="transport">Transport ({{ number_format($scolarite->montant_transport-$scolarite->paye_transport,0,',',' ') }} restant)</option>
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Montant (FCFA) *</label>
                <input type="number" name="montant" required min="1" class="form-input">
                @error('montant') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Date *</label>
                <input type="date" name="date_paiement" required class="form-input" value="{{ date('Y-m-d') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Note</label>
                <textarea name="note" rows="2" class="form-textarea"></textarea>
            </div>
            <button type="submit" class="btn-success w-full">
                <i data-lucide="check" class="w-4 h-4"></i> Enregistrer
            </button>
        </form>
    </div>
</div>
@endsection