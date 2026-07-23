@extends('layouts.parent')
@section('title', 'Tableau de bord')

@section('content')
<div class="card mb-6">
    <h3 class="font-semibold text-gray-800 mb-1">Bonjour, {{ auth()->user()->name }}</h3>
    <p class="text-sm text-gray-500">Année scolaire : <strong>{{ $annee?->libelle }}</strong></p>
</div>

<div class="grid grid-cols-2 gap-6">
    @foreach ($data as $item)
    @php $eleve = $item['eleve']; $sc = $item['scolarite']; @endphp
    <div class="card">
        <div class="flex items-center gap-4 mb-4">
            @if($eleve->photo)
                <img src="{{ asset('storage/'.$eleve->photo) }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;">
            @else
                <div style="width:52px;height:52px;border-radius:50%;background:#e8edf5;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#1a3a6b;font-size:18px;">
                    {{ strtoupper(substr($eleve->prenom,0,1)) }}
                </div>
            @endif
            <div>
                <p class="font-bold text-gray-800">{{ $eleve->nomComplet() }}</p>
                <p class="text-xs text-gray-500">{{ $eleve->matricule }}</p>
                @if($sc)
                    <span class="badge-blue" style="font-size:10px;">{{ $sc->classe->nom }}</span>
                @else
                    <span class="badge-gray" style="font-size:10px;">Non inscrit</span>
                @endif
            </div>
        </div>

        @if($sc)
        <div class="space-y-1 text-sm">
            <div class="flex justify-between py-1 border-t">
                <span class="text-gray-500">Section</span>
                <span>{{ $sc->classe->section->nom }}</span>
            </div>
            <div class="flex justify-between py-1 border-t">
                <span class="text-gray-500">Solde scolarité</span>
                <span style="color:{{ $sc->solde() > 0 ? '#c0392b' : '#1a7a1a' }};font-weight:bold;">
                    {{ number_format($sc->solde(), 0, ',', ' ') }} FCFA
                </span>
            </div>
        </div>
        @endif

        <div class="flex gap-2 mt-4">
            <a href="{{ route('parent.bulletins', ['eleve_id' => $eleve->id]) }}" class="btn-primary" style="flex:1;font-size:12px;padding:6px;">
                <i data-lucide="file-text" class="w-4 h-4"></i> Bulletins
            </a>
            <a href="{{ route('parent.emploi-du-temps', ['eleve_id' => $eleve->id]) }}" class="btn-outline" style="flex:1;font-size:12px;padding:6px;">
                <i data-lucide="calendar-clock" class="w-4 h-4"></i> Emploi
            </a>
            <a href="{{ route('parent.absences', ['eleve_id' => $eleve->id]) }}" class="btn-outline" style="flex:1;font-size:12px;padding:6px;">
                <i data-lucide="calendar-x" class="w-4 h-4"></i> Absences
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection