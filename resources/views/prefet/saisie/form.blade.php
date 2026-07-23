{{-- resources/views/prefet/saisie/form.blade.php --}}
@extends('layouts.prefet')
@section('title', 'Saisie des notes — ' . $classe->nom)

@section('content')
<div class="card">
    <h3 class="font-semibold text-gray-800 mb-1">{{ $classe->nom }} — {{ $matiere->nom }}</h3>
    <p class="text-sm text-gray-500 mb-4">{{ $sequence->nom }} · Coefficient : {{ $coefficient }}</p>

    <form method="POST" action="{{ route('prefet.saisie.store') }}">
        @csrf
        <input type="hidden" name="classe_id" value="{{ $classe->id }}">
        <input type="hidden" name="matiere_id" value="{{ $matiere->id }}">
        <input type="hidden" name="sequence_id" value="{{ $sequence->id }}">

        <div class="table-wrapper">
            <table class="table-base">
                <thead><tr><th>Élève</th><th style="width:120px;">Note /20</th><th style="width:80px;">Absent</th></tr></thead>
                <tbody>
                    @foreach ($eleves as $eleve)
                    @php $note = $notesExistantes->get($eleve->id); @endphp
                    <tr>
                        <td class="font-medium">{{ $eleve->nomComplet() }}</td>
                        <td>
                            <input type="number" name="notes[{{ $eleve->id }}]" min="0" max="20" step="0.25"
                                   value="{{ $note?->note }}" class="form-input" style="padding:4px 8px;">
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="absents[]" value="{{ $eleve->id }}"
                                   {{ $note?->absent ? 'checked' : '' }} class="form-checkbox">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn-primary w-full mt-4">
            <i data-lucide="save" class="w-4 h-4"></i> Enregistrer les notes
        </button>
    </form>
</div>
@endsection