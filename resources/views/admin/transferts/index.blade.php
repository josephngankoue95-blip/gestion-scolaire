@extends('layouts.admin')
@section('title', 'Transfert d\'élèves')

@section('content')
<div class="grid grid-cols-2 gap-6">

    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Effectuer un transfert</h3>

        @if(session('error'))
            <div class="alert-error mb-4">{{ session('error') }}</div>
        @endif
        
        <form method="POST" action="{{ route('admin.transferts.store') }}" class="space-y-4" id="form-transfert">
            @csrf

            <div class="form-group">
                <label class="form-label">Classe source (départ) *</label>
                <select name="classe_source_id" id="sel_classe_source" required class="form-select">
                    <option value="">-- Choisir --</option>
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->nom }} ({{ $c->section->code }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Alerte classe terminale --}}
            <div id="alerte_terminale" style="display:none;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 14px;">
                <p style="font-size:12px;color:#991b1b;">
                    <i data-lucide="alert-triangle" class="w-4 h-4 inline"></i>
                    <strong>Attention :</strong> cette classe est un <strong>niveau terminal</strong> (fin de cycle).
                    Les élèves de cette classe ne sont normalement pas transférables — ils sont censés terminer
                    leur cursus dans l'établissement. Confirmez uniquement si nécessaire (cas exceptionnel).
                </p>
                <label class="login-checkbox-label mt-2" style="display:block;">
                    <input type="checkbox" name="confirmation_terminale" value="1" class="form-checkbox">
                    Je confirme vouloir transférer malgré tout
                </label>
            </div>

            <div class="form-group">
                <label class="form-label">Élève(s) à transférer *</label>
                <select name="eleves_ids[]" id="sel_eleves_transfert" multiple required class="form-select"
                        style="min-height:120px;" disabled>
                    <option>Choisir d'abord la classe source</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Ctrl+clic pour sélectionner plusieurs élèves</p>
            </div>

            <div class="form-group">
                <label class="form-label">Classe destination (arrivée) *</label>
                <select name="classe_destination_id" required class="form-select">
                    <option value="">-- Choisir --</option>
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->nom }} ({{ $c->section->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Date du transfert *</label>
                <input type="date" name="date_transfert" required class="form-input" value="{{ date('Y-m-d') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Motif</label>
                <textarea name="motif" rows="2" class="form-textarea"></textarea>
            </div>

            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 14px;">
                <p style="font-size:12px;color:#991b1b;">
                    <i data-lucide="alert-triangle" class="w-4 h-4 inline"></i>
                    <strong>Attention :</strong> le transfert supprimera définitivement dans l'ancienne classe :
                    l'inscription scolaire (tranches, transport, paiements), les notes et les absences de l'élève.
                    Les comptes d'accès (parent/élève) et l'historique de scolarité des années précédentes sont conservés.
                </p>
                <label class="login-checkbox-label mt-2" style="display:block;">
                    <input type="checkbox" name="confirmation_purge" value="1" required class="form-checkbox">
                    Je confirme avoir compris et j'accepte la suppression des données ci-dessus
                </label>
            </div>

            <button type="submit" class="btn-primary w-full" onclick="return confirm('Confirmer le transfert ?')">
                <i data-lucide="arrow-right" class="w-4 h-4"></i> Effectuer le transfert
            </button>
        </form>
    </div>

    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Historique — {{ $annee?->libelle }}</h3>
        @forelse ($transferts as $t)
        <div style="border:1px solid #e5e7eb;border-radius:8px;padding:10px 12px;margin-bottom:6px;">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-medium text-gray-800">{{ $t->eleve->nomComplet() }}</p>
                    <p class="text-xs text-gray-500">{{ $t->date_transfert->format('d/m/Y') }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="badge-red" style="font-size:10px;">{{ $t->classeSource->nom }}</span>
                        <i data-lucide="arrow-right" style="width:12px;height:12px;color:#9ca3af;"></i>
                        <span class="badge-green" style="font-size:10px;">{{ $t->classeDestination->nom }}</span>
                    </div>
                    @if($t->motif)<p class="text-xs text-gray-400 mt-1 italic">{{ $t->motif }}</p>@endif
                </div>
                <form method="POST" action="{{ route('admin.transferts.destroy', $t) }}"
                      onsubmit="return confirm('Annuler ce transfert ?')">
                    @csrf @method('DELETE')
                    <button class="text-red-500"><i data-lucide="rotate-ccw" class="w-4 h-4"></i></button>
                </form>
            </div>
        </div>
        @empty
        <p class="text-gray-400 text-center py-6">Aucun transfert cette année.</p>
        @endforelse
        <div class="mt-3">{{ $transferts->links() }}</div>
    </div>
</div>

@push('scripts')
<script>
const urlEleves = "{{ route('admin.transferts.eleves-classe') }}";

document.getElementById('sel_classe_source').addEventListener('change', function () {
    const sel = document.getElementById('sel_eleves_transfert');
    const alerteBox = document.getElementById('alerte_terminale');
    sel.innerHTML = '<option>Chargement...</option>';
    sel.disabled = true;
    alerteBox.style.display = 'none';

    if (!this.value) return;

    fetch(`${urlEleves}?classe_id=${this.value}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        sel.innerHTML = '';
        if (data.eleves.length === 0) {
            sel.innerHTML = '<option>Aucun élève dans cette classe</option>';
            return;
        }
        data.eleves.forEach(e => {
            const opt = document.createElement('option');
            opt.value = e.id;
            opt.textContent = `${e.nom} (${e.matricule})`;
            sel.appendChild(opt);
        });
        sel.disabled = false;

        if (data.est_terminale) {
            alerteBox.style.display = 'block';
        }
    });
});
</script>
@endpush
@endsection