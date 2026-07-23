@extends('layouts.eleve')
@section('title', 'Emploi du temps')

@section('content')
<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">
        Mon emploi du temps
        @if($scolarite) <span class="badge-blue">{{ $scolarite->classe->nom }}</span> @endif
    </h3>

    @if(!$scolarite || $creneaux->isEmpty())
        <p class="text-gray-400 text-center py-8">Aucun emploi du temps disponible pour le moment.</p>
    @else
    <div class="space-y-4">
        @foreach ($jours as $jour)
        @php $cours = $creneaux->get($jour, collect())->sortBy('heure_debut'); @endphp
        <div>
            <p style="background:#1a3a6b;color:#fff;font-size:11px;font-weight:bold;padding:3px 12px;border-radius:20px;display:inline-block;text-transform:uppercase;">
                {{ $jour }}
            </p>
            @if($cours->isEmpty())
                <p class="text-sm text-gray-400 italic ml-2 mt-2">Pas de cours.</p>
            @else
            <div class="space-y-2 mt-2">
                @foreach ($cours as $c)
                <div style="display:flex;align-items:center;gap:12px;background:#f8faff;border:1px solid #dce6f5;border-radius:8px;padding:10px 14px;border-left:4px solid #2563eb;">
                    <div style="min-width:85px;text-align:center;background:#1a3a6b;color:#fff;border-radius:6px;padding:4px 6px;font-size:11px;font-weight:bold;">
                        {{ substr($c->heure_debut,0,5) }}<br>{{ substr($c->heure_fin,0,5) }}
                    </div>
                    <div>
                        <p class="font-medium">{{ $c->matiere->nom }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $c->enseignant->user->name }}
                            @if($c->salle) · Salle {{ $c->salle }} @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection