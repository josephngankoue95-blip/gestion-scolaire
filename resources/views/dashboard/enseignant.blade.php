@extends('layouts.admin')

@section('title', 'Tableau de bord — Enseignant')

@section('content')
<div class="container">

    <div class="topbar">
        <div class="title">Tableau de bord — Enseignant</div>
    </div>

    <div class="section">
        <div class="card" style="margin-bottom:16px;">
            <!-- <div class="label">Bienvenue</div>
            <div class="value">{{ auth()->user()->name }}</div> -->

            <div class="label" style="margin-top:8px;">Affectations actives</div>
            <div class="value">{{ $affectations->count() }}</div>
        </div>

        <div class="section-title">
            Mes affectations
        </div>

        <div class="grid-card">
            @forelse ($affectations as $aff)
                <div class="card">
                    <div class="label">Matière</div>
                    <div class="value">{{ $aff->matiere->nom }}</div>

                    <div class="label" style="margin-top:8px;">Classe</div>
                    <div class="value">{{ $aff->classe->nom }}</div>
                </div>
            @empty
                <div class="card" style="grid-column:1/-1;">
                    <div class="value" style="color:#6b7280;">
                        Aucune affectation active cette année.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection