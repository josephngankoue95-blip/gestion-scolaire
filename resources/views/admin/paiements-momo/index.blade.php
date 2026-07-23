@extends('layouts.admin')
@section('title', 'Paiements Mobile Money')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">Paiements Mobile Money</h3>
        <span class="badge-amber">{{ $enAttente }} en attente de validation</span>
    </div>

    <form method="GET" class="flex gap-3 mb-4">
        <select name="statut" class="form-select" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            <option value="en_attente" {{ request('statut')==='en_attente'?'selected':'' }}>En attente</option>
            <option value="confirme" {{ request('statut')==='confirme'?'selected':'' }}>Confirmé</option>
            <option value="echoue" {{ request('statut')==='echoue'?'selected':'' }}>Rejeté</option>
        </select>
        <select name="operateur" class="form-select" onchange="this.form.submit()">
            <option value="">Tous opérateurs</option>
            <option value="mtn_momo" {{ request('operateur')==='mtn_momo'?'selected':'' }}>MTN MoMo</option>
            <option value="orange_money" {{ request('operateur')==='orange_money'?'selected':'' }}>Orange Money</option>
        </select>
    </form>

    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr><th>Élève</th><th>Opérateur</th><th>Téléphone</th><th>Rubrique</th><th>Montant</th><th>Référence</th><th>Statut</th><th class="text-right">Action</th></tr>
            </thead>
            <tbody>
                @forelse ($paiements as $p)
                <tr>
                    <td class="font-medium">{{ $p->scolarite->eleve->nomComplet() }}</td>
                    <td>{{ $p->operateur === 'mtn_momo' ? 'MTN MoMo' : 'Orange Money' }}</td>
                    <td>{{ $p->numero_telephone }}</td>
                    <td>{{ ucfirst(str_replace(['tranche','inscription','transport'],['Tranche ','Inscription','Transport'],$p->type_paiement)) }}</td>
                    <td style="font-weight:bold;">{{ number_format($p->montant,0,',',' ') }} FCFA</td>
                    <td><code style="font-size:11px;">{{ $p->reference_transaction }}</code></td>
                    <td><span class="{{ $p->badgeClass() }}">{{ ucfirst($p->statut) }}</span></td>
                    <td class="text-right">
                        @if($p->statut === 'en_attente')
                        <form method="POST" action="{{ route('admin.paiements-momo.valider', $p) }}" class="inline" onsubmit="return confirm('Valider ce paiement ?')">
                            @csrf<button class="text-green-600 text-sm mr-2">Valider</button>
                        </form>
                        <form method="POST" action="{{ route('admin.paiements-momo.rejeter', $p) }}" class="inline" onsubmit="return confirm('Rejeter ce paiement ?')">
                            @csrf<button class="text-red-500 text-sm">Rejeter</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-gray-400 py-6">Aucun paiement Mobile Money.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $paiements->links() }}</div>
</div>
@endsection