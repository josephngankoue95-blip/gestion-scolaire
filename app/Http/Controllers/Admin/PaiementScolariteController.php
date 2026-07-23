<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaiementScolaire;
use App\Models\Scolarite;
use App\Models\Etablissement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaiementScolariteController extends Controller
{
    public function store(Request $request, Scolarite $scolarite)
    {
        $validated = $request->validate([
            'type'          => 'required|in:inscription,tranche1,tranche2,tranche3,transport',
            'montant'       => 'required|numeric|min:1',
            'date_paiement' => 'required|date',
            'note'          => 'nullable|string|max:255',
        ]);

        $champDu   = "montant_{$validated['type']}";
        $champPaye = "paye_{$validated['type']}";
        if ($validated['type'] === 'inscription') {
            $champDu   = 'frais_inscription';
            $champPaye = 'paye_inscription';
        }

        $resteAPayer = (float)$scolarite->$champDu - (float)$scolarite->$champPaye;

        if ($validated['montant'] > $resteAPayer + 0.01) {
            return back()->with('error', "Le montant dépasse le reste à payer ({$resteAPayer} FCFA).");
        }

        $paiement = PaiementScolaire::create([
            ...$validated,
            'scolarite_id'   => $scolarite->id,
            'numero_recu'    => PaiementScolaire::genererNumeroRecu(),
            'enregistre_par' => Auth::id(),
        ]);

        $scolarite->increment($champPaye, $validated['montant']);

        return back()->with('success', "Paiement enregistré. N° Reçu : {$paiement->numero_recu}");
    }

    public function recu(PaiementScolaire $paiementScolarite)
    {
        $paiementScolarite->load('scolarite.eleve','scolarite.classe.section','enregistrePar');
        $pdf = Pdf::loadView('admin.scolarite.recu', [
            'paiement'      => $paiementScolarite,
            'etablissement' => Etablissement::instance(),
        ])->setPaper([0, 0, 226.77, 340.16], 'portrait');

        return $pdf->stream("recu_{$paiementScolarite->numero_recu}.pdf");
    }

    public function destroy(PaiementScolaire $paiementScolarite)
    {
        $scolarite = $paiementScolarite->scolarite;
        $champPaye = "paye_{$paiementScolarite->type}";
        if ($paiementScolarite->type === 'inscription') $champPaye = 'paye_inscription';
        $scolarite->decrement($champPaye, $paiementScolarite->montant);
        $paiementScolarite->delete();
        return back()->with('success', 'Paiement annulé.');
    }
}