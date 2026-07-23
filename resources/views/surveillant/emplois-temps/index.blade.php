@extends('layouts.admin')

@section('title', 'Emploi du temps')

@section('content')
<div class="container">

    <div class="topbar">
        <div class="title">Emploi du temps</div>
    </div>

    <div class="section">
        <div class="section-title">Choisir une classe</div>

        <div class="grid-card">
            @forelse ($classes as $classe)
                <a href="{{ route('surveillant.emplois-temps.show', $classe) }}" class="card">
                    <div class="label">Classe</div>
                    <div class="value">{{ $classe->nom }}</div>

                    <div class="label" style="margin-top:8px;">Section</div>
                    <div class="value">{{ $classe->section?->nom ?? '-' }}</div>
                </a>
            @empty
                <div class="card" style="grid-column:1/-1;">
                    <div class="value" style="color:#6b7280;">
                        Aucune classe disponible.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection