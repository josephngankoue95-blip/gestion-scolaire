@extends('layouts.eleve')
@section('title', 'Travaux Dirigés')

@section('content')
<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Mes Travaux Dirigés</h3>

    @if($tds->isEmpty())
        <div style="text-align:center;padding:40px;color:#9ca3af;">
            <i data-lucide="book-marked" style="width:48px;height:48px;display:block;margin:0 auto 12px;"></i>
            <p class="font-medium">Aucun TD disponible pour le moment.</p>
        </div>
    @else
    <div class="space-y-3">
        @foreach ($tds as $td)
        @php
            $expire = now() > $td->date_limite_acces;
            $restantH = now()->diffInHours($td->date_limite_acces, false);
        @endphp
        <div style="border:1px solid {{ $expire ? '#fecaca' : '#dce6f5' }};border-radius:10px;padding:14px 16px;background:{{ $expire ? '#fef2f2' : '#f8faff' }};">
            <div class="flex justify-between items-start">
                <div style="flex:1;">
                    <p class="font-semibold text-gray-800">{{ $td->titre }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $td->matiere->nom }} · {{ $td->enseignant->user->name }}
                    </p>
                    @if($td->description)
                        <p class="text-xs text-gray-400 mt-1">{{ $td->description }}</p>
                    @endif
                    <div class="flex gap-2 mt-2">
                        <span style="font-size:11px;color:#555;">Publié : {{ $td->date_publication->format('d/m/Y H:i') }}</span>
                        @if(!$expire)
                            <span class="badge-green" style="font-size:10px;">
                                Expire dans {{ $restantH > 24 ? floor($restantH/24).'j' : $restantH.'h' }}
                            </span>
                        @else
                            <span class="badge-red" style="font-size:10px;">Expiré</span>
                        @endif
                    </div>
                </div>
                @if(!$expire)
                <a href="{{ route('eleve.travaux.show', $td) }}" class="btn-primary" style="padding:8px 14px;font-size:12px;margin-left:12px;">
                    <i data-lucide="eye" class="w-4 h-4"></i> Voir
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection