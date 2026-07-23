@extends('layouts.admin')
@section('title', 'Groupes de matières')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">Groupes de matières</h3>
        <a href="{{ route('admin.groupes-matieres.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Nouveau groupe
        </a>
    </div>

    <div class="grid grid-cols-3 gap-4">
        @forelse ($groupes as $groupe)
        <a href="{{ route('admin.groupes-matieres.show', $groupe) }}" class="card-hover">
            <div class="flex justify-between items-start mb-2">
                <p class="font-medium text-gray-800">{{ $groupe->nom }}</p>
                <span class="badge-blue">{{ $groupe->code }}</span>
            </div>
            <p class="text-xs text-gray-500">{{ $groupe->section->nom }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $groupe->matieres->count() }} matière(s)</p>
        </a>
        @empty
        <p class="text-gray-400 col-span-3 py-6 text-center">Aucun groupe créé.</p>
        @endforelse
    </div>
</div>
@endsection