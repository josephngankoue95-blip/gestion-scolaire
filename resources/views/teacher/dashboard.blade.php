@extends('layouts.admin')
@section('title', 'Tableau de bord')

@section('content')
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="stat-card">
        <p class="stat-label">Classes assignées</p>
        <p class="stat-value">{{ $classesCount }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Matières assignées</p>
        <p class="stat-value">{{ $matieresCount }}</p>
    </div>
</div>

<!-- <div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Mes affectations</h3>
    <div class="table-wrapper">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Classe</th>
                    <th>Section</th>
                    <th>Matière</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($affectations as $aff)
                <tr>
                    <td class="font-medium">{{ $aff->classe->nom }}</td>
                    <td>{{ $aff->classe->section->nom }}</td>
                    <td>{{ $aff->matiere->nom }}</td>
                    <td class="text-right">
                        <a href="{{ route('teacher.saisie.index') }}" class="login-link">
                            Saisir les notes →
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-gray-400 py-6">Aucune affectation.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> -->
@endsection