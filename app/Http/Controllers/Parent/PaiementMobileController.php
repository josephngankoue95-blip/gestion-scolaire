<?php
namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Scolarite;
use App\Models\PaiementMobileMoney;
use App\Services\MobileMoneyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaiementMobileController extends Controller
{
    public function __construct(protected MobileMoneyService $momo) {}

    protected function mesEnfants()
    {
        return Eleve::where('parent_user_id', Auth::id())->get();
    }

    public function create(Request $request)
    {
        $enfants = $this->mesEnfants();
        $enfant  = $enfants->firstWhere('id', $request->eleve_id) ?? $enfants->first();
        abort_if(!$enfant, 403);

        $scolarite = $enfant->scolariteActive();

        return view('parent.paiement-momo', compact('enfant','enfants','scolarite'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scolarite_id'     => 'required|exists:scolarites,id',
            'operateur'        => 'required|in:mtn_momo,orange_money',
            'numero_telephone' => 'required|string|max:20',
            'type_paiement'    => 'required|in:inscription,tranche1,tranche2,tranche3,transport',
            'montant'          => 'required|numeric|min:100',
        ]);

        $scolarite = Scolarite::findOrFail($validated['scolarite_id']);

        // Vérifier appartenance à un enfant du parent connecté
        abort_if($scolarite->eleve->parent_user_id !== Auth::id(), 403);

        // Vérifier que le montant ne dépasse pas le solde de la rubrique
        $champDu   = $validated['type_paiement'] === 'inscription' ? 'frais_inscription' : "montant_{$validated['type_paiement']}";
        $champPaye = $validated['type_paiement'] === 'inscription' ? 'paye_inscription'  : "paye_{$validated['type_paiement']}";
        $reste     = (float)$scolarite->$champDu - (float)$scolarite->$champPaye;

        if ($validated['montant'] > $reste + 0.01) {
            return back()->with('error', "Le montant dépasse le solde restant ({$reste} FCFA).");
        }

        // Initier la demande auprès de l'opérateur (stub pour l'instant)
        $resultat = $this->momo->initierPaiement(
            $validated['operateur'],
            $validated['numero_telephone'],
            $validated['montant'],
            'SCOL-' . $scolarite->id . '-' . time()
        );

        if (!$resultat['success']) {
            return back()->with('error', 'Échec de l\'initiation du paiement : ' . $resultat['message']);
        }

        PaiementMobileMoney::create([
            'scolarite_id'          => $scolarite->id,
            'operateur'             => $validated['operateur'],
            'numero_telephone'      => $validated['numero_telephone'],
            'type_paiement'         => $validated['type_paiement'],
            'montant'               => $validated['montant'],
            'reference_transaction' => $resultat['reference_transaction'],
            'statut'                => 'en_attente',
        ]);

        return back()->with('success', $resultat['message'] . ' Vous recevrez une confirmation une fois validée.');
    }

    public function historique()
    {
        $enfants = $this->mesEnfants();
        $paiements = PaiementMobileMoney::whereHas('scolarite', fn($q) =>
            $q->whereIn('eleve_id', $enfants->pluck('id')))
            ->with('scolarite.eleve')
            ->latest()->paginate(15);

        return view('parent.paiement-momo-historique', compact('paiements'));
    }
}