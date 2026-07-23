@extends('layouts.admin')
@section('title', 'Zones de transport')

@section('content')
<div class="grid grid-cols-2 gap-6">

    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Ajouter une zone — {{ $annee?->libelle }}</h3>
        <form method="POST" action="{{ route('admin.scolarite.transport.store') }}" class="space-y-4">
            @csrf
            <div class="form-group">
                <label class="form-label">Nom de la zone *</label>
                <input type="text" name="nom" required class="form-input" placeholder="ex: Zone Centre">
            </div>
            <div class="form-group">
                <label class="form-label">Quartiers couverts</label>
                <textarea name="quartiers" rows="3" class="form-textarea" placeholder="Akwa, Bonanjo, Bonaberi..."></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Montant annuel (FCFA) *</label>
                <input type="number" name="montant" required min="0" step="500" class="form-input">
            </div>
            <div class="form-group">
                <label class="login-checkbox-label">
                    <input type="checkbox" name="actif" value="1" checked class="form-checkbox">
                    Zone active
                </label>
            </div>
            <button type="submit" class="btn-primary w-full">Créer la zone</button>
        </form>
    </div>

    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Zones existantes</h3>
        @forelse ($zones as $zone)
        <div style="border:1px solid #e5e7eb;border-radius:8px;padding:10px 12px;margin-bottom:8px;">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="font-medium">{{ $zone->nom }}</p>
                    @if($zone->quartiers)
                        <p class="text-xs text-gray-500 mt-1">{{ $zone->quartiers }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-{{ $zone->actif ? 'green' : 'gray' }}">
                        {{ $zone->actif ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.scolarite.transport.update', $zone) }}" class="flex gap-2 items-end">
                @csrf @method('PUT')
                <input type="hidden" name="nom" value="{{ $zone->nom }}">
                <input type="hidden" name="quartiers" value="{{ $zone->quartiers }}">
                <div class="form-group" style="margin-bottom:0;flex:1;">
                    <label class="form-label" style="font-size:11px;">Montant (FCFA)</label>
                    <input type="number" name="montant" value="{{ $zone->montant }}" class="form-input" style="padding:4px 8px;">
                </div>
                <button type="submit" class="btn-primary" style="padding:6px 10px;font-size:12px;">
                    <i data-lucide="save" class="w-3 h-3"></i>
                </button>
                <form method="POST" action="{{ route('admin.scolarite.transport.destroy', $zone) }}" onsubmit="return confirm('Supprimer ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger" style="padding:6px 10px;font-size:12px;">
                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                    </button>
                </form>
            </form>
        </div>
        @empty
            <p class="text-gray-400 text-center py-6">Aucune zone définie.</p>
        @endforelse
    </div>
</div>
@endsection