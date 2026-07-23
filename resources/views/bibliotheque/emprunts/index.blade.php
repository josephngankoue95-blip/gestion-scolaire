@extends('layouts.bibliotheque')
@section('title','Emprunts')
@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold">Emprunts</h3>
        <a href="{{ route('bibliotheque.emprunts.create') }}" class="btn-primary">+ Nouvel emprunt</a>
    </div>
    <div class="table-wrapper">
        <table class="table-base">
            <thead><tr><th>Livre</th><th>Emprunteur</th><th>Date emprunt</th><th>Retour prévu</th><th>Statut</th><th class="text-right">Action</th></tr></thead>
            <tbody>
                @foreach ($emprunts as $e)
                <tr>
                    <td>{{ $e->livre->titre }}</td>
                    <td>{{ $e->emprunteurNom() }}</td>
                    <td>{{ $e->date_emprunt->format('d/m/Y') }}</td>
                    <td style="color:{{ $e->estEnRetard() ? '#c0392b' : '#111' }};">{{ $e->date_retour_prevue->format('d/m/Y') }}</td>
                    <td>
                        @if($e->statut === 'retourne') <span class="badge-green">Retourné</span>
                        @elseif($e->estEnRetard()) <span class="badge-red">En retard</span>
                        @elseif($e->statut === 'perdu') <span class="badge-gray">Perdu</span>
                        @else <span class="badge-blue">En cours</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($e->statut === 'en_cours')
                        <form method="POST" action="{{ route('bibliotheque.emprunts.retour',$e) }}" class="inline">
                            @csrf<button class="login-link">Retour</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $emprunts->links() }}</div>
</div>
@endsection