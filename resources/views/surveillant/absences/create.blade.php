@extends('layouts.admin')
@section('title', 'Saisie des absences — ' . $classe->nom)

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-semibold text-gray-800">Appel — {{ $classe->nom }}</h3>
    </div>

    <form method="POST" action="{{ route('surveillant.absences.store', $classe) }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm text-gray-600 mb-1">Date</label>
            <input type="date" name="date_absence" value="{{ date('Y-m-d') }}" required class="px-3 py-2 border rounded-lg">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="py-2 px-2">Élève</th>
                        <th class="py-2 px-2 text-center">Présent</th>
                        <th class="py-2 px-2 w-32">Type</th>
                        <th class="py-2 px-2">Motif (optionnel)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($eleves as $index => $eleve)
                    <tr>
                        <td class="py-2 px-2">
                            <input type="hidden" name="absences[{{ $index }}][eleve_id]" value="{{ $eleve->id }}">
                            {{ $eleve->nomComplet() }}
                        </td>
                        <td class="py-2 px-2 text-center">
                            <input type="checkbox" name="absences[{{ $index }}][present]" value="1" checked class="present-checkbox w-4 h-4">
                        </td>
                        <td class="py-2 px-2">
                            <select name="absences[{{ $index }}][type]" class="type-select w-full px-2 py-1 border rounded-lg" disabled>
                                <option value="absence">Absence</option>
                                <option value="retard">Retard</option>
                            </select>
                        </td>
                        <td class="py-2 px-2">
                            <input type="text" name="absences[{{ $index }}][motif]" class="motif-input w-full px-2 py-1 border rounded-lg" disabled>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-100 mt-6">
            <button type="submit" class="btn-primary">Enregistrer l'appel</button>
        </div>
    </form>
</div>
@endsection