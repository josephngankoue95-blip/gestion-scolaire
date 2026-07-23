@extends('layouts.eleve')
@section('title', 'Notes & Bulletins')

@section('content')
<div class="grid grid-cols-3 gap-6">

    {{-- Résumé moyennes --}}
    <div class="col-span-3 card">
        <h3 class="font-semibold text-gray-800 mb-4">Suivi des moyennes — {{ $scolarite->classe->nom }}</h3>
        <div class="grid grid-cols-6 gap-3">
            @foreach ($moyennesSeq as $ms)
            <div style="background:#f0f4ff;border-radius:8px;padding:10px;text-align:center;">
                <p style="font-size:10px;color:#555;">{{ $ms['sequence']->nom }}</p>
                <p style="font-size:18px;font-weight:bold;color:{{ ($ms['moyenne'] ?? 0) >= 10 ? '#1a3a6b' : '#c0392b' }};">
                    {{ $ms['moyenne'] !== null ? number_format($ms['moyenne'],2) : '-' }}
                </p>
                <p style="font-size:10px;color:#9ca3af;">Rang : {{ $ms['rang'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Séquentiels --}}
    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-3">Bulletins Séquentiels</h4>
        @foreach ($sequences as $seq)
        <form method="GET" action="{{ route('eleve.bulletins.voir') }}" target="_blank" class="mb-2">
            <input type="hidden" name="type" value="sequentiel">
            <input type="hidden" name="periode_id" value="{{ $seq->id }}">
            <button type="submit" class="btn-outline w-full" style="text-align:left;padding:8px 12px;font-size:12px;">
                <i data-lucide="file-text" class="w-4 h-4 inline"></i>
                {{ $seq->nom }}
                <span style="font-size:10px;color:#9ca3af;">({{ $seq->trimestre->nom }})</span>
            </button>
        </form>
        @endforeach
    </div>

    {{-- Trimestriels --}}
    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-3">Bulletins Trimestriels</h4>
        @foreach ($trimestres as $trim)
        <form method="GET" action="{{ route('eleve.bulletins.voir') }}" target="_blank" class="mb-2">
            <input type="hidden" name="type" value="trimestriel">
            <input type="hidden" name="periode_id" value="{{ $trim->id }}">
            <button type="submit" class="btn-outline w-full" style="text-align:left;padding:8px 12px;font-size:12px;">
                <i data-lucide="file-text" class="w-4 h-4 inline"></i>
                {{ $trim->nom }}
            </button>
        </form>
        @endforeach
    </div>

    {{-- Annuel --}}
    <div class="card">
        <h4 class="font-semibold text-gray-800 mb-3">Bulletin Annuel</h4>
        <form method="GET" action="{{ route('eleve.bulletins.voir') }}" target="_blank">
            <input type="hidden" name="type" value="annuel">
            <button type="submit" class="btn-primary w-full" style="padding:12px;">
                <i data-lucide="award" class="w-4 h-4"></i> Bulletin Annuel
            </button>
        </form>
    </div>
</div>
@endsection