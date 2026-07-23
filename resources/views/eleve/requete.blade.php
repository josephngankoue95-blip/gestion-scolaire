@extends('layouts.eleve')
@section('title', 'Mes Requêtes')

@section('content')
<div class="grid grid-cols-2 gap-6">

    {{-- Formulaire --}}
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Soumettre une requête</h3>
        <form method="POST" action="{{ route('eleve.requetes.store') }}" class="space-y-4">
            @csrf
            <div class="form-group">
                <label class="form-label">Type de demande *</label>
                <select name="type" required class="form-select">
                    <option value="">-- Choisir --</option>
                    <option value="attestation">Attestation de scolarité</option>
                    <option value="certificat_scolarite">Certificat de scolarité</option>
                    <option value="bulletin">Demande de bulletin</option>
                    <option value="transfert">Demande de transfert</option>
                    <option value="autre">Autre</option>
                </select>
                @error('type') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Objet *</label>
                <input type="text" name="objet" required class="form-input" placeholder="Résumé de votre demande">
                @error('objet') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Message *</label>
                <textarea name="message" rows="5" required class="form-textarea" placeholder="Décrivez votre demande en détail..."></textarea>
                @error('message') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-primary w-full">
                <i data-lucide="send" class="w-4 h-4"></i> Soumettre la requête
            </button>
        </form>
    </div>

    {{-- Historique --}}
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Mes requêtes</h3>
        @forelse ($requetes as $r)
        <div style="border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:8px;">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="font-medium text-gray-800">{{ $r->objet }}</p>
                    <p class="text-xs text-gray-400">{{ $r->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <span class="{{ $r->badgeClass() }}">{{ ucfirst(str_replace('_',' ',$r->statut)) }}</span>
            </div>
            @if($r->reponse)
            <div style="background:#f0fdf4;border-left:3px solid #1a7a1a;padding:8px 10px;border-radius:0 6px 6px 0;margin-top:8px;">
                <p style="font-size:12px;color:#1a7a1a;font-weight:bold;">Réponse :</p>
                <p style="font-size:12px;color:#374151;">{{ $r->reponse }}</p>
                @if($r->traitee_le)
                    <p style="font-size:10px;color:#9ca3af;margin-top:4px;">
                        Par {{ $r->traitePar?->name }} le {{ $r->traitee_le->format('d/m/Y') }}
                    </p>
                @endif
            </div>
            @endif
        </div>
        @empty
        <p class="text-gray-400 text-center py-6">Aucune requête soumise.</p>
        @endforelse
        <div class="mt-3">{{ $requetes->links() }}</div>
    </div>
</div>
@endsection