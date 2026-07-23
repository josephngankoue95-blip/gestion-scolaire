@extends('layouts.parent')
@section('title', 'Bulletins')

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="topbar">
        <div class="title">Bulletins</div>
    </div>

    {{-- Sélecteur enfant --}}
    @if($enfants->count() > 1)
        <div class="card mb-4">
            <form method="GET" class="flex gap-3 items-end">
                <div class="form-group" style="margin-bottom:0;flex:1;max-width:300px;">
                    <label class="form-label">Enfant</label>
                    <select name="eleve_id" class="form-select" onchange="this.form.submit()">
                        @foreach ($enfants as $e)
                            <option value="{{ $e->id }}" {{ $enfant->id == $e->id ? 'selected' : '' }}>
                                {{ $e->nomComplet() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    @endif

    @if(!$scolarite)
        <div class="card">
            <p class="text-gray-400 text-center py-6">
                {{ $enfant->nomComplet() }} n'est pas inscrit pour cette année.
            </p>
        </div>
    @else
        {{-- INFO ÉLÈVE --}}
        <div class="section">
            <div class="section-title">Informations de l'enfant</div>

            <div class="grid-card">
                <div class="card">
                    <div class="label">Nom complet</div>
                    <div class="value">{{ $enfant->nomComplet() }}</div>
                </div>

                <div class="card">
                    <div class="label">Classe</div>
                    <div class="value">
                        <span class="badge badge-other">{{ $scolarite->classe->nom }}</span>
                    </div>
                </div>

                <div class="card">
                    <div class="label">Année scolaire</div>
                    <div class="value">{{ $scolarite->annee_scolaire ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- BULLETINS --}}
        <div class="section">
            <div class="section-title">Documents disponibles</div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Séquentiels --}}
                <div class="card">
                    <div class="label mb-3">Bulletins séquentiels</div>

                    @forelse ($sequences as $seq)
                        <form method="GET" action="{{ route('parent.bulletins.voir', $enfant) }}" target="_blank" class="mb-2">
                            <input type="hidden" name="type" value="sequentiel">
                            <input type="hidden" name="periode_id" value="{{ $seq->id }}">
                            <button type="submit" class="btn-outline w-full" style="text-align:left;padding:8px 12px;font-size:12px;">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                {{ $seq->nom }} <span class="text-gray-400">({{ $seq->trimestre->nom }})</span>
                            </button>
                        </form>
                    @empty
                        <p class="text-gray-400 text-sm">Aucun bulletin séquentiel disponible.</p>
                    @endforelse
                </div>

                {{-- Trimestriels --}}
                <div class="card">
                    <div class="label mb-3">Bulletins trimestriels</div>

                    @forelse ($trimestres as $trim)
                        <form method="GET" action="{{ route('parent.bulletins.voir', $enfant) }}" target="_blank" class="mb-2">
                            <input type="hidden" name="type" value="trimestriel">
                            <input type="hidden" name="periode_id" value="{{ $trim->id }}">
                            <button type="submit" class="btn-outline w-full" style="text-align:left;padding:8px 12px;font-size:12px;">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                {{ $trim->nom }}
                            </button>
                        </form>
                    @empty
                        <p class="text-gray-400 text-sm">Aucun bulletin trimestriel disponible.</p>
                    @endforelse
                </div>

                {{-- Annuel --}}
                <div class="card">
                    <div class="label mb-3">Bulletin annuel</div>

                    <form method="GET" action="{{ route('parent.bulletins.voir', $enfant) }}" target="_blank">
                        <input type="hidden" name="type" value="annuel">
                        <button type="submit" class="btn-primary w-full" style="padding:10px;">
                            <i data-lucide="award" class="w-4 h-4"></i> Bulletin Annuel
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @endif
</div>
@endsection