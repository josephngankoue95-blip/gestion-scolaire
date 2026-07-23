@extends('layouts.admin')
@section('title', 'Années scolaires')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">Années scolaires</h3>
        <a href="{{ route('admin.annees-scolaires.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Nouvelle année
        </a>
    </div>

    @forelse ($annees as $annee)
    <div class="flex justify-between items-center py-3 border-t">
        <div>
            <p class="font-medium text-gray-800">{{ $annee->libelle }}</p>
            <p class="text-xs text-gray-500">
                {{ $annee->date_debut?->format('d/m/Y') }} → {{ $annee->date_fin?->format('d/m/Y') }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            @if ($annee->active)
                <span class="badge badge-green">Active</span>
            @endif

            @if ($annee->initialisee)
                <span class="badge badge-blue">Initialisée</span>
            @else
                <span class="badge badge-gray">Non initialisée</span>
            @endif

            @if (!$annee->active)
                <form method="POST" action="{{ route('admin.annees-scolaires.activer', $annee) }}">
                    @csrf
                    <button type="submit" class="btn-outline" style="padding:5px 12px;font-size:12px;"
                            onclick="return confirm('Activer {{ $annee->libelle }} ?\n\n{{ $annee->initialisee ? 'Cette année est déjà initialisée : ses données seront restaurées telles quelles, rien ne sera modifié.' : 'Première activation : les classes, matières, frais, zones et élèves seront copiés depuis la dernière année initialisée.' }}')">
                        <i data-lucide="play" class="w-3 h-3"></i> Activer
                    </button>
                </form>

                @if (!$annee->initialisee)
                <form method="POST" action="{{ route('admin.annees-scolaires.destroy', $annee) }}"
                      onsubmit="return confirm('Supprimer cette année ?')">
                    @csrf @method('DELETE')
                    <button class="text-red-500"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                </form>
                @endif
            @endif
        </div>
    </div>
    @empty
    <p class="text-gray-400 py-6 text-center">Aucune année scolaire.</p>
    @endforelse
</div>
@endsection