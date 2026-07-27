@extends('layouts.eleve')
@section('title', 'Épreuves d\'examens')
@section('content')
<div class="card">
    <h3 class="font-semibold text-gray-800 mb-1">Épreuves d'examens des années précédentes</h3>
    <p class="text-sm text-gray-500 mb-4">Consultez les sujets déposés par vos enseignants et la préfecture des études.</p>

    @if($anneesDisponibles->isNotEmpty())
    <form method="GET" class="mb-4">
        <select name="annee" class="form-select" style="max-width:200px;" onchange="this.form.submit()">
            @foreach ($anneesDisponibles as $annee)
                <option value="{{ $annee }}" {{ $anneeSelectionnee == $annee ? 'selected' : '' }}>Année {{ $annee }}</option>
            @endforeach
        </select>
    </form>
    @endif

    @if($epreuves->isEmpty())
        <div style="text-align:center;padding:40px;color:#9ca3af;">
            <i data-lucide="file-search" style="width:44px;height:44px;display:block;margin:0 auto 12px;"></i>
            <p>Aucune épreuve disponible pour cette année.</p>
        </div>
    @else
    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Titre</th><th>Matière</th><th>Année</th><th class="text-right">Action</th></tr></thead>
            <tbody>
                @foreach ($epreuves as $ep)
                <tr>
                    <td class="font-medium">{{ $ep->titre }}</td>
                    <td>{{ $ep->matiere->nom }}</td>
                    <td>{{ $ep->annee_examen }}</td>
                    <td class="text-right">
                        <a href="{{ asset('storage/'.$ep->fichier) }}" target="_blank" class="btn-primary" style="padding:6px 12px;font-size:12px;">
                            <i data-lucide="download" class="w-4 h-4"></i> Télécharger
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection