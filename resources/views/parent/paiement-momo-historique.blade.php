@extends('layouts.parent')
@section('title', 'Historique Mobile Money')

@section('content')
<div class="space-y-6">

    <div class="rounded-3xl bg-white shadow-sm ring-1 ring-gray-200">
        <div class="border-b border-gray-200 px-5 py-4 sm:px-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="min-w-0">
                    <h3 class="text-xl font-bold text-gray-900">Historique de mes paiements Mobile Money</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Consultez tous vos paiements effectués par Mobile Money.
                    </p>
                </div>

                <div class="shrink-0">
                    <a href="{{ route('parent.paiement-momo.create') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-blue-500/20 transition duration-200 hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 whitespace-nowrap">
                        <i data-lucide="smartphone" class="h-4 w-4"></i>
                        Nouveau paiement
                    </a>
                </div>
            </div>
        </div>

        <div class="px-5 py-6 sm:px-6">
            @if($paiements->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-6 py-16 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                        <i data-lucide="smartphone" class="h-8 w-8"></i>
                    </div>
                    <p class="text-base font-semibold text-gray-700">Aucun paiement Mobile Money effectué pour le moment.</p>
                    <p class="mt-1 text-sm text-gray-500">Cliquez sur “Nouveau paiement” pour ajouter un paiement.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Élève</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Opérateur</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Rubrique</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Montant</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Référence</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($paiements as $p)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                        {{ $p->scolarite->eleve->nomComplet() }}
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        @if($p->operateur === 'mtn_momo')
                                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800 ring-1 ring-inset ring-yellow-200">
                                                MTN MoMo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-800 ring-1 ring-inset ring-orange-200">
                                                Orange Money
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        {{ ucfirst(str_replace(['tranche','inscription','transport'], ['Tranche ','Inscription','Transport'], $p->type_paiement)) }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-semibold text-gray-900">
                                        {{ number_format($p->montant, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <code class="rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                            {{ $p->reference_transaction }}
                                        </code>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        {{ $p->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        @if($p->statut === 'confirme')
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-200">
                                                Confirmé
                                            </span>
                                        @elseif($p->statut === 'en_attente')
                                            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-200">
                                                En attente
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-200">
                                                {{ ucfirst($p->statut) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $paiements->links() }}
                </div>
            @endif
        </div>
    </div>

    <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-900">
        <div class="flex items-start gap-3">
            <i data-lucide="info" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <p>
                Un paiement passe au statut <strong>en attente</strong> juste après son enregistrement, puis il devient
                <strong>confirmé</strong> après validation.
            </p>
        </div>
    </div>

</div>
@endsection