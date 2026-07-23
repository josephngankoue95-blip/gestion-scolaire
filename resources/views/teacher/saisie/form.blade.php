@extends('layouts.admin')
@section('title', 'Saisie — ' . $matiere->nom . ' · ' . $classe->nom)

@section('content')
<div class="card">
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="font-semibold text-gray-800">
            {{ $matiere->nom }} — {{ $classe->nom }}
        </h3>
        <p class="text-sm text-gray-500">
            {{ $sequence->nom }} ({{ $sequence->trimestre->nom }})
            · Coefficient : <strong>{{ $coefficient }}</strong>
            · Notes sur 20
        </p>
    </div>

    <a href="{{ route('teacher.saisie.index') }}" class="btn btn-info">
        ← Changer
    </a>
</div>

    <form method="POST" action="{{ route('teacher.saisie.store') }}" id="form-notes">
        @csrf
        <input type="hidden" name="classe_id"   value="{{ $classe->id }}">
        <input type="hidden" name="matiere_id"  value="{{ $matiere->id }}">
        <input type="hidden" name="sequence_id" value="{{ $sequence->id }}">

        <div class="table-wrapper">
            <table class="table-base">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Nom complet</th>
                        <th style="width:130px;">Note / 20</th>
                        <th style="width:100px;">Coefficient</th>
                        <th style="width:130px;">Note coefficiée</th>
                        <th style="width:80px;" class="text-center">Absent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eleves as $index => $eleve)
                    @php $noteExistante = $notesExistantes->get($eleve->id); @endphp
                    <tr id="row-{{ $index }}">
                        <td class="text-gray-400">{{ $index + 1 }}</td>
                        <td class="font-medium">{{ $eleve->nomComplet() }}</td>

                        {{-- Note --}}
                        <td>
                            <input type="hidden" name="notes[{{ $index }}][eleve_id]" value="{{ $eleve->id }}">
                            <input type="number"
                                   name="notes[{{ $index }}][note]"
                                   value="{{ $noteExistante && !$noteExistante->absent ? $noteExistante->note : '' }}"
                                   step="0.25" min="0" max="20"
                                   class="form-input note-input"
                                   data-index="{{ $index }}"
                                   data-coef="{{ $coefficient }}"
                                   style="width:90px;padding:6px 8px;"
                                   placeholder="0 — 20">
                        </td>

                        {{-- Coefficient (affiché, non éditable) --}}
                        <td class="text-center font-medium text-gray-700">
                            {{ $coefficient }}
                        </td>

                        {{-- Note coefficiée --}}
                        <td>
                            <span id="coeff-{{ $index }}" class="font-semibold" style="color:#1d4ed8;">
                                @if ($noteExistante && !$noteExistante->absent && $noteExistante->note !== null)
                                    {{ round($noteExistante->note * $coefficient, 2) }}
                                @else
                                    —
                                @endif
                            </span>
                        </td>

                        {{-- Absent --}}
                        <td class="text-center">
                            <input type="checkbox"
                                   name="notes[{{ $index }}][absent]"
                                   value="1"
                                   class="absent-checkbox"
                                   data-index="{{ $index }}"
                                   {{ $noteExistante?->absent ? 'checked' : '' }}>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

                {{-- Moyenne en pied de tableau --}}
                <tfoot>
                    <tr style="border-top:2px solid #e5e7eb;">
                        <td colspan="2" class="text-right font-semibold text-gray-600 py-3">
                            Moyenne de la classe :
                        </td>
                        <td><span id="moy-note" class="font-bold text-gray-800">—</span></td>
                        <td></td>
                        <td><span id="moy-coeff" class="font-bold" style="color:#1d4ed8;">—</span></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="flex justify-end gap-3 mt-6" style="border-top:1px solid #e5e7eb;padding-top:20px;">
            <a href="{{ route('teacher.saisie.index') }}" class="btn btn-danger">
                Annuler
            </a>

            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" class="w-4 h-4"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const coef = {{ $coefficient }};

    function calculer(index) {
        const row    = document.getElementById('row-' + index);
        const input  = row.querySelector('.note-input');
        const absent = row.querySelector('.absent-checkbox');
        const coeffEl = document.getElementById('coeff-' + index);

        if (absent.checked || input.value === '') {
            coeffEl.textContent = '—';
            coeffEl.style.color = '#9ca3af';
            return;
        }

        const note = parseFloat(input.value);
        if (isNaN(note) || note < 0 || note > 20) {
            coeffEl.textContent = '—';
            return;
        }

        const noteCoeff = Math.round(note * coef * 100) / 100;
        coeffEl.textContent = noteCoeff.toFixed(2);
        coeffEl.style.color = note >= 10 ? '#1d4ed8' : '#b91c1c';

        calculerMoyenne();
    }

    function calculerMoyenne() {
        const inputs  = document.querySelectorAll('.note-input');
        const absents = document.querySelectorAll('.absent-checkbox');
        let totalNote  = 0;
        let totalCoeff = 0;
        let count      = 0;

        inputs.forEach((input, i) => {
            if (absents[i].checked || input.value === '') return;
            const note = parseFloat(input.value);
            if (isNaN(note)) return;
            totalNote  += note;
            totalCoeff += note * coef;
            count++;
        });

        document.getElementById('moy-note').textContent  = count > 0 ? (totalNote / count).toFixed(2)  : '—';
        document.getElementById('moy-coeff').textContent = count > 0 ? (totalCoeff / count).toFixed(2) : '—';
    }

    // Init
    document.querySelectorAll('.note-input').forEach(input => {
        const index = input.dataset.index;
        calculer(index);
        input.addEventListener('input', () => calculer(index));
    });

    document.querySelectorAll('.absent-checkbox').forEach(checkbox => {
        const index = checkbox.dataset.index;
        const row   = document.getElementById('row-' + index);
        const input = row.querySelector('.note-input');

        checkbox.addEventListener('change', () => {
            input.disabled = checkbox.checked;
            if (checkbox.checked) input.value = '';
            calculer(index);
        });

        if (checkbox.checked) input.disabled = true;
    });

    calculerMoyenne();
});
</script>
@endpush
@endsection