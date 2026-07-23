@extends('layouts.parent')
@section('title', 'Payer par Mobile Money')

@section('content')
<div class="payment-page">
    @if($enfants->count() > 1)
        <div class="selector-card">
            <form method="GET" class="d-flex gap-3 align-items-center flex-wrap justify-content-center">
                <label class="fw-semibold text-secondary mb-0">Enfant</label>
                <select name="eleve_id" class="form-select selector-select" onchange="this.form.submit()">
                    @foreach ($enfants as $e)
                        <option value="{{ $e->id }}" {{ $enfant->id == $e->id ? 'selected' : '' }}>
                            {{ $e->nomComplet() }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    @endif

    <div class="payment-card">
        <div class="text-center mb-3">
            <div class="payment-icon">
                <i data-lucide="smartphone" class="w-7 h-7"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1 mt-3">Payer la scolarité</h3>
            <p class="text-muted mb-0">{{ $enfant->nomComplet() }}</p>
        </div>

        @if(!$scolarite)
            <div class="alert alert-light border text-center py-4 rounded-4 mb-0">
                <div class="fw-semibold text-secondary mb-1">Aucune inscription active</div>
                <div class="text-muted">Cet enfant n'a pas encore de scolarité active.</div>
            </div>
        @else
            <form method="POST" action="{{ route('parent.paiement-momo.store') }}" class="vstack gap-4">
                @csrf
                <input type="hidden" name="scolarite_id" value="{{ $scolarite->id }}">

                <div class="form-section">
                    <label class="form-label section-title">Opérateur *</label>

                    <div class="row justify-content-center g-4">

                        <div class="col-md-4">
                            <label class="operator-card">
                                <input type="radio" name="operateur"
                                    value="mtn_momo"
                                    required
                                    class="operator-input">

                                <div class="operator-box mtn-box">
                                    <img src="{{ asset('images/mtn-momo.jpg') }}"
                                        class="operator-logo">

                                    <span class="operator-name">
                                        MTN MoMo
                                    </span>
                                </div>
                            </label>
                        </div>

                        <div class="col-md-4">
                            <label class="operator-card">
                                <input type="radio"
                                    name="operateur"
                                    value="orange_money"
                                    required
                                    class="operator-input">

                                <div class="operator-box orange-box">
                                    <img src="{{ asset('images/orange-money.png') }}"
                                        class="operator-logo">

                                    <span class="operator-name">
                                        Orange Money
                                    </span>
                                </div>
                            </label>
                        </div>

                    </div>

                </div>

                <div>
                    <label class="form-label fw-semibold mb-2">Numéro Mobile Money *</label>
                    <input type="text" name="numero_telephone" required class="form-control form-control-lg rounded-4" placeholder="6XXXXXXXX">
                </div>

                <div>
                    <label class="form-label fw-semibold mb-2">Rubrique à payer *</label>
                    <select name="type_paiement" required class="form-select form-select-lg rounded-4">
                        @if($scolarite->frais_inscription > $scolarite->paye_inscription)
                            <option value="inscription">
                                Inscription ({{ number_format($scolarite->frais_inscription - $scolarite->paye_inscription,0,',',' ') }} FCFA restant)
                            </option>
                        @endif

                        @foreach (['tranche1','tranche2','tranche3'] as $t)
                            @if($scolarite->{"montant_{$t}"} > $scolarite->{"paye_{$t}"})
                                <option value="{{ $t }}">
                                    {{ ucfirst(str_replace('tranche','Tranche ',$t)) }}
                                    ({{ number_format($scolarite->{"montant_{$t}"} - $scolarite->{"paye_{$t}"},0,',',' ') }} FCFA restant)
                                </option>
                            @endif
                        @endforeach

                        @if($scolarite->montant_transport > $scolarite->paye_transport)
                            <option value="transport">
                                Transport ({{ number_format($scolarite->montant_transport - $scolarite->paye_transport,0,',',' ') }} FCFA restant)
                            </option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="form-label fw-semibold mb-2">Montant à payer (FCFA) *</label>
                    <input type="number" name="montant" required min="100" class="form-control form-control-lg rounded-4" placeholder="Ex: 25000">
                    @error('montant') <p class="text-danger small mt-2 mb-0">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn btn-warning btn-lg w-100 rounded-4 fw-semibold shadow-sm">
                    <i data-lucide="send" class="w-4 h-4 me-2"></i> Initier le paiement
                </button>

                <p class="text-xs text-muted text-center mb-0">
                    Vous recevrez une notification sur votre téléphone pour confirmer le paiement.
                </p>
            </form>

            <a href="{{ route('parent.paiement-momo.historique') }}" class="d-block text-center mt-4 text-decoration-none fw-semibold history-link">
                Voir l'historique de mes paiements Mobile Money →
            </a>
        @endif
    </div>
</div>

<style>
.payment-page{
    min-height: calc(100vh - 140px);
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    gap:30px;
    width:100%;
    padding:30px;
}

/* -----------------------------
   Sélection de l'enfant
--------------------------------*/

.selector-card{
    width:70%;
    max-width:1100px;
    background:#fff;
    border:2px solid #dbe4f0;
    border-radius:20px;
    padding:22px 30px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
}

.selector-select{
    max-width:350px;
}

/* -----------------------------
   Carte principale
--------------------------------*/

.payment-card{

    width:70%;
    max-width:1100px;

    background:#ffffff;

    border:2px solid #d9e4f5;
    border-radius:24px;

    padding:45px;

    box-shadow:
        0 10px 35px rgba(0,0,0,.06);

}

/* séparation entre chaque bloc */

.payment-card form>div{

    padding-bottom:28px;
    margin-bottom:28px;

    border-bottom:1px solid #edf2f7;

}

.payment-card form>div:last-of-type{

    border-bottom:none;
    margin-bottom:15px;
    padding-bottom:10px;

}

/* -----------------------------
   Icône
--------------------------------*/

.payment-icon{

    width:72px;
    height:72px;

    border-radius:50%;

    display:flex;
    justify-content:center;
    align-items:center;

    background:linear-gradient(135deg,#ff7900,#ffb648);

    color:#fff;

    margin:auto;

    box-shadow:0 8px 18px rgba(255,121,0,.30);

}

/* -----------------------------
   Labels
--------------------------------*/

.form-label{

    font-weight:700;
    color:#183153;
    margin-bottom:12px;

}

/* -----------------------------
   Champs
--------------------------------*/

.form-control,
.form-select{

    border:2px solid #dce5ef;

    border-radius:14px;

    padding:14px 18px;

    transition:.25s;

    box-shadow:none;

}

.form-control:focus,
.form-select:focus{

    border-color:#ff7900;

    box-shadow:0 0 0 .25rem rgba(255,121,0,.15);

}

/* -----------------------------
   Cartes opérateurs
--------------------------------*/

.operator-card{

    display:block;
    cursor:pointer;
    height:100%;

}

.operator-box{

    background:#fff;

    border:2px solid #dde7f3;

    border-radius:18px;

    min-height:170px;

    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;

    gap:15px;

    transition:.30s;

    padding:25px;

}

.operator-box:hover{

    border-color:#ff7900;

    transform:translateY(-4px);

    box-shadow:0 12px 25px rgba(0,0,0,.08);

}

.operator-input:checked + .operator-box{

    border:2px solid #ff7900;

    background:#fffaf4;

    box-shadow:0 0 0 5px rgba(255,121,0,.12);

}

.operator-logo{
    width:70px;
    height:70px;
    object-fit:contain;
}

.operator-name{
    font-size:17px;
    font-weight:700;

}

.mtn-box .operator-name{

    color:#f5c000;

}

.orange-box .operator-name{

    color:#ff7900;

}

/* -----------------------------
   Bouton
--------------------------------*/

.btn-warning{

    padding:15px;

    border-radius:14px;

    font-size:17px;

    font-weight:700;

    box-shadow:0 10px 20px rgba(255,121,0,.25);

}

.btn-warning:hover{

    transform:translateY(-2px);

}

/* -----------------------------
   Historique
--------------------------------*/

.history-link{

    display:inline-block;

    margin-top:25px;

    font-weight:700;

    color:#ff7900;

    transition:.25s;

}

.history-link:hover{

    letter-spacing:.5px;

    color:#d76600;

}
</style>
@endsection