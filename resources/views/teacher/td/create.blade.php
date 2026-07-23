@extends('layouts.admin')
@section('title', 'Nouveau Travail Dirigé')

@section('content')
<div class="card" style="max-width:720px;">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800">Nouveau Travail Dirigé</h3>
        <a href="{{ route('teacher.td.index') }}" class="btn-secondary">← Retour</a>
    </div>
    
    @if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('teacher.td.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div class="form-group">
                <label class="form-label">Matière *</label>
                <select name="matiere_id" required class="form-select" id="sel_matiere">
                    <option value="">-- Choisir --</option>
                    @foreach ($affectations->groupBy('matiere_id') as $matiereId => $group)
                        @php $aff = $group->first(); @endphp
                        <option value="{{ $matiereId }}">
                            {{ $aff->matiere->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Classe *</label>
                    <select name="classe_id" required class="form-select" id="sel_classe_td">
                        <option value="">-- Choisir une matière d'abord --</option>
                        @foreach ($affectations as $aff)
                            <option value="{{ $aff->classe_id }}" data-matiere="{{ $aff->matiere_id }}">
                                {{ $aff->classe->nom }} ({{ $aff->classe->section->code }})
                            </option>
                        @endforeach
                    </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Titre du TD *</label>
            <input type="text" name="titre" required class="form-input" value="{{ old('titre') }}"
                   placeholder="ex: TD N°1 — Fonctions dérivées">
        </div>

        <div class="form-group">
            <label class="form-label">Description (résumé pour les élèves)</label>
            <textarea name="description" rows="2" class="form-textarea"
                      placeholder="Objectifs du TD...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Contenu du TD *</label>
            <textarea name="contenu" id="contenu" rows="10" required class="form-textarea"
                      placeholder="Énoncé du TD, exercices, questions...">{{ old('contenu') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Fichier joint (PDF, Word, PowerPoint — max 10 Mo)</label>
            <input type="file" name="fichier" accept=".pdf,.doc,.docx,.ppt,.pptx" class="form-input">
        </div>

        <div style="background:#f0f4ff;border:1px solid #dce6f5;border-radius:10px;padding:16px;">
            <h4 class="font-semibold text-gray-700 mb-3">
                <i data-lucide="clock" class="w-4 h-4 inline"></i>
                Délai de validité d'accession
            </h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Date/heure de publication *</label>
                    <input type="datetime-local" name="date_publication" required class="form-input"
                           value="{{ old('date_publication', now()->format('Y-m-d\TH:i')) }}">
                    <p class="text-xs text-gray-400 mt-1">Le TD sera visible à partir de cette date</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Date/heure limite d'accès *</label>
                    <input type="datetime-local" name="date_limite_acces" required class="form-input"
                           value="{{ old('date_limite_acces') }}">
                    <p class="text-xs text-gray-400 mt-1">Le TD ne sera plus accessible après cette date</p>
                </div>
            </div>

            <div id="duree_affichee" style="background:#1a3a6b;color:#fff;padding:6px 12px;border-radius:6px;margin-top:8px;font-size:12px;text-align:center;display:none;">
                Durée d'accès : <strong id="duree_txt"></strong>
            </div>
        </div>

        <div class="form-group">
            <label class="login-checkbox-label">
                <input type="checkbox" name="publie" value="1" class="form-checkbox"
                       {{ old('publie') ? 'checked' : '' }}>
                Publier immédiatement (sinon le TD reste en brouillon)
            </label>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('teacher.td.index') }}" class="btn-secondary w-full">Annuler</a>
            <button type="submit" class="btn-primary w-full">
                <i data-lucide="save" class="w-4 h-4"></i> Créer le TD
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selMatiere = document.getElementById('sel_matiere');
    const selClasse = document.getElementById('sel_classe_td');

    selMatiere.addEventListener('change', function () {
        const matiereId = String(this.value);

        [...selClasse.options].forEach(opt => {
            if (!opt.dataset.matiere) return;
            const ok = String(opt.dataset.matiere) === matiereId;
            opt.hidden = !ok;
            opt.disabled = !ok;
        });

        selClasse.value = '';
    });
});
</script>
@endpush
@endsection