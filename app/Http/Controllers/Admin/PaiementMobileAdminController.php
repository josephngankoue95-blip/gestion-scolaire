<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaiementMobileMoney;
use App\Models\PaiementScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaiementMobileAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = PaiementMobileMoney::with('scolarite.eleve', 'verifiePar');

        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('operateur')) $query->where('operateur', $request->operateur);

        $paiements = $query->latest()->paginate(20);

        $enAttente = PaiementMobileMoney::where('statut', 'en_attente')->count();

        return view('admin.paiements-momo.index', compact('paiements', 'enAttente'));
    }

    /** Valide manuellement (ou après vérification API) un paiement Mobile Money */
    public function valider(PaiementMobileMoney $paiement)
    {
        if ($paiement->statut !== 'en_attente') {
            return back()->with('error', 'Ce paiement a déjà été traité.');
        }

        $scolarite = $paiement->scolarite;
        $champPaye = $paiement->type_paiement === 'inscription' ? 'paye_inscription' : "paye_{$paiement->type_paiement}";

        // Créer le paiement scolarité officiel
        PaiementScolaire::create([
            'scolarite_id'   => $scolarite->id,
            'type'           => $paiement->type_paiement,
            'montant'        => $paiement->montant,
            'date_paiement'  => now(),
            'numero_recu'    => \App\Models\PaiementScolaire::genererNumeroRecu(),
            'note'           => "Paiement Mobile Money ({$paiement->operateur}) — Réf: {$paiement->reference_transaction}",
            'enregistre_par' => Auth::id(),
        ]);

        $scolarite->increment($champPaye, $paiement->montant);

        $paiement->update([
            'statut'      => 'confirme',
            'verifie_par' => Auth::id(),
            'verifie_le'  => now(),
        ]);

        return back()->with('success', 'Paiement validé et intégré à la scolarité.');
    }

    public function rejeter(Request $request, PaiementMobileMoney $paiement)
    {
        $paiement->update([
            'statut'      => 'echoue',
            'verifie_par' => Auth::id(),
            'verifie_le'  => now(),
            'note'        => $request->input('note'),
        ]);

        return back()->with('success', 'Paiement rejeté.');
    }
}