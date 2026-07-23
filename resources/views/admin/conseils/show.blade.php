@extends('layouts.admin')
@section('title', 'Conseil — ' . $conseil->classe->nom)

@section('content')
<div class="card mb-4">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="font-semibold text-gray-800">Conseil de classe — {{ $conseil->classe->nom }}</h3>
            <p class="text-sm text-gray-500">
                {{ $conseil->trimestre?->nom ?? 'Ponctuel' }} · {{ $conseil->date_conseil->format('d/m/Y') }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.conseils.pv', $conseil) }}" target="_blank" class="btn-outline">
                <i data-lucide="printer" class="w-4 h-4"></i> PV du conseil
            </a>
            @if($conseil->statut !== 'cloture')
            <form method="POST" action="{{ route('admin.conseils.cloturer', $conseil) }}">
                @csrf
                <button class="btn-success">Clôturer</button>
            </form>
            @endif
        </div>
    </div>
</div>

<div class="card">
    <h4 class="font-semibold text-gray-800 mb-4">Décisions par élève</h4>
    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr><th>Élève</th><th>Moyenne</th><th>Décision</th><th>Motif</th><th class="text-right">Action</th></tr>
            </thead>
            <tbody>
                @foreach ($eleves as $eleve)
                @php $decision = $conseil->decisions->firstWhere('eleve_id', $eleve->id); @endphp
                <tr>
                    <td class="font-medium">{{ $eleve->nomComplet() }}</td>
                    <td>{{ isset($moyennes[$eleve->id]) && $moyennes[$eleve->id] !== null ? number_format($moyennes[$eleve->id], 2) : '-' }}</td>
                    <td>
                        @if($decision)
                            <span class="{{ $decision->badgeClass() }}">{{ $decision->libelle() }}</span>
                        @else
                            <span class="badge badge-gray">Non décidé</span>
                        @endif
                    </td>
                    <td>{{ $decision?->motif ?? '-' }}</td>
                    <td class="text-right">
                        <button type="button" class="login-link" onclick="document.getElementById('modal-{{ $eleve->id }}').classList.remove('hidden')">
                            {{ $decision ? 'Modifier' : 'Décider' }}
                        </button>
                    </td>
                </tr>

                {{-- Modal simple --}}
                <tr id="modal-{{ $eleve->id }}" class="hidden">
                    <td colspan="5" style="background:#f8faff;">
                        <form method="POST" action="{{ route('admin.conseils.decision', $conseil) }}" class="grid grid-cols-4 gap-2 items-end p-2">
                            @csrf
                            <input type="hidden" name="eleve_id" value="{{ $eleve->id }}">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" style="font-size:11px;">Décision *</label>
                                <select name="type_decision" required class="form-select" style="padding:5px;font-size:12px;">
                                    <option value="passage" {{ $decision?->type_decision==='passage'?'selected':'' }}>Passage</option>
                                    <option value="redoublement" {{ $decision?->type_decision==='redoublement'?'selected':'' }}>Redoublement</option>
                                    <option value="felicitations" {{ $decision?->type_decision==='felicitations'?'selected':'' }}>Félicitations</option>
                                    <option value="encouragements" {{ $decision?->type_decision==='encouragements'?'selected':'' }}>Encouragements</option>
                                    <option value="tableau_honneur" {{ $decision?->type_decision==='tableau_honneur'?'selected':'' }}>Tableau d'honneur</option>
                                    <option value="avertissement" {{ $decision?->type_decision==='avertissement'?'selected':'' }}>Avertissement</option>
                                    <option value="blame" {{ $decision?->type_decision==='blame'?'selected':'' }}>Blâme</option>
                                    <option value="exclusion_temporaire" {{ $decision?->type_decision==='exclusion_temporaire'?'selected':'' }}>Exclusion temporaire</option>
                                    <option value="exclusion_definitive" {{ $decision?->type_decision==='exclusion_definitive'?'selected':'' }}>Exclusion définitive</option>
                                    <option value="autre" {{ $decision?->type_decision==='autre'?'selected':'' }}>Autre</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom:0;grid-column:span 2;">
                                <label class="form-label" style="font-size:11px;">Motif</label>
                                <input type="text" name="motif" value="{{ $decision?->motif }}" class="form-input" style="padding:5px;font-size:12px;">
                            </div>
                            <button type="submit" class="btn-primary" style="padding:6px 10px;font-size:12px;">Enregistrer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection