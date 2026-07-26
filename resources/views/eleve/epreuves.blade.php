@extends('layouts.eleve')
@section('title', 'Épreuves d\'examens')
@section('content')
<div class="card">
    <h3 class="font-semibold text-gray-800 mb-1">Épreuves d'examens externes</h3>
    <p class="text-sm text-gray-500 mb-6">Téléchargez des sujets d'examens correspondant à votre niveau, depuis des sites de référence comme Examens Cameroun.</p>

    @if($epreuves->isEmpty())
        <div style="text-align:center;padding:40px;color:#9ca3af;">
            <i data-lucide="file-search" style="width:44px;height:44px;display:block;margin:0 auto 12px;"></i>
            <p>Aucune épreuve disponible pour le moment.</p>
        </div>
    @else
    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Titre</th><th>Matière</th><th>Année</th><th>Source</th><th class="text-right">Action</th></tr></thead>
            <tbody>
                @foreach ($epreuves as $ep)
                <tr>
                    <td class="font-medium">{{ $ep->titre }}</td>
                    <td>{{ $ep->matiere ?? '-' }}</td>
                    <td>{{ $ep->annee_examen ?? '-' }}</td>
                    <td>{{ $ep->source ?? '-' }}</td>
                    <td class="text-right">
                        <a href="{{ $ep->lien_externe }}" target="_blank" rel="noopener" class="btn-primary" style="padding:6px 12px;font-size:12px;">
                            <i data-lucide="external-link" class="w-4 h-4"></i> Télécharger
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