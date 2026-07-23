@extends('layouts.admin')
@section('title', 'Tableau de bord — Surveillance générale')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="card">
        <p class="text-sm text-gray-500">Absences aujourd'hui</p>
        <p class="text-2xl font-bold text-red-600 mt-1">{{ $absAujourdhui->count() }}</p>
    </div>
    <div class="card">
        <p class="text-sm text-gray-500">Non justifiées (total)</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $absNonJust }}</p>
    </div>
</div>

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Absences du jour</h3>
    <div class="space-y-2">
        @forelse ($absAujourdhui as $absence)
        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <div>
                <span class="font-medium">{{ $absence->eleve->nomComplet() }}</span>
                <span class="text-sm text-gray-500"> — {{ $absence->classe->nom }}</span>
            </div>
            <span class="px-2 py-1 text-xs rounded-full {{ $absence->justifiee ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $absence->justifiee ? 'Justifiée' : 'Non justifiée' }}
            </span>
        </div>
        @empty
        <p class="text-gray-400 text-center py-6">Aucune absence signalée aujourd'hui.</p>
        @endforelse
    </div>
</div>
@endsection