@extends('layouts.secretaire')
@section('title', 'Requêtes élèves')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">Requêtes des élèves</h3>
        <form method="GET" class="flex gap-2">
            <select name="statut" class="form-select" onchange="this.form.submit()" style="max-width:160px;">
                <option value="">Tous</option>
                <option value="en_attente" {{ request('statut')==='en_attente'?'selected':'' }}>En attente</option>
                <option value="en_cours" {{ request('statut')==='en_cours'?'selected':'' }}>En cours</option>
                <option value="traitee" {{ request('statut')==='traitee'?'selected':'' }}>Traitée</option>
                <option value="rejetee" {{ request('statut')==='rejetee'?'selected':'' }}>Rejetée</option>
            </select>
        </form>
    </div>

    @forelse ($requetes as $r)
    <div style="border:1px solid #e5e7eb;border-radius:8px;padding:14px;margin-bottom:10px;">
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="font-medium text-gray-800">{{ $r->objet }}</p>
                <p class="text-xs text-gray-500">
                    {{ $r->eleve->nomComplet() }} — {{ $r->created_at->format('d/m/Y H:i') }}
                    <span class="badge-blue ml-1" style="font-size:10px;">{{ str_replace('_',' ',$r->type) }}</span>
                </p>
            </div>
            <span class="{{ $r->badgeClass() }}">{{ ucfirst(str_replace('_',' ',$r->statut)) }}</span>
        </div>
        <p class="text-sm text-gray-600 mb-3">{{ $r->message }}</p>

        @if($r->statut === 'en_attente' || $r->statut === 'en_cours')
        <form method="POST" action="{{ route('secretaire.requetes.traiter', $r) }}" class="flex gap-2 items-end">
            @csrf
            <div class="form-group" style="margin-bottom:0;flex:1;">
                <select name="statut" class="form-select" style="padding:6px 8px;font-size:12px;">
                    <option value="en_cours">En cours de traitement</option>
                    <option value="traitee">Marquer comme traitée</option>
                    <option value="rejetee">Rejeter</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;flex:2;">
                <input type="text" name="reponse" placeholder="Réponse..." class="form-input" style="padding:6px 8px;font-size:12px;">
            </div>
            <button type="submit" class="btn-primary" style="padding:6px 14px;font-size:12px;">Envoyer</button>
        </form>
        @else
        <div style="background:#f0fdf4;border-left:3px solid #1a7a1a;padding:8px 10px;font-size:12px;">
            <strong>Réponse :</strong> {{ $r->reponse }}
        </div>
        @endif
    </div>
    @empty
    <p class="text-gray-400 text-center py-6">Aucune requête.</p>
    @endforelse

    <div class="mt-4">{{ $requetes->links() }}</div>
</div>
@endsection